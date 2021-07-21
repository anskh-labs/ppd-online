<?php

/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;

use App\Models\CannedModel;
use App\Models\PriorityModel;
use App\Models\TicketNotesModel;
use App\Models\TicketsMessage;
use Config\Database;
use Config\Services;

class Tickets
{
    protected $ticketsModel;
    protected $topics;
    protected $categories;
    protected $messagesModel;
    protected $settings;

    public function __construct()
    {
        $this->settings = Services::settings();
        $this->ticketsModel = new \App\Models\Tickets();
        $this->messagesModel = new TicketsMessage();
        $this->topics = Services::topics();
        $this->categories = Services::categories();
    }
    public function createTicket($client_id, $subject, $topic, $priority_id = 1)
    {
        $t = time(); 
        $calendarLib = Services::calendar();
        $data = $calendarLib->getWithStaff(date('Y-m-d', $t));
        $this->ticketsModel->protect(false);
        $this->ticketsModel->insert([
            'topic' => $topic,
            'priority_id' => $priority_id,
            'user_id' => $client_id,
            'subject' => $subject,
            'date' => $t,
            'staff_id' =>(isset($data)? $data->staff_id: 0),
            'last_update' => $t,
            'last_replier' => 0,
        ]);
        $this->ticketsModel->protect(true);
        return $this->ticketsModel->getInsertID();
    }

    public function addMessage($ticket_id, $message, $staff_id = 0, $detect_ip = true)
    {
        $this->messagesModel->protect(false);
        $this->messagesModel->insert([
            'ticket_id' => $ticket_id,
            'date' => time(),
            'customer' => ($staff_id == 0 ? 1 : 0),
            'staff_id' => $staff_id,
            'message' => $message,
            'ip' => ($detect_ip ? Services::request()->getIPAddress() : ''),
            'email' => ($detect_ip ? 0 : 1),
        ]);
        $this->messagesModel->protect(true);
        return $this->messagesModel->getInsertID();
    }

    public function updateTicketReply($ticket_id, $ticket_status, $staff = false)
    {
        $this->ticketsModel->protect(false);
        if ($staff) {
            if (!in_array($ticket_status, [4, 5])) {
                $this->ticketsModel->set('status', 2);
            }
            $this->ticketsModel->set('last_update', time())
                ->set('replies', 'replies+1', false)
                ->set('last_replier', Services::staff()->getData('id'))
                ->update($ticket_id);
        } else {
            if (in_array($ticket_status, [2, 5])) {
                $this->ticketsModel->set('status', 3);
            }
            $this->ticketsModel->set('last_update', time())
                ->set('replies', 'replies+1', false)
                ->set('last_replier', 0)
                ->update($ticket_id);
        }
        $this->ticketsModel->protect(true);
    }

    public function getTicketFromEmail($client_id, $subject)
    {
        if (!preg_match('/\[#[0-9]+]/', $subject, $regs)) {
            return null;
        }
        $ticket_id = str_replace(['[#', ']'], '', $regs[0]);
        if (!$ticket = $this->getTicket(['user_id' => $client_id, 'id' => $ticket_id])) {
            return null;
        }
        return $ticket;
    }

    public function getTicket($field, $value = '')
    {
        if (!is_array($field)) {
            $field = array($field => $value);
        }
        foreach ($field as $k => $v) {
            $this->ticketsModel->where('tickets.' . $k, $v);
        }
        $q = $this->ticketsModel->select('tickets.*, t.name as topic_name, c.name as category_name, p.name as priority_name, 
        u.fullname, u.email, u.avatar, s.fullname as staff_name')
            ->join('topic as t', 't.id=tickets.topic')
            ->join('category as c', 'c.id=tickets.category', 'left')
            ->join('priority as p', 'p.id=tickets.priority_id')
            ->join('users as u', 'u.id=tickets.user_id', 'left')
            ->join('staff as s', 's.id=tickets.staff_id', 'left')
            ->get(1);
        if ($q->getNumRows() == 0) {
            return null;
        }
        return $q->getRow();
    }

    public function countTickets($data)
    {
        return $this->ticketsModel->where($data)
            ->countAllResults();
    }

    /*
     * --------------------------------
     * Notifications
     * --------------------------------
     */
    public function newTicketNotification($ticket)
    {
        //Send Mail to client
        $emails = new Emails();
        $emails->sendFromTemplate('new_ticket', [
            '%client_name%' => $ticket->fullname,
            '%client_email%' => $ticket->email,
            '%ticket_id%' => $ticket->id,
            '%ticket_subject%' => $ticket->subject,
            '%ticket_topic%' => $ticket->topic_name,
            '%ticket_status%' => lang('Client.form.open'),
            '%ticket_priority%' => $ticket->priority_name,
        ], $ticket->email);
    }

    public function staffNotification($ticket)
    {
        if (isset($ticket) && $ticket->staff_id !== 0) {
            $emails = new Emails();
            $staffModel = new \App\Models\Staff();
            //Send Mail to staff
            $q = $staffModel->where(['id' => $ticket->staff_id])
                ->get(1);
            if ($q->getNumRows() > 0) {
                foreach ($q->getResult() as $item) {
                    $emails->sendFromTemplate('staff_ticketnotification', [
                        '%staff_name%' => $item->fullname,
                        '%ticket_id%' => $ticket->id,
                        '%ticket_subject%' => $ticket->subject,
                        '%ticket_topic%' => $ticket->topic_name,
                        '%ticket_status%' => lang('open'),
                        '%ticket_priority%' => $ticket->priority_name,
                    ], $item->email);
                }
                $q->freeResult();
            }
        }
    }

    public function replyTicketNotification($ticket, $message, $attachments = null)
    {
        $files = array();
        if (is_array($attachments)) {
            foreach ($attachments as $file) {
                $files[] = [
                    'name' => $file['name'],
                    'path' => WRITEPATH . 'attachments/' . $file['encoded_name'],
                    'file_type' => $file['file_type']
                ];
            }
        }

        //Send Mail to client
        $emails = new Emails();
        $emails->sendFromTemplate('staff_reply', [
            '%ticket_id%' => $ticket->id,
            '%ticket_subject%' => $ticket->subject,
            '%ticket_topic%' => $ticket->topic_name,
            '%ticket_status%' => $this->statusName($ticket->status),
            '%ticket_priority%' => $ticket->priority_name,
            '%message%' => $message,
        ], $ticket->email, $files);
    }

    /*
     * -----------------------------
     * Get Messages
     * -----------------------------
     */
    public function getFirstMessage($ticket_id)
    {
        $q = $this->messagesModel->where('ticket_id', $ticket_id)
            ->orderBy('date', 'asc')
            ->get(1);
        if ($q->getNumRows() == 0) {
            return null;
        }
        return $q->getRow();
    }
    public function getMessages($ticket_id, $select = '*')
    {
        $settings = Services::settings();
        $per_page = $settings->config('tickets_replies');
        $result = $this->messagesModel->select($select)
            ->where('ticket_id', $ticket_id)
            ->orderBy('date', $settings->config('reply_order'))
            ->paginate($per_page, 'default');
        return [
            'result' => $result,
            'pager' => $this->messagesModel->pager
        ];
    }

    /*
     * -------------------------
     * Canned Response
     * -------------------------
     */
    public function getCannedAll()
    {
        $cannedModel = new CannedModel();
        $result = $cannedModel->select('*')->orderBy('position', 'asc')
            ->paginate(site_config('page_size'), 'default');
        return [
            'canned_list' => $result,
            'pager' => $cannedModel->pager
        ];
    }
    public function getCannedList()
    {
        $cannedModel = new CannedModel();
        $q = $cannedModel->orderBy('position', 'asc')
            ->get();
        if ($q->getNumRows() == 0) {
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function insertCanned($title, $message)
    {
        $cannedModel = new CannedModel();
        $next_position = $cannedModel->countAll() + 1;
        $cannedModel->protect(false)
            ->insert([
                'title' => esc($title),
                'message' => $message,
                'position' => $next_position,
                'date' => time(),
                'last_update' => time(),
                'staff_id' => Services::staff()->getData('id')
            ]);
        $cannedModel->protect(true);
    }

    public function getCannedResponse($id)
    {
        $cannedModel = new CannedModel();
        if (!$canned = $cannedModel->find($id)) {
            return null;
        }
        return $canned;
    }


    public function updateCanned($data, $id)
    {
        $cannedModel = new CannedModel();
        $cannedModel->protect(false)
            ->update($id, $data);
        $cannedModel->protect(true);
    }

    public function changeCannedPosition($position, $id)
    {
        $cannedModel = new CannedModel();
        $cannedModel->protect(false)
            ->update($id, [
                'position' => $position,
            ]);
        $cannedModel->protect(true);
    }
    public function lastCannedPosition()
    {
        $cannedModel = new CannedModel();

        $q = $cannedModel->select('position')
            ->orderBy('position', 'desc')
            ->get(1);
        if ($q->getNumRows() == 0) {
            return 0;
        }
        return $q->getRow()->position;
    }
    public function deleteCanned($id)
    {
        $cannedModel = new CannedModel();
        $cannedModel->protect(false)
            ->delete($id);
        $cannedModel->protect(true);
    }
    /*
     * --------------------------------
     * Status
     * -------------------------------
     */
    public function statusName($id)
    {
        return isset($this->statusList()[$id]) ? $this->statusList()[$id] : 'open';
    }

    public function statusList()
    {
        return $ticket_status = array(
            1 => 'open',
            2 => 'answered',
            3 => 'awaiting_reply',
            4 => 'in_progress',
            5 => 'closed'
        );
    }
    /*
     * -------------------------------
     * Priorities
     * -------------------------------
     */
    public function getPriorities()
    {
        $priorityModel = new PriorityModel();
        $q = $priorityModel->orderBy('id', 'asc')
            ->get();
        $r = $q->getResult();
        $q->freeResult();;
        return $r;
    }
    public function existPriority($id)
    {
        $priorityModel = new PriorityModel();
        return ($priorityModel->where('id', $id)->countAllResults() == 0) ? false : true;
    }
    /*
     * ----------------------------------
     * Ticket Actions
     * ----------------------------------
     */
    public function autoCloseTickets()
    {
        $date_left = time() - (60 * 60 * $this->settings->config('ticket_autoclose'));
        $this->ticketsModel->protect(false)
            ->where('status', 2)
            ->where('last_update<=', $date_left)
            ->set('status', 5)
            ->update();
        $this->ticketsModel->protect(true);
    }

    public function deleteTicket($ticket_id)
    {
        $this->ticketsModel->delete($ticket_id);
        $this->messagesModel->where('ticket_id', $ticket_id)
            ->delete();
        Services::attachments()->deleteFiles(['ticket_id' => $ticket_id]);
    }

    public function updateTicket($data, $id)
    {
        $this->ticketsModel->protect(false)
            ->update($id, $data);
        $this->ticketsModel->protect(true);
    }

    /*
     * ----------------------------------
     * Client Panel
     * ---------------------------------
     */
    public function clientTickets($client_id)
    {
        $request = Services::request();
        $per_page = Services::settings()->config('tickets_page');
        if ($request->getGet('do') == 'search') {
            if ($request->getGet('code')) {
                $code = str_replace(['[', '#', ']'], '', $request->getGet('code'));
                $this->ticketsModel->where('tickets.id', $code);
            }
        }
        $result = $this->ticketsModel->where('tickets.user_id', $client_id)
            ->orderBy('tickets.status', 'asc')
            ->orderBy('tickets.last_update', 'desc')
            ->join('topic as t', 't.id=tickets.topic')
            ->join('category as c', 'c.id=tickets.category', 'left')
            ->join('priority as p', 'p.id=tickets.priority_id')
            ->select('tickets.*, t.name as topic_name, c.name as category_name, p.name as priority_name')
            ->paginate($per_page, 'default', null, 2);
        return [
            'result' => $result,
            'pager' => $this->ticketsModel->pager
        ];
    }


    /*
     * ---------------------------------------
     * Staff Panel
     * ---------------------------------------
     */
    public function staffTickets($page = '')
    {
        $staff = Services::staff();
        $request = Services::request();

        switch ($page) {
            case 'search':
                if ($request->getGet('keyword') != '') {
                    $this->ticketsModel->groupStart()
                        ->where('tickets.id', $request->getGet('keyword'))
                        ->orLike('tickets.subject', $request->getGet('keyword'))
                        ->orLike('u.fullname', $request->getGet('keyword'))
                        ->orWhere('u.email', $request->getGet('keyword'))
                        ->groupEnd();
                }

                if (array_key_exists($request->getGet('status'), $this->statusList())) {
                    $this->ticketsModel->where('tickets.status', $request->getGet('status'));
                }
                if ($this->topics->isValid($request->getGet('topic'))) {
                    $this->ticketsModel->where('tickets.topic', $request->getGet('topic'));
                }
                if ($this->categories->isValid($request->getGet('category'))) {
                    $this->ticketsModel->where('tickets.category', $request->getGet('category'));
                }
                if ($request->getGet('date_created')) {
                    $dates = explode(' - ', $request->getGet('date_created'));
                    if (($start = strtotime($dates[0])) && ($end = strtotime($dates[1] . ' +1 day'))) {
                        $this->ticketsModel->groupStart()
                            ->where('tickets.date>=', $start)
                            ->where('tickets.date<', $end)
                            ->groupEnd();
                    }
                }
                if ($request->getGet('last_update')) {
                    $dates = explode(' - ', $request->getGet('last_update'));
                    if (($start = strtotime($dates[0])) && ($end = strtotime($dates[1] . ' +1 day'))) {
                        $this->ticketsModel->groupStart()
                            ->where('tickets.last_update>=', $start)
                            ->where('tickets.last_update<', $end)
                            ->groupEnd();
                    }
                }
                if ($request->getGet('overdue') == '1') {
                    $this->ticketsModel->groupStart()
                        ->where('tickets.status', 1)
                        ->orWhere('tickets.status', 3)
                        ->orWhere('tickets.status', 4)
                        ->groupEnd()
                        ->where('tickets.last_update<', time() - ($this->settings->config('overdue_time') * 60 * 60));
                }
                break;
            case 'overdue':
                if ($staff->getData('roleName') == 'operator') {
                    $this->ticketsModel
                        ->where('tickets.staff_id', $staff->getData('id'))
                        ->where('tickets.last_update<', time() - ($this->settings->config('overdue_time') * 60 * 60))
                        ->where("(tickets.status = 1 or tickets.status = 3 or tickets.status = 4)");
                } else {
                    $this->ticketsModel
                        ->where('tickets.last_update<', time() - ($this->settings->config('overdue_time') * 60 * 60))
                        ->where("(tickets.status = 1 or tickets.status = 3 or tickets.status = 4)");
                }
                break;
            case 'answered':
                if ($staff->getData('roleName') == 'operator') {
                    $this->ticketsModel->where('tickets.status', 2)->where('tickets.staff_id', $staff->getData('id'));
                } else {
                    $this->ticketsModel->where('tickets.status', 2);
                }
                break;
            case 'closed':
                if ($staff->getData('roleName') == 'operator') {
                    $this->ticketsModel->where('tickets.status', 5)->where('tickets.staff_id', $staff->getData('id'));
                } else {
                    $this->ticketsModel->where('tickets.status', 5);
                }
                break;
            default:
                if ($staff->getData('roleName') == 'operator') {
                    $this->ticketsModel
                        ->where('tickets.status', 1)
                        ->orWhere('tickets.status', 3)
                        ->orWhere('tickets.status', 4)
                        ->Where('tickets.staff_id', $staff->getData('id'));
                } else {
                    $this->ticketsModel
                        ->where('tickets.status', 1)
                        ->orWhere('tickets.status', 3)
                        ->orWhere('tickets.status', 4);
                }
                break;
        }

        if ($request->getGet('sort')) {
            $sort_list = [
                'id' => 'tickets.id',
                'subject' => 'tickets.subject',
                'last_reply' => 'tickets.last_update',
                'topic' => 't.name',
                'category' => 'c.name',
                'priority' => 'p.id',
                'status' => 'tickets.status'
            ];
            if (array_key_exists($request->getGet('sort'), $sort_list)) {
                $this->ticketsModel->orderBy($sort_list[$request->getGet('sort')], ($request->getGet('order') == 'ASC' ? 'ASC' : 'DESC'));
            }
        } else {
            $this->ticketsModel->orderBy('tickets.last_update', 'desc');
        }

        $db = Database::connect();
        $result = $this->ticketsModel->select('tickets.*, u.fullname, t.name as topic_name, c.name as category_name,
        p.name as priority_name, p.color as priority_color, s.fullname as staff_name,
        IF(last_replier=0, "", (SELECT username FROM ' . $db->prefixTable('staff') . ' WHERE id=last_replier)) as staff_username')
            ->join('users as u', 'u.id=tickets.user_id')
            ->join('topic as t', 't.id=tickets.topic', 'left')
            ->join('category as c', 'c.id=tickets.category', 'left')
            ->join('staff as s', 's.id=tickets.staff_id', 'left')
            ->join('priority as p', 'p.id=tickets.priority_id')
            ->paginate($this->settings->config('tickets_page'));
        return [
            'result' => $result,
            'pager' => $this->ticketsModel->pager
        ];
    }

    public function countStatus($status)
    {
        $staff = Services::staff();
        switch ($status) {
            case 'active':
                if ($staff->getData('roleName') == 'operator') {
                    $total = $this->ticketsModel
                        ->where('tickets.staff_id', $staff->getData('id'))
                        ->where("(tickets.status = 1 or tickets.status = 3 or tickets.status = 4)")
                        ->countAllResults();
                } else {
                    $total = $this->ticketsModel
                        ->where("(tickets.status = 1 or tickets.status = 3 or tickets.status = 4)")
                        ->countAllResults();
                }
                break;
            case 'overdue':
                if ($staff->getData('roleName') == 'operator') {
                    $total = $this->ticketsModel
                        ->where('tickets.staff_id', $staff->getData('id'))
                        ->where("(tickets.status = 1 or tickets.status = 3 or tickets.status = 4)")
                        ->where('last_update<', time() - ($this->settings->config('overdue_time') * 60 * 60))
                        ->countAllResults();
                } else {
                    $total = $this->ticketsModel
                        ->where("(tickets.status = 1 or tickets.status = 3 or tickets.status = 4)")
                        ->where('last_update<', time() - ($this->settings->config('overdue_time') * 60 * 60))
                        ->countAllResults();
                }
                break;
            case 'answered':
                if ($staff->getData('roleName') == 'operator') {
                    $total = $this->ticketsModel
                        ->where('tickets.staff_id', $staff->getData('id'))
                        ->where('status', 2)
                        ->countAllResults();
                } else {
                    $total = $this->ticketsModel
                        ->where('status', 2)
                        ->countAllResults();
                }
                break;
            case 'closed':
                if ($staff->getData('roleName') == 'operator') {
                    $total = $this->ticketsModel
                        ->where('tickets.staff_id', $staff->getData('id'))
                        ->where('status', 5)
                        ->countAllResults();
                } else {
                    $total = $this->ticketsModel
                        ->where('status', 5)
                        ->countAllResults();
                }
                break;
            default:
                $total = 0;
                break;
        }
        return $total;
    }

    public function isOverdue($date, $status)
    {
        $timeleft = time() - $date;
        if ($timeleft >= ($this->settings->config('overdue_time') * 60 * 60) && in_array($status, [1, 3, 4])) {
            return true;
        }
        return false;
    }

    public function purifyHTML($message)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($message);
    }

    /*
     * ----------------------------------------
     * Notes
     * ----------------------------------------
     */
    public function getNotes($ticket_id)
    {
        $ticketsNotesModel = new TicketNotesModel();
        $q = $ticketsNotesModel->select('ticket_notes.*, staff.username, staff.fullname')
            ->orderBy('date', 'desc')
            ->join('staff', 'staff.id=ticket_notes.staff_id')
            ->where('ticket_id', $ticket_id)
            ->get();
        if ($q->getNumRows() == 0) {
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }
    public function getNote($note_id)
    {
        $ticketsNoteModel = new TicketNotesModel();
        return $ticketsNoteModel->find($note_id);
    }
    public function addNote($ticket_id, $staff_id, $note)
    {
        $ticketNotesModel = new TicketNotesModel();
        return $ticketNotesModel->insert([
            'ticket_id' => $ticket_id,
            'staff_id' => $staff_id,
            'date' => time(),
            'message' => esc($note)
        ]);
    }
    public function deleteNote($ticket_id, $note_id)
    {
        $ticketNotesModel = new TicketNotesModel();
        $ticketNotesModel->where('ticket_id', $ticket_id)
            ->where('id', $note_id)
            ->delete();
    }
    public function updateNote($note, $note_id)
    {
        $ticketNotesModel = new TicketNotesModel();
        $ticketNotesModel->update($note_id, [
            'message' => esc($note)
        ]);
    }
    public function getTickett(){
        $ticket = $this->ticketsModel->select('tickets.category, category.name as name')
        ->join('category', 'tickets.category=category.id')->get();
        $cust = $ticket->getResult();
        $ticket->freeResult();
        return $cust;
    }

    public function getStatuss(){
        $ticket = $this->ticketsModel->select('tickets.status, staff.fullname as name')
        ->join('staff', 'tickets.staff_id=staff.id')
        ->where('tickets.status', 5)->get();
        $cust = $ticket->getResult();
        $ticket->freeResult();
        return $cust;
    }
}

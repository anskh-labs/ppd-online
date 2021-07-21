<?php

/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers\Staff;


use App\Controllers\BaseController;
use App\Models\CannedModel;
use Config\Services;


class Tickets extends BaseController
{
    public function manage($page)
    {
        $tickets = new \App\Libraries\Tickets();
        $topics = new \App\Libraries\Topics();
        $categories = new \App\Libraries\Categories();

        if ($this->request->getPost('action')) {
            if (!is_array($this->request->getPost('ticket_id'))) {
                $error_msg = lang('Admin.error.noItemsSelected');
            } else {
                foreach ($this->request->getPost('ticket_id') as $ticket_id) {
                    if (is_numeric($ticket_id)) {
                        if ($this->request->getPost('action') == 'remove') {
                            $tickets->deleteTicket($ticket_id);
                        } elseif ($this->request->getPost('action') == 'update') {
                            if (is_numeric($this->request->getPost('topic_'))) {
                                if ($topics->isValid($this->request->getPost('topic_'))) {
                                    $tickets->updateTicket([
                                        'topic' => $this->request->getPost('topic_')
                                    ], $ticket_id);
                                }
                            }
                            if (is_numeric($this->request->getPost('category_'))) {
                                if ($categories->isValid($this->request->getPost('category_'))) {
                                    $tickets->updateTicket([
                                        'category' => $this->request->getPost('category_')
                                    ], $ticket_id);
                                }
                            }
                            if (is_numeric($this->request->getPost('status_'))) {
                                if (array_key_exists($this->request->getPost('status_'), $tickets->statusList())) {
                                    $tickets->updateTicket([
                                        'status' => $this->request->getPost('status_')
                                    ], $ticket_id);
                                }
                            }
                            if (is_numeric($this->request->getPost('priority_'))) {
                                if ($tickets->existPriority($this->request->getPost('priority_'))) {
                                    $tickets->updateTicket([
                                        'priority_id' => $this->request->getPost('priority_')
                                    ], $ticket_id);
                                }
                            }
                            if (is_numeric($this->request->getPost('assign_to'))) {
                                $tickets->updateTicket([
                                    'staff_id' => $this->request->getPost('assign_to')
                                ], $ticket_id);
                            }
                            $this->session->setFlashdata('ticket_update', 'Ticket Updated.');
                        }
                    }
                }
                return redirect()->to(current_url(true));
            }
        }

        if ($this->session->has('ticket_error')) {
            $error_msg = $this->session->getFlashdata('ticket_error');
        }
        $result = $tickets->staffTickets($page);
        return view('staff/tickets', [
            'locale' => $this->locale,
            'agent_list' => $this->staff->getOperator(),
            'statuses' => $tickets->statusList(),
            'tickets_result' => $result['result'],
            'priorities' => $tickets->getPriorities(),
            'pager' => $result['pager'],
            'top_list' => $topics->publicTopics(),
            'cat_list' => $categories->publicCategories(),
            'page_type' => $page,
            'count_status_active' => $tickets->countStatus('active'),
            'count_status_overdue' => $tickets->countStatus('overdue'),
            'count_status_answered' => $tickets->countStatus('answered'),
            'count_status_closed'=>$tickets->countStatus('closed'),
            'error_msg' => isset($error_msg) ? $error_msg : null
        ]);
    }

    public function view($ticket_id)
    {
        $tickets = Services::tickets();
        if (!$ticket = $tickets->getTicket(['id' => $ticket_id])) {
            $this->session->setFlashdata('ticket_error', lang('Admin.error.ticketNotFound'));
            return redirect()->route('staff_tickets');
        }
        if ($this->staff->getData('roleName') != 'supervisor' && $this->staff->getData('roleName') != 'admin') {
            if ($ticket->staff_id !== $this->staff->getData('id')) {
                $this->session->setFlashdata('ticket_error', lang('Admin.error.ticketNotPermission'));
                return redirect()->route('staff_tickets');
            }
        }
        $attachments = Services::attachments();
        #Download
        if ($this->request->getGet('download')) {
            if (!$file = $attachments->getRow(['id' => $this->request->getGet('download'), 'ticket_id' => $ticket->id])) {
                return view('client/error', [
                    'locale' => $this->locale,
                    'title' => lang('Client.error.fileNotFound'),
                    'body' => lang('Client.error.fileNotFoundMsg'),
                    'footer' => ''
                ]);
            }
            return $attachments->download($file);
        } elseif (is_numeric($this->request->getGet('delete_file'))) {
            if (!$file = $attachments->getRow([
                'id' => $this->request->getGet('delete_file'),
                'ticket_id' => $ticket->id
            ])) {
                return redirect()->to(current_url());
            } else {
                $attachments->deleteFile($file);
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.attachmentRemoved'));
                return redirect()->to(current_url());
            }
        }
        //Update Information
        if ($this->request->getPost('do') == 'update_information') {
            $validation = Services::validation();
            $validation->setRules([
                'topic' => 'required|is_natural_no_zero|is_not_unique[topic.id]',
                'category' => 'required|is_natural_no_zero|is_not_unique[category.id]',
                'status' => 'required|is_natural|in_list[' . implode(',', array_keys($tickets->statusList())) . ']',
                'priority' => 'required|is_natural_no_zero|is_not_unique[priority.id]',
                'assign_to' => 'required|is_natural_no_zero|is_not_unique[staff.id]'
            ], [
                'topic' => [
                    'required' => lang('Admin.error.invalidTopic'),
                    'is_natural_no_zero' => lang('Admin.error.invalidTopic'),
                    'is_not_unique' => lang('Admin.error.invalidTopic'),
                ],
                'category' => [
                    'required' => lang('Admin.error.invalidCategory'),
                    'is_natural_no_zero' => lang('Admin.error.invalidCategory'),
                    'is_not_unique' => lang('Admin.error.invalidCategory'),
                ],
                'status' => [
                    'required' => lang('Admin.error.invalidStatus'),
                    'is_natural' => lang('Admin.error.invalidStatus'),
                    'in_list' => lang('Admin.error.invalidStatus'),
                ],
                'priority' => [
                    'required' => lang('Admin.error.invalidPriority'),
                    'is_natural_no_zero' => lang('Admin.error.invalidPriority'),
                    'is_not_unique' => lang('Admin.error.invalidPriority')
                ],
                'assign_to' => [
                    'required' => lang('Admin.error.invalidStaff'),
                    'is_natural_no_zero' => lang('Admin.error.invalidStaff'),
                    'is_not_unique' => lang('Admin.error.invalidStaff')
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $tickets->updateTicket([
                    'topic' => $this->request->getPost('topic'),
                    'category' => $this->request->getPost('category'),
                    'status' => $this->request->getPost('status'),
                    'priority_id' => $this->request->getPost('priority'),
                    'staff_id' => $this->request->getPost('assign_to'),
                ], $ticket->id);
                $this->session->setFlashdata('ticket_update', 'Ticket updated.');
                return redirect()->to(current_url());
            }
        }
        //Reply Ticket
        elseif ($this->request->getPost('do') == 'reply') {
            $validation = Services::validation();
            $validation->setRule('message', 'message', 'required', [
                'required' => lang('Admin.error.enterMessage')
            ]);

            if ($this->settings->config('ticket_attachment')) {
                $max_size = $this->settings->config('ticket_file_size') * 1024;
                $allowed_extensions = unserialize($this->settings->config('ticket_file_type'));
                $allowed_extensions = implode(',', $allowed_extensions);
                $validation->setRule('attachment', 'attachment', 'ext_in[attachment,' . $allowed_extensions . ']|max_size[attachment,' . $max_size . ']', [
                    'ext_in' => lang('Admin.error.fileNotAllowed'),
                    'max_size' => lang_replace('Admin.error.fileBig', ['%size%' => number_to_size($max_size * 1024, 2)])
                ]);
            }

            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                if ($this->settings->config('ticket_attachment')) {
                    if ($files_uploaded = $attachments->ticketUpload()) {
                        $files = $files_uploaded;
                    }
                }
                //Message
                $message = $this->request->getPost('message');
                $message_id = $tickets->addMessage($ticket->id, $message, $this->staff->getData('id'));

                //File
                if (isset($files)) {
                    $attachments->addTicketFiles($ticket->id, $message_id, $files);
                }
                $tickets->updateTicketReply($ticket->id, $ticket->status, true);
                $tickets->replyTicketNotification($ticket, $message, (isset($files) ? $files : null));

                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.messageSent'));
                return redirect()->to(current_url());
            }
        } elseif ($this->request->getPost('do') == 'delete_note') {
            $validation = Services::validation();
            $validation->setRule('note_id', 'note_id', 'required|is_natural_no_zero');
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = lang('Admin.tickets.invalidRequest');
            } elseif (!$note = $tickets->getNote($this->request->getPost('note_id'))) {
                $error_msg = lang('Admin.tickets.invalidRequest');
            } elseif ($this->staff->getData('roleName') == 'admin' || $this->staff->getData('roleName') == 'supervisor'|| $this->staff->getData('id') == $note->staff_id) {
                $tickets->deleteNote($ticket->id, $this->request->getPost('note_id'));
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.noteRemoved'));
                return redirect()->to(current_url());
            } else {
                $error_msg = lang('Admin.tickets.invalidRequest');
            }
        } elseif ($this->request->getPost('do') == 'edit_note') {
            $validation = Services::validation();
            $validation->setRule('note_id', 'note_id', 'required|is_natural_no_zero');
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = lang('Admin.tickets.invalidRequest');
            } elseif ($this->request->getPost('new_note') == '') {
                $error_msg = lang('Admin.tickets.enterNote');
            } elseif (!$note = $tickets->getNote($this->request->getPost('note_id'))) {
                $error_msg = lang('Admin.tickets.invalidRequest');
            } elseif ($this->staff->getData('roleName') == 'admin' || $this->staff->getData('roleName') == 'supervisor' || $this->staff->getData('id') == $note->staff_id) {
                $tickets->updateNote($this->request->getPost('new_note'), $note->id);
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.noteUpdated'));
                return redirect()->to(current_url());
            } else {
                $error_msg = lang('Admin.tickets.invalidRequest');
            }
        } elseif ($this->request->getPost('do') == 'save_notes') {
            if ($this->request->getPost('noteBook') == '') {
                $error_msg = lang('Admin.tickets.enterNote');
            } else {
                $tickets->addNote($ticket->id, $this->staff->getData('id'), $this->request->getPost('noteBook'));
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.notesSaved'));
                return redirect()->to(current_url());
            }
        }

        if ($this->session->has('ticket_update')) {
            $success_msg = $this->session->getFlashdata('ticket_update');
        }

        $messages = $tickets->getMessages($ticket->id);

        return view('staff/ticket_view', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => isset($success_msg) ? $success_msg : null,
            'ticket' => $ticket,
            'canned_response' => $tickets->getCannedList(),
            'message_result' => $messages['result'],
            'pager' => $messages['pager'],
            'topic_list' => Services::topics()->getAll(),
            'ticket_statuses' => $tickets->statusList(),
            'ticket_priorities' => $tickets->getPriorities(),
            'kb_selector' => Services::kb()->kb_article_selector(),
            'agent_list' => $this->staff->getOperator(),
            'cat_list' => Services::categories()->publicCategories(),
            'topic_list' => Services::topics()->publicTopics(),
            'notes' => $tickets->getNotes($ticket->id)
        ]);
    }

    public function create()
    {
        $tickets = Services::tickets();
        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'email' => 'required|valid_email',
                'topic' => 'required|is_natural_no_zero|is_not_unique[topic.id]',
                'priority' => 'required|is_natural_no_zero|is_not_unique[priority.id]',
                'status' => 'required|is_natural|in_list[' . implode(',', array_keys($tickets->statusList())) . ']',
                'subject' => 'required',
                'message' => 'required'
            ], [
                'email' => [
                    'required' => lang('Admin.error.enterValidEmail'),
                    'valid_email' => lang('Admin.error.enterValidEmail')
                ],
                'topic' => [
                    'required' => lang('Admin.error.invalidTopic'),
                    'is_natural_no_zero' => lang('Admin.error.invalidTopic'),
                    'is_not_unique' => lang('Admin.error.invalidTopic'),
                ],
                'priority' => [
                    'required' => lang('Admin.error.invalidPriority'),
                    'is_natural_no_zero' => lang('Admin.error.invalidPriority'),
                    'is_not_unique' => lang('Admin.error.invalidPriority'),
                ],
                'status' => [
                    'required' => lang('Admin.error.invalidStatus'),
                    'is_natural' => lang('Admin.error.invalidStatus'),
                    'in_list' => lang('Admin.error.invalidStatus'),
                ],
                'subject' => [
                    'required' => lang('Admin.error.enterSubject'),
                ],
                'message' => [
                    'required' => lang('Admin.error.enterMessage'),
                ]
            ]);
            if ($this->settings->config('ticket_attachment')) {
                $max_size = $this->settings->config('ticket_file_size') * 1024;
                $allowed_extensions = unserialize($this->settings->config('ticket_file_type'));
                $allowed_extensions = implode(',', $allowed_extensions);
                $validation->setRule('attachment', 'attachment', 'ext_in[attachment,' . $allowed_extensions . ']|max_size[attachment,' . $max_size . ']', [
                    'ext_in' => lang('Admin.error.fileNotAllowed'),
                    'max_size' => lang_replace('Admin.error.fileBig', ['%size%' => number_to_size($max_size * 1024, 2)])
                ]);
            }

            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $attachments = Services::attachments();
                if ($this->settings->config('ticket_attachment')) {
                    if ($uploaded_files = $attachments->ticketUpload()) {
                        $files = $uploaded_files;
                    }
                }
                $client_id = $this->client->getClientID($this->request->getPost('email'));
                $ticket_id = $tickets->createTicket($client_id, $this->request->getPost('subject'), $this->request->getPost('topic'), $this->request->getPost('priority'));
                $message = $this->request->getPost('message');
                $message_id = $tickets->addMessage($ticket_id, $message, $this->staff->getData('id'));
                $tickets->updateTicket([
                    'last_replier' => $this->staff->getData('id'),
                    'status' => $this->request->getPost('status')
                ], $ticket_id);
                //File
                if (isset($files)) {
                    $attachments->addTicketFiles($ticket_id, $message_id, $files);
                }

                $ticket = $tickets->getTicket(['id' => $ticket_id]);
                $tickets->replyTicketNotification($ticket, $message, (isset($files) ? $files : null));
                $this->session->setFlashdata('form_success', 'Ticket has been created and client was notified.');
                return redirect()->route('staff_ticket_view', [$ticket_id]);
            }
        }


        return view('staff/ticket_new', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => isset($success_msg) ? $success_msg : null,
            'canned_response' => $tickets->getCannedList(),
            'topic_list' => Services::topics()->publicTopics(),
            'ticket_statuses' => $tickets->statusList(),
            'ticket_priorities' => $tickets->getPriorities(),
            'kb_selector' => Services::kb()->kb_article_selector(),
        ]);
    }

    public function cannedResponses()
    {
        $tickets = Services::tickets();
        if ($this->request->getPost('do') == 'remove') {
            if (!$canned = $tickets->getCannedResponse($this->request->getPost('msgID'))) {
                $error_msg = lang('Admin.error.invalidCannedResponse');
            } elseif (($this->staff->getData('roleName') != 'admin' || $this->staff->getData('roleName') != 'supervisor') && $canned->staff_id != $this->staff->getData('id')) {
                $error_msg = lang('Admin.error.invalidCannedResponse');
            } else {
                $tickets->deleteCanned($canned->id);
                $this->session->setFlashdata('canned_update', 'Canned response has been removed.');
                return redirect()->route('staff_canned');
            }
        }

        if ($this->request->getGet('action') && is_numeric($this->request->getGet('msgID'))) {
            if (!$canned = $tickets->getCannedResponse($this->request->getGet('msgID'))) {
                $error_msg = lang('Admin.error.invalidCannedResponse');
            } else {
                $cannedModel = new CannedModel();
                switch ($this->request->getGet('action')) {
                    case 'move_up':
                        if ($canned->position > 1) {
                            $cannedModel->protect(false);
                            $cannedModel->set('position', $canned->position)
                                ->where('position', ($canned->position - 1))
                                ->update();
                            $cannedModel->protect(true);
                            $tickets->changeCannedPosition(($canned->position - 1), $canned->id);
                        }
                        break;
                    case 'move_down':
                        if ($canned->position < $tickets->lastCannedPosition()) {
                            $cannedModel->protect(false);
                            $cannedModel->set('position', $canned->position)
                                ->where('position', ($canned->position + 1))
                                ->update();
                            $cannedModel->protect(true);
                            $tickets->changeCannedPosition(($canned->position + 1), $canned->id);
                        }
                        break;
                }
                return redirect()->route('staff_canned');
            }
        }
        if ($this->session->has('canned_update')) {
            $success_msg = $this->session->getFlashdata('canned_update');
        }
        $data = $tickets->getCannedAll();
        return view('staff/canned_manage', [
            'locale' => $this->locale,
            'cannedList' => $data['canned_list'],
            'lastCannedPosition' => $tickets->lastCannedPosition(),
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => isset($success_msg) ? $success_msg : null,
            'pager' => $data['pager']
        ]);
    }

    public function editCannedResponses($canned_id)
    {
        $tickets = Services::tickets();
        if (!$canned = $tickets->getCannedResponse($canned_id)) {
            return redirect()->route('staff_canned');
        }
        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'title' => 'required',
                'message' => 'required'
            ], [
                'title' => [
                    'required' => lang('Admin.error.enterTitle'),
                ],
                'message' => [
                    'required' => lang('Admin.error.enterMessage')
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $tickets->updateCanned([
                    'title' => esc($this->request->getPost('title')),
                    'message' => $this->request->getPost('message'),
                    'last_update' => time()
                ], $canned_id);
                $this->session->setFlashdata('canned_update', 'Canned response has been updated.');
                return redirect()->to(current_url());
            }
        }

        if ($this->session->has('canned_update')) {
            $success_msg = $this->session->getFlashdata('canned_update');
        }
        return view('staff/canned_form', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => isset($success_msg) ? $success_msg : null,
            'canned' => $canned,
            'staff_canned' => ($canned->staff_id > 0 ? $this->staff->getRow(['id' => $canned->staff_id], 'fullname') : null)
        ]);
    }

    public function newCannedResponse()
    {
        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'title' => 'required',
                'message' => 'required'
            ], [
                'title' => [
                    'required' => lang('Admin.error.enterTitle'),
                ],
                'message' => [
                    'required' => lang('Admin.error.enterMessage')
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $tickets = Services::tickets();
                $tickets->insertCanned($this->request->getPost('title'), $this->request->getPost('message'));
                $this->session->setFlashdata('canned_update', 'Canned response has been inserted.');
                return redirect()->route('staff_canned');
            }
        }

        return view('staff/canned_form', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('canned_update') ? $this->session->getFlashdata('canned_update') : null,
        ]);
    }

    public function dashboard()
    {
        $tickets = Services::tickets();
        $ticketCat = $tickets->getTickett();
        $arai = [];
        foreach ($ticketCat as $a) {
            array_push($arai, $a->name);
        }
        $ticket_done = $tickets->getStatuss();
        $arai1 = [];
        foreach ($ticket_done as $b) {
            array_push($arai1, $b->name);
        }

        return view('staff/dashboard', [
            'ticket' => $arai,
            'ticket_done' =>$arai1,
            'locale' => $this->locale,
            'count_status_active' => $tickets->countStatus('active'),
            'count_status_overdue' => $tickets->countStatus('overdue'),
            'count_status_answered' => $tickets->countStatus('answered'),
            'count_status_closed'=>$tickets->countStatus('closed')
        ]);
    }
}

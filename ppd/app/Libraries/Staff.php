<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use Config\Database;
use Config\Services;

class Staff
{
    private $staffModel;
    private $roleModel;
    private $db;
    private $is_online=null;
    private $user_data=null;
    public function __construct()
    {
        $this->staffModel = new \App\Models\Staff();
        $this->roleModel = new \App\Models\Roles(); 
        $this->db = Database::connect();
    }
    public function isOnline()
    {
        if(is_null($this->is_online)){
            $this->is_online = false;
            $session = Services::session();
            if(is_numeric(get_cookie('sid')) && get_cookie('shash') != ''){
                $this->is_online = $this->validate_session(get_cookie('sid'), get_cookie('shash'));
            }elseif($session->has('sid') && $session->has('shash')){
                $this->is_online = $this->validate_session($session->get('sid'), $session->get('shash'));
            }
        }
        return $this->is_online;
    }

    private function validate_session($user_id, $hash)
    {
        if(!$data = $this->getAgentWithRole(['id' => $user_id])){
            return $this->logout();
        }
        $request = Services::request();
        if(!password_verify(sha1($data->password.$data->token).':'.$request->getUserAgent(), $hash)){
            return $this->logout();
        }

        $this->user_data = $data;
        return true;
    }

    public function create_session($id, $password, $token, $remember=true)
    {
        $request = Services::request();
        $session = Services::session();
        $hash = sha1($password.$token).':'.$request->getUserAgent();
        $hash = password_hash($hash, PASSWORD_BCRYPT);
        $session->set('sid', $id);
        $session->set('shash', $hash);
        if($remember){
            set_cookie('sid', $id, 60*60*24*365);
            set_cookie('shash', $hash, 60*60*24*365);
        }
    }

    public function logout()
    {
        set_cookie('sid','');
        set_cookie('shash','');
        Services::session()->destroy();
        return redirect()->withCookies()->route('staff_login');
    }

    public function updatePassword($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->update([
            'password' => $hash
        ]);
        $this->create_session($this->getData('id'), $hash, $this->getData('token'), true);
    }

    public function verifyPassword($staffData)
    {
        $password = Services::request()->getPost('password');
        if(!password_verify($password, $staffData->password)){
            //Verify if it belongs to old version (1.2 or less)
            if(sha1($password) != $staffData->password){
                return false;
            }else{
                //Update password
                $this->update([
                    'password' => password_hash($password, PASSWORD_BCRYPT)
                ], $staffData->id);
            }
        }
        return true;
    }

    /*
     * ------------------------------
     * Login
     * ------------------------------
     */
    public function login($staff_data, $remember=true)
    {
        $this->update([
            'login' => time(),
            'last_login' => ($staff_data->login == 0 ? time() : $staff_data->login)
        ], $staff_data->id);
        $this->addLoginLog($staff_data->id, true);
        $token = random_string('sha1');
        $this->update(['token' => $token],$staff_data->id);
        $this->create_session($staff_data->id, $staff_data->password, $token, $remember);
    }

    public function isLocked($ip_address=null)
    {
        //Delete logs
        $settings = Services::settings();
        $request = Services::request();
        $ip_address = is_null($ip_address) ? $request->getIPAddress() : $ip_address;
        $builder = $this->db->table('login_attempt');
        $builder->delete([
                'date <' => time()-(60*$settings->config('login_attempt_minutes'))
            ]);
        //Verify
        $q = $builder->select('attempts, date')
            ->where('ip', $ip_address)
            ->get();
        if($q->getNumRows() == 0){
            return false;
        }
        $result = $q->getRow();
        if($settings->config('login_attempt') > 0 && $result->attempts >= $settings->config('login_attempt')){
            return true;
        }
        return false;
    }

    public function addLoginAttempt($ip_address=null)
    {
        $settings = Services::settings();
        if($settings->config('login_attempt') == 0){
            return '0';
        }
        $request = Services::request();
        $builder = $this->db->table('login_attempt');
        $ip_address = (!is_null($ip_address) ? $ip_address : $request->getIPAddress());
        $q = $builder->where('ip', $ip_address)
            ->get();
        if($q->getNumRows() == 0){
            $builder->insert([
                'ip' => $ip_address,
                'attempts' => 1,
                'date' => time()
            ]);
            return '1';
        }
        $result = $q->getRow();
        $builder->set('attempts','attempts+1', false)
            ->set('date', time())
            ->where('ip', $result->ip)
            ->update();
        return ($result->attempts+1);
    }

    public function addLoginLog($staff_id, $success=false, $ip_address=null)
    {
        $request = Services::request();
        $user_agent = (!is_null($ip_address) ? 'HelpDeskZ API' : $request->getUserAgent());
        $ip_address = (!is_null($ip_address) ? $ip_address : $request->getIPAddress());
        if($success){
            $builder = $this->db->table('login_attempt');
            $builder->where('ip', $ip_address)
                ->delete();
        }

        $builder = $this->db->table('login_log');
        $builder->insert([
            'date' => time(),
            'staff_id' => $staff_id,
            'ip' => $ip_address,
            'agent' => $user_agent,
            'success' => $success
        ]);
    }

    /*
     * -----------------------------------
     * Topics
     * -----------------------------------
     */
    public function countTicketsByStatus($status)
    {
        $ticketsModel = new \App\Models\Tickets();
        return $ticketsModel->where('status', $status)
            ->countAllResults();
    }

    public function countTicketsFromTopics()
    {
        $where = array();
        foreach (getTopics() as $item){
            $where[] = array('t.id' => $item->id);
        }
        if(count($where) > 0){
            foreach ($where as $item)
            {
                $this->db->or_where($item);
            }

        }
        $q = $this->db->select("t.id, t.name, 
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE topic=t.id AND status=1) as open, 
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE topic=t.id AND status=2) as answered, 
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE topic=t.id AND status=3) as awaiting_reply,
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE topic=t.id AND status=4) as in_progress,
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE topic=t.id AND status=5) as closed", false)
            ->order_by('t.id','asc')
            ->from('topic as t')
            ->get();
        if($q->getNumRows() == 0){
            return null;
        }
        $r = $q->getResult();
        $q->free_result();
        return $r;
    }

    /*
     * -------------------------------------
     * Agents
     * -------------------------------------
     */
    public function getAgentsWithRoles()
    {
        $q = $this->staffModel->select('*')
            ->join('roles', 'staff.role=roles.role_id', 'left')
            ->orderBy('id','asc');
         
        return [
            'agents' => $q->paginate(site_config('page_size'), 'default'),
            'pager' => $this->staffModel->pager
        ];
    }
    public function getAgentWithRole($data=[])
    {
        $q = $this->staffModel->select('*')
            ->join('roles', 'staff.role=roles.role_id', 'left')
            ->where($data)
            ->get();
        return $q->getRow();
    }
    public function getOperator()
    {
        $q = $this->staffModel->select('*')
            ->join('roles','staff.role=roles.role_id','left')
            ->orderBy('id','asc')
            ->where('role_name', 'operator')
            ->get();
        if($q->getNumRows() == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }    

    public function newAgent($fullname, $username, $email, $password, $role=0, $active=1)
    {
        $this->staffModel->protect(false);
        $this->staffModel->insert([
            'fullname' => esc($fullname),
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'registration' => time(),
            'role' => $role,
            'active' => $active
        ]);
        $this->staffModel->protect(true);
        return $this->staffModel->getInsertID();
    }

    public function updateAgent($id, $fullname, $username, $email, $password, $role=0, $active=1)
    {
        $this->staffModel->protect(false);
        if($password != ''){
            $this->staffModel->set('password', password_hash($password, PASSWORD_BCRYPT));
        }
        $this->staffModel->set([
            'fullname' => esc($fullname),
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'active' => $active
        ])->update($id);
        $this->staffModel->protect(false);
        return true;
    }

    public function removeAgent($id)
    {
        $this->staffModel->delete($id);
        $this->db->table('tickets')
            ->where('staff_id', $id)
            ->set(['staff_id'=>0])
            ->update();
        $this->db->table('tickets_messages')
            ->where('staff_id', $id)
            ->set(['staff_id'=>0])
            ->update();
        $this->db->table('ticket_notes')
            ->where('staff_id', $id)
            ->set(['staff_id'=>0])
            ->update();
    }
    public function agentRoles()
    {
        $result = $this->roleModel->orderBy('role_id','asc')
        ->paginate(site_config('page_size'), 'default');
        return [
            'roles' => $result,
            'pager' => $this->roleModel->pager
        ];
    }

    public function getRoles()
    {
        $q = $this->roleModel->orderBy('role_id', 'asc')->get();
        if($q->getNumRows() == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function newRole($name)
    {
        $this->roleModel->protect(false);
        $this->roleModel->insert([
            'role_name' => esc($name)
        ]);
        $this->roleModel->protect(true);
        return $this->roleModel->getInsertID();
    }

    public function getRoleData($data=array(),$select='*')
    {
        $q = $this->roleModel->select($select)
            ->where($data)
            ->get(1);
        if($q->getNumRows() == 0){
            return null;
        }
        return $q->getRow();
    }

    public function updateRole($data=array(), $id=null){
        $this->roleModel->protect(false);
        $this->roleModel->update($id, $data);
        $this->roleModel->protect(true);
    }

    public function removeRole($id)
    {
        $this->roleModel->delete($id);
        $this->staffModel->where('role',$id)->set(['role' => 0])->update();
    }
    /*
     * ---------------------------------
     * Database queries
     * ---------------------------------
     */
    public function getRow($data=array(),$select='*')
    {
        $q = $this->staffModel->select($select)
            ->where($data)
            ->get(1);
        if($q->getNumRows() == 0){
            return null;
        }
        return $q->getRow();
    }

    public function update($data=array(), $id=''){
        if(!is_numeric($id)){
            $id = $this->getData('id');
        }
        $this->staffModel->protect(false);
        $this->staffModel->update($id, $data);
        $this->staffModel->protect(true);
    }

    public function getData($var)
    {
        return isset($this->user_data->{$var}) ? $this->user_data->{$var} : null;
    }

    public function lastLoginLogs()
    {
        $builder = $this->db->table('login_log');
        $q = $builder->where('staff_id', $this->getData('id'))
            ->limit(10)
            ->get();
        if($q->getNumRows() == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function getListAccess()
    {
        $fields = $this->db->getFieldNames('roles');
        $list = [];
        foreach($fields as $field){
            if($field != 'role_id' && $field != 'role_name'){
                $list[] = $field;
            }
        }
        return $list;
    }
}
<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use App\Models\Users;
use Config\Database;
use Config\Services;

class Client
{
    protected $usersModel;
    private $user_data;
    private $is_online = null;

    public function __construct()
    {
        $this->usersModel = new Users();
    }

    public function getData($field)
    {
        return isset($this->user_data->$field) ? $this->user_data->$field : null;
    }

    public function isOnline()
    {
        if(is_null($this->is_online)){
            $this->is_online = false;
            $session = Services::session();
            if(is_numeric(get_cookie('clientID')) && get_cookie('clientHash') != ''){
                return $this->is_online = $this->validateSession(get_cookie('clientID'), get_cookie('clientHash'));
            }

            if($session->has('clientID') && $session->has('clientHash')){
                return $this->is_online = $this->validateSession($session->get('clientID'), $session->get('clientHash'));
            }
        }
        return $this->is_online;
    }

    private function validateSession($client_id, $client_hash)
    {
        if(!$user_data = $this->usersModel->find($client_id)){
            return $this->logout();
        }

        $request = Services::request();
        if(!password_verify(md5($user_data->token.$request->getUserAgent()), $client_hash)){
            return $this->logout();
        }
        $this->user_data = $user_data;
        return true;
    }

    public function logout()
    {
        $session = Services::session();
        $session->destroy();
        set_cookie('clientID','');
        set_cookie('clientHash','');
        return redirect()->withCookies()->route('home');
    }

    public function login($client_id)
    {
        $this->usersModel->protect(false);
        $this->usersModel->update($client_id, [
            'last_login' => time()
        ]);
        $this->usersModel->protect(true);
        $this->createSession($client_id);
    }

    public function createSession($client_id)
    {
        $request = Services::request();
        $session = Services::session();
        $token = random_string('md5',60);
        $hash = password_hash(md5($token.$request->getUserAgent()), PASSWORD_BCRYPT);
        $this->usersModel->protect(false);
        $this->usersModel->update($client_id, [
            'token' => $token,
        ]);
        $this->usersModel->protect(true);
        $session->set([
            'clientID' => $client_id,
            'clientHash' => $hash
        ]);
        set_cookie('clientID', $client_id, 0);
        set_cookie('clientHash', $hash, 0);
    }

    public function getRow($data=array(),$select='*')
    {
        $q = $this->usersModel->select($select)
            ->where($data)
            ->get(1);
        if($q->resultID->num_rows == 0){
            return null;
        }
        return $q->getRow();
    }

    public function update($data=array(), $client_id='')
    {
        if(!is_numeric($client_id)){
            $client_id = $this->getData('id');
        }
        $this->usersModel->protect(false);
        $this->usersModel->update($client_id, $data);
        $this->usersModel->protect(true);
    }

    public function getClientID($email){
        $client_id = 0;
        if($user_data = $this->getRow(['email' => $email])){
            $client_id = $user_data->id;
        }
        return $client_id;
    }

    public function createAccount($name, $email, $phone, $location, $send_mail=true)
    {
        $this->usersModel->protect(false);
        $this->usersModel->insert([
            'fullname' => esc($name),
            'email' => esc($email),
            'registration' => time(),
            'phone' => esc($phone),
            'in_rokanhulu' => esc($location)
        ]);
        $this->usersModel->protect(true);
        $client_id = $this->usersModel->getInsertID();

        if($send_mail){
            $emails = new Emails();
            $emails->sendFromTemplate('new_user',[
                '%client_name%' => $name,
                '%client_email%' => $email,
                '%client_phone%' => $phone
            ], $email);
        }
        return $client_id;
    }

    public function createAccountClient($name, $email, $phone, $location)
    {      
        $this->usersModel->protect(false);
        $this->usersModel->insert([
            'fullname' => esc($name),
            'email' => esc($email),
            'registration' => time(),
            'phone' => esc($phone),
            'in_rokanhulu' => esc($location)
        ]);
        $this->usersModel->protect(true);
        $client_id = $this->usersModel->getInsertID();
        return $client_id;
    }

    public function manage()
    {
        $result = $this->usersModel->orderBy('id','desc')
            ->paginate(25, 'default');
        return [
            'result' => $result,
            'pager' => $this->usersModel->pager
        ];
    }

    public function deleteAccount($user_id)
    {
        $this->usersModel->protect(false);
        $this->usersModel->delete($user_id);
        $this->usersModel->protect(true);
        $tickets = Services::tickets();
        $ticketModel = new \App\Models\Tickets();
        $q = $ticketModel->select('id')
            ->where('user_id', $user_id)
            ->get();
        foreach ($q->getResult() as $item){
            $tickets->deleteTicket($item->id);
        }
    }
}
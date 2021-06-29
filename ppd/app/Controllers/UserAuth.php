<?php

/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers;

use Config\Services;

class UserAuth extends BaseController
{
    public function auth()
    {
        return view('frontend/auth', [
            'locale' => $this->locale
        ]);
    }
    public function getlogin()
    {
        if ($this->request->getPost('do') == 'submit') {
            $session = Services::session();
            $validation = Services::validation();
            $validation->setRule('email', 'Email', 'required|valid_email|is_not_unique[users.email]', [
                'required' => lang('Client.error.enterValidEmail'),
                'valid_email' => lang('Client.error.enterValidEmail'),
                'is_unique' => lang('Client.error.emailNotFound')
            ]);
            $validation->setRule('captcha', 'Captcha', 'required|in_list['. $session->getFlashdata('captcha') . ']', [
                'required' => lang('Client.error.enterValidCaptcha'),
                'in_list' => lang('Client.error.invalidCaptcha') 
            ]);
            
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $client_data = $this->client->getRow([
                    'email' => $this->request->getPost('email'),
                    'status' => 1]);
                $this->client->login($client_data->id);
                return redirect()->route('view_tickets');
            }
        }
        return view('frontend/getlogin', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null
        ]);
    }

    public function login()
    {
        if ($this->request->getPost('do') == 'submit') {
            $session = Services::session();
            $validation = Services::validation();
            $validation->setRule('email', 'email', 'required|valid_email');
            $validation->setRule('fullname', lang('Client.form.fullName'), 'required', [
                'required' => lang('Client.error.enterFullName')
            ]);
            $validation->setRule('email', 'Email', 'required|valid_email|is_unique[users.email]', [
                'required' => lang('Client.error.enterValidEmail'),
                'valid_email' => lang('Client.error.enterValidEmail'),
                'is_unique' => lang('Client.error.emailTaken'),
            ]);
            $validation->setRule('phone', lang('Client.form.phone'), 'required|min_length[10]|is_unique[users.phone]', [
                'required' => lang('Client.error.enterPhone'),
                'min_length' => lang('Client.error.enterPhone'),
                'is_unique' => lang('Client.error.phoneTaken'),
            ]);
            $validation->setRule('location', lang('Client.form.location'), 'required', [
                'required' => lang('Client.error.location'),
            ]);
            $validation->setRule('captcha', 'Captcha', 'required|in_list['. $session->getFlashdata('captcha') . ']', [
                'required' => lang('Client.error.enterValidCaptcha'),
                'in_list' => lang('Client.error.invalidCaptcha') 
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $client_id = $this->client->createAccount(
                    $this->request->getPost('fullname'),
                    $this->request->getPost('email'),
                    $this->request->getPost('phone'),
                    $this->request->getPost('location'),
                    email_template_status('new_user') === 1
                );
                $this->client->login($client_id);
                $this->session->setFlashdata('form_success', lang('Client.users.accountCreated'));
                return redirect()->route('submit_ticket');
            }
        }
        return view('frontend/login', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null
        ]);
    }

    public function profile()
    {
        $user_id = $this->client->getData('id');
        if(!$user = $this->client->getRow(['id' => $user_id])){
            return redirect()->route('login');
        }
        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation = Services::validation();
            $validation->setRule('email', 'email', 'required|valid_email');
            $validation->setRule('fullname', lang('Client.form.fullName'), 'required', [
                'required' => lang('Client.error.enterFullName')
            ]);
            if ($this->request->getPost('email') != $user->email) {
                $validation->setRule('email', 'email', 'required|valid_email|is_unique[users.email]', [
                    'required' => lang('Client.error.enterValidEmail'),
                    'valid_email' => lang('Client.error.enterValidEmail'),
                    'is_unique' => lang('Client.error.emailTaken')
                ]);
            }
            if ($this->request->getPost('phone') != $user->phone) {
                $validation->setRule('phone', lang('Client.form.phone'), 'required|min_length[10]|is_unique[users.phone]', [
                    'required' => lang('Client.error.enterPhone'),
                    'min_length' => lang('Client.error.enterPhone'),
                    'is_unique' => lang('Client.error.phoneTaken'),
                ]);
            }
            $validation->setRule('location', lang('Client.form.location'), 'required', [
                'required' => lang('Client.error.location'),
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $this->client->update(
                    [
                        'fullname' => $this->request->getPost('fullname'),
                        'email' => $this->request->getPost('email'),
                        'phone' => $this->request->getPost('phone'),
                        'in_rokanhulu' => $this->request->getPost('location')
                    ],
                    $user_id
                );
                $this->session->setFlashdata('form_success', lang('Client.account.profileUpdated'));
                return redirect()->route('profile');
            }
        }
        return view('frontend/profile', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null
        ]);
    }

    public function logout()
    {
        return $this->client->logout();
    }
}

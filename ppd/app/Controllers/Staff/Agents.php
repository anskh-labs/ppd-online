<?php

/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers\Staff;


use App\Controllers\BaseController;
use Config\Services;

class Agents extends BaseController
{
    /**
     * {locale}/staff/agents/agents
     */
    public function manage()
    {
        if (!$this->autorize('view_agents')) {
            return redirect()->route('staff_dashboard');
        }
        if ($this->request->getPost('do') == 'remove') {
            $this->staff->removeAgent($this->request->getPost('agent_id'));
            $this->session->setFlashdata('form_success', lang('Admin.agents.agentRemoved'));
            return redirect()->to(current_url());
        }
        $data = $this->staff->getAgentsWithRoles();
        return view('staff/agents', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'agents_list' => $data['agents'],
            'pager' => $data['pager']
        ]);
    }

    /**
     * {locale}/staff/agents/agents/edit/{id}
     */
    public function edit($agent_id)
    {
        if (!$this->autorize('change_agents')) {
            return redirect()->route('staff_dashboard');
        }
        if ($agent_id == $this->staff->getData('id') || !$agent = $this->staff->getRow(['id' => $agent_id])) {
            return redirect()->route('staff_agents');
        }
        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRule('fullname', lang('Admin.form.fullName'), 'required', [
                'required' => lang('Admin.error.enterFullName')
            ]);

            if ($this->request->getPost('username') != $agent->username) {
                $validation->setRule('username', lang('Admin.form.username'), 'required|alpha_dash|is_unique[staff.username]', [
                    'required' => lang('Admin.error.enterUsername'),
                    'alpha_dash' => lang('Admin.error.enterUsername'),
                    'is_unique' => lang('Admin.error.usernameTaken')
                ]);
            }
            if ($this->request->getPost('email') != $agent->email) {
                $validation->setRule('email', 'Email', 'required|valid_email|is_unique[staff.email]', [
                    'required' => lang('Admin.error.enterValidEmail'),
                    'valid_email' => lang('Admin.error.enterValidEmail'),
                    'is_unique' => lang('Admin.error.emailTaken'),
                ]);
            }
            if ($this->request->getPost('password')) {
                $validation->setRule('password', 'Password', 'required|min_length[6]', [
                    'required' => lang('Admin.error.enterPassword'),
                    'min_length' => lang('Admin.error.enterPassword')
                ]);
            }

            $validation->setRule('role', lang('Admin.form.role'), 'required', [
                'required' => lang('Admin.error.selectTypeAccess')
            ]);
            $validation->setRule('active', 'active', 'required|in_list[0,1]', [
                'required' => lang('Admin.error.invalidStatus'),
                'in_list' => lang('Admin.error.invalidStatus')
            ]);

            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $this->staff->updateAgent(
                    $agent->id,
                    $this->request->getPost('fullname'),
                    $this->request->getPost('username'),
                    $this->request->getPost('email'),
                    $this->request->getPost('password'),
                    $this->request->getPost('role'),
                    $this->request->getPost('active')
                );
                $this->session->setFlashdata('form_success', lang('Admin.agents.informationUpdated'));
                return redirect()->to(current_url());
            }
        }
        $roles = $this->staff->getRoles();
        return view('staff/agents_form', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'agent' => $agent,
            'roles' => $roles
        ]);
    }

    /**
     * {locale}/staff/agents/agents/new
     */
    public function create()
    {
        if (!$this->autorize('create_agents')) {
            return redirect()->route('staff_dashboard');
        }

        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'fullname' => 'required',
                'username' => 'required|alpha_dash|is_unique[staff.username]',
                'email' => 'required|valid_email|is_unique[staff.email]',
                'password' => 'required|min_length[6]',
                'role' => 'required',
                'active' => 'required|in_list[0,1]',
            ], [
                'fullname' => [
                    'required' => lang('Admin.error.enterFullName')
                ],
                'username' => [
                    'required' => lang('Admin.error.enterUsername'),
                    'alpha_dash' => lang('Admin.error.enterUsername'),
                    'is_unique' => lang('Admin.error.usernameTaken')
                ],
                'email' => [
                    'required' => lang('Admin.error.enterValidEmail'),
                    'valid_email' => lang('Admin.error.enterValidEmail'),
                    'is_unique' => lang('Admin.error.emailTaken'),
                ],
                'password' => [
                    'required' => lang('Admin.error.enterPassword'),
                    'min_length' => lang('Admin.error.enterPassword')
                ],
                'role' => [
                    'required' => lang('Admin.error.selectTypeAccess')
                ],
                'active' => [
                    'required' => lang('Admin.error.invalidStatus'),
                    'in_list' => lang('Admin.error.invalidStatus')
                ]
            ]);


            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $this->staff->newAgent(
                    $this->request->getPost('fullname'),
                    $this->request->getPost('username'),
                    $this->request->getPost('email'),
                    $this->request->getPost('password'),
                    $this->request->getPost('role'),
                    $this->request->getPost('active')
                );
                $this->session->setFlashdata('form_success', lang('Admin.agents.agentCreated'));
                return redirect()->to(current_url());
            }
        }
        $roles = $this->staff->getRoles();
        return view('staff/agents_form', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'roles' => $roles
        ]);
    }

    /**
     * {locale}/staff/agents/roles
     */
    public function roles()
    {
        if (!$this->autorize('view_roles')) {
            return redirect()->route('staff_dashboard');
        }
        if ($this->request->getPost('do') == 'remove') {
            $this->staff->removeRole($this->request->getPost('role_id'));
            $this->session->setFlashdata('form_success', lang('Admin.agents.roleRemoved'));
            return redirect()->to(current_url());
        }
        $data = $this->staff->agentRoles();
        $list_access = $this->staff->getListAccess();
        return view(
            'staff/agents_roles',
            [
                'roles' => $data['roles'],
                'list_access' => $list_access,
                'locale' => $this->locale,
                'pager' => $data['pager'],
                'error_msg' => isset($error_msg) ? $error_msg : null,
                'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null
            ]
        );
    }

    /**
     * {locale}/staff/agents/roles/new
     */
    public function newRole()
    {
        if (!$this->autorize('create_roles')) {
            return redirect()->route('staff_dashboard');
        }

        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'rolename' => 'required|alpha_dash|is_unique[roles.role_name]'
            ], [
                'rolename' => [
                    'required' => lang('Admin.error.enterRoleName'),
                    'alpha_dash' => lang('Admin.error.enterValidRoleName'),
                    'is_unique' => lang('Admin.error.roleNameTaken')
                ]
            ]);


            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $this->staff->newRole(
                    $this->request->getPost('rolename')
                );
                $this->session->setFlashdata('form_success', lang('Admin.agents.rolesCreated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/agents_roles_form', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null
        ]);
    }

    /**
     * {locale}/staff/agents/roles/edit/{id}
     */
    public function editRole($role_id)
    {
        if (!$this->autorize('change_roles')) {
            return redirect()->route('staff_dashboard');
        }
        if (!$role = $this->staff->getRoleData(['role_id' => $role_id])) {
            return redirect()->route('staff_agents_roles');
        }
        if ($this->request->getPost('action') == 'change_status') {
            $validation = Services::validation();
            $validation->setRules([
                'role_access' => 'required'
            ], [
                'role_access' => [
                    'required' => lang('Admin.error.enterRoleAccess')
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $access = $this->request->getPost('role_access');
                $this->staff->updateRole(
                    [$access => ($role->{$access} == 1 ? 0 : 1)],
                    $role->role_id
                );
                return redirect()->to(current_url());
            }
        }
        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'rolename' => 'required'
            ], [
                'rolename' => [
                    'required' => lang('Admin.error.enterRoleName')
                ]
            ]);
            if ($this->request->getPost('rolename') != $role->role_name) {
                $validation->setRules([
                    'rolename' => 'required|alpha_dash|is_unique[roles.role_name]'
                ], [
                    'rolename' => [
                        'required' => lang('Admin.error.enterRoleName'),
                        'alpha_dash' => lang('Admin.error.enterValidRoleName'),
                        'is_unique' => lang('Admin.error.roleNameTaken')
                    ]
                ]);
            }

            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $this->staff->updateRole(
                    ['role_name' => $this->request->getPost('rolename')],
                    $role->role_id
                );
                $this->session->setFlashdata('form_success', lang('Admin.agents.informationRoleUpdated'));
                return redirect()->to(current_url());
            }
        }
        $list_access = $this->staff->getListAccess();
        return view('staff/agents_roles_form', [
            'locale' => $this->locale,
            'list_access' => $list_access,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'role' => $role
        ]);
    }
}

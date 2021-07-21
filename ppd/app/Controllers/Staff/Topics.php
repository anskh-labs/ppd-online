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

class Topics extends BaseController
{
    /*
     * Topics
     */
    public function manage()
    {
        $topics = Services::topics();
        if ($this->request->getPost('do') == 'remove') {
            $topics->removeTopic($this->request->getPost('topic_id'));
            $this->session->setFlashdata('form_success', lang('Admin.top.topicRemoved'));
            return redirect()->to(current_url());
        }
        $data = $topics->getAll();
        return view('staff/topics', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => ($this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null),
            'top_list' => $data['topics'],
            'pager'=>$data['pager']
        ]);
    }

    public function create()
    {
        $topics = Services::topics();
        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'name' => 'required',
                'public' => 'required|in_list[0,1]'
            ], [
                'name' => [
                    'required' => lang('Admin.error.enterTopicName')
                ],
                'public' => [
                    'required' => lang('Admin.error.selectTopicType'),
                    'in_list' => lang('Admin.error.selectTopicType')
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $topics->create($this->request->getPost('name'), $this->request->getPost('public'));
                $this->session->setFlashdata('form_success', lang('Admin.cat.topicCreated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/topics_form', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'top_list' => $topics->publicTopics()
        ]);
    }

    public function edit($topic_id)
    {
        $topics = Services::topics();
        if (!$topic = $topics->getById($topic_id)) {
            return redirect()->route('staff_topics');
        }

        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'name' => 'required',
                'public' => 'required|in_list[0,1]'
            ], [
                'name' => [
                    'required' => lang('Admin.error.enterTopicName')
                ],
                'public' => [
                    'required' => lang('Admin.error.selectTopicType'),
                    'in_list' => lang('Admin.error.selectTopicType')
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $topics->update($topic->id, $this->request->getPost('name'), $this->request->getPost('public'));
                $this->session->setFlashdata('form_success', lang('Admin.cat.topicUpdated'));
                return redirect()->to(current_url());
            }
        }

        return view('staff/topics_form', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'topic' => $topic,
            'top_list' => $topics->publicTopics()
        ]);
    }
}

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

class Attachments extends BaseController
{
    private $attachmentLib;

    public function __construct()
    {
        $this->attachmentLib = Services::attachments();
    }

    public function manage()
    {
        if (!$this->autorize('view_attachments')) {
            return redirect()->route('staff_dashboard');
        }

        $attachments = Services::attachments();
        #Download
        if ($this->request->getGet('download')) {
            if (!$file = $attachments->getRow(['id' => $this->request->getGet('download')])) {
                $error_msg = lang('Admin.attachments.fileNotFound');
            } else {
                return $attachments->download($file);
            }
        } elseif (is_numeric($this->request->getGet('delete_file'))) {
            if (!$file = $attachments->getRow([
                'id' => $this->request->getGet('delete_file')
            ])) {
                return redirect()->to(current_url());
            } else {
                $attachments->deleteFile($file);
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.attachmentRemoved'));
                return redirect()->to(current_url());
            }
        }

        $data = $this->attachmentLib->getFiles();
        return view('staff/attachments', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'file_list' => $data['files'],
            'pager' => $data['pager']
        ]);
    }
}

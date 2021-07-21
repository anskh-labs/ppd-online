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

class Calendar extends BaseController
{
    private $calendarLib;

    public function __construct()
    {
        $this->calendarLib = Services::calendar();
    }

    public function manage()
    {
        if ($this->request->getPost('month_calendar')) {
            $cal = $this->buildCalendar($this->request->getPost('month_calendar'));
        } else {
            $cal = $this->buildCalendar();
        }
        $list_month = $this->calendarLib->getListMonth();
        $selected_month = date('n');

        return view('staff/calendar', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => ($this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null),
            'cal' => $cal,
            'list_month' => $list_month,
            'selected_month' => $selected_month
        ]);
    }

    public function buildCalendar($month = null)
    {
        $dateComponents = getdate();
        if (empty($month)) {
            $month = $dateComponents['mon'];
        }
        $year = $dateComponents['year'];
        return $this->calendarLib->buildCalendar($month, $year, $this->locale);
    }

    public function assign($dateString)
    {
        if (empty($dateString) || strlen($dateString)!=10) {
            return redirect()->route('staff_calendar');
        }
        $cal = $this->calendarLib->getWithStaff($dateString);

        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'date_calendar' => 'required',
                'staff_calendar' => 'required'
            ], [
                'date_calendar' => [
                    'required' => lang('Admin.error.enterDate')
                ],
                'staff_calendar' => [
                    'required' => lang('Admin.error.selectStaff')
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            } else {
                $staff_id = $this->request->getPost('staff_calendar');
                if($cal){
                    $this->calendarLib->updateCalendar($dateString, $staff_id);
                }else{
                    $this->calendarLib->addCalendar($dateString, $staff_id);
                }
                $this->session->setFlashdata('form_success', lang('Admin.cal.assignSuccess'));
                return redirect()->to(current_url());
            }
        }
        $staff_list = $this->staff->getOperator();
        return view('staff/calendar_form', [
            'locale' => $this->locale,
            'date_string' => $dateString,
            'staff_list' => $staff_list,
            'cal' => (isset($cal) ? $cal : null),
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => ($this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null),
        ]);
    }
}

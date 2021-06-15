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

class Categories extends BaseController
{
    /*
     * Categories
     */
    public function manage()
    {
        $cat = Services::categories();
        if ($this->request->getGet('action') == 'move_down' && is_numeric($this->request->getGet('id'))) {
            $cat->moveCategory($this->request->getGet('id'), false);
            return redirect()->to(current_url());
        } elseif ($this->request->getGet('action') == 'move_up' && is_numeric($this->request->getGet('id'))) {
            $cat->moveCategory($this->request->getGet('id'), true);
            return redirect()->to(current_url());
        }
        if ($this->request->getPost('do') == 'remove') {
            $cat->removeCategory($this->request->getPost('category_id'));
            $this->session->setFlashdata('form_success', lang('Admin.cat.categoryRemoved'));
            return redirect()->to(current_url());
        }
        return view('staff/categories', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => ($this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null),
            'cat_list' => $cat->getChildren(0, false, 0, ' - - - ')
        ]);
    }

    public function create()
    {
        $cat = Services::categories();
        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'name' => 'required',
                'parent' => 'required|is_natural',
                'public' => 'required|in_list[0,1]',
            ], [
                'name' => [
                    'required' => lang('Admin.error.enterCategoryName')
                ],
                'parent' => [
                    'required' => lang('Admin.error.selectParentCategory'),
                    'is_natural' => lang('Admin.error.selectParentCategory'),
                ],
                'public' => [
                    'required' => lang('Admin.error.selectCategoryType'),
                    'in_list' => lang('Admin.error.selectCategoryType'),
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            }else {
                $cat->insertCategory($this->request->getPost('name'), $this->request->getPost('parent'), $this->request->getPost('public'));
                $this->session->setFlashdata('form_success', lang('Admin.cat.categoryCreated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/categories_form', [
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'cat_list' => $cat->getChildren(0, false, 0, ' - - - '),
            'parent' => (is_numeric($this->request->getGet('parent')) ? $this->request->getGet('parent') : 0)
        ]);
    }

    public function edit($category_id)
    {
        $cat = Services::categories();
        if (!$category = $cat->getCategory($category_id, false)) {
            return redirect()->to('staff_categories');
        }

        if ($this->request->getPost('do') == 'submit') {
            $validation = Services::validation();
            $validation->setRules([
                'name' => 'required',
                'parent' => 'required|is_natural',
                'public' => 'required|in_list[0,1]',
            ], [
                'name' => [
                    'required' => lang('Admin.error.enterCategoryName')
                ],
                'parent' => [
                    'required' => lang('Admin.error.selectParentCategory'),
                    'is_natural' => lang('Admin.error.selectParentCategory'),
                ],
                'public' => [
                    'required' => lang('Admin.error.selectCategoryType'),
                    'in_list' => lang('Admin.error.selectCategoryType'),
                ]
            ]);
            if ($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            }else {
                $cat->updateCategory([
                    'name' => esc($this->request->getPost('name')),
                    'parent' => $this->request->getPost('parent'),
                    'public' => $this->request->getPost('public')
                ], $category->id);
                $this->session->setFlashdata('form_success', lang('Admin.cat.categoryUpdated'));
                return redirect()->to(current_url());
            }
        }

        return view('staff/categories_form', [
            'locale' =>$this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'category' => $category,
            'cat_list' => $cat->getChildren(0, false, 0, ' - - - ')
        ]);
    }
}

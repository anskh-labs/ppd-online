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

class Kb extends BaseController
{
    /*
     * Articles
     */
    public function articles($category=0)
    {
        $kb = Services::kb();
        if($this->request->getPost('do') == 'remove'){
            $kb->removeArticle($this->request->getPost('article_id'));
            $this->session->setFlashdata('form_success','Article has been removed.');
            return redirect()->to(current_url());
        }
        $pagination = $kb->articlesPagination($category);
        return view('staff/kb_articles',[
            'locale' => $this->locale,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'articles_result' => $pagination['result'],
            'pager' => $pagination['pager'],
            'category' => $category,
            'kb_list' => $kb->getChildren(0, false, 0, ' - - - '),
        ]);
    }

    public function newArticle()
    {
        $kb = Services::kb();
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRules([
                'title' => 'required',
                'category_id' => 'required|is_natural_no_zero',
                'public' => 'required|in_list[0,1]',
                'content' => 'required'
            ],[
                'title' => [
                    'required' => lang('Admin.error.enterTitle'),
                ],
                'category_id' => [
                    'required' => lang('Admin.error.selectCategory'),
                    'is_natural_no_zero' => lang('Admin.error.selectCategory'),
                ],
                'public' => [
                    'required' => lang('Admin.error.selectArticleType'),
                    'in_list' => lang('Admin.error.selectArticleType'),
                ],
                'content' => [
                    'required' => lang('Admin.error.enterContent')
                ]
            ]);

            if ($this->settings->config('kb_attachment')) {
                $max_size = $this->settings->config('kb_file_size') * 1024;
                $allowed_extensions = unserialize($this->settings->config('kb_file_type'));
                $allowed_extensions = implode(',', $allowed_extensions);
                $validation->setRule('attachment', 'attachment', 'ext_in[attachment,' . $allowed_extensions . ']|max_size[attachment,' . $max_size . ']', [
                    'ext_in' => lang('Admin.error.fileNotAllowed'),
                    'max_size' => lang_replace('Admin.error.fileBig', ['%size%' => number_to_size($max_size * 1024, 2)])
                ]);
            }

            if($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            }else{
                $attachments = Services::attachments();
                if ($this->settings->config('kb_attachment')) {
                    if ($uploaded_files = $attachments->articleUpload()) {
                        $files = $uploaded_files;
                    }
                }
                $article_id = $kb->addArticle(
                    $this->request->getPost('title'), 
                    $this->request->getPost('content'),
                    $this->request->getPost('category_id'), 
                    $this->request->getPost('public'));
                //File
                if (isset($files)) {
                    $attachments->addArticleFiles($article_id, $files);
                }
                $this->session->setFlashdata('form_success','New article has been created.');
                return redirect()->to(current_url());
            }
        }
        return view('staff/kb_articles_form',[
            'locale' => $this->locale,
            'error_msg' => (isset($error_msg) ? $error_msg : null),
            'success_msg' => ($this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null),
            'kb_list' => $kb->getChildren(0, false, 0, ' - - - '),
            'category_id' => (is_numeric($this->request->getGet('category_id')) ? $this->request->getGet('category_id') : 0)
        ]);
    }

    public function editArticle($article_id)
    {
        $kb = Services::kb();
        if(!$article = $kb->getArticle($article_id, false)){
            return redirect()->route('staff_kb_articles');
        }
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRules([
                'title' => 'required',
                'category_id' => 'required|is_natural_no_zero',
                'public' => 'required|in_list[0,1]',
                'content' => 'required'
            ],[
                'title' => [
                    'required' => lang('Admin.error.enterTitle'),
                ],
                'category_id' => [
                    'required' => lang('Admin.error.selectCategory'),
                    'is_natural_no_zero' => lang('Admin.error.selectCategory'),
                ],
                'public' => [
                    'required' => lang('Admin.error.selectArticleType'),
                    'in_list' => lang('Admin.error.selectArticleType'),
                ],
                'content' => [
                    'required' => lang('Admin.error.enterContent')
                ]
            ]);

            if($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            }else{
                $kb->updateArticle($article->id, $this->request->getPost('title'), $this->request->getPost('content'),
                    $this->request->getPost('category_id'), $this->request->getPost('public'));
                $this->session->setFlashdata('form_success','Article has been updated.');
                return redirect()->to(current_url());
            }
        }
        return view('staff/kb_articles_form',[
            'locale' => $this->locale,
            'error_msg' => (isset($error_msg) ? $error_msg : null),
            'success_msg' => ($this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null),
            'kb_list' => $kb->getChildren(0, false, 0, ' - - - '),
            'article' => $article
        ]);
    }
}
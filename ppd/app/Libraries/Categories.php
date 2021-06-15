<?php

/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;

use Config\Services;

class Categories
{
    private $allowed_cats;
    private $categoryModel;
    private $articlesModel;
    private $ticketsModel;
    public function __construct()
    {
        $this->categoryModel = new \App\Models\Categories();
        $this->articlesModel = new \App\Models\Articles();
        $this->ticketsModel = new \App\Models\Tickets();
        $this->allowed_cats = $this->publicCatList();
    }
    /*
     * ---------------------------------------------
     * Categories
     * ---------------------------------------------
     */
    private function publicCatList($parent = 0)
    {
        $q = $this->categoryModel->where('parent', $parent)
            ->where('public', 1)
            ->orderBy('position', 'asc')
            ->get();
        if ($q->resultID->num_rows == 0) {
            return null;
        }
        $list = array();
        $result = $q->getResult();
        $q->freeResult();
        foreach ($result as $item) {
            $list[] = $item;
            if ($subcat = $this->publicCatList($item->id)) {
                $list = array_merge($list, $subcat);
            }
        }
        return $list;
    }

    public function publicCategories()
    {
        return $this->allowed_cats;
    }
    public function isValid($id)
    {
        $q = $this->categoryModel->where('id', $id)
            ->countAllResults();
        return ($q == 0) ? false : true;
    }
    public function getCategory($id, $public = true)
    {
        if ($public) {
            $this->categoryModel->where('public', 1);
        }
        if ($category = $this->categoryModel->find($id)) {
            return $category;
        }
        return null;
    }

    public function getParents($parent_id)
    {
        if (!$category = $this->categoryModel->find($parent_id)) {
            return null;
        }
        $list[] = $category;
        if ($sub_parent = $this->getParents($category->parent)) {
            $list = array_merge($list, $sub_parent);
        }
        return $list;
    }

    public function getChildren($parent = 0, $public = true, $level = 1, $prepend = '')
    {
        if ($public) {
            $this->categoryModel->where('public', 1);
        }
        $q = $this->categoryModel->where('parent', $parent)
            ->orderBy('position', $parent)
            ->get();
        if ($q->resultID->num_rows == 0) {
            return null;
        }
        $list = array();
        $result = $q->getResult();
        $q->freeResult();;
        foreach ($result as $item) {
            $item->name = str_repeat($prepend, $level) . $item->name;
            $list[] = $item;
            if ($subcat = $this->getChildren($item->id, $public, $level + 1, $prepend)) {
                $list = array_merge($list, $subcat);
            }
        }
        return $list;
    }

    public function getCategories($parent = 0, $public = true)
    {
        if ($public) {
            $this->categoryModel->where('public', 1);
        }
        $q = $this->categoryModel->where('parent', $parent)
            ->orderBy('position')
            ->get();
        if ($q->resultID->num_rows == 0) {
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();;
        return $r;
    }

    public function insertCategory($name, $parent = 0, $public = 1)
    {
        $q = $this->categoryModel->select('position')
            ->where('parent', $parent)
            ->orderBy('position', 'desc')
            ->get(1);
        if ($q->resultID->num_rows == 0) {
            $position = 1;
        } else {
            $r = $q->getRow();
            $position = $r->position + 1;
        }
        $this->categoryModel->protect(false);
        $this->categoryModel->insert([
            'name' => esc($name),
            'position' => $position,
            'parent' => $parent,
            'public' => $public
        ]);
        $this->categoryModel->protect(true);
        return $this->categoryModel->getInsertID();
    }

    public function updateCategory($data, $id)
    {
        $this->categoryModel->protect(false);
        $this->categoryModel->update($id, $data);
        $this->categoryModel->protect(true);
    }

    public function moveCategory($category_id, $up = true)
    {
        $category = $this->getCategory($category_id, false);
        if ($up) {
            $q = $this->categoryModel->select('id, position')
                ->where('parent', $category->parent)
                ->where('position<', $category->position)
                ->orderBy('position', 'desc')
                ->get(1);
        } else {
            $q = $this->categoryModel->select('id, position')
                ->where('parent', $category->parent)
                ->where('position>', $category->position)
                ->orderBy('position', 'asc')
                ->get(1);
        }
        if ($q->resultID->num_rows == 0) {
            return false;
        }
        $other = $q->getRow();
        $this->updateCategory([
            'position' => $other->position
        ], $category->id);
        $this->updateCategory([
            'position' => $category->position
        ], $other->id);
        return true;
    }

    public function moveUpOrDownLink($category_id, $parent)
    {
        $q = $this->categoryModel->select('id, position')
            ->where('parent', $parent)
            ->orderBy('position', 'desc')
            ->get(1);
        $last = $q->getRow();
        $q = $this->categoryModel->select('id, position')
            ->where('parent', $parent)
            ->orderBy('position', 'asc')
            ->get(1);
        $first = $q->getRow();
        if ($last->id == $first->id) {
            return null;
        }
        $html = '';
        if ($last->id != $category_id) {
            $html .= '<a class="btn btn-outline-primary" href="' . current_url() . '?action=move_down&id=' . $category_id . '"><i class="fa fa-angle-down"></i></a>';
        }
        if ($first->id != $category_id) {
            $html .= '<a class="btn btn-outline-primary" href="' . current_url() . '?action=move_up&id=' . $category_id . '"><i class="fa fa-angle-up"></i></a>';
        }
        return $html;
    }

    public function removeCategory($category_id)
    {
        if ($articles = $this->getArticles($category_id, false)) {
            foreach ($articles as $article) {
                $this->removeArticle($article->id);
            }
        }
        if ($tickets = $this->getTickets($category_id)) {
            foreach ($tickets as $ticket) {
                $this->removeTicket($ticket->id);
            }
        }
        if ($children = $this->getChildren($category_id, false)) {
            foreach ($children as $item) {
                $this->removeCategory($item->id);
            }
        }
        $this->categoryModel->delete($category_id);
    }
    /*
     * ---------------------------------------------------
     * Articles
     * ----------------------------------------------------
     */
    public function getArticles($cat_id, $public = true)
    {
        if ($public) {
            $this->articlesModel->where('public', 1);
        }
        $q = $this->articlesModel->where('category', $cat_id)
            ->orderBy('date', 'desc')
            ->get();
        if ($q->resultID->num_rows == 0) {
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }
    public function removeArticle($id)
    {
        $attachments = Services::attachments();
        $attachments->deleteFiles([
            'article_id' => $id
        ]);
        $this->articlesModel->delete($id);
    }
    /*
     * ---------------------------------------------------
     * Tickets
     * ----------------------------------------------------
     */
    public function getTickets($cat_id)
    {
        $q = $this->ticketsModel->where('category', $cat_id)
            ->orderBy('date', 'desc')
            ->get();
        if ($q->resultID->num_rows == 0) {
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }
    public function removeTicket($id)
    {
        $attachments = Services::attachments();
        $attachments->deleteFiles([
            'ticket_id' => $id
        ]);
        $this->ticketsModel->delete($id);
    }
}

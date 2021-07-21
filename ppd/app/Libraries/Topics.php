<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use Config\Services;

class Topics
{
    private $topicsModel;
    private $ticketModel;
    private $allowed_topics;
    public function __construct()
    {
        $this->topicsModel = new \App\Models\Topics();
        $this->ticketModel = new \App\Models\Tickets();
        $this->allowed_topics = $this->publicTopicList();
    }
    private function publicTopicList()
    {
        $q = $this->topicsModel->where('public', 1)
            ->get();
        if ($q->getNumRows() == 0) {
            return null;
        }
        $list = $q->getResult();
        $q->freeResult();
        return $list;
    }
    public function publicTopics()
    {
        if(!$this->allowed_topics){
            $this->allowed_topics = $this->publicTopicList();
        }
        return $this->allowed_topics;
    }
    public function getAll()
    {
        $result = $this->topicsModel->select('*')
        ->orderBy('id', 'asc')
        ->paginate(site_config('page_size'), 'default');
        return [
            'topics' => $result,
            'pager' => $this->topicsModel->pager
        ];
    }

    public function getByID($id)
    {
        if($topic = $this->topicsModel->find($id)){
            return $topic;
        }
        return null;
    }

    public function count()
    {
        return $this->topicsModel->countAll();
    }

    public function isValid($id)
    {
        $q = $this->topicsModel->where('id', $id)
            ->countAllResults();
        return ($q == 0) ? false : true;
    }

    public function countTickets($topic_id)
    {
        return $this->ticketModel->where('topic', $topic_id)
            ->countAllResults();
    }


    public function removeTopic($id)
    {
        $tickets = Services::tickets();
        $q = $this->ticketModel->select('id')
            ->where('topic', $id)
            ->get();
        if($q->getNumRows() > 0){
            foreach ($q->getResult() as $item){
                $tickets->deleteTicket($item->id);
            }
            $q->freeResult();
        }
        $this->topicsModel->delete($id);
        return true;
    }

    public function create($name, $public = true){
        $this->topicsModel->protect(false);
        $this->topicsModel->insert([
            'name' => esc($name),
            'public' => (($public == true) ? 1 : 0)
        ]);
        $this->topicsModel->protect(true);
        return $this->topicsModel->getInsertID();
    }

    public function update($id, $name, $public = true)
    {
        $this->topicsModel->protect(false);
        $this->topicsModel->update($id, [
            'name' => esc($name),
            'public' => (($public == true) ? 1 : 0)
        ]);
        $this->topicsModel->protect(true);
    }
}
<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers;


class Pages extends BaseController
{
    public function maintenance()
    {
        if($this->settings->config('maintenance') == '0'){
            return redirect()->route('home');
        }
        return view('frontend/maintenance',[
            'locale' => $this->locale,
            'body' => $this->settings->config('maintenance_message'),
        ]);
    }

}
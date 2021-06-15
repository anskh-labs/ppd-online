<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */
namespace App\Models;
use CodeIgniter\Model;

class CalendarModel extends Model
{
    protected $table      = 'calendar';
    protected $primaryKey = 'calendar_date';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
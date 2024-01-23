<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace Config;


use CodeIgniter\Config\BaseConfig;

class Helpdesk extends BaseConfig
{
    #Database host
    const DB_HOST = 'localhost';

    #Database username
    const DB_USER = 'root';

    #Database password
    const DB_PASSWORD = '';

    #Database name
    const DB_NAME = 'ppd-online';

    #Database table prefix
    const DB_PREFIX = 'ppd_';

    #Database port, do not change it if you are not sure
    const DB_PORT = 3306;

    #URL of your helpdesk
    const SITE_URL = 'http://localhost/ppd-online/';

    #Upload path for images used in HTML editor and logo
    const UPLOAD_PATH = FCPATH.'upload';

    #Default helpdesk language
    const DEFAULT_LANG = 'id';

    #URI name to access to staff panel. Ex: staff / then you can access in localhost/ppd-online/{locale}/staff
    const STAFF_URI = 'staff';
}
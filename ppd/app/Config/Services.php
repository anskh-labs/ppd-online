<?php namespace Config;

use App\Libraries\Categories;
use App\Libraries\Api;
use App\Libraries\Attachments;
use App\Libraries\Calendar;
use App\Libraries\Client;
use App\Libraries\Kb;
use App\Libraries\Settings;
use App\Libraries\Staff;
use App\Libraries\Tickets;
use App\Libraries\Emails;
use App\Libraries\Topics;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends \CodeIgniter\Config\Services
{
    public static function settings($getShared = true)
    {
        if($getShared){
            return static::getSharedInstance('settings');
        }
        return new Settings();
    }

    public static function kb($getShared = true)
    {
        if($getShared){
            return static::getSharedInstance('kb');
        }
        return new Kb();
    }

    public static function attachments($getShared = true)
    {
        if($getShared){
            return static::getSharedInstance('attachments');
        }
        return new Attachments();
    }

    public static function client($getShared = true)
    {
        if($getShared){
            return static::getSharedInstance('client');
        }
        return new Client();
    }

    public static function staff($getShared = true)
    {
        if($getShared){
            return static::getSharedInstance('staff');
        }
        return new Staff();
    }

    public static function tickets($getShared = true)
    {
        if($getShared){
            return static::getSharedInstance('tickets');
        }
        return new Tickets();
    }

    public static function categories($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('categories');
        }
        return new Categories();
    }

    public static function emails($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('emails');
        }
        return new Emails();
    }

    public static function topics($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('topics');
        }
        return new Topics();
    }
    public static function calendar($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('calendar');
        }
        return new Calendar();
    }
    public static function api($getShared = true)
    {
        if($getShared){
            return static::getSharedInstance('api');
        }
        return new Api();
    }

}

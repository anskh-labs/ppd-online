<?php

use Config\Services;

function set_timezone($timezone)
{
    if ($timezone != '' && in_array($timezone, timezone_identifiers_list())) {
        date_default_timezone_set($timezone);
    }
}
function site_logo()
{
    return \Config\Services::settings()->getLogo();
}

function site_config($var)
{
    return \Config\Services::settings()->config($var);
}

function client_online()
{
    return \Config\Services::client()->isOnline();
}

function client_data($var)
{
    return \Config\Services::client()->getData($var);
}

function staff_data($var)
{
    return \Config\Services::staff()->getData($var);
}

function staff_avatar($avatarFile = '')
{
    $default_avatar = base_url('assets/helpdeskz/images/agent.jpg');
    if ($avatarFile == '') {
        return $default_avatar;
    }
    $upload_avatar = \Config\Helpdesk::UPLOAD_PATH . DIRECTORY_SEPARATOR . $avatarFile;
    if (!file_exists($upload_avatar)) {
        return $default_avatar;
    }
    return base_url('upload/' . $avatarFile);
}

function user_avatar($avatarFile = '')
{
    $default_avatar = base_url('assets/helpdeskz/images/user.jpg');
    if ($avatarFile == '') {
        return $default_avatar;
    }
    $upload_avatar = \Config\Helpdesk::UPLOAD_PATH . DIRECTORY_SEPARATOR . $avatarFile;
    if (!file_exists($upload_avatar)) {
        return $default_avatar;
    }
    return base_url('upload/' . $avatarFile);
}

function staff_info($staff_id)
{
    static $staffList;
    if ($staff_id == 0) {
        return ['fullname' => '-', 'avatar' => staff_avatar()];
    }
    if (!is_array($staffList) || !isset($staffList[$staff_id])) {
        if (!$data = \Config\Services::staff()->getRow(['id' => $staff_id], 'fullname, avatar')) {
            return $staffList[$staff_id] = ['fullname' => '-', 'avatar' => staff_avatar()];
        } else {
            return $staffList[$staff_id] = ['fullname' => $data->fullname, 'avatar' => staff_avatar($data->avatar)];
        }
    } else {
        return $staffList[$staff_id];
    }
}

function uri_page()
{
    static $page;
    if (!$page) {
        $uri = \Config\Services::uri();
        $page = $uri->getSegment(2);
    }
    return $page;
}
function uri_page_staff()
{
    static $page;
    if (!$page) {
        $uri = \Config\Services::uri();
        $page = $uri->getSegment(3);
    }
    return $page;
}
function full_url()
{
    static $fullUrl;
    if(!$fullUrl){
        $uri = \Config\Services::uri();
        $fullUrl = $uri->__toString();
    }
    return $fullUrl;
}
/*
* Tickets
*/
function count_tickets_category($category_id)
{
    return \Config\Services::tickets()->countTickets(['category' => $category_id]);
}
function count_tickets_topic($topic_id)
{
    return \Config\Services::tickets()->countTickets(['topic' => $topic_id]);
}
/*
* Categories 
*/
function cat_move_link($category_id, $parent)
{
    return \Config\Services::categories()->moveUpOrDownLink($category_id, $parent);
}
/*
 * Knowledge Base
 */
function kb_parents($parent_id)
{
    return \Config\Services::kb()->getParents($parent_id);
}
function kb_categories($parent = 0, $public = true)
{
    return \Config\Services::kb()->getCategories($parent, $public);
}
function kb_count_articles($category_id, $public = true)
{
    return \Config\Services::kb()->countArticles($category_id, $public);
}

function kb_count_articles_category($category_id, $public = true)
{
    return \Config\Services::kb()->totalArticlesInCat($category_id, $public);
}
function kb_articles_category($category_id, $public = true)
{
    return \Config\Services::kb()->articlesUnderCategory($category_id, $public);
}

function kb_articles($category_id, $public = true)
{
    return \Config\Services::kb()->getArticles($category_id, $public);
}

function kb_popular($public = 1)
{
    return \Config\Services::kb()->popularArticles($public);
}

function kb_newest($public = 1)
{
    return \Config\Services::kb()->newestArticles($public);
}

function resume_content($text, $chars, $clean_html = true)
{
    if ($clean_html) {
        $text = strip_tags($text);
    }
    if (strlen($text) > $chars) {
        return substr($text, 0, ($chars - 3)) . '...';
    } else {
        return $text;
    }
}

#Language
function lang_replace($var, $field, $value = null)
{
    if (!is_array($field)) {
        $field = array($field => $value);
    }
    return str_replace(array_keys($field), array_values($field), lang($var));
}
function change_locale_url($locale)
{
    if (empty(uri_page())) {
        return str_replace(
            '/' . $locale,
            '/' . change_lang($locale),
            full_url()
        );
    }
    return str_replace(
        '/' . $locale . '/',
        '/' . change_lang($locale) . '/',
        full_url()
    );
}
function change_lang(string $locale)
{
    return $locale === 'id' ? 'en' : 'id';
}
function current_locale()
{
    return \Config\Services::request()->getLocale();
}
#Attachments
function article_files($article_id)
{
    return \Config\Services::attachments()->getList(['article_id' => $article_id]);
}

function ticket_files($ticket_id, $msg_id)
{
    return \Config\Services::attachments()->getList(['ticket_id' => $ticket_id, 'msg_id' => $msg_id]);
}

#Date
function dateFormat($data)
{
    return date(site_config('date_format'), $data);
}

#tickets
function count_tickets($status)
{
    return \Config\Services::staff()->countTicketsByStatus($status);
}

function sort_link($id, $string)
{
    $url = current_url(true);
    $url = parse_url($url);
    $query = (isset($url['query'])) ? $url['query'] : '';
    parse_str($query, $output);
    if (isset($output['sort']) && $output['sort'] == $id) {
        if (isset($output['order']) && $output['order'] == 'DESC') {
            $output['order'] = 'ASC';
            $icon = '<i class="fa fa-caret-down"></i></a>';
        } else {
            $output['order'] = 'DESC';
            $icon = '<i class="fa fa-caret-up"></i></a>';
        }
    } else {
        $output['order'] = 'DESC';
        $icon = '<i class="fa fa-sort"></i></a>';
    }
    $output['sort'] = $id;

    $query = http_build_query($output);
    return '<span data-href="' . current_url() . '?' . $query . '" class="pointer">' . $string . ' ' . $icon . '</span>';
}

function isOverdue($date, $status)
{
    return \Config\Services::tickets()->isOverdue($date, $status);
}

function time_ago($time, $staff = true)
{

    if (date("d-m-Y", $time) == date("d-m-Y")) {
        $lang = ($staff ? 'Admin.form.todayAt' : 'Client.todayAt');
        return lang_replace($lang, ['%date%' => date('h:i a', $time)]);
    } elseif (date("d-m-Y", $time) == date('d-m-Y', strtotime("+1 day ago"))) {
        $lang = ($staff ? 'Admin.form.yesterdayAt' : 'Client.yesterdayAt');
        return lang_replace($lang, ['%date%' => date('h:i a', $time)]);
    } else {
        return dateFormat($time);
    }
}

function count_status($status)
{
    return \Config\Services::tickets()->countStatus($status);
}

function max_file_size()
{
    static $file_size;
    if (!$file_size) {
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $max_post = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        $file_size = min($max_upload, $max_post, $memory_limit);
        $file_size = $file_size * 1024;
    }
    return $file_size;
}

function countCategoryTickets($category_id)
{
    return \Config\Services::categories()->countTickets($category_id);
}

function getTopics()
{
    return \Config\Services::topics()->getAll();
}

function getTopicByID($topic_id)
{
    return \Config\Services::topics()->getByID($topic_id);
}

function getCategories($onlyPublic = true)
{
    return ($onlyPublic === true)
        ? \Config\Services::categories()->getPublic()
        : \Config\Services::categories()->getAll();
}

/*
 * -----------------------------------------
 * Encode/Decode data
 * -----------------------------------------
 */
function str_encode($str)
{
    if ($str == '') {
        return '';
    } else {
        $ascii = strrev(base64_encode(strrev($str)));
        $hex = '';
        for ($i = 0; $i < strlen($ascii); $i++) {
            $byte = strtoupper(dechex(ord($ascii[$i])));
            $byte = str_repeat('0', 2 - strlen($byte)) . $byte;
            $hex .= $byte;
        }
        return $hex;
    }
}

function str_decode($str)
{
    if ($str == '') {
        return '';
    } else {
        $hex = $str;
        $ascii = '';
        $hex = str_replace(" ", "", $hex);
        for ($i = 0; $i < strlen($hex); $i = $i + 2) {
            $ascii .= chr(hexdec(substr($hex, $i, 2)));
        }
        return strrev(base64_decode(strrev($ascii)));
    }
}
/**
 * string function
 */
function starts_with($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}
function ends_with($string, $endString)
{
    $len = strlen($endString);
    if ($len == 0) {
        return true;
    }
    return (substr($string, -$len) === $endString);
}
/**
 * template
 */
function email_template_status($var)
{
    return \Config\Services::emails()->getTemplate($var)->status;
}
/**
 * Captcha
 */
function captcha()
{
    $session = Services::session();
    // string yang akan diacak membentuk captcha 0-z dan sebanyak 6 karakter
    $captcha = \substr(\str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,6); 
    //set session dengan nama captcha dimana isinya adalah isi dari variabel $captcha
    $session->setFlashdata('captcha', $captcha);
    
    $pic = \imagecreate(60,20);// ukuran kotak width=60 dan height=20
    $box_color = \imagecolorallocate($pic,217, 217, 217); // membuat warna box
    $text_color = \imagecolorallocate($pic,0,0,0); // membuat warna tulisan
    \imagefilledrectangle($pic,0,0,50,20,$box_color);
    \imagestring($pic,10,3,3,$captcha,$text_color);
    
    \ob_start();
    // Output the image
    \imagejpeg($pic);
    // Free up memory
    \imagedestroy($pic);
    $binary = \ob_get_clean();
    return '<img src="data:image/jpeg;base64,' . \base64_encode($binary) . '">';
}
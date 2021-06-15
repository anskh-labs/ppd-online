<?php

/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;

use App\Models\CalendarModel;
use Config\Database;
use Config\Services;

class Calendar
{
     private $model;

     public function __construct()
     {
          $this->model = new CalendarModel();
     }
     public function addCalendar($date,$staff_id,$note=null){
          $this->model->protect(false);
          $this->model->insert([
              'calendar_date' => $date,
              'staff_id' => $staff_id,
              'note' => $note
          ]);
          $this->model->protect(true);
          return $this->model->getInsertID();
      }
  
      public function updateCalendar($date, $staff_id,$note=null)
      {
          $this->model->protect(false);
          $this->model->update($date, [
              'staff_id' => esc($staff_id),
              'note' => $note
          ]);
          $this->model->protect(true);
      }

     public function getWithStaff($dateString)
     {
          $q = $this->model->select('calendar.*, s.username as staff_name')
               ->where('calendar_date', $dateString)
               ->join('staff as s', 's.id=calendar.staff_id', 'left')
               ->get(1);
          if ($q->resultID->num_rows == 0) {
               return null;
          }
          return $q->getRow();
     }

     public function getRow($data = array(), $select = '*')
     {
          $q = $this->model->select($select)
               ->where($data)
               ->get(1);
          if ($q->resultID->num_rows == 0) {
               return null;
          }
          return $q->getRow();
     }

     public function buildCalendar($month, $year, $locale)
     {

          // Draw table for Calendar 
          $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
          $headings = [
               'id' => ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
               'en' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
          ];
          // Draw Calendar table headings 
          $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings[$locale]) . '</td></tr>';

          //days and weeks variable for now ... 
          $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
          $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
          $days_in_this_week = 1;
          $day_counter = 0;

          // row for week one 
          $calendar .= '<tr class="calendar-row">';

          // Display "blank" days until the first of the current week 
          for ($x = 0; $x < $running_day; $x++) {
               $calendar .= '<td class="calendar-day-np">&nbsp;</td>';
               $days_in_this_week++;
          }

          // Show days.... 
          for ($list_day = 1; $list_day <= $days_in_month; $list_day++) {
               if ($list_day == date('d') && $month == date('n')) {
                    $currentday = ' currentday';
               } else {
                    $currentday = '';
               }
               $calendar .= '<td class="calendar-day' . $currentday . '">';

               // Add in the day number
               $strmonth = str_pad($month, 2, "0", STR_PAD_LEFT);
               $strday = str_pad($list_day, 2, "0", STR_PAD_LEFT);
               $thisDay = "{$year}-{$strmonth}-{$strday}";
               if ($list_day < date('d') && $month == date('n')) {
                    $showtoday = '<a href="' . current_url() . '/assign' . "/{$thisDay}" . '"><strong class="overday">' . $list_day . '</strong></a>';
               } else {
                    $showtoday = '<a href="' . current_url() . '/assign' . "/{$thisDay}" . '">' . $list_day . '</a>';
               }
               $calData = $this->getWithStaff($thisDay);
               $calendar .= '<div class="day-number">' . $showtoday . '<br>' . (isset($calData) ? $calData->staff_name : '') . '</div>';

               // Draw table end
               $calendar .= '</td>';
               if ($running_day == 6) {
                    $calendar .= '</tr>';
                    if (($day_counter + 1) != $days_in_month) {
                         $calendar .= '<tr class="calendar-row">';
                    }
                    $running_day = -1;
                    $days_in_this_week = 0;
               }
               $days_in_this_week++;
               $running_day++;
               $day_counter++;
          }

          // Finish the rest of the days in the week
          if ($days_in_this_week < 8) {
               for ($x = 1; $x <= (8 - $days_in_this_week); $x++) {
                    $calendar .= '<td class="calendar-day-np">&nbsp;</td>';
               }
          }

          // Draw table final row
          $calendar .= '</tr>';

          // Draw table end the table 
          $calendar .= '</table>';
          // Finally all done, return result 
          return $calendar;
     }

     public function getListMonth()
     {
          return [
               '1' => lang('Admin.cal.jan'),
               '2' => lang('Admin.cal.feb'),
               '3' => lang('Admin.cal.mar'),
               '4' => lang('Admin.cal.apr'),
               '5' => lang('Admin.cal.may'),
               '6' => lang('Admin.cal.jun'),
               '7' => lang('Admin.cal.jul'),
               '8' => lang('Admin.cal.agu'),
               '9' => lang('Admin.cal.sep'),
               '10' => lang('Admin.cal.okt'),
               '11' => lang('Admin.cal.nov'),
               '12' => lang('Admin.cal.des'),
          ];
     }
}

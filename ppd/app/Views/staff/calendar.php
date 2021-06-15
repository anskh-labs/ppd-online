<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('staff/template');
$this->section('css_block');
?>
<style>
@charset "utf-8";
/* CSS Document */
table.calendar{border-style: solid; border-width: 1px; border-width:1px; border-color:#666; -moz-box-shadow:0px 0px 4px #CCCCCC; -webkit-box-shadow:0px 0px 4px #CCCCCC; box-shadow:0px 0px 4px #CCCCCC; }
tr.calendar-row  {  }
td.calendar-day  { min-height:80px; position:relative; } * html div.calendar-day { height:80px; }
td.calendar-day:hover  { background:#FFF; -moz-box-shadow:0px 0px 20px #eeeeee inset; -webkit-box-shadow:0px 0px 20px #eeeeee inset; box-shadow:0px 0px 20px #eeeeee inset;}
td.calendar-day-np  { background:#eee; min-height:80px; } * html div.calendar-day-np { height:80px; }
td.calendar-day-head {font-weight:bold; text-shadow:0px 1px 0px #FFF;color:#666; text-align:center; width:64px; padding:12px 6px; border-bottom:1px solid #CCC; border-top:1px solid #CCC; border-right:1px solid #CCC; background: #ffffff;
background: -moz-linear-gradient(top,  #ffffff 0%, #ededed 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#ededed));
background: -webkit-linear-gradient(top,  #ffffff 0%,#ededed 100%);
background: -o-linear-gradient(top,  #ffffff 0%,#ededed 100%);
background: -ms-linear-gradient(top,  #ffffff 0%,#ededed 100%);
background: linear-gradient(to bottom,  #ffffff 0%,#ededed 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
}
div.day-number{padding:6px; text-align:center; }
/* shared */
td.calendar-day, td.calendar-day-np {padding:5px; border-bottom:1px solid #DBDBDB; border-right:1px solid #DBDBDB; font-size:14px;background: #ffffff;
background: -moz-linear-gradient(top,  #ffffff 0%, #f2f2f2 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f2f2f2));
background: -webkit-linear-gradient(top,  #ffffff 0%,#f2f2f2 100%);
background: -o-linear-gradient(top,  #ffffff 0%,#f2f2f2 100%);
background: -ms-linear-gradient(top,  #ffffff 0%,#f2f2f2 100%);
background: linear-gradient(to bottom,  #ffffff 0%,#f2f2f2 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f2f2f2',GradientType=0 );
}


.overday{ color:#164b87; text-shadow:0px 1px 0px #FFF;}
.currentday{background: #6cb7f3 !important;
background: -moz-linear-gradient(top,  #6cb7f3 0%, #388be8 100%) !important;
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#6cb7f3), color-stop(100%,#388be8)) !important;
background: -webkit-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
background: -o-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
background: -ms-linear-gradient(top,  #6cb7f3 0%,#388be8 100%) !important;
background: linear-gradient(to bottom,  #6cb7f3 0%,#388be8 100%) !important;
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6cb7f3', endColorstr='#388be8',GradientType=0 ) !important; color:#FFF  !important; font-weight:bold; -moz-box-shadow:0px 0px 18px #1F68BA inset; -webkit-box-shadow:0px 0px 18px #1F68BA inset; box-shadow:0px 0px 18px #1F68BA inset;
}
.currentday:hover{-moz-box-shadow:0px 0px 24px #074080 inset !important; -webkit-box-shadow:0px 0px 24px #074080 inset !important; box-shadow:0px 0px 24px #074080 inset !important;}

</style>
<?php
$this->endSection();
$this->section('content');
?>
<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle"><?php echo lang('Admin.cal.menu'); ?></span>
        <h3 class="page-title"><?php echo lang('Admin.cal.calendar'); ?></h3>
    </div>
</div>
<!-- End Page Header -->

<?php
if (isset($error_msg)) {
    echo '<div class="alert alert-danger">' . $error_msg . '</div>';
}
if (isset($success_msg)) {
    echo '<div class="alert alert-success">' . $success_msg . '</div>';
}

?>
<div class="card">
    <div class="card-body">
    <?php echo form_open('', ['id' => 'manageForm'], ['do' => 'remove']) ;?>
        <div class="row">
            <div class="form-group">
                <label><?php echo lang('Admin.cal.month'); ?></label>
                <select name="month_calendar" class="form-group custom-select">
                    <?php
                    $default = set_value('month_calendar', (isset($selectedMonth) ? $selectedMonth : date('n')));    
                    foreach ($list_month as $k => $v) {
                        if ($default == $k) {
                            echo '<option value="' . $k . '" selected>' . $v . '</option>';
                        } else {
                            echo '<option value="' . $k . '">' . $v . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row mb-5">
            <button type="submit" class="btn btn-primary"><?= lang('Admin.form.submit')?></button>
            <?php echo form_close(); ?>
        </div>
        <div class="row">
            <?=$cal?>
        </div>
    </div>
</div>

<?php
$this->endSection();

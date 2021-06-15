<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('staff/template');
$this->section('content');
?>
    <!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.cat.menu');?></span>
            <h3 class="page-title"><?php echo isset($cal) ? lang('Admin.cal.editAssignment') : lang('Admin.cal.newAssignment');?></h3>
        </div>
    </div>
    <!-- End Page Header -->



    <div class="card">
        <div class="card-body">
            <?php
            if(isset($error_msg)){
                echo '<div class="alert alert-danger">'.$error_msg.'</div>';
            }
            if(isset($success_msg)){
                echo '<div class="alert alert-success">'.$success_msg.'</div>';
            }
            echo form_open('',[],['do'=>'submit']);
            ?>
            <div class="form-group">
                <label><?php echo lang('Admin.form.date');?></label>
                <input type="text" class="form-control" name="date_calendar" value="<?php echo set_value('date_calendar', $date_string);?>" readonly>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.form.staff');?></label>
                <select name="staff_calendar" class="form-control custom-select">
                    <option value="0"> - - - - - - - - - - </option>
                    <?php
                    $default = set_value('staff_calendar', (isset($cal) ? $cal->staff_id : "0"));
                    if(isset($staff_list)){
                        foreach ($staff_list as $item){
                            if($default == $item->id){
                                echo '<option value="'.$item->id.'" selected>'.$item->username.'</option>';
                            }else{
                                echo '<option value="'.$item->id.'">'.$item->username.'</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Admin.form.submit');?></button>
                <a href="<?php echo site_url(route_to('staff_calendar'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
<?php
$this->endSection();
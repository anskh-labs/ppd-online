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
            <span class="text-uppercase page-subtitle"><?php echo lang('Admin.tools.customFields');?></span>
            <h3 class="page-title"><?php echo isset($customField) ? lang('Admin.tools.editCustomField') : lang('Admin.tools.newCustomField');?></h3>
        </div>
    </div>
    <!-- End Page Header -->


<?php
if(isset($error_msg)){
    echo '<div class="alert alert-danger">'.$error_msg.'</div>';
}
if(isset($success_msg)){
    echo '<div class="alert alert-success">'.$success_msg.'</div>';
}
?>

    <div class="card">
        <div class="card-body">
            <?php
            echo form_open('',[],['do' => 'submit']);
            ?>
            <div class="form-group">
                <label><?php echo lang('Admin.tools.fieldTitle');?></label>
                <input type="text" name="title" class="form-control" value="<?php echo set_value('title', isset($customField) ? $customField->title : '');?>" required>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.tools.fieldType');?></label>
                <select name="type" class="form-control custom-select" id="fieldType">
                    <?php
                    $default = set_value('type', isset($customField) ? $customField->type : 'text');
                    foreach ($customFieldsType as $k => $v){
                        if($k == $default){
                            echo '<option value="'.$k.'" selected>'.$v.'</option>';
                        }else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div id="singleOption">
                <div class="form-group">
                    <label><?php echo lang('Admin.tools.defaultValue');?></label>
                    <input type="text" name="value" class="form-control" value="<?php echo set_value('value', isset($customField) ? $customField->value : '');?>">
                </div>
            </div>
            <div id="multipleOption">
                <div class="form-group">
                    <label><?php echo lang('Admin.tools.options');?></label>
                    <textarea class="form-control" name="options"><?php echo set_value('options', isset($customField) ? $customField->options : '');?></textarea>
                    <small class="form-text text-muted"><?php echo lang('Admin.tools.optionsDescription');?></small>
                </div>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.tools.required');?></label>
                <select name="required" class="custom-select">
                    <?php
                    $default = set_value('required', isset($customField) ? $customField->required : '0');
                    foreach ([0 => lang('Admin.form.no'),1 => lang('Admin.form.yes')] as $k => $v){
                        if($k == $default){
                            echo '<option value="'.$k.'" selected>'.$v.'</option>';
                        }else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.form.topic');?></label>
                <select name="topic_list" class="custom-select" id="topicList">
                    <?php
                    $default = set_value('topic_list',
                        isset($customField) ?
                            ($customField->topics === '' ? 0 : 1) :
                            0
                    );
                    foreach ([0 => lang('Admin.form.all'),1 => lang('Admin.tools.select')] as $k => $v){
                        if($k === $default){
                            echo '<option value="'.$k.'" selected>'.$v.'</option>';
                        }else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div id="topicOptions">
                <div class="form-group">
                    <select name="topics[]" class="custom-select" multiple>
                        <?php
                        if(is_array($selectedTopic)){
                            $default = $selectedTopic;
                        }elseif(isset($customField)){
                            $default = unserialize($customField->topics);
                        }else{
                            $default = '';
                        }
                        foreach (getTopics() as $item)
                        {
                            if(is_array($default) && in_array($item->id, $default)){
                                echo '<option value="'.$item->id.'" selected>'.$item->name.'</option>';
                            }else{
                                echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><?php echo lang('Admin.form.submit');?></button>
                <a href="<?php echo site_url(route_to('staff_custom_fields'));?>" class="btn btn-secondary"><?php echo lang('Admin.form.goBack');?></a>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
<?php
$this->endSection();
$this->section('script_block');
?>
<script>
    $(function (){
        checkFieldType();
        checkTopicList();
        $('#fieldType').on('change', function (){
            checkFieldType();
        });
        $('#topicList').on('change', function(){
            checkTopicList();
        });
    });

    function checkFieldType()
    {
        fieldType = $('#fieldType').val();
        if(fieldType === 'text' || fieldType === 'textarea' || fieldType === 'password') {
            $('#singleOption').show();
            $('#multipleOption').hide();
        }else if(fieldType === 'email' || fieldType === 'date'){
            $('#multipleOption').hide();
            $('#singleOption').hide();
        }else{
            $('#multipleOption').show();
            $('#singleOption').hide();
        }
    }

    function checkTopicList()
    {
        if($('#topicList').val() === '1'){
            $('#topicOptions').show();
        }else{
            $('#topicOptions').hide();
        }
    }
</script>
<?php
$this->endSection();
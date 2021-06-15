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
        <span class="text-uppercase page-subtitle"><?php echo lang('Admin.settings.menu'); ?></span>
        <h3 class="page-title"><?php echo lang('Admin.settings.kb'); ?></h3>
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
        <?php
        echo form_open('', [], ['do' => 'submit']);
        ?>
        <div class="form-group">
            <label><?php echo lang('Admin.settings.articlesUnderCategory'); ?></label>
            <input type="number" step="1" min="1" name="kb_articles" class="form-control" value="<?php echo set_value('kb_articles', site_config('kb_articles')); ?>">
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.settings.charLimitArticlePreview'); ?></label>
            <input type="number" step="1" min="1" name="kb_maxchar" class="form-control" value="<?php echo set_value('kb_maxchar', site_config('kb_maxchar')); ?>">
            <small class="text-muted form-text"><?php echo lang('Admin.settings.charLimitArticlePreviewDescription'); ?></small>
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.settings.popularArticles'); ?></label>
            <input type="number" step="1" min="1" name="kb_popular" class="form-control" value="<?php echo set_value('kb_popular', site_config('kb_popular')); ?>">
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.settings.newestArticles'); ?></label>
            <input type="number" step="1" min="1" name="kb_latest" class="form-control" value="<?php echo set_value('kb_latest', site_config('kb_latest')); ?>">
        </div>
        <div class="form-group">
            <label><?php echo lang('Admin.settings.allowAttachments'); ?></label>
            <select name="kb_attachment" class="form-control custom-select" id="attachments">
                <?php
                $default = set_value('kb_attachment', site_config('kb_attachment'));
                foreach (['0' => lang('Admin.form.no'), '1' => lang('Admin.form.yes')] as $k => $v) {
                    if ($k == $default) {
                        echo '<option value="' . $k . '" selected>' . $v . '</option>';
                    } else {
                        echo '<option value="' . $k . '">' . $v . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div id="attachments_details">
            <div class="form-group">
                    <label><?php echo lang('Admin.settings.numberAttachments');?></label>
                    <input type="number" class="form-control" name="kb_attachment_number" value="<?php echo set_value('kb_attachment_number', site_config('kb_attachment_number'));?>">
                </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.maxUploadSize'); ?></label>
                <div class="input-group">
                    <input type="text" name="kb_file_size" value="<?php echo set_value('kb_file_size', site_config('kb_file_size')); ?>" class="form-control">
                    <div class="input-group-append"><span class="input-group-text">MB</span></div>
                </div>
                <small class="text-muted form-text"><?php echo lang_replace('Admin.settings.maxUploadSizeDescription', ['%size%' => number_to_size(max_file_size() * 1024)]); ?></small>
            </div>
            <div class="form-group">
                <label><?php echo lang('Admin.settings.allowedFileTypes'); ?></label>
                <input type="text" name="kb_file_type" class="form-control" value="<?php echo set_value('kb_file_type', implode(', ', unserialize(site_config('kb_file_type')))); ?>">
                <small class="text-muted form-text"><?php echo lang('Admin.settings.allowedFileTypesDescription'); ?></small>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-primary"><?php echo lang('Admin.form.save'); ?></button>
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
    $(function() {
        attachment_status();
        $('#attachments').on('change', function() {
            attachment_status();
        });
    })

    function attachment_status() {
        if ($('#attachments').val() === '1') {
            $('#attachments_details').show();
        } else {
            $('#attachments_details').hide();
        }
    }
</script>
<?php
$this->endSection();

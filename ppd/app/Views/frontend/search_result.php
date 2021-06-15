<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('frontend/page_template');
$this->section('window_title');
echo lang('Client.kb.title');
$this->endSection();

$this->section('breadcrumb');
?>
<section class="pathway">
    <div class="container">
        <nav aria-label="breadcrumb" class="navbar-dark">
            <div class="row">
                <div class="col-12">
                    <ol class="nav py-2" style="background-color: transparent!important;">
                        <li class="breadcrumb-item text-light"><a href="<?= site_url(route_to('home')) ?>"><?= lang('Client.menu.home') ?></a></li>
                        <li class="breadcrumb-item text-light"><a href="<?= site_url(route_to('kb')) ?>"><?= lang('Client.kb.menu'); ?></a></li>
                        <li class="breadcrumb-item text-light"><a class="inactive_link" href="<?=site_url()?>"><?=lang('Client.form.search').' &nbsp;' ?></a></li>
                        <li class="breadcrumb-item text-light current"><a class="static_link" href="<?=current_url(true)?>"><?=lang('Client.kb.searchResults')?></a></li>
                    </ol>
                </div>
            </div>
        </nav>
    </div>
</section>
<?php
$this->endSection();
$this->section('page_content');
?>
<h1 class="heading mb-5"><?php echo lang_replace('Client.kb.searchResultsFor',['%keyword%' => '<span class="font-weight-bold">"'.$keyword.'"</span>']);?></h1>
<?php
if(!$result){
    echo lang('Client.error.search');
}else{
    echo '<div class="list-group">';
    foreach ($result as $item){
        ?>
        <div class="list-group-item border-right-0 border-left-0">

            <div class="float-left">
                <div class="float-left mr-3">
                    <i class="fa fa-file-text-o kb_article_icon_lg"></i>
                </div>
                <div class="mb-1">
                    <a class="font-weight-bold" href="<?php echo site_url(route_to('article', $item->id, url_title($item->title)));?>"><?php echo $item->title;?></a>
                </div>

                <div class="text-muted">
                    <?php echo resume_content($item->content, site_config('kb_maxchar'));?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
    }
    echo '</div>';
}
$this->endSection();

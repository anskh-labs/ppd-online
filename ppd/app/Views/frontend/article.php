<?php

/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('frontend/page_template');
$this->section('window_title');
echo $article->title;
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
                        <?php if (isset($category)) : ?>
                            <?php if ($parents = kb_parents($category->parent)) : ?>
                                <?php foreach ($parents as $item) : ?>
                                    <li class="breadcrumb-item text-light"><a href="<?= site_url(route_to('category', $item->id, url_title($item->name))) ?>"><?= $item->name ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <li class="breadcrumb-item text-light current"><a href="<?= site_url(route_to('category', $category->id, url_title($category->name))) ?>"><?= $category->name ?></a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item text-light current"><a href="#"><?= $article->title; ?></a></li>
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
<h2 class="sub_heading mb-3"><i class="fa fa-file-text-o kb_article_icon_lg"></i> <?php echo $article->title ?></h2>
<div class="article_description mb-5">
    <?php echo lang_replace('Client.kb.postedOn', ['%date%' => dateFormat($article->date)]); ?>
    <hr>
</div>
<div><?php echo $article->content; ?></div>

<?php if ($attachments = article_files($article->id)) : ?>
    <div class="knowledgebasearticleattachment"><?php echo lang('Client.form.attachments'); ?></div>
    <?php foreach ($attachments as $item) : ?>
        <div>
            <span class="knowledgebaseattachmenticon"></span>
            <a href="<?php echo site_url(route_to('article', $article->id, url_title($article->title)) . '?download=' . $item->id); ?>" target="_blank"><?php echo $item->name; ?> (<?php echo $item->filesize; ?>)</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php
$this->endSection();

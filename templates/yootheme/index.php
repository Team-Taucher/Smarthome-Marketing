<?php

namespace YOOtheme;

use Joomla\Input\Input;

defined('_JEXEC') or die();

/**
 * @var Config $config
 * @var View   $view
 * @var Input  $input
 */
list($config, $view, $input) = app(Config::class, View::class, Input::class);

$config->addAlias('~site', '~theme.site');
$config->addAlias('~sidebar', '~theme.main_sidebar');

// Set HTML5 output
$this->setHtml5(true);

// Set view
$layout = '';

if (in_array($input->get('option'), ['com_content', 'com_tags'])) {

    if (in_array($input->get('view'), ['category', 'featured', 'tag'])) {
        $layout = 'blog';
    } elseif ($input->get('view') === 'article' && $config('~theme.page_layout') === 'post') {
        // article category has to be 'uncategorized', 'page_layout' is set in html/com_content/article/default.php
        $layout = 'post';
    }

}

// Page
$attrs_page = [];
$attrs_page_container = [];
$attrs_image = [];
$attrs_media_overlay = [];
$attrs_page['class'][] = 'tm-page';

if ($config('~site.layout') == 'boxed') {

    $attrs_page['class'][] = $config('~site.boxed.alignment') ? 'uk-margin-auto' : '';
    $attrs_page['class'][] = $config('~site.boxed.margin_top') ? 'tm-page-margin-top' : '';
    $attrs_page['class'][] = $config('~site.boxed.margin_bottom') ? 'tm-page-margin-bottom' : '';
    $attrs_page_container['class'][] = 'tm-page-container uk-clearfix';

    // Image
    if ($config('~site.boxed.media')) {

        $attrs_image = $view->bgImage($config('~site.boxed.media'), [
            'width' => $config('~site.image_width'),
            'height' => $config('~site.image_height'),
            'size' => $config('~site.image_size'),
            'position' => $config('~site.image_position'),
            'visibility' => $config('~site.image_visibility'),
            'blend_mode' => $config('~site.media_blend_mode'),
            'background' => $config('~site.media_background'),
            'effect' => $config('~site.image_effect'),
            'parallax_bgx_start' => $config('~site.image_parallax_bgx_start'),
            'parallax_bgy_start' => $config('~site.image_parallax_bgy_start'),
            'parallax_bgx_end' => $config('~site.image_parallax_bgx_end'),
            'parallax_bgy_end' => $config('~site.image_parallax_bgy_end'),
            'parallax_easing' => $config('~site.image_parallax_easing'),
            'parallax_breakpoint' => $config('~site.image_parallax_breakpoint'),
            'parallax_target' => 'body',
        ]);

        if ($config('~site.image_effect')) {
            $attrs_image['class'][] = 'uk-position-cover uk-position-fixed';
        } else {
            $attrs_page_container = array_merge_recursive($attrs_page_container, $attrs_image);
            $attrs_image = [];
        }

        // Overlay
        if ($config('~site.media_overlay')) {
            $attrs_page_container['class'][] = 'uk-position-relative';
            $attrs_media_overlay['class'][] = 'uk-position-cover';
            $attrs_media_overlay['style'] = "background-color: {$config('~site.media_overlay')};";
        }

    }

}

// Main section
$attrs_main_section = [];
$attrs_main_section['class'][] = 'tm-main uk-section uk-section-default';
$attrs_main_section['class'][] = $layout == 'blog' && $config('~theme.blog.padding') ? "uk-section-{$config('~theme.blog.padding')}" : '';
$attrs_main_section['class'][] = $layout == 'post' && $config('~theme.post.padding') ? "uk-section-{$config('~theme.post.padding')}" : '';
$attrs_main_section['class'][] = $layout == 'post' && $config('~theme.post.padding_remove') ? 'uk-padding-remove-top' : '';

// Main container
$attrs_main_container = [];

if ($layout == 'post') {
    if ($config('~theme.post.width')) {
        $attrs_main_container['class'][] = $config('~theme.post.width') == 'default' ? 'uk-container' : "uk-container uk-container-{$config('~theme.post.width')}";
    }
} elseif ($layout == 'blog') {
    if ($config('~theme.blog.width')) {
        $attrs_main_container['class'][] = $config('~theme.blog.width') == 'default' ? 'uk-container' : "uk-container uk-container-{$config('~theme.blog.width')}";
    }
} else {
    $attrs_main_container['class'][] = 'uk-container';
}

?>
<!DOCTYPE html>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>" vocab="http://schema.org/">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?= $config('~theme.favicon') ?>">
        <link rel="apple-touch-icon" href="<?= $config('~theme.touchicon') ?>">
        <jdoc:include type="head" />
		<style>
			/*
			.uk-navbar-toggle {
				display: none !important;
			}*/
		</style>
    </head>
    <body class="<?= join(' ', (array) $config('~theme.body_class')) ?>">

        <?php if ($config('~site.layout') == 'boxed') : ?>
        <div<?= $view->attrs($attrs_page_container) ?>>

            <?php if ($attrs_image) : ?>
            <div<?= $view->attrs($attrs_image) ?>></div>
            <?php endif ?>

            <?php if ($attrs_media_overlay) : ?>
            <div class="uk-position-cover"<?= $view->attrs($attrs_media_overlay) ?>></div>
            <?php endif ?>

        <?php endif ?>

        <?php if ($config('~site.layout') == 'boxed' && $config('~site.boxed.header_outside')) : ?>
        <?= $view('~theme/templates/header') ?>
        <?php endif ?>

        <div<?= $view->attrs($attrs_page) ?>>

            <?php if (!($config('~site.layout') == 'boxed' && $config('~site.boxed.header_outside'))) : ?>
            <?= $view('~theme/templates/header') ?>
            <?php endif ?>

            <jdoc:include type="modules" name="top" style="section" />

            <?php if (!$config('app.isBuilder')) : ?>

            <div id="tm-main" <?= $view->attrs($attrs_main_section) ?> uk-height-viewport="expand: true">
                <div<?= $view->attrs($attrs_main_container) ?>>

                    <?php if ($this->countModules('sidebar')) :
                        $grid = ['uk-grid'];
                        $grid[] = $config('~sidebar.gutter') ? "uk-grid-{$config('~sidebar.gutter')}" : '';
                        $grid[] = $config('~sidebar.divider') ? 'uk-grid-divider' : '';
                    ?>

                    <div<?= $view->attrs(['class' => $grid, 'uk-grid' => true]) ?>>
                        <div class="uk-width-expand@<?= $config('~sidebar.breakpoint') ?>">

                    <?php endif ?>

                            <?php if ($config('~site.breadcrumbs')) : ?>
                                <?= $view->section('breadcrumbs') ?>
                            <?php endif ?>

            <?php endif ?>

            <jdoc:include type="message" />
            <jdoc:include type="component" />

            <?php if (!$config('app.isBuilder')) : ?>

                        <?php if ($this->countModules('sidebar')) : ?>
                        </div>

                        <?= $view('~theme/templates/sidebar') ?>

                    </div>
                     <?php endif ?>

                </div>
            </div>
            <?php endif ?>

            <jdoc:include type="modules" name="bottom" style="section" />

            <?= $view->builder(json_encode($config('~theme.footer.content')), ['prefix' => 'footer']) ?>

        </div>

        <?php if ($config('~site.layout') == 'boxed') : ?>
        </div>
        <?php endif ?>

        <jdoc:include type="modules" name="debug" />

    </body>
</html>

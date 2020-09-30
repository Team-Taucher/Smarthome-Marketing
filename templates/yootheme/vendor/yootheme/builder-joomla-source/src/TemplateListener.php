<?php

namespace YOOtheme\Builder\Joomla\Source;

use YOOtheme\Config;

class TemplateListener
{
    public static function onPrepareContent(Config $config)
    {
        if ($config('customizer.page')) {
            $config->set('customizer.view', 'com_content.article');
        }
    }

    public static function onTemplate(Config $config, $template, $context)
    {
        if ($temp = $config('req.customizer.template')) {
            $config->set("builder.templates.{$temp['id']}.layout", $temp['layout']);
        }

        if ($config('app.isCustomizer')) {
            $config->set('customizer.view', $context);
        }

        return $template;
    }
}

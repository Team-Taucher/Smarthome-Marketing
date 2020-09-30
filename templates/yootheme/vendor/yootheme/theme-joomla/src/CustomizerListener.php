<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Input\Input;
use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;

class CustomizerListener
{
    public static function initTheme(Config $config, Session $session, Input $input)
    {
        $cookie = hash_hmac('md5', $config('theme.template'), $config('app.secret'));
        $active = $input->get('p') === 'customizer' || $config('app.isSite') && $input->cookie->get($cookie);

        if (!$active) {
            return;
        }

        HTMLHelper::_('behavior.keepalive');

        $params = $session->get($cookie) ?: [];

        if ($custom = $input->getBase64('customizer')) {
            $params = array_replace($params, json_decode(base64_decode($custom), true));
            $session->set($cookie, Arr::pick($params, ['config', 'admin', 'user_id']));
        }

        // override theme config
        if (isset($params['config'])) {
            $config->set('~theme', $params['config']);
        }

        $config('joomla.config')->set('caching', 0);
        $config->add('req.customizer', $params);
        $config->set('app.isCustomizer', $active);
        $config->set('theme.cookie', $cookie);
        $config->set('customizer.id', $config('theme.id'));
    }

    public static function initCustomizer(Config $config)
    {
        $config->addFile('customizer', Path::get('../config/customizer.json'));
    }

    public static function prepareData(Config $config, $context, $data)
    {
        if ($context !== 'com_templates.style') {
            return;
        }

        $config->add('customizer', [
            'context' => $context,
            'apikey' => $config('app.apikey'),
            'url' => Route::_("index.php?p=customizer&option=com_ajax&style={$data->id}", false),
        ]);
    }

    public static function compileHead(Config $config, Metadata $metadata)
    {
        if ($config('joomla.config.themeFile') !== 'offline.php' && $data = $config('customizer')) {
            $metadata->set('script:customizer-data', sprintf('var $customizer = JSON.parse(atob("%s"));', base64_encode(json_encode($data))));
        }
    }
}

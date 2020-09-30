<?php

namespace YOOtheme;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$template = Event::emit('builder.template|filter', [], $context, isset($filter) ? $filter : []);

if (!$template || empty($template['layout'])) {
    return;
}

$config->set('app.isBuilder', true);

echo app(Builder::class)->render(json_encode($template['layout']), $params + [
    'prefix' => "template-{$template['id']}",
    'template' => $template,
]);

if ($context === 'com_content.article' && $params['item']->params->get('access-edit') && !$config('app.isCustomizer')) {
    $url = Route::_(\ContentHelperRoute::getFormRoute($params['item']->id) . '&return=' . base64_encode(Uri::getInstance()));
    echo "<a style=\"position: fixed!important\" class=\"uk-position-medium uk-position-bottom-right uk-button uk-button-primary\" href=\"{$url}\">" . \JText::_('JACTION_EDIT') . '</a>';
}

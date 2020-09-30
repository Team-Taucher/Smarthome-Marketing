<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\Input\Input;
use YOOtheme\Application;
use YOOtheme\Arr;
use YOOtheme\Http\Exception;
use YOOtheme\Metadata;
use YOOtheme\Url;

class Platform
{
    /**
     * Handle application routes.
     *
     * @param Application    $app
     * @param CMSApplication $cms
     * @param Input          $input
     */
    public static function handleRoute(Application $app, CMSApplication $cms, Input $input)
    {
        if ($input->get('p') && $input->getCmd('option') === 'com_ajax') {

            $response = $app->run(false);

            if ($response->getAttribute('send') === false) {
                return;
            }

            $response->send();

            $cms->triggerEvent('onAfterRespond');

            exit;
        }
    }

    /**
     * Handle application errors.
     *
     * @param Response   $response
     * @param \Exception $exception
     */
    public static function handleError($response, $exception)
    {
        if (!$exception instanceof Exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * Callback to register assets.
     *
     * @param Metadata     $metadata
     * @param HtmlDocument $document
     */
    public static function registerAssets(Metadata $metadata, HtmlDocument $document)
    {
        foreach ($metadata->all('style:*') as $style) {

            if ($style->href) {
                $document->addStyleSheet(htmlentities(Url::to($style->href)), ['type' => 'text/css', 'version' => $style->version], Arr::omit($style->getAttributes(), ['version', 'href', 'rel']));
            } elseif ($value = $style->getValue()) {
                $document->addStyleDeclaration($value);
            }

        }

        foreach ($metadata->all('script:*') as $script) {

            if ($script->src) {
                $document->addScript(htmlentities(Url::to($script->src)), ['type' => 'text/javascript', 'version' => $script->version], Arr::omit($script->getAttributes(), ['version', 'src']));
            } elseif ($value = $script->getValue()) {
                $document->addCustomTag("<script>{$value}</script>");
            }

        }
    }
}

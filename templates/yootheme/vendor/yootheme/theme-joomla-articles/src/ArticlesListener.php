<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Router\Router;
use Joomla\CMS\Uri\Uri;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use YOOtheme\Str;
use YOOtheme\Url;

class ArticlesListener
{
    public static function prepareData(Config $config, Metadata $metadata, $context, $article)
    {
        if ($context !== 'com_content.article') {
            return;
        }

        // on error $article is an array instead of object
        $article = (object) $article;

        if (empty($article->id)) {
            return;
        }

        $articles = json_encode([
            'context' => $context,
            'apikey' => $config('app.apikey'),
            'url' => Url::to("{$config('req.baseUrl')}/index.php?p=customizer&option=com_ajax&section=builder", [
                'section' => 'builder',
                'site' => static::getRoute($article, 0),
            ]),
        ]);

        $metadata->set('script:articles-data', "var \$articles = {$articles};");
        $metadata->set('script:articles', ['src' => Path::get('../app/articles.min.js'), 'defer' => true]);
    }

    public static function beforeSave(Config $config, $context, $article)
    {
        if (!in_array($context, ['com_content.form', 'com_content.article'], true)) {
            return;
        }

        // use "jform.articletext" from request to keep builder data, when JText filters are active
        if (preg_match('/<!--\s{.*}\s-->\s*$/', $config('req.body.jform.articletext'), $matches)) {
            $article->fulltext = $matches[0];
        }
    }

    protected static function getRoute($article, $mode = 1)
    {
        if (!class_exists('ContentHelperRoute')) {
            \JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');
        }

        $route = \ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->language);
        $router = clone Router::getInstance('site');
        $router->setMode($mode);

        $site = (string) $router->build($route);

        // Workaround for bug in Joomla 3.7
        $base = Uri::root(true) . '/administrator';
        if (Str::startsWith($site, $base)) {
            $site = Uri::root(true) . Str::substr($site, strlen($base));
        }

        return $site;
    }
}

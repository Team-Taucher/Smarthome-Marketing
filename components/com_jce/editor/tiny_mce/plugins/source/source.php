<?php

/**
 * @copyright     Copyright (c) 2009-2020 Ryan Demmer. All rights reserved
 * @license       GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses
 */
require_once WF_EDITOR_LIBRARIES . '/classes/plugin.php';

final class WFSourcePlugin extends WFEditorPlugin
{
    public function __construct($config = array())
    {
        // Call parent
        parent::__construct();

        $language = JFactory::getLanguage();
        $language->load('WF_pro', JPATH_SITE);
    }

    public function display()
    {
        $document = WFDocument::getInstance();

        $view = $this->getView();

        $view->addTemplatePath(WF_EDITOR_PLUGIN . '/tmpl');

        $theme = $this->getParam('source.theme', 'codemirror');

        $document->addScript(array(
            'jquery.min'
        ), 'jquery');

        $document->addScript(array(
            'plugin.min.js'
        ), 'libraries');

        $document->addStyleSheet(array(
            'plugin.min.css'
        ), 'libraries');

        $document->addScript(array(
            'editor.min',
            'format.min',
            'codemirror/codemirror.min'
        ), 'plugins');

        $document->addStyleSheet(array(
            'editor.min',
            'codemirror/codemirror.min',
            'codemirror/theme/' . $theme,
        ), 'plugins');

        // keep as ltr for source code
        $document->setDirection('ltr');
    }
}

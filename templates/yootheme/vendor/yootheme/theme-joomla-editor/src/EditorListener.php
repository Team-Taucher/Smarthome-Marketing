<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Editor\Editor;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\PluginHelper;
use YOOtheme\Config;

class EditorListener
{
    public static function initCustomizer(Config $config)
    {
        $editor = $config('joomla.config.editor');

        if (in_array($editor, ['tinymce', 'jce'])) {
            // all good, use enabled visual editor
        } elseif (!in_array($editor, ['none', 'codemirror'])) {
            // tinymce installed? use as visual
            $editor = PluginHelper::getPlugin('editors', 'tinymce') ? 'tinymce' : null;
        } else {
            $editor = null;
        }

        if ($editor) {

            HTMLHelper::_('behavior.modal');
            HTMLHelper::_('jquery.framework');

            $instance = Editor::getInstance($editor);
            $instance->display('yo_dummy_editor', '', '', '', '', '', ['pagebreak', 'readmore', 'widgetkit']);

            if ($editor === 'jce') {

                // add config
                $config->add('customizer', [
                    'editorButtonsXtd' => LayoutHelper::render(
                        'joomla.editors.buttons',
                        $instance->getButtons('yo_dummy_editor', ['pagebreak', 'readmore', 'widgetkit'])
                    ),
                ]);

            }
        }
    }
}

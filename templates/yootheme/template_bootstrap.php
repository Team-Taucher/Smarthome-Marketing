<?php

namespace YOOtheme;

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

$option = $this->app->input->getCmd('option');
$options = ['com_ajax', 'com_content', 'com_templates', 'com_modules', 'com_advancedmodules'];

if ($this->app->isClient('site') || in_array($option, $options, true))  {

    // bootstrap application
    $app = require 'bootstrap.php';
    $app->load(__DIR__ . '/{vendor/yootheme/{platform-joomla,theme{,-analytics,-cookie,-highlight,-settings,-joomla*},styler,builder{,-source,-templates,-newsletter,-joomla*}}/bootstrap.php,config.php}');

    // add modules event
    $this->app->registerEvent('onAfterCleanModuleList', function (&$modules) {
        $modules = Event::emit('modules.load|filter', $modules);
    });

} else {

    // add shortcut icon
    $this->app->registerEvent('onGetIcons', function ($context) {

        $user = Factory::getUser();
        $query = "SELECT * FROM #__template_styles WHERE client_id=0 AND home='1'";

        if ($context !== 'mod_quickicon' || !$user->authorise('core.edit', 'com_templates')) {
            return;
        }

        if ($templ = $this->db->setQuery($query)->loadObject()) {
            $templ->params = new Registry($templ->params);

            return $templ->params->get('yootheme') ? [[
                'image' => 'star',
                'text'  => 'YOOtheme',
                'link'  => "index.php?option=com_ajax&p=customizer&style={$templ->id}",
            ]] : null;
        }

    });

}

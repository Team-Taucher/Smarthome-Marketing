<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Factory;
use YOOtheme\Application\EventLoader;
use YOOtheme\Container;
use YOOtheme\EventDispatcher;

class ActionLoader extends EventLoader
{
    /**
     * @var CMSApplication
     */
    protected $app;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(new EventDispatcher());

        $this->app = Factory::getApplication();
    }

    /**
     * Load action listeners.
     *
     * @param Container $container
     * @param array     $configs
     */
    public function __invoke(Container $container, array $configs)
    {
        static $handlers = [];

        foreach ($configs as $actions) {
            foreach ($actions as $action => $listeners) {

                if (empty($handlers[$action])) {

                    $handlers[$action] = function (...$arguments) use ($action) {
                        return $this->dispatcher->dispatch($action, ...$arguments);
                    };

                    $this->app->registerEvent($action, $handlers[$action]);
                }

                foreach ($listeners as $class => $parameters) {

                    $parameters = (array) $parameters;

                    if (is_string($parameters[0])) {
                        $parameters = [$parameters];
                    }

                    foreach ($parameters as $params) {
                        $this->addListener($container, $action, $class, ...$params);
                    }
                }
            }
        }

        $container->set('dispatcher', $this->dispatcher);
    }
}

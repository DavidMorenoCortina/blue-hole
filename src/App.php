<?php

namespace DavidMorenoCortina\BlueHole;


use DavidMorenoCortina\DependencyContainer\Container;
use DavidMorenoCortina\Router\Controllers\BaseController;
use DavidMorenoCortina\Router\Controllers\Error500Controller;
use DavidMorenoCortina\Router\Exception\CLIRequestException;
use DavidMorenoCortina\Router\Response\Response;
use DavidMorenoCortina\Router\Router;
use Exception;
use PDO;

class App {
    /**
     * @var Container $container
     */
    protected $container;

    public function __construct($settings = []) {
        $this->container = new Container($settings);

        $this->container['pdo'] = function(Container $container){
            $settings = $container['settings'];

            $dsn = 'mysql:host=' . $settings['db']['host'] . ';port=' . $settings['db']['port'] . ';dbname=' . $settings['db']['dbName'];
            return new PDO($dsn, $settings['db']['user'], $settings['db']['password'], [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
        };

        $router = new Router($this->container);
        $router->registerDependencies();
    }

    /**
     * @return Container
     */
    public function getContainer() :Container {
        return $this->container;
    }

    /**
     * @return Router
     */
    public function getRouter() :Router {
        return $this->container['router'];
    }

    public function run() :string {
        $router = $this->getRouter();

        $output = '';

        try {
            $route = $router->match();
            $className = $route->getClassName();
            $action = $route->getAction();
            try {
                /** @var BaseController $controller */
                $controller = new $className($this->container);
                $controller->setParams($route->getParams());
                /** @var Response $response */
                $response = $controller->$action();

            }catch (Exception $e){
                $errorResponse = new Error500Controller($this->container);
                $response = $errorResponse->action();
            }
            $settings = $this->getContainer()['settings'];
            $output = $response->send($settings['silent']);

        } catch (CLIRequestException $e) {
            return $output;
        }

        return $output;
    }
}
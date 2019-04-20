<?php

namespace Functional;


use DavidMorenoCortina\BlueHole\App;
use DavidMorenoCortina\Router\Controllers\BaseController;
use DavidMorenoCortina\Router\Response\HtmlResponse;
use DavidMorenoCortina\Router\Response\Response;
use PHPUnit\Framework\TestCase;

class HomeController extends BaseController{
    public function action() {
        return new HtmlResponse('<h1>Hello ' . $this->getParams()['name'] . '</h1>');
    }
}

class AppTest extends TestCase {
    public function testAppValidRoute() {
        $settings = require __DIR__ . '/../../phpunit-settings.php';

        $_SERVER['REQUEST_METHOD'] = 'get';
        $_SERVER['REQUEST_URI'] = '/pedro';

        $app = new App($settings);

        $router = $app->getRouter();

        $router->get('/{name}', HomeController::class, 'action');

        $output = $app->run();

        $parts = explode(Response::EOL . Response::EOL, $output, 2);

        $this->assertCount(2, $parts);

        $this->assertEquals('<h1>Hello pedro</h1>', $parts[1]);
    }
}
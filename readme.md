# Blue Hole - Lite framework 

Blue Hole is a small PHP framework built of ad-hoc libraries


## Usage

A **settings** file:
    
    return [
        'silent' => false,
        'jwtKeyName' => '',
        'db' => [
            'host' => '',
            'port' => 3306,
            'dbName' => '',
            'user' => '',
            'password' => ''
        ]
    ];

Instantiate **App** class with settings:

    $app = new App($settings);

Define some routes:

    $router = $app->getRouter();

    $router->get('/', HomeController::class, 'action');
    
    $router->get('/products', ProductsController::class, 'action');
    
    $router->get('/product/{id}', ProductDetailController::class, 'action');

Run app:

    $output = $app->run();

If you need to define new dependencies:

    $container = $app->getContainer();
    
    $container['newDependency'] = function(Container $container){
        return new ...
    }

## Tests

**phpunit-settings.php** must be configured to be able to run tests.

## License

[MIT License](https://opensource.org/licenses/MIT)

## Authors

 - David Moreno Cortina
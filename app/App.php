<?php
namespace App;

use Pimple\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

class App extends Container
{
    private static $vars = [];

    public function run()
    {
        Post::init();
        $capsule = new Capsule;
        $capsule->addConnection(APP_DB);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $route = Router::get();
        $controller = '\\App\\Controllers\\' . ucfirst($route['controllerName']) . 'Controller';
        if (class_exists($controller)) {
            new $controller($route['actionName']);
        }
    }

    public static function get($name)
    {
        return self::$vars[$name];
    }

    public static function set($name, $value)
    {
        self::$vars[$name] = $value;
    }
}



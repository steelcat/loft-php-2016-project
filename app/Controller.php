<?php
namespace App;

class Controller
{
    protected static $actionName;

    public function __construct($actionName)
    {
        self::$actionName = $actionName;
        $this->init();
    }

    public function init()
    {
        $actionName = self::$actionName;
        if (method_exists(get_class($this), 'action' . $actionName)) {
            $this->{'action' . $actionName}();
        } else {
            $this->action404();
        }
    }

    public function action404()
    {
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        View::show('404');
    }
}

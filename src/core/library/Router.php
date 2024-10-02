<?php 

namespace core\Library;

use core\controllers\ErrorController;
use core\exceptions\ControllerNotFoundException;
use DI\Container;

class Router
{
    protected array $routes = [];
    protected ?string $controller = null;
    protected string $action;
protected array $parameters = [];

public function __construct(private Container $container)
{

}

    public function add(string $method, string $uri, array $route)
    {
        $this->routes[$method][$uri] = $route;
    }

    public function execute()
    {
        foreach ($this->routes as $request => $routes) {
            if($request === REQUEST_METHOD) {
                return $this->hendleUri($routes);
            }
        }
    }

    private function hendleUri(array $routes)
    {
        foreach($routes as $uri => $route){
            
            if($uri === REQUEST_URI) {
                 [$this->controller, $this->action] = $route;
                break;
            }
            
            $pattern = str_replace('/', '\/', trim($uri, '/'));
            if($uri !== '/' && preg_match("/^$pattern$/", trim(REQUEST_URI, '/'), $this->parameters)) {
                [$this->controller, $this->action] = $route;
                unset ($this->parameters[0]);
                break;
            }
        }

        if($this->controller){
            return $this->hendleController();
        }

        return $this->hendleNotFound();
    }

    private function hendleController()
    {
        if(!class_exists($this->controller) || !method_exists($this->controller, $this->action)) {
            throw new ControllerNotFoundException(
                "[$this->controller::$this->action] does not exists."
            );
        }   
        $controller = $this->container->get($this->controller);
        $this->container->call([$controller, $this->action], [...$this->parameters]);
    }

    private function hendleNotFound()
    {
        (new ErrorController)->notFound();
    }
}
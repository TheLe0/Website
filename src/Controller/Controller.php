<?php

    namespace src\Controller {

        use src\Util\Uri;
        use src\Exception\ControllerNotExistException;

        class Controller {
            private $uri;
            private $controller;
            private $namespace;

            public function __construct() {
                $this->uri = Uri::uri();
            }
            public function load() {
                if ($this->isHome()) {
                    return $this->controllerHome();
                }
                return $this->controllerNotHome();
            }
            private function controllerHome() {
                if (!$this->controllerExist('HomeController')) {
                    throw new ControllerNotExistException("Esse controller não existe");
                }
                return $this->instantiateController();
            }

            private function getControllerNotHome() {
                if (substr_count($this->uri, '/') > 1) {
                    list($controller, $method) = array_values(array_filter(explode('/', $this->uri)));
                    return ucfirst($controller) . 'Controller';
                }
                return ucfirst(ltrim($this->uri, '/')) . 'Controller';
            }
            
            private function isHome() {
                return ($this->uri == '/');
            }
 
            private function instantiateController() {
                $controller = $this->namespace . '\\' . $this->controller;
                return new $controller;
            }
        }
    }
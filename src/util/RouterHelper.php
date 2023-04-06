<?php
/**
 * Clase auxiliar para manejo del enrutador
 */
class RouterHelper{

    // Guardamos todas las rutas en un array
    private $routes = [];

    private function addRoute($method, $path, $controller, $fun, $auth){
        $this->routes[] = ["PATH"=>$path, "CONTROLLER"=>$controller, "FUN"=>$fun, "AUTH"=>$auth, "METHOD"=>$method];
    }

    public function get($path, $controller, $fun, $auth = false){
        $this->addRoute("GET", $path, $controller, $fun, $auth);
    }

    public function post($path, $controller, $fun, $auth = false){
        $this->addRoute("POST", $path, $controller, $fun, $auth);
    }

    public function patch($path, $controller, $fun, $auth = false){
        $this->addRoute("PATCH", $path, $controller, $fun, $auth);
    }

    public function delete($path, $controller, $fun, $auth = false){
        $this->addRoute("DELETE", $path, $controller, $fun, $auth);
    }

    public function getCurrentRoute(){
        $url_items = explode("/", substr($_SERVER["REQUEST_URI"], 1));
        foreach ($this->routes as $route) {
            if ($_SERVER['REQUEST_METHOD'] != $route['METHOD']) continue; 
            $route_items = explode("/", $route["PATH"]);
            if ($route_items[0] == $url_items[0]) {
                if (count($route_items) == count($url_items)) {
                    return $route;
                }
            }
        }
        return null;
    }
}
?>
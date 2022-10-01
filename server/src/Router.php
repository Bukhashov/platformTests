<?php
namespace App;

class Router { 
    private array $handles;
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private $notFoundHandler;

    private function addHandler($mathod, $path, $handler):void { 
        $this->handles[$mathod. $path] = [
            'path' => $path,
            'mathod' => $mathod,
            'handler' => $handler
        ];
    }

    public function get(string $path, $handler):void {
        $this->addHandler(self::METHOD_GET, $path, $handler);
    }
    public function post(string $path, $handler):void {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }
    public function addNotFoundHandler($handler):void{
        $this->notFoundHandler = $handler;
    }

    public function run(){
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        $callback = null;
        foreach($this->handles as $handler){
            if($handler['path'] === $requestPath && $handler['mathod'] === $method){
                $callback = $handler['handler'];
            }
        }

        if(!$callback){
            header("HTTP/1.0 404 Not Found");
            if(!empty($this->notFoundHandler)){
                $callback = $this->notFoundHandler;
            }
        }

        if(is_string($callback)){
            $parps = explode('::', $callback);
            if(is_array($parps)){
                $className = array_shift($parps);
                $handler = new $className;

                $method = array_shift($parps);
                $callback = [$handler, $method];
            }
        }

        call_user_func_array($callback, [ 
            array_merge($_GET, $_POST)
        ]);
    }
}

<?php
require './src/Router.php';

use App\Router;

const API_VERSION = '/api/v1';

header('Access-Control-Allow-Origin: *');
header("Content-type: application/json; charset=utf-8");

$router = new Router();


$router->get(API_VERSION . '/', function( $parps ){
    $arr = array('id'=> '12', 'name'=>'berik');
    
    echo json_encode($arr);
});

$router->post(API_VERSION . '/user/signin', function($parps) {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    echo $input['last name'];
});

$router->post(API_VERSION . '/user/signup', function($parps) {
    
});

$router->addNotFoundHandler(function(){
    echo "NOT FOUND PATH";
});

$router->run();

?>
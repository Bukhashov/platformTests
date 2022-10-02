<?php
require_once './src/Router.php';
require_once './src/jwt.php';

use App\Router;

const API_VERSION = '/api/v1';
const DB_PATH = './db/database.db';

$router = new Router();

$router->get(API_VERSION . '/', function( $parps ){ 
    // $res = $db->signUp($newUserLastName, $newUserFirstName, $newUserEmail, $newUserPassword);

    echo 'bd';
});

$router->post(API_VERSION . '/user/signin', function($parps) {
    $request = json_decode(file_get_contents('php://input'), true);

    $userEmail = $request['email'];
    $userPassword = $request['password'];

    if(!$userEmail == '' || !$userEmail == null){
        if(!$userPassword == '' || !$userPassword == null){
            $db = new PDO("sqlite:./db/database.db");

            $result = $db->query('SELECT * FROM users WHERE email="'.$userEmail.'" OR password="'.$userPassword.'"')->fetchAll(PDO::FETCH_ASSOC);
            
            if($request){
                $respons = [];
                $_id = null;
                $_email = null;

                foreach($result as $res){
                    $_lastname = $res['lastname'];
                    $_fistname = $res['firstname'];
                    $_id = $res['id'];
                    $_email = $res['email'];
                }
                
                $respons = array(
                    "status" => http_response_code(200),
                    "lastname" => $_lastname, 
                    "firtsname" => $_fistname,
                    "token" => jwtEncode($_id, $_email),                        
                );
                echo json_encode($respons);
            }else{

            }
    
        }else{
            echo 'user password';
        }
    }else{
        echo 'user email';
    }
});

$router->post(API_VERSION . '/user/signup', function($parps){
    $request = json_decode(file_get_contents('php://input'), true);

    $newUserFirstName = $request["firstname"];
    $newUserLastName = $request['lastname'];
    $newUserPassword = $request['password'];
    $newUserEmail = $request['email'];
    
    if(!$newUserFirstName == "" || !$newUserFirstName == null){
        if(!$newUserLastName == "" || !$newUserLastName === null){
            if(strlen($newUserPassword) > 8 || !$newUserPassword === ''){
                if(!$newUserEmail == "" || !$newUserEmail == null){
                    $db = new PDO("sqlite:./db/database.db");

                    $controlEmail = $db->query('SELECT email FROM users WHERE email="'.$newUserEmail.'";');
                    
                    if(!$controlEmail == 0){
                         $db->exec('CREATE TABLE IF NOT EXISTS users (
                            id INTEGER PRIMARY KEY,
                            firstname VARCHAR(50),
                            lastname VARCHAR(50),
                            email VARCHAR(50),
                            password VARCHAR(250)
                        );');

                        $requestSave = 'INSERT INTO users(firstname, lastname, email, password) VALUES("'.$newUserFirstName.'", "'.$newUserLastName.'", "'.$newUserEmail.'", "'.$newUserPassword.'");';
                        
                        if($db->exec($requestSave)){
                            echo 'user created';
                        }else{
                            echo 'ERROR DB';
                        }

                    }else{
                        echo 'user email yes !';
                    }                
                }else{
                    echo 'user email none !';
                }
            }else{
                echo 'user password <8 !';
            }
        }else{
            echo 'user lastname error !';
        }
    }else{
        echo ' user firstname error !';
    }
});

$router->post(API_VERSION . '/tasks/create', function($parps){
    $headers = getallheaders();
    
    if(!$headers["Authorization"] == "" || !$headers["Authorization"] == null){
        $jwtStatus = jwtControl(jwtDecode($headers["Authorization"]));
        
        if($jwtStatus){
            $request = json_decode(file_get_contents('php://input'), true);

            $userLastname = $request['user']['lastname'];
            $userFirstName = $request['user']['firstname'];
            $taskTitle = $request['task']['title'];
            $tasks = $request['task']['tests'];

            $req_db = $db->exec('CREATE TABLE '.  )

        }
        else if($jwtStatus == 'TIMEOUT'){
            echo 'JWT TIME ONT';
        }else{
            echo 'USER NOT FONT';
        }

    }else{
        echo 'NO JWT';
    }
});


$router->addNotFoundHandler(function(){
    echo "NOT FOUND PATH";
});

$router->run();

?>
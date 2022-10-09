<?php
require_once './src/Router.php';
require_once './src/jwt.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Method: POST');
header("Access-Control-Allow-Headers: Content-type, Accept, Authorization, X-Requested-With, X-Auth-Token, Origin, Application");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {    
    return 0;
} 

use App\Router;

const API_VERSION = '/api/v1';
const DB_PATH = './db/database.db';

$router = new Router();

$router->post(API_VERSION . '/user/signin', function($parps) {
    $request = json_decode(file_get_contents('php://input'), true);

    $userEmail = $request['email'];
    $userPassword = $request['password'];

    if(!$userEmail == '' || !$userEmail == null){
        if(!$userPassword == '' || !$userPassword == null){
            $db = new PDO("sqlite:./db/database.db");

            $result = $db->query('SELECT * 
                                FROM users 
                                WHERE email="'.$userEmail.'" OR password="'.$userPassword.'"')
                                ->fetchAll(PDO::FETCH_ASSOC);

            if(!count($request) == 0){
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
                    "_id" => $_id,
                    "token" => jwtEncode($_id, $_email),                        
                );
                echo json_encode($respons);
            }else{
                echo 'error';
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

$router->post(API_VERSION . '/task/create', function($parps){
    $headers = getallheaders();
    
    if(!$headers["Authorization"] == "" || !$headers["Authorization"] == null){
        $jwtStatus = jwtControl(jwtDecode($headers["Authorization"]));
        
        if($jwtStatus){
            $request = json_decode(file_get_contents('php://input'), true);

            $taskTitle = $request['task']['title'];
            $tasks = $request['task']['tests'];

            $db = new PDO("sqlite:./db/database.db");

            $db->exec('CREATE TABLE IF NOT EXISTS tests (
                id INTEGER PRIMARY KEY,
                title VARCHAR(50),
                question VARCHAR(150),
                answer VARCHAR(150),
                answer_v1 VARCHAR(150),
                answer_v2 VARCHAR(150),
                answer_v3 VARCHAR(150),
                answer_v4 VARCHAR(150)                
            );');

            foreach($tasks as $task){
                $db->exec('INSERT INTO tests(title, question, answer, answer_v1, answer_v2, answer_v3, answer_v4) 
                    VALUES("'.$taskTitle.'", 
                    "'.$task['question'].'", 
                    "'.$task['answer'].'", 
                    "'.$task['answer_variants']['1'].'", 
                    "'.$task['answer_variants']['2'].'", 
                    "'.$task['answer_variants']['3'].'", 
                    "'.$task['answer_variants']['4'].'"
                );');
            }

            echo '200';
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

$router->get(API_VERSION . '/tasks/all', function($parps) {
    $db = new PDO("sqlite:./db/database.db");

    $res_db = $db->query('SELECT title, COUNT(*) AS amount 
                        FROM tests GROUP BY title HAVING COUNT(*)>1;')
                        ->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($res_db);
});

$router->get(API_VERSION . '/tasks/get', function($parps) {
    if($parps['task']){
        $taskname = $parps['task'];
        $db = new PDO("sqlite:./db/database.db");

        $tests = $db->query('SELECT id, question, answer_v1, answer_v2, answer_v3, answer_v4 
                            FROM tests 
                            WHERE title="'.$taskname.'";')
                            ->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($tests);
    
    }else{
        echo 'PARAMS TEST NOT FOUND';
    }
});

$router->post(API_VERSION . '/tasks/control', function($parps){
    $request = json_decode(file_get_contents('php://input'), true);
    $db = new PDO("sqlite:./db/database.db");

    $user_respons_title = $request['title'];
    $user_respons_tests = $request['tests'];
    $user_id = $request['userId'];
    $controlNamber = 0;

    $db->exec('CREATE TABLE IF NOT EXISTS userAnswers (
        id INTEGER PRIMARY KEY,
        userId INTEGER,
        testId INTEGER,
        title VARCHAR(150),
        answer VARCHAR(250)
    );');
    
    foreach($user_respons_tests as $test){
        $answers = $db->query('SELECT id, answer FROM tests 
            WHERE title="'.$user_respons_title.'" AND question="'.$test['question'].'";')->fetchAll(PDO::FETCH_ASSOC);

        if($test['answer'] == $answers[0]['answer']) $controlNamber++;

        $db->exec('INSERT INTO userAnswers(userId, testId, title, answer) VALUES(
            '.$user_id.',
            '.$answers[0]['id'].',
            "'.$user_respons_title.'",
            "'.$test['answer'].'"
        );' );
    }

    echo json_encode([
        "right answers" => $controlNamber
    ]);
});

$router->addNotFoundHandler(function(){
    echo "NOT FOUND PATH";
});

$router->run();

?>
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Method: GET, POST');
header("Content-type: application/json; charset=utf-8");

require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function jwtEncode($userID, $userEmail){
    $key = 'example_key';
    
    $payload = [
        'iss' => 'http://localhost/',
        'aud' => 'http://localhost/',
        'exp' => time()+3600,
        'data' => [
            'id' => $userID,
            'email' => $userEmail
        ]
    ];

    return JWT::encode($payload, $key, 'HS256');
}
function jwtDecode($jwt){
    $key = 'example_key';
    $decoded = (array) JWT::decode($jwt, new Key($key, 'HS256'));
    return $decoded;
}

function jwtControl($jwt):bool{
    if($jwt['exp'] >= time()){
        $db = new PDO("sqlite:./db/database.db");
        $jwtData = $jwt['data'];
        $jwtId = $jwtData->id;
        $jwtEmail = $jwtData->email;

        $req_db = $db->query('SELECT * FROM users WHERE id='.$jwtId.' AND email="'.$jwtEmail.'"')->fetchAll(PDO::FETCH_ASSOC);

        if(sizeof($req_db) == 1) return true;
        if(sizeof($req_db) == 0) return false;
    }else{
        return 'TIMEOUT';
    }
}



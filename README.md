# PLATFORMTESTS

DOCUMENTATION API
API URL = "[hostname]/api/v1"

Sing in
Sing up
Create task
All tasks
Controll task

================= SING IN =======================
URL = '[hostname]/api/v1/user/signin'

REQUEST
METHOD="POST"
HEADER="Content-Type: application/json"
BODY = {
    email: String
    password: String
}

RESPONS
BOBY = {
    lastname: String,
    firtname: String,
    token: 'String'
} 

================= SING UP ========================
URL = '[hostname]/api/v1/user/signup'

REQUEST
METHOD="POST"
HEADER="Content-Type: application/json"
BODY = {
    firstname: String,
    lastname: String,
    email: String,
    password: String
}

RESPONS
BOBY = {
    status: 200
}

================ CREATE TASK ========================
URL = '[hostname]/api/v1/task/create'

REQUEST
METHOD="POST"
HEADER="Content-Type: application/json
        Authorization: TOKEN",    
BODY = {
    title: String,
    tests { 
        test: {
            question: String,
            answer_variants: {
                1 answer: String,
                2 answer: String,
                3 answer: String,
                4 answer: String
            }
            answer: String
        }
    }
}

RESPONS
BOBY = {
    status: 200
}

================ GET ALL TASK ========================
URL = '[hostname]/api/v1/tasks/all'
REQUEST
METHOD="GET"
HEADER="Content-Type: application/json"

RESPONS:
BODY{
    titles: String,
    amount: String
}

================ GET TASK ========================
URL = '[hostname]/api/v1/tasks/get'
REQUEST
METHOD="GET"
HEADER="Content-Type: application/json"
PARAMS=task
EXAMPLE='[hostname]/api/v1/tasks/get?task="алтынорда"'

RESPONS:
BODY: {
    id: String,
    title: String,
    question: String,
    answer_v1: String,
    answer_v2: String,
    answer_v3: String,
    answer_v4: String,
}

================ CONTROL TASK ========================
URL = '[hostname]/api/v1/tasks/control'
REQUEST
METHOD="POST"
HEADER="Content-Type: application/json"
BODY = {
    "userId": Number,
    "title" : String,
    "tests": {
        "question" : String,
        "answer : String
    }
}

RESPONS
BODY {
    "right answers": int
}
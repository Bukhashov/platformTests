import {useLoaderData, useNavigate } from 'react-router-dom';
import Test from '../components/Test';

export default function Task(){
    const task = useLoaderData();
    const tests = Object.values(task);
    const navigate = useNavigate();
    
    let arrayAnswers = new Array(tests.length);
    let i = 0;
    
    function getValueRadioBtn(id, answer, question){
        let ObAnswer = {"answer" : answer, 
                        "question" : question };
        arrayAnswers[id] = ObAnswer;
    }
    async function controlTest(){
        try{
            await fetch('http://localhost:8800/api/v1/tasks/control',{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    "userId" : sessionStorage.getItem("_id"),
                    "title" : sessionStorage.getItem('tasktitle'),
                    "tests" : arrayAnswers
                })
            })
            .then(response => response.json())
            .then(res => {
                sessionStorage.setItem('right', res['right answers']);
                sessionStorage.setItem('Nq', tests.length);
                return navigate("/task/ball");
            })
        }catch(e){

        }

        console.log()
    }

    return(
        <div className="task grid mt-6 mb-6">
            {
                tests.map((id) => (
                    <Test getValueRadioBtn={getValueRadioBtn}
                                                    id={i++}
                                                    question={id['question']} 
                                                    av1={id['answer_v1']} 
                                                    av2={id['answer_v2']}
                                                    av3={id['answer_v3']}
                                                    av4={id['answer_v4']} />
                ))
            }

            <div className="flex justify-center">
                <div className="p-3 bg-indigo-500 text-white cursor-pointer" onClick={controlTest}>Control</div>
            </div>
        </div>
    );
}
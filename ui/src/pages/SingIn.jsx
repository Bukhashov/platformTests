import { SimpleButton } from '../components/SimpleButton';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';

export default function Singin(){
    let navigate = useNavigate();
    const [email, stateEmail] = useState('');
    const [password, statePassord] = useState('');

    const onChangeEmail = event => {
        stateEmail(event.target.value);
    }
    const onChangePassword = event => {
        statePassord(event.target.value);
    }

    async function onclickLongin(){
            await fetch('http://localhost:8800/api/v1/user/signin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                password: password,
            })
            })
            .then(response => response.json())
            .then(res => {
                if(res['status'] == 200){
                    sessionStorage.setItem('jwt', res['token'])
                    sessionStorage.setItem('_id', res['_id'])
                    return navigate("/");
                }
            });
            
    }
    return(
        <div className="grid justify-center">
            <div className="bg-indigo-500 grid justify-center p-5 mt-20">
                <div className="flex justify-center text-white">SING IN</div>
                <div className="grid">
                    <div className="m-2">
                        <input className="w-25" 
                            type="text" 
                            value={email}
                            onChange={onChangeEmail}
                        />
                    </div>
                    <div className="m-2">
                        <input className="w-25" 
                            type="text" 
                            value={password}
                            onChange={onChangePassword}
                        />
                    </div>
                </div>
                <div className="flex justify-center" onClick={onclickLongin}><SimpleButton path={""} title={"Log In"} /></div>
            </div>
        </div>
    );
}
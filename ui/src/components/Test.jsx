import {useState} from 'react';

export default function Test (props){
    const rundomName = "iRName" + Math.floor(Math.random() * 1000);

    function onChangeValue(event){;
        props.getValueRadioBtn(props.id, event.target.value, props.question);
    }
    
    return(
        <div className="Test py-3 px-6">
            <div className="">{props.question}</div>
            <div className=""  onChange={onChangeValue}>
                <div className=""> <input type="radio" value={props.av1} name={rundomName}/> {props.av1} </div>
                <div className=""> <input type="radio" value={props.av2} name={rundomName}/> {props.av2} </div>
                <div className=""> <input type="radio" value={props.av3} name={rundomName}/> {props.av3} </div>
                <div className=""> <input type="radio" value={props.av4} name={rundomName}/> {props.av4} </div>
            </div>
        </div>
    );
}
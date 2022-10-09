import {useLoaderData } from 'react-router-dom';
import Task from '../components/Task';

export default function Index(){
    const titles = useLoaderData();
    const val = Object.values(titles);

    
    

    return(
        <div className="index mt-3 flex flex-wrap justify-center">
            
            {
                val.map( (id) => (
                    <div className="px-2 py-3">
                        <Task title={id['title']} amount={id['amount']} path={id['title']}/>
                    </div>
                ))
            }
            
        </div>
    );
}
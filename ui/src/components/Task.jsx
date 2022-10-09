import { Link } from "react-router-dom";
export default function Task({title, amount, path}){
    function savetitle(){
        sessionStorage.setItem('tasktitle', title);
    }
    return(
        <div className="w-[350px] h-[80px] bg-indigo-500 rounded">
            <Link to={`task/${path}`}>
                <div onClick={savetitle} className="w-full h-full flex justify-between items-center px-2">
                    <div className="text-white">{title}</div>
                    <div className="flex items-center">
                        <div className="bg-white w-[2px] h-[60px] rounded mx-2"></div>
                        <div className="text-white text-2xl">{amount}</div>
                    </div>
                </div>
            </Link>
        </div>
    );
}
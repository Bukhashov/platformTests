export default function Ball(){
    const right = sessionStorage.getItem('right');
    const nq = sessionStorage.getItem('Nq');
    const title = sessionStorage.getItem('tasktitle');
    
    const y = Math.floor(right*100)/nq;

    return(
        <div className="flex justify-center">
            <div className="grid mt-9">
                <div className="w-96 text-white bg-indigo-500 flex justify-center py-6">{title}</div>
                <div className="w-96">
                    <div>You answered: {right}/{nq}</div>
                    <div className="w-full flex">
                        <div style={{ width: y+"%" }} className={" bg-indigo-500 h-2"}></div>
                        <div className="w-full bg-red-500"></div>
                    </div>
                </div>
            </div>         
        </div>
    )
}
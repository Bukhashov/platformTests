import { Link } from "react-router-dom"
export function SimpleButton({title, path} ){
    return(
        <button className="text-white"><Link to={path}>{title}</Link></button>
    )
}
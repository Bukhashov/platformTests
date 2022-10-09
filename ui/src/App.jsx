import { Link, Outlet, useLoaderData, useLocation} from 'react-router-dom';
import { SimpleButton } from './components/SimpleButton';

function App() {
  let navberBtn = "";
  let location = useLocation();
  
  if(location.pathname == "/"){
    navberBtn = <div className='flex items-center'>
                  <div className="pr-3"><SimpleButton title={"Sing in"} path={'/singin'}/></div>
                  <div className="bg-white w-[3px] h-[20px] rounded"></div>
                  <div className="pl-3"><SimpleButton title={"Sing up"} path={'/singup'}/></div>
                </div>
  }else if(location.pathname == "/singin"){
    navberBtn = <div className='flex items-center'>
                  <div><SimpleButton title={"Sing up"} path={'/singup'}/></div>
                </div>
  }else if(location.pathname == "/singup"){
    navberBtn = <div className='flex items-center'>
                  <div><SimpleButton title={"Sing in"} path={'/singin'}/></div>
                </div>
  }else if(location.pathname == "/test/"){
    
  }
  
  return (
    <div className="app">
      <header className="bg-indigo-500">
        <div className="wrapper">
          <nav className="navbar flex items-center py-5 justify-between">
            <div className="text-xl text-white font-medium"><Link to="/">Web Test</Link></div>
            {navberBtn}
          </nav>
        </div>
      </header>
      <div className="wrapper">
        <Outlet/>
      </div>
    </div>
  );
}

export default App;
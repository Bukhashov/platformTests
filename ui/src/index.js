import React from 'react';
import ReactDOM from 'react-dom/client';
import { createBrowserRouter, 
          RouterProvider, 
          createRoutesFromElements, 
          Route, 
          redirect } from 'react-router-dom';
import './index.css';
import App from './App';
import Index from './pages/Index';
import Singin from './pages/SingIn';
import Singup from './pages/SingUp';
import Task from './pages/Task';
import Ball from './pages/Ball';

const urlapi = 'http://localhost:8800/api/v1';

const router = createBrowserRouter(
  createRoutesFromElements(
    <Route>
      <Route path="/" element={<App/>} >
        <Route index 
          element={<Index/>}
          loader={ async() => {
            return fetch(`${urlapi}/tasks/all`);
          }}
        />
        <Route path="/singin" element={<Singin/>} />
        <Route path="/singup" element={<Singup/>} />
        <Route path="/task/:taskname"  element={<Task/>}
          loader = { async ({params}) => {
            if(sessionStorage.getItem('jwt')){
              return fetch(`${urlapi}/tasks/get?task=${params.taskname}`);
            }else{
              return redirect('/singin');
            }
            
          }}
        />
        <Route path="/task/ball" element={<Ball/>} 
          loader = { async () => {
            if(!sessionStorage.getItem('jwt') || !sessionStorage.getItem('right')){ 
              return redirect('/singin');
            }
          }} 
        />
      </Route>

    </Route>
  )
);



const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode> 
      <RouterProvider router={router} />
  </React.StrictMode>
);

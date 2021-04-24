<?php

namespace System;

 class App
 {
     /** 
     * @throws \ErrorException
     */
     public static function run()
    {
         
         $path = $_SERVER['REQUEST_URI'];

         $pathParts = explode('/', $path);

         // Получаем имя контроллера
         $controller = $pathParts[1] ? $pathParts[1] : 'organization';
       
     
        $action = $pathParts[2] ? $pathParts[2] : 'list';

         // Формируем пространство имен для контроллера
         $controller = 'Controllers\\' . $controller . 'Controller';
    
         $action = 'action' . ucfirst($action);

    
         if (!class_exists($controller)) {
             throw new \ErrorException('Controller does not exist');
         }

        
         $objController = new $controller;

       
         if (!method_exists($objController, $action)) {
            throw new \ErrorException('action does not exist');
        }

          $objController->$action();
     }
 }
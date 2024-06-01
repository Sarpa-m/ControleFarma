<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Http\Request;


class Exception
{
    /**
     * 
     *
     * @param  Request $request
     * @param  int|null $alert
     * @return string
     */
    public static function getViewException($mensagen)
    {
       
      
        return View::render('Exception\\ViewException', $mensagen);

       
    }


   
}

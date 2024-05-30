<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Http\Request;


class Login
{
    /**
     * 
     *
     * @param  Request $request
     * @param  int|null $alert
     * @return string
     */
    public static function getLoginPege($request, $alert = null)
    {
        // Verifica o valor do alerta e define a mensagem de erro correspondente
        switch ($alert) {
            case 1:
                $alert = Page::getAlertError("Usuário não registrado");
                break;
            case 2:
                $alert = Page::getAlertError("Usuário ou senha incorreto");
                break;
            default:
                $alert = "";
                break;
        }

      
        return View::render('login\\LoginPege', [
            "alert" => $alert,
            "footer" => Page::getFooter(),
        ]);

       
    }


   
}

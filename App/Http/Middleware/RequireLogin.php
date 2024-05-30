<?php

namespace App\Http\Middleware;

use App\Session\Login as SessionLogin;
use App\Http\Request;
use App\Utils\View;

class RequireLogin
{
    /**
     * Método responsavel por executar o Middleware
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {

        if (!SessionLogin::isLogged()) {
            $request->getRouter()->redirect(URL . '/login');
        }

        View::init([
            "NOME" => $_SESSION['usuario']['nome'],
            "USERNAME" => $_SESSION['usuario']['username'],
            "USERID" => $_SESSION['usuario']['id'],
        ]);

        //CONTINUA A EXECUÇÃO
        return $next($request);
    }
}

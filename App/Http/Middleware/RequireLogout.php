<?php

namespace App\Http\Middleware;

use App\Session\Login as SessionLogin;
use App\Http\Request;


class RequireLogout
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

        if (SessionLogin::isLogged()) {
            $request->getRouter()->redirect(URL . '/');
        }
        //CONTINUA A EXECUÇÃO
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Session\Login as SessionLogin;
use App\Http\Request;

class RequireLogout
{
    /**
     * Método responsável por executar o Middleware.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        // Verifica se o usuário está logado
        if (SessionLogin::isLogged()) {
            // Redireciona para a página principal se estiver logado
            $request->getRouter()->redirect(URL . '/');
        }

        // Continua a execução do próximo middleware
        return $next($request);
    }
}

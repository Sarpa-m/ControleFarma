<?php

namespace App\Http\Middleware;

use App\Session\Login as SessionLogin;
use App\Http\Request;
use App\Utils\View;

class RequireLogin
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
        if (!SessionLogin::isLogged()) {
            // Redireciona para a página de login se não estiver logado
            $request->getRouter()->redirect(URL . '/login');
        }

        // Inicializa as variáveis de view com os dados do usuário logado
        View::init([
            "NOME" => $_SESSION['usuario']['nome'],
            "USERNAME" => $_SESSION['usuario']['username'],
            "USERID" => $_SESSION['usuario']['id'],
        ]);

        // Continua a execução do próximo middleware
        return $next($request);
    }
}

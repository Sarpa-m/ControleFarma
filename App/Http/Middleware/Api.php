<?php

namespace App\Http\Middleware;

use App\Http\Request;

class Api
{

    /**
     * Método responsável por executar o Middleware
     *
     * @param Request $request A requisição atual.
     * @param \Closure $next A função de callback que executa o próximo middleware.
     * @return Response 
     */
    public function handle($request, $next)
    {
        // Define o tipo de conteúdo da resposta como JSON.
        $request->getRouter()->setContentType("application/json");

        // Executa o próximo Middleware.
        return $next($request);
    }
}


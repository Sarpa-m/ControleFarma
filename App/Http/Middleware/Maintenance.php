<?php

namespace App\Http\Middleware;

use App\Http\Request;

class Maintenance
{
    /**
     * Método responsável por executar o Middleware.
     *
     * @param Request $request A requisição atual.
     * @param \Closure $next A função de callback que executa o próximo middleware.
     * @return Response A resposta gerada.
     * @throws \Exception Se o site estiver em manutenção.
     */
    public function handle($request, $next)
    {
        // Verifica se a variável de ambiente "Maintenance" está definida como "true".
        if (getenv("Maintenance") == "true") {
            // Lança uma exceção informando que a página está em manutenção.
            throw new \Exception('PÁGINA EM MANUTENÇÃO', 503);
        }

        // Executa o próximo middleware.
        return $next($request);
    }
}

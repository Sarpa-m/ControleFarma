<?php

namespace App\Http\Middleware;

use App\Http\Request;


class Mentenance
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

        if (getenv("Mentenance") == "true") {
            throw new \Exception('PÁGINA EM MANUTEMÇÃO', 200);
            
        }
        //EXCUTA O PROXIMO Middleware   
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Utils\Cache\File as CacheFile;
use App\Utils\Token;

class Cache
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
        //VERIFICA SE A ROTA ATUAL É CACHEÁVEL se sim EXCUTA O PROXIMO Middleware  
        if (!$this->isCacheable($request))  return $next($request);

        //HACH DO CACHE
        $hash = $this->getHash($request);

        //RETORNA OS DADOS DO CACHE
        return CacheFile::getCache(
            $hash,
            getenv('CACHE_TIME'),
            function () use ($request, $next) {
                return $next($request);
            }
        );
    }

    /**
     * Método responsavel por verifivicar se a request pode ser cacheada
     *  
     * @param  Request $request
     * @return boolean
     */
    private function isCacheable($request)
    {
        //VALIDA O TEMPO DE CACHE
        if (getenv('CACHE_TIME') <= 0) return false;

        //VALIDA O METODO DA REQUSIÇÃO
        if ($request->getHttpMethod() != 'GET') return false;

        //VALIDA O HEADER DE CACHE
        $headers = $request->getHeaders();
        if (isset($headers['Cache-Control']) and $headers['Cache-Control'] == 'no-cache') return false;

        //CACHEÁVEL
        return true;
    }

    /**
     * Método responsavel por retoenar a HACHE doi cache
     *
     * @param Request $request
     * @return string
     */
    private function getHash($request)
    {
        //URI DA ROTA
        $uri =  rtrim($request->getRouter()->getUri(), '/');


        //QUERY PARAMS
        $queryParams = $request->getQueyParams();

        $uri .= !empty($queryParams) ? '?' . http_build_query($queryParams) : '';
        $uri .= "c";
        //REMOVE AS BARRAS E RETRORNA A HASH
        return preg_replace('/[^0-9a-zA-Z]/', "-", ltrim($uri, '/'));
    }
}

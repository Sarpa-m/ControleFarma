<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Utils\Cache\File as CacheFile;
use App\Utils\Token;

class Cache
{
    /**
     * Método responsável por executar o Middleware
     *
     * @param Request $request A requisição atual.
     * @param \Closure $next A função de callback que executa o próximo middleware.
     * @return Response A resposta gerada, possivelmente vinda do cache.
     */
    public function handle($request, $next)
    {
        // Verifica se a rota atual é cacheável. Se não for, executa o próximo middleware.
        if (!$this->isCacheable($request)) return $next($request);

        // Gera o hash do cache.
        $hash = $this->getHash($request);

        // Retorna os dados do cache, ou executa o próximo middleware se o cache estiver expirado.
        return CacheFile::getCache(
            $hash,
            getenv('CACHE_TIME'),
            function () use ($request, $next) {
                return $next($request);
            }
        );
    }

    /**
     * Método responsável por verificar se a request pode ser cacheada.
     *
     * @param Request $request A requisição atual.
     * @return boolean Indica se a requisição é cacheável.
     */
    private function isCacheable($request)
    {
        // Valida o tempo de cache.
        if (getenv('CACHE_TIME') <= 0) return false;

        // Valida o método da requisição.
        if ($request->getHttpMethod() != 'GET') return false;

        // Valida o header de cache.
        $headers = $request->getHeaders();
        if (isset($headers['Cache-Control']) && $headers['Cache-Control'] == 'no-cache') return false;

        // Se todas as validações passarem, a requisição é cacheável.
        return true;
    }

    /**
     * Método responsável por retornar o hash do cache.
     *
     * @param Request $request A requisição atual.
     * @return string O hash do cache.
     */
    private function getHash($request)
    {
        // URI da rota.
        $uri = rtrim($request->getRouter()->getUri(), '/');

        // Query parameters.
        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?' . http_build_query($queryParams) : '';
        $uri .= "c";

        // Remove as barras e retorna o hash.
        return preg_replace('/[^0-9a-zA-Z]/', "-", ltrim($uri, '/'));
    }
}

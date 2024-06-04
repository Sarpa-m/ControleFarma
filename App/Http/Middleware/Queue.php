<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;

class Queue
{
    /**
     * Mapeamento de middlewares padrão.
     *
     * @var array
     */
    private static $default = [];

    /**
     * Mapeamento de middlewares a serem executados.
     *
     * @var array
     */
    private static $map = [];

    /**
     * Fila de middlewares a serem executados.
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * Função de execução do controlador.
     *
     * @var mixed
     */
    private $controller;

    /**
     * Argumentos da função do controlador.
     *
     * @var array
     */
    private $controllerArgs = [];

    /**
     * Método responsável por construir a classe de fila de middlewares.
     *
     * @param array $middlewares Fila de middlewares a serem executados.
     * @param \Closure $controller Controlador a ser executado.
     * @param array $controllerArgs Argumentos da função do controlador.
     */
    public function __construct($middlewares, $controller, array $controllerArgs)
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Método responsável por executar o próximo nível da fila.
     *
     * @param Request $request A requisição atual.
     * @return Response A resposta gerada.
     * @throws \Exception Se o middleware não puder ser processado.
     */
    public function next($request)
    {
        // Verifica se a fila está vazia.
        if (empty($this->middlewares)) {
            return call_user_func_array($this->controller, $this->controllerArgs);
        }

        // Obtém o próximo middleware.
        $middleware = array_shift($this->middlewares);

        // Verifica o mapeamento.
        if (!isset(self::$map[$middleware])) {
            throw new \Exception('Middleware não pode ser processado: ' . $middleware, 500);
        }

        // Define o próximo passo na fila.
        $queue = $this;
        $next = function ($request) use ($queue) {
            return $queue->next($request);
        };

        // Executa o middleware.
        return (new self::$map[$middleware])->handle($request, $next);
    }

    /**
     * Método responsável por definir o mapeamento de middlewares.
     *
     * @param array $map Mapeamento de middlewares.
     */
    public static function setMap($map)
    {
        self::$map = $map;
    }

    /**
     * Método responsável por definir os middlewares padrões.
     *
     * @param array $default Middlewares padrão.
     */
    public static function setDefault($default)
    {
        self::$default = $default;
    }
}

<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;


class Queue
{
    /**
     * Mapeamento de middlewares Padão
     *
     * @var array
     */
    private static $default=[];
    /**
     * Mapeamento de  middlewares a seram executados 
     *
     * @var array
     */
    private static $map = [];

    /**
     * Fila de middlewares a seram executados 
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * Função de excuçõa do contralador 
     *
     * @var mixed
     */
    private $controller;

    /**
     * Argumentos da função do controlador 
     *
     * @var array
     */
    private $controllerArags = [];

    /**
     * Métod responsavel por contruir a classe de fila de middlewares
     *
     * @param  array $middlewares
     * @param  \Closure $controller
     * @param  array $controllerArags
     * @return void
     */
    public function __construct($middlewares, $controller, array $controllerArags)
    {
        $this->middlewares = array_merge(self::$default,$middlewares);
        $this->controller = $controller;
        $this->controllerArags = $controllerArags;
    }

    /**
     * Método responsavel por excutar o proximo nivel da fila 
     *
     * @param  Request $request
     * @return Response
     */
    public function next($request)
    {
        //VERIFICA SE A FILA ESTA VAZIA
        if (empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArags);

        //MIDDLEWARE
        $middleware =array_shift($this->middlewares);
      
        //VERIFICA O MAPEAMENTO
        if(!isset(self::$map[$middleware])){
            throw new \Exception('Middleware nãp pode ser prosesado <br>' . $middleware, 500);            
        }

        //Next
        $queue = $this;
        $next = function($request) use ($queue){
            return $queue->next($request);
        };

        //EXCUTA O MIDDLEWARE
      return (new self::$map[$middleware])->handle($request,$next);
    }

    /**
     * Método responsavel por definir o mapeamento de middlewares
     * @author Mauricio Sarpa <mauricio@sarpa.dev>
     * @param  array $map
     */
    public static function setMap($map)
    {
        self::$map = $map;
    }
    
    /**
     * Método responsavel por definir os middlewares padrôes
     *
     * @param  array $default
     * @return void
     */
    public static function setDefault($default){
        self::$default = $default;
    }
}

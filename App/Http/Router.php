<?php

namespace App\Http;

use App\Http\Middleware\Queue as MiddlewareQueue;
use  App\Http\Request;
use Exception;

class Router
{
    /**
     * URL completa do projeto
     *
     * @var string
     */
    private $url = "";
    /**
     * Prefixo de todoas as rotas 
     *
     * @var string
     */
    private $prefix = '';
    /**
     * Índice das rotas
     *
     * @var array
     */
    private $routes = [];
    /**
     * Instancia de Request
     *
     * @var Request
     */
    private $request;
    /**
     * Content type padrão do response
     *
     * @var string
     */
    private $contentType = 'text/html';
    /**
     * Método responsavel por definir a classe
     *
     * @param  mixed $url
     * @return void
     */
    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url     = $url;
        $this->serPrefix();
    }
    /**
     * Método responsavel por alterar o valor do contenty Type
     * @param strinf $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }
    /**
     * Metodo responsaverl por configurar as rotas
     *
     * @param array $class
     * @param Router $obRouter
     * @return void
     */
    public function setRoutes($class, $obRouter)
    {
        foreach ($class as $value) {
            $value::init($obRouter);
        }
    }
    /**
     * Método resposavel por definir o prefixo da rota
     */
    private function serPrefix()
    {
        //INFORMAÇOES DA URL ATUAL
        $parseUrl = parse_url($this->url);

        //DEFINE O PREFIXO 
        $this->prefix = $parseUrl['path'] ?? '';
    }
    /**
     * Método resposaver por adcionar uma rota na classe
     * @param  string $method
     * @param  string $route
     * @param  array $params
     */
    private function addRout($method, $route, $params = [])
    {
        //FORÇA A TER "/" NO FINAL DA ROTA
        $route = preg_replace('#/$#', '', $route) . "/";


        //VALIDAÇÃO DOS PARAMETROS
        foreach ($params as $key => $value) {
            if ($value instanceof \Closure) {
                $params["controller"] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //MIDDLESWARES DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        //Variaves da rota
        $params["variables"] = [];

        //PADRÃO DE VARIAVES DAS ROTAS
        $patternVariables = "/{(.*?)}/";
        if (preg_match_all($patternVariables, $route, $matches)) {
            $route               = preg_replace($patternVariables, "(.*?)", $route);
            $params["variables"] = $matches[1];
        }

        //Padão de validação da URL
        $patternRoute = "/^" . str_replace("/", "\/", $route) . "$/";


        //Adciona a rota dentro da classe

        $this->routes[$patternRoute][$method] = $params;
    }
    /**
     * Método resposavel por definir uma rota de GET
     * @param  string $route
     * @param  array $params
     */
    public function get($route, $params = [])
    {
        return $this->addRout("GET", $route, $params);
    }
    /**
     * Método resposavel por definir uma rota de POST
     * @param  string $route
     * @param  array $params
     */
    public function post($route, $params = [])
    {
        return $this->addRout("POST", $route, $params);
    }
    /**
     * Método resposavel por definir uma rota de PUT
     * @param  string $route
     * @param  array $params
     */
    public function put($route, $params = [])
    {
        return $this->addRout("PUT", $route, $params);
    }
    /**
     * Método resposavel por definir uma rota de DELETE
     * @param  string $route
     * @param  array $params
     */
    public function delete($route, $params = [])
    {
        return $this->addRout("DELETE", $route, $params);
    }
    /**
     * Método responsavel por retotnar a uri desconsiderando o prefixo
     *
     * @return string
     */
    public function getUri()
    {
        $uri = $this->request->getUri();
        //fatia a uri com o prefixo 
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //retorna a uri sem prefixo 
        return end($xUri);
    }
    /**
     * Método responsavel por retornar os dados da rota atual
     *
     * @return array
     */
    private function getRoute()
    {
        //URL
        $uri = $this->getUri();

        //Method
        $httpMethod = $this->request->getHttpMethod();

        //valida as rotas 

        foreach ($this->routes as $patternRoute => $method) {
            //Verifica se a url bate com o padrão
            if (preg_match($patternRoute, $uri, $matches)) {
                //Verifica o metodo
                if (isset($method[$httpMethod])) {
                    //REMOVI A PRIMEIRA POSIÇÃO DO matches
                    unset($matches[0]);

                    //CHAVES
                    $keys = $method[$httpMethod]['variables'];

                    $method[$httpMethod]['variables']            = array_combine($keys, $matches);
                    $method[$httpMethod]['variables']["request"] = $this->request;


                    //Retorno dos parametros da rota 
                    return $method[$httpMethod];
                }
                //Metodo não permitido
                throw new Exception("Metodo não permitido", 405);
            }
        }
        // URL não encontrada 
        throw new Exception("URL não encontrada ", 404);
    }
    /**
     * Método resporaser por executar a rota
     *
     * @return Response
     */
    public function run()
    {

        try {
            //OBITEM A ROTA ATUAL
            $route = $this->getRoute();

            //Verifica o controlador
            if (!isset($route['controller'])) {
                throw new Exception("A URL não pode ser procesada", 500);
            }

            //argumetos da função 
            $args = [];

            //REFLECTION
            $reflection = new \ReflectionFunction($route['controller']);

            foreach ($reflection->getParameters() as $parameter) {

                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            //RETORNA A EXCUÇÃO DA FILA DE MIDDLEWARES
            return (new MiddlewareQueue(
                $route['middlewares'],
                $route['controller'],
                $args
            ))->next($this->request);
        } catch (\Exception $e) {
            
            return new Response($e->getCode(), $this->getErrorMenssage($e->getMessage()), $this->contentType);
        }
    }
    /**
     * Métod responsavel por retornar a mensagem de erro de acordo com o content type
     *
     * @param string $mensagem
     * @return mixed
     */
    private function getErrorMenssage($mensagem)
    {
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error' => $mensagem
                ];
                break;

            default:
                return $mensagem;
                break;
        }
    }
    /**
     * Método responsavel por retarnar a URL atual
     *
     * @return string
     */
    public function getcurrentUrl()
    {
        $URLc = $this->url . $this->getUri();

        return preg_replace('#/$#', '', $URLc);
    }
    public function redirect($URL)
    {
       
       

        //EXECUTA O REDIRECT
        header('location: ' . $URL);
      
        exit;
    }
}

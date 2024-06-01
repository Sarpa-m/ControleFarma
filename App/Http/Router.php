<?php

namespace App\Http;

use App\Controller\Exception\Exception as ControllerException;
use App\Http\Middleware\Queue as MiddlewareQueue;
use App\Http\Request;
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
     * Prefixo de todas as rotas 
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
     * Instância de Request
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
     * Método responsável por definir a classe
     *
     * @param string $url URL completa do projeto
     */
    public function __construct($url)
    {
        // Cria uma nova instância de Request
        $this->request = new Request($this);

        // Define a URL do projeto
        $this->url = $url;

        // Define o prefixo das rotas
        $this->setPrefix();
    }

    /**
     * Método responsável por alterar o valor do contentType
     *
     * @param string $contentType Tipo de conteúdo a ser definido
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Método responsável por configurar as rotas
     *
     * @param array $class Array de classes que definem as rotas
     * @param Router $obRouter Instância do roteador
     */
    public function setRoutes($class, $obRouter)
    {
        // Itera sobre as classes e inicializa as rotas
        foreach ($class as $value) {
            $value::init($obRouter);
        }
    }

    /**
     * Método responsável por definir o prefixo da rota
     */
    private function setPrefix()
    {
        // Obtém as informações da URL atual
        $parseUrl = parse_url($this->url);

        // Define o prefixo da rota
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     * 
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param string $route Caminho da rota
     * @param array $params Parâmetros adicionais para a rota
     */
    private function addRoute($method, $route, $params = [])
    {
        // FORÇA A TER "/" NO FINAL DA ROTA
        $route = preg_replace('#/$#', '', $route) . "/";

        // VALIDAÇÃO DOS PARÂMETROS
        foreach ($params as $key => $value) {
            if ($value instanceof \Closure) {
                $params["controller"] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // MIDDLEWARES DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        // Variáveis da rota
        $params["variables"] = [];

        // PADRÃO DE VARIÁVEIS DAS ROTAS
        $patternVariables = "/{(.*?)}/";
        if (preg_match_all($patternVariables, $route, $matches)) {
            $route = preg_replace($patternVariables, "(.*?)", $route);
            $params["variables"] = $matches[1];
        }

        // PADRÃO DE VALIDAÇÃO DA URL
        $patternRoute = "/^" . str_replace("/", "\/", $route) . "$/";

        // Adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota de GET
     * 
     * @param string $route Caminho da rota
     * @param array $params Parâmetros adicionais para a rota
     */
    public function get($route, $params = [])
    {
        return $this->addRoute("GET", $route, $params);
    }

    /**
     * Método responsável por definir uma rota de POST
     * 
     * @param string $route Caminho da rota
     * @param array $params Parâmetros adicionais para a rota
     */
    public function post($route, $params = [])
    {
        return $this->addRoute("POST", $route, $params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     * 
     * @param string $route Caminho da rota
     * @param array $params Parâmetros adicionais para a rota
     */
    public function put($route, $params = [])
    {
        return $this->addRoute("PUT", $route, $params);
    }

    /**
     * Método responsável por definir uma rota de DELETE
     * 
     * @param string $route Caminho da rota
     * @param array $params Parâmetros adicionais para a rota
     */
    public function delete($route, $params = [])
    {
        return $this->addRoute("DELETE", $route, $params);
    }

    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     *
     * @return string
     */
    public function getUri()
    {
        $uri = $this->request->getUri();
        // Fatia a URI com o prefixo 
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        // Retorna a URI sem prefixo 
        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     *
     * @return array
     */
    private function getRoute()
    {
        // URL
        $uri = $this->getUri();

        // Método HTTP
        $httpMethod = $this->request->getHttpMethod();

        // Valida as rotas
        foreach ($this->routes as $patternRoute => $method) {
            // Verifica se a URL bate com o padrão
            if (preg_match($patternRoute, $uri, $matches)) {
                // Verifica o método
                if (isset($method[$httpMethod])) {
                    // Remove a primeira posição do matches
                    unset($matches[0]);

                    // Chaves
                    $keys = $method[$httpMethod]['variables'];

                    $method[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $method[$httpMethod]['variables']["request"] = $this->request;

                    // Retorno dos parâmetros da rota 
                    return $method[$httpMethod];
                }
                // Método não permitido
                throw new Exception("Método não permitido", 405);
            }
        }
        // URL não encontrada 
        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Método responsável por executar a rota atual da aplicação.
     * Este método:
     * - Obtém a rota atual.
     * - Verifica se o controlador da rota está definido.
     * - Obtém os argumentos necessários para o controlador.
     * - Executa a fila de middlewares da rota.
     * - Retorna a resposta da execução da rota, tratando exceções.
     *
     * @return Response A resposta da execução da rota.
     */
    public function run()
    {
        try {
            // Obtém a rota atual
            $route = $this->getRoute();

            // Verifica se o controlador está definido
            if (!isset($route['controller'])) {
                throw new Exception("A URL não pode ser processada", 500);
            }

            // Argumentos da função
            $args = [];

            // Reflection
            $reflection = new \ReflectionFunction($route['controller']);

            // Obtém os parâmetros do controlador
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            // Executa a fila de middlewares
            return (new MiddlewareQueue(
                $route['middlewares'],
                $route['controller'],
                $args
            ))->next($this->request);
        } catch (\Exception $e) {

            // Trata exceções e retorna a resposta
            $content =  ControllerException::InterpretException($e, $this->contentType);
            return new Response($e->getCode(), $content, $this->contentType);
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
     * Método responsável por retornar a URL atual
     *
     * @return string URL atual
     */
    public function getCurrentUrl()
    {
        // Concatena a URL base com o URI atual
        $URLc = $this->url . $this->getUri();

        // Remove a barra final da URL, se existir
        return preg_replace('#/$#', '', $URLc);
    }

    /**
     * Método responsável por redirecionar para uma nova URL
     *
     * @param string $URL URL para a qual redirecionar
     */
    public function redirect($URL)
    {
        // Executa o redirecionamento
        header('Location: ' . $URL);

        // Encerra o script após o redirecionamento
        exit;
    }
}

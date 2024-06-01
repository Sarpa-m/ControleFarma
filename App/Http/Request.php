<?php
namespace App\Http;

use App\Http\Router;

class Request
{
    /**
     * Parâmetros da URL ($_GET) 
     *
     * @var array
     */
    private $queryParams = [];

    /**
     * Variáveis do POST da página ($_POST)
     *
     * @var array
     */
    private $postVars = [];

    /**
     * Método HTTP da requisição
     *
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página (rota)
     *
     * @var string
     */
    private $uri;

    /**
     * Cabeçalhos da requisição
     *
     * @var array
     */
    private $headers = [];

    /**
     * Cookies da página
     *
     * @var array
     */
    private $cookie = [];

    /**
     * Instância de Router
     *
     * @var Router
     */
    private $router;

    /**
     * Construtor da classe
     *
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->queryParams = $_GET ?? [];
        $this->setPostVars();
        $this->headers = getallheaders();
        $this->cookie = $_COOKIE ?? [];
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->router = $router;
    }

    /**
     * Método responsável por definir as variáveis POST
     */
    private function setPostVars()
    {
        // Verifica o método de requisição
        if ($this->httpMethod == "GET") {
            return false;
        }

        // POST padrão
        $this->postVars = $_POST ?? [];

        // POST JSON
        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }

    /**
     * Método responsável por definir a URI
     *
     * @return void
     */
    private function setUri()
    {
        $uri = explode("?", ($_SERVER['REQUEST_URI'] ?? ''))[0];
        $uri = preg_replace('#/$#', '', $uri);
        $uri .= "/";
        $this->uri = $uri;
    }

    /**
     * Método responsável por retornar a instância de Router
     *
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Método responsável por retornar o método HTTP da requisição
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Método responsável por retornar a URI da requisição
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Método responsável por retornar os headers da requisição
     *
     * @param string|null $key Chave específica para buscar no array de headers
     * @param mixed|null $default Valor padrão a ser retornado se a chave não existir ou estiver vazia
     * @return mixed Retorna o valor da chave específica, todos os headers, o valor padrão ou null
     */
    public function getHeaders($key = null, $default = null)
    {
        // Se a chave não for fornecida, retorna todos os headers
        if ($key == null) {
            return $this->headers;
        }

        // Verifica se a chave existe e não está vazia no array de headers
        if (isset($this->headers[$key]) && !empty($this->headers[$key])) {
            return $this->headers[$key];
        } else if ($default != null) {
            // Retorna o valor padrão se fornecido
            return $default;
        }

        // Retorna null se a chave não existe ou está vazia e o valor padrão não foi fornecido
        return null;
    }

    /**
     * Método responsável por retornar os queryParams da requisição
     *
     * @param string|null $key Chave específica para buscar no array de queryParams
     * @param mixed|null $default Valor padrão a ser retornado se a chave não existir ou estiver vazia
     * @return mixed Retorna o valor da chave específica, todos os queryParams, o valor padrão ou null
     */
    public function getQueryParams($key = null, $default = null)
    {
        // Se a chave não for fornecida, retorna todos os queryParams
        if ($key == null) {
            return $this->queryParams;
        }

        // Verifica se a chave existe e não está vazia no array de queryParams
        if (isset($this->queryParams[$key]) && !empty($this->queryParams[$key])) {
            return $this->queryParams[$key];
        } else if ($default != null) {
            // Retorna o valor padrão se fornecido
            return $default;
        }

        // Retorna null se a chave não existe ou está vazia e o valor padrão não foi fornecido
        return null;
    }

    /**
     * Método responsável por retornar as variáveis POST da requisição
     *
     * @param string|null $key Chave específica para buscar no array de variáveis POST
     * @param mixed|null $default Valor padrão a ser retornado se a chave não existir ou estiver vazia
     * @return mixed Retorna o valor da chave específica, todas as variáveis POST, o valor padrão ou null
     */
    public function getPostVars($key = null, $default = null)
    {
        // Se a chave não for fornecida, retorna todas as variáveis POST
        if ($key == null) {
            return $this->postVars;
        }

        // Verifica se a chave existe e não está vazia no array de variáveis POST
        if (isset($this->postVars[$key]) && !empty($this->postVars[$key])) {
            return $this->postVars[$key];
        } else if ($default != null) {
            // Retorna o valor padrão se fornecido
            return $default;
        }

        // Retorna null se a chave não existe ou está vazia e o valor padrão não foi fornecido
        return null;
    }

    /**
     * Método responsável por retornar os cookies da requisição
     *
     * @param string|null $key Chave específica para buscar no array de cookies
     * @param mixed|null $default Valor padrão a ser retornado se a chave não existir ou estiver vazia
     * @return mixed Retorna o valor da chave específica, todos os cookies, o valor padrão ou null
     */
    public function getCookie($key = null, $default = null)
    {
        // Se a chave não for fornecida, retorna todos os cookies
        if ($key == null) {
            return $this->cookie;
        }

        // Verifica se a chave existe e não está vazia no array de cookies
        if (isset($this->cookie[$key]) && !empty($this->cookie[$key])) {
            return $this->cookie[$key];
        } else if ($default != null) {
            // Retorna o valor padrão se fornecido
            return $default;
        }

        // Retorna null se a chave não existe ou está vazia e o valor padrão não foi fornecido
        return null;
    }
}

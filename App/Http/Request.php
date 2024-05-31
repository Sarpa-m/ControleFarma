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
    private $queyParams = [];
    /**
     * Variáves do POST da pagina ($_POST)
     *
     * @var array
     */
    private $postVars = [];
    /**
     * Método Http da requisição
     *
     * @var string
     */
    private $httpMethod;
    /**
     * URI da pagina (rota)
     *
     * @var string
     */
    private $uri;
    /**
     * Cabesolha da requisição
     *
     * @var array
     */
    private $headers = [];
    /**
     * Cookie da pagina 
     *
     * @var array
     */
    private  $cookie = [];
    /**
     * intacia de router
     *
     * @var Router
     */
    private $router;
    /**
     * contrutor da classe
     *
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->queyParams = $_GET ?? [];
        $this->setPostVars();
        $this->headers = getallheaders();
        $this->cookie = $_COOKIE ?? [];
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->router = $router;
    }
    private function setPostVars()
    {
        //VERIFICA O METODO DE REQUISIÇÃO
        if ($this->httpMethod == "GET") {
            return false;
        }

        //POST PADRÃO
        $this->postVars = $_POST ?? [];

        //POST JSON 
        $inputRaw = file_get_contents('php://input');

        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }
    /**
     * Método responsavel por definir a URI
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
     * Método resposaver por retornar a intacia de Router
     *
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }
    /**
     * Método responsavel por retornar o método HTTP da requisição
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }
    /**
     * Método responsavel por retornar o URI da requisição
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
    /**
     * Método responsavel por retornar o URI da requisição
     *
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    /**
     * Método responsavel por retornar os queyParams da requisição
     *
     * @return array
     */
    public function getQueyParams()
    {
        return $this->queyParams;
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
     * Método responsavel por retornar os cookies da requisição
     *
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }
}

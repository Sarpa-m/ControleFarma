<?php

namespace App\Http;

class Response
{
    /**
     * codigo do status HTTP
     *
     * @var int
     */
    private $httpCode = 200;
    /**
     * Cabeçalho di Response
     *
     * @var array
     */
    private $headers = [];
    /**
     * Tipo de conteúdo que esta sendo retornado
     *
     * @var string
     */
    private $contentType = "text/html";
    /**
     * Conteudo do Response
     *
     * @var mixed
     */
    private $content;
    /**
     * Método responsavel por iniciar a classe e definir os valores do Response
     *
     * @param  int $httpCode
     * @param  mixed $content
     * @param  string $contentType
     */
    public function __construct($httpCode, $content, $contentType = "text/html")
    {
        $this->content  = $content;
        $this->httpCode = $httpCode;
        $this->setContentType($contentType);
    }
    /**
     * Método responsavel por alterar o contentType do Response
     *
     * @param  mixed $contentType
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeaders("Content-Type", $contentType);
    }
    /**
     * Método responsavel por adcionar um registro no cabeçalho do Response
     *
     * @return void
     */
    public function addHeaders($key, $value)
    {
        $this->headers[$key] = $value;
    }
    /**
     * Médodo responsavel por enviar os Headers para o navegador
     *
     * @return void
     */
    private function sendHeader()
    {
        //Status 
        http_response_code($this->httpCode);

        //enviar os Headers

        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }
    /**
     * Metodo responsavelver por enviar a resposnta para o usuário
     *
     * @return void
     */
    public function sendResponse()
    {
        $this->sendHeader();
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            default:
                throw new \Exception("Error Processing Request", 500);

                exit;
        }
    }
}

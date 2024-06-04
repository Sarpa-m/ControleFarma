<?php

namespace App\Controller\Exception;

use App\Controller\Pages\Exception as PagesException;
use App\Utils\View;
use App\Http\Request;
use App\Model\Entity\Usuarios\Log;

class Exception
{
    /**
     * Método responsável por interpretar uma exceção e retornar uma mensagem formatada de acordo com o tipo de conteúdo
     *
     * @param \Exception $exception A exceção a ser interpretada
     * @param string $contentType O tipo de conteúdo da resposta (ex: text/html, application/json)
     * @return string|array A mensagem formatada da exceção, dependendo do tipo de conteúdo
     */
    public static function InterpretException($exception, $contentType)
    {


        self::setLog($exception);


        if (getenv("Debug") == 'true') {
            // Informações da exceção
            $info = [
                'Message' => $exception->getMessage(),
                'Code' => $exception->getCode(),
                'File' => $exception->getFile(),
                'Line' => $exception->getLine(),
                'Trace' => explode("\n", $exception->getTraceAsString()),
                'GET' => $_GET,
                'POST' => $_POST,
                'SERVER' => $_SERVER,
                'SESSION' => isset($_SESSION) ? $_SESSION : null
            ];
        } else {

            // Informações da exceção
            $info = [
                'Message' => $exception->getMessage(),
                'Code' => $exception->getCode(),
                'File' => str_replace(DIR . "\\", '', $exception->getFile()),
                'Line' => $exception->getLine()
            ];
        }
        $message =   self::getErrors($exception->getCode());
        // Tratamento da exceção de acordo com o tipo de conteúdo
        switch ($contentType) {
            case 'text/html':
                // Formata a mensagem de erro em HTML
                $message = array_merge(
                    [
                        'message' => "<pre>" . print_r($info, true) . "</pre>"
                    ],
                    $message
                );

                // Retorna a página de erro HTML
                return PagesException::getViewException($message);
                break;


            default:

                $message = array_merge(
                    $info,
                    ['message' => $message['technical_message']]
                );


                // Retorna a mensagem de erro em formato JSON
                return [
                    "error" => $message
                ];
                break;
        }
    }

    /**
     * Método privado responsável por retornar as informações de erro de acordo com o código de erro
     *
     * @param int $code O código de erro da exceção
     * @return array As informações de erro
     */
    private static function getErrors($code)
    {
        // Mapeamento dos códigos de erro para mensagens amigáveis e técnicas
        $errors = [
            400 => [
                "title" => "Erro 400",
                "friendly_message" => "Oops! Parece que houve um problema com a sua requisição. Por favor, verifique os dados fornecidos e tente novamente.",
                "technical_message" => "A requisição é inválida. Verifique os parâmetros e a sintaxe da requisição."
            ],
            401 => [
                "title" => "Erro 401",
                "friendly_message" => "Você não tem permissão para acessar este recurso. Por favor, faça login ou verifique suas credenciais.",
                "technical_message" => "A autenticação falhou ou não foi fornecida. Verifique suas credenciais."
            ],
            403 => [
                "title" => "Erro 403",
                "friendly_message" => "Desculpe, você não tem permissão para acessar esta página. Se você acha que isso é um erro, entre em contato com o suporte.",
                "technical_message" => "Acesso proibido. O usuário não tem permissão para acessar este recurso."
            ],
            404 => [
                "title" => "Erro 404",
                "friendly_message" => "Ops! A página que você está procurando não foi encontrada. Certifique-se de que a URL está correta ou volte à página inicial.",
                "technical_message" => "O recurso solicitado não foi encontrado no servidor."
            ],
            405 => [
                "title" => "Erro 405",
                "friendly_message" => "Desculpe, o método solicitado não é permitido para esta URL. Por favor, entre em contato com o suporte se precisar de assistência.",
                "technical_message" => "Método não permitido para esta URL. Verifique o método da requisição."
            ],
            408 => [
                "title" => "Erro 408",
                "friendly_message" => "O servidor demorou muito para responder. Por favor, tente novamente mais tarde.",
                "technical_message" => "Tempo limite da requisição. O servidor demorou muito para responder."
            ],
            429 => [
                "title" => "Erro 429",
                "friendly_message" => "Você fez muitas requisições em um curto período de tempo. Por favor, espere um momento antes de tentar novamente.",
                "technical_message" => "Muitas requisições. O usuário enviou muitas requisições em um curto período de tempo."
            ],
            500 => [
                "title" => "Erro 500",
                "friendly_message" => "Uma falha interna ocorreu no servidor. Estamos trabalhando para corrigir isso. Por favor, tente novamente mais tarde.",
                "technical_message" => "Erro interno do servidor. Uma condição inesperada foi encontrada no processamento da requisição."
            ],
            502 => [
                "title" => "Erro 502",
                "friendly_message" => "Recebemos uma resposta inválida do servidor. Por favor, tente novamente mais tarde.",
                "technical_message" => "Bad Gateway. O servidor recebeu uma resposta inválida ao tentar acessar outro servidor."
            ],
            503 => [
                "title" => "Erro 503",
                "friendly_message" => "Desculpe, o serviço não está disponível no momento devido a manutenção. Tente novamente mais tarde ou entre em contato com o suporte.",
                "technical_message" => "Serviço indisponível devido a manutenção. O servidor não está pronto para manipular a requisição. Tente novamente mais tarde."
            ],
            504 => [
                "title" => "Erro 504",
                "friendly_message" => "O servidor demorou muito para responder. Por favor, tente novamente mais tarde.",
                "technical_message" => "Tempo limite do gateway. O servidor demorou muito para receber uma resposta de outro servidor."
            ]
        ];

        // Retorna as informações de erro correspondentes ao código, ou o erro padrão (500) se não encontrado
        return $errors[$code] ?? $errors[500];
    }

    private static function setLog($exception)
    {
        $obLog = new Log();


        $usuario_id = (isset($_SESSION['usuario']['id'])) ? $_SESSION['usuario']['id'] : null;


        $obLog->usuario_id = $usuario_id;
        $obLog->log  = [
            'Message' => $exception->getMessage(),
            'Code' => $exception->getCode(),
            'File' => $exception->getFile(),
            'Line' => $exception->getLine(),
            'Trace' => explode("\n", $exception->getTraceAsString()),
            'GET' => $_GET,
            'POST' => $_POST,
            'SERVER' => $_SERVER,
            'SESSION' => isset($_SESSION) ? $_SESSION : null
        ];

        $obLog->cadastrar();
    }
}

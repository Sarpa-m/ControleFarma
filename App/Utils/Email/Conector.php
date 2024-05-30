<?php

namespace App\Utils\Email;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Conector
{
    /**
     * Host Email
     *
     * @var string
     */
    private static $Host = "";

    /**
     * Username Email
     *
     * @var string
     */
    private static $Username = "";

    /**
     * Password Email
     *
     * @var string
     */
    private static $Password = "";

    /**
     * Port Email
     *
     * @var string
     */
    private static $Port = "";

    /**
     * var Email
     *
     * @var PHPMailer
     */
    private $ObEmail;


    public static function init($Host, $Username, $Password, $Port = 587)
    {
        self::$Host = $Host;
        self::$Username = $Username;
        self::$Password = $Password;
        self::$Port = $Port;
    }

    public function __construct()
    {
        $this->ObEmail = new PHPMailer();
        $this->setConnection();
    }

    private function setConnection()
    {
        $this->ObEmail->isSMTP();
        $this->ObEmail->Host = self::$Host;
        $this->ObEmail->SMTPAuth = true;
        $this->ObEmail->SMTPSecure = 'tls';
        $this->ObEmail->Username =  self::$Username;
        $this->ObEmail->Password = self::$Password;
        $this->ObEmail->Port =  self::$Port;
       

    }

    public function setEndereço($remetente, $emailDestinatario, $NomeDestinatario)
    {
        $this->ObEmail->setFrom($remetente);
        $this->ObEmail->AddReplyTo($remetente);
        $this->ObEmail->addAddress($emailDestinatario, $NomeDestinatario);
    }

    public function setContent($bodyHtml, $Assunto)
    {
        $this->ObEmail->isHTML(true);
        $this->ObEmail->Subject = $Assunto;
        $this->ObEmail->Body    = $bodyHtml;
    }

    public function Enviar()
    {
        if (!$this->ObEmail->send()) {
            echo 'Não foi possível enviar a mensagem.<br>';
            echo 'Erro: ' . $this->ObEmail->ErrorInfo;
        } else {
            echo 'Mensagem enviada.';
        }
        $this->ObEmail->ClearAllRecipients();
        $this->ObEmail->ClearAttachments();
    }
}

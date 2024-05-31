<?php

namespace App\Session;

use App\Model\Entity\Usuarios\User;


class Login
{

    /**
     * Método responsavel por iniciar a sessção
     */
    private static function init()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_name('controlefarma');
            session_start([
                'cookie_lifetime' => 43200, // Tempo de vida do cookie em segundos (12 horas)
                //  'read_and_close' => true,   // Fechar a sessão após leitura
            ]);
        }
    }

    /**
     * Método responsavel por criar o login do usuário
     * @param  array $obUser
     * @return boolean
     */
    public static function login($user)
    {

        //INICIA A SESSÃO
        self::init();

        $_SESSION['usuario'] = array_merge($user, ["timestamp" => time()]);


        //SUCESSO
        return true;
    }

    /**
     * Método responsável por verificar se o usuário está logado
     *
     * @return boolean Retorna true se o usuário estiver logado, false caso contrário
     */
    public static function isLogged()
    {
        // INICIA A SESSÃO
        self::init();

        // Verifica se há um ID de usuário na sessão
        if (isset($_SESSION['usuario']['id'])) {

            // Verifica se a sessão do usuário ainda é válida (dentro de 10 minutos)
            if ($_SESSION['usuario']['timestamp'] + (20 * 60) >= time()) {
                return true;
            }

            // Verifica se a sessão do usuário ainda é válida (dentro de 40 minutos) e atualiza o timestamp
            if ($_SESSION['usuario']['timestamp'] + (40 * 60) >= time()) {
                $obUser = User::getUserById($_SESSION['usuario']['id']);
                if ($obUser instanceof User) {
                    // Atualiza o timestamp da sessão do usuário
                    $_SESSION['usuario']['timestamp'] = time();
                    return true;
                }
            }

            // Se a sessão tiver mais que 40 minutos, retorna false
            if ($_SESSION['usuario']['timestamp'] + (40 * 60) < time()) {
                self::logout();
                return false;
            }
        }

        // Se não estiver logado, encerra a sessão do usuário
        self::logout();
        return false;
    }


    public static function logout()
    {
        //INICIA A SESSÃO
        self::init();

        //DESLOGA O USUARIO
        unset($_SESSION['usuario']);

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Por último, destrói a sessão
        session_destroy();
    }
}

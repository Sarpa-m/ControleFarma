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
            session_start();
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
     * Método responsavel por verificar se o usuário esta logado
     * @return boolean
     */
    public static function isLogged()
    {
        //INICIA A SESSÃO
        self::init();

        if (isset($_SESSION['usuario']['id'])) {

            if ($_SESSION['usuario']['timestamp'] + (3 * 60 * 60) >= time()) {
                return true;
            }
            $obUser = User::getUserById($_SESSION['usuario']['id']);
            if ($obUser instanceof User) {
                $_SESSION['usuario']['timestamp'] = time();
                return true;
            }
        }
        self::logout();
        return false;
    }

    public static function logout()
    {
        //INICIA A SESSÃO
        self::init();

        //DESLOGA O USUARIO
        unset($_SESSION['usuario']);
    }
}

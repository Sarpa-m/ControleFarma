<?php
namespace Router;


use App\Controller\Login as ControllerLogin;
use App\Controller\Pages\Pacientes;
use App\Http\Response;
use App\Http\Router;

class login
{

    /**
     * Pre URL da rota
     *
     * @var string
     */
    private static $preUlr = null;

    /**
     * __construct
     *
     * @param  Router $obRouter
     * @param  string $preUlr
     * @return void
     */
    public static function  init($obRouter, $preUlr = null)
    {
         $obRouter->Post(self::$preUlr . '/login', [
            'middlewares' => [
                "required-logout"
            ],
            function ($request) {
                return new Response(200, ControllerLogin\Login::setLogin($request));
            }
        ]);

        $obRouter->get(self::$preUlr . '/logoff', [
            'middlewares' => [
                "required-login"
            ],
            function ($request) {
                return new Response(200, ControllerLogin\Login::setLogout($request));
            }
        ]);

       
       

    }
}

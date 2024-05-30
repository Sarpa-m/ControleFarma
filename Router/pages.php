<?php
namespace Router;

use App\Controller\Pages as ControllerPages;
use App\Controller\Pages\Pacientes;
use App\Http\Response;
use App\Http\Router;

class pages
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
         $obRouter->get(self::$preUlr . '/login', [
            'middlewares' => [
                "required-login"
            ],
            function ($request) {
                return new Response(200, ControllerPages\Login::getLoginPege ($request));
            }
        ]);


        $obRouter->get(self::$preUlr . '/', [
            'middlewares' => [
                "required-login"
            ],
            function ($request) {
                return new Response(200, Pacientes::GetVierPacientes());
            }
        ]);
       

    }
}

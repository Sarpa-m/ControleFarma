<?php

namespace Router;

use App\Controller\Pacientes as ControllerPacientes;
use App\Http\Response;
use App\Http\Router;

class pacientes
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
        $obRouter->post(self::$preUlr . '/dados/pacientes', [
            'middlewares' => [
                "required-login"
            ],
            function ($request) {
                return new Response(200, ControllerPacientes\Pacientes::getArrayPacientes($request), 'application/json');
            }
        ]);
    }
}

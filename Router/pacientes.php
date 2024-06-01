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
                // Define a resposta para o endpoint que retorna um array de pacientes
                return new Response(200, ControllerPacientes\Pacientes::getArrayPacientes($request), 'application/json');
            }
        ]);

        $obRouter->post(self::$preUlr . '/dados/medicos', [
            'middlewares' => [
                "required-login" 
            ],
            function ($request) {
                // Executa a função getMedicos no controlador Cadastro e retorna a resposta em formato JSON
                return new Response(200, ControllerPacientes\Cadastro::getMedicos($request), 'application/json');
            }
        ]);
        
        $obRouter->post(self::$preUlr . '/paciente/cadastro', [
            'middlewares' => [
                "required-login"
            ],
            function ($request) {
                // Define a resposta para o endpoint de cadastro de paciente
                return new Response(201, ControllerPacientes\Cadastro::setCadastroDePaciente($request), 'application/json');
            }
        ]);
    }
}

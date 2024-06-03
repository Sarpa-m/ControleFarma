<?php

namespace Router;

use App\Controller\Pacientes as ControllerPacientes;
use App\Http\Response;
use App\Http\Router;

class pacientes
{

    /**
     * Prefixo da URL da rota.
     *
     * @var string
     */
    private static $preUlr = null;

    /**
     * Inicializa as rotas relacionadas aos pacientes.
     *
     * @param  Router $obRouter Objeto Router para definir as rotas.
     * @param  string $preUlr Prefixo da URL das rotas.
     * @return void
     */
    public static function init($obRouter, $preUlr = null)
    {
        // Define a rota POST para obter dados dos pacientes
        $obRouter->post(self::$preUlr . '/dados/pacientes', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Define a resposta para o endpoint que retorna um array de pacientes
                return new Response(200, ControllerPacientes\Pacientes::getArrayPacientes($request), 'application/json');
            }
        ]);

        // Define a rota POST para obter dados dos médicos
        $obRouter->post(self::$preUlr . '/dados/medicos', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Executa a função getMedicos no controlador Cadastro e retorna a resposta em formato JSON
                return new Response(200, ControllerPacientes\Cadastro::getMedicos($request), 'application/json');
            }
        ]);
        
        // Define a rota POST para cadastrar um paciente
        $obRouter->post(self::$preUlr . '/paciente/cadastro', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Define a resposta para o endpoint de cadastro de paciente
                return new Response(201, ControllerPacientes\Cadastro::setCadastroDePaciente($request), 'application/json');
            }
        ]);

        // Define a rota POST para editar um paciente
        $obRouter->post(self::$preUlr . '/paciente/editar', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Define a resposta para o endpoint de edição de paciente
                return new Response(201, ControllerPacientes\Cadastro::setEditPaciente($request));
            }
        ]);
    }
}

<?php

namespace Router;

use App\Controller\Pages as ControllerPages;
use App\Controller\Pages\Pacientes;
use App\Http\Response;
use App\Http\Router;

class pages
{

    /**
     * Prefixo da URL da rota
     *
     * @var string
     */
    private static $preUlr = null;

    /**
     * Inicializa as rotas das páginas.
     *
     * @param  Router $obRouter Objeto Router para definir as rotas.
     * @param  string $preUlr Prefixo da URL das rotas.
     * @return void
     */
    public static function init($obRouter, $preUlr = null)
    {
        // Define a rota GET para a página de login
        $obRouter->get(self::$preUlr . '/login', [
            'middlewares' => [
                "required-logout" // Middleware que exige logout para acessar a rota
            ],
            function ($request) {
                // Define a resposta para a rota de login
                return new Response(200, ControllerPages\Login::getLoginPege($request));
            }
        ]);

        // Define a rota GET para a página principal (listagem de pacientes)
        $obRouter->get(self::$preUlr . '/', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Define a resposta para a rota padrão (listagem de pacientes)
                return new Response(200, Pacientes::GetViewPacientes($request));
            }
        ]);

        // Define a rota GET para a página de cadastro de paciente
        $obRouter->get(self::$preUlr . '/paciente/cadastro', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Define a resposta para a rota de cadastro de paciente
                return new Response(200, Pacientes::GetViewPacienteCadastro($request));
            }
        ]);

        // Define a rota GET para a página de exibição de dados de um paciente específico
        $obRouter->get(self::$preUlr . '/paciente', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Define a resposta para a rota de exibição de dados de um paciente específico
                return new Response(200, Pacientes::GetViewPacienteByID($request));
            }
        ]);

        // Define a rota GET para a página de edição de dados de um paciente específico
        $obRouter->get(self::$preUlr . '/paciente/editar', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Define a resposta para a rota de edição de dados de um paciente específico
                return new Response(200, Pacientes::GetViewEditPacienteByID($request));
            }
        ]);
    }
}

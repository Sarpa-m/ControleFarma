<?php

namespace Router;

use App\Controller\medicamento as Controllermedicamento;
use App\Http\Response;
use App\Http\Router;

class medicamento
{

    /**
     * Prefixo da URL da rota.
     *
     * @var string
     */
    private static $preUlr = null;

    /**
     * Inicializa as rotas relacionadas aos medicamento.
     *
     * @param  Router $obRouter Objeto Router para definir as rotas.
     * @param  string $preUlr Prefixo da URL das rotas.
     * @return void
     */
    public static function init($obRouter, $preUlr = null)
    {
       
        $obRouter->post(self::$preUlr . '/dados/medicamento', [
            'middlewares' => [
                "required-login", // Middleware que exige login para acessar a rota
                "Api"
            ],
            function ($request) {
                
                return new Response(200, Controllermedicamento\medicamento::getArraymedicamento($request), 'application/json');
            }
        ]);

       
        $obRouter->put(self::$preUlr . '/medicamento/status', [
            'middlewares' => [
                "required-login", // Middleware que exige login para acessar a rota
                "Api"
            ],
            function ($request) {
                
                return new Response(200, Controllermedicamento\medicamento::setStatusMedicamento($request), 'application/json');
            }
        ]);

        $obRouter->post(self::$preUlr . '/medicamento/cadastrar', [
            'middlewares' => [
                "required-login", // Middleware que exige login para acessar a rota
                "Api"
            ],
            function ($request) {
                
                return new Response(200, Controllermedicamento\medicamento::setMedicamento($request), 'application/json');
            }
        ]);
    }
}

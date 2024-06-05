<?php
namespace App\Controller\Pages;

use App\Utils\View;
use App\Http\Request;

class Medicamento
{
    /**
     * Retorna a view da página de listagem de medicamentos.
     *
     * @param  Request $request Requisição HTTP.
     * @return string HTML da página de listagem de medicamentos.
     */
    public static function getViewMedicamento($request)
    {
        return View::render('Medicamento\\ViewMedicamento', [
            "header" => Page::getHeader(), // Obtém o cabeçalho da página.
            "footer" => Page::getFooter(), // Obtém o rodapé da página.
        ]);
    }

    /**
     * Retorna a view da página de cadastro de medicamentos.
     *
     * @param  Request $request Requisição HTTP.
     * @return string HTML da página de cadastro de medicamentos.
     */
    public static function getViewMedicamentoCadastro($request)
    {
        return View::render('Medicamento\\ViewMedicamentoCadastro', [
            "header" => Page::getHeader(), // Obtém o cabeçalho da página.
            "footer" => Page::getFooter(), // Obtém o rodapé da página.
        ]);
    }
}

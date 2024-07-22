<?php

namespace App\Controller\Pages;

use App\Controller\Pacientes\Pacientes as ControllerPacientes;
use App\Utils\View;
use App\Http\Request;

class RegistrarRetirada
{
    /**
     * 
     *
     * @param  Request $request Requisição HTTP contendo os dados da requisição.
     * @return string HTML renderizado da página de edição do paciente.
     */
    public static function GetViewRegistrarRetirada($request)
    {
        $id = $request->getQueryParams("id", "-1"); // Obtém o ID do paciente dos parâmetros da query.

        $vars = array_merge(
            ControllerPacientes::getPacienteByID($id), // Obtém os dados do paciente pelo ID.
            [
                "header" => Page::getHeader(), // Obtém o cabeçalho da página.
                "footer" => Page::getFooter(), // Obtém o rodapé da página.
            ]
        );

        return View::render('Pacientes\\RegistrarRetirada', $vars);
    }
}
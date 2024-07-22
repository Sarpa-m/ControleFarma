<?php

namespace App\Controller\Pages;

use App\Controller\Pacientes\Pacientes as ControllerPacientes;
use App\Utils\View;
use App\Http\Request;

class Pacientes
{
    /**
     * Retorna a view da página de listagem de pacientes.
     *
     * @param  Request $request Requisição HTTP contendo os dados da requisição.
     * @return string HTML renderizado da página de listagem de pacientes.
     */
    public static function GetViewPacientes($request)
    {
        return View::render('Pacientes\\PacientesPege', [
            "header" => Page::getHeader(), // Obtém o cabeçalho da página.
            "footer" => Page::getFooter(), // Obtém o rodapé da página.
        ]);
    }

    /**
     * Retorna a view da página de cadastro de paciente.
     *
     * @param  Request $request Requisição HTTP contendo os dados da requisição.
     * @return string HTML renderizado da página de cadastro de paciente.
     */
    public static function GetViewPacienteCadastro($request)
    {
        return View::render('Pacientes\\PacienteCadastro', [
            "header" => Page::getHeader(), // Obtém o cabeçalho da página.
            "footer" => Page::getFooter(), // Obtém o rodapé da página.
        ]);
    }

    /**
     * Retorna a view da página de detalhes de um paciente específico.
     *
     * @param  Request $request Requisição HTTP contendo os dados da requisição.
     * @return string HTML renderizado da página de detalhes do paciente.
     */
    public static function GetViewPacienteByID($request)
    {
        $id = $request->getQueryParams("id", "-1"); // Obtém o ID do paciente dos parâmetros da query.

        $vars = array_merge(
            ControllerPacientes::getPacienteByID($id), // Obtém os dados do paciente pelo ID.
            [
                "header" => Page::getHeader(), // Obtém o cabeçalho da página.
                "footer" => Page::getFooter(), // Obtém o rodapé da página.
            ]
        );

        return View::render('Pacientes\\PacienteByID', $vars); // Renderiza a view com os dados do paciente.
    }

    /**
     * Retorna a view da página de edição de um paciente específico.
     *
     * @param  Request $request Requisição HTTP contendo os dados da requisição.
     * @return string HTML renderizado da página de edição do paciente.
     */
    public static function GetViewEditPacienteByID($request)
    {
        $id = $request->getQueryParams("id", "-1"); // Obtém o ID do paciente dos parâmetros da query.

        $vars = array_merge(
            ControllerPacientes::getPacienteByID($id), // Obtém os dados do paciente pelo ID.
            [
                "header" => Page::getHeader(), // Obtém o cabeçalho da página.
                "footer" => Page::getFooter(), // Obtém o rodapé da página.
            ]
        );

        return View::render('Pacientes\\EditPacienteByID', $vars); // Renderiza a view com os dados do paciente para edição.
    }

    
}

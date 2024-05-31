<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Http\Request;

class Pacientes
{

    /**
     * 
     *
     * @param  Request $request
     * @param  int|null $alert
     * @return string
     */
    public static function getVierPacientes()
    {



        return View::render('Pacientes\\PacientesPege', [
            "header" => Page::getHeader(),
            "footer" => Page::getFooter(),
        ]);
    }
}

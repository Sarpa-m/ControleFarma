<?php

namespace App\Controller\Pacientes;

use App\Http\Request;
use App\Model\Entity\Farma\Paciente as EntityPaciente;
use App\Utils\FormatarString;

class Cadastro
{

    /**
     * 
     *
     * @param  Request $request
     * @return array
     */
    public static function setCadastroDePaciente($request)
    {

        $obEntityPaciente = new  EntityPaciente();

        $obEntityPaciente->nome_completo = FormatarString::isSafeString($request->getPostVars('nome_completo'));
        $obEntityPaciente->numero_sim = FormatarString::isSafeString($request->getPostVars('numero_sim'));
        $obEntityPaciente->data_nascimento = FormatarString::isSafeString($request->getPostVars('data_nascimento'));
        $obEntityPaciente->telefones = FormatarString::isSafeString($request->getPostVars('telefones'));
        $obEntityPaciente->medico_solicitante = FormatarString::isSafeString($request->getPostVars('medico_solicitante'));


        $obEntityPaciente->cadastrar();



        echo '<pre>';
        print_r($request->getPostVars());
        echo '</pre>';
        exit;
    }
}

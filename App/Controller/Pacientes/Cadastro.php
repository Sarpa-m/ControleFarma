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
        $obEntityPaciente->telefone = FormatarString::isSafeString($request->getPostVars('telefone'));
        $obEntityPaciente->medico_solicitante = FormatarString::isSafeString($request->getPostVars('medico_solicitante'));


        $obEntityPaciente->cadastrar();



        echo '<pre>';
        print_r($request->getPostVars());
        echo '</pre>';
        exit;
    }
    
    /**
     * Método responsável por obter uma lista de médicos.
     *
     * @param Request $request Requisição contendo os dados necessários.
     * @return array Lista de médicos correspondentes à pesquisa.
     */
    public static function getMedicos($request)
    {
        // Sanitiza a string de busca obtida da requisição para prevenir ataques de injeção
        $search = FormatarString::isSafeString($request->getPostVars('search'));

        // Retorna a lista de médicos correspondentes à string de busca
        return EntityPaciente::getMedicos($search);
    }
}

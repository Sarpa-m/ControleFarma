<?php

namespace App\Controller\Pacientes;

use App\Http\Request;
use App\Model\Entity\Farma\Paciente as EntityPaciente;
use App\Utils\FormatarString;
use App\Utils\Redirect;

class Cadastro
{
    /**
     * Método responsável por Cadastra um novo paciente.
     *
     * @param Request $request Requisição contendo os dados do paciente.
     * @return array
     */
    public static function setCadastroDePaciente($request)
    {
        $obEntityPaciente = new EntityPaciente();

        // Sanitiza e define os dados do paciente
        $obEntityPaciente->nome_completo = FormatarString::isSafeString($request->getPostVars('nome_completo'));
        $obEntityPaciente->numero_sim = FormatarString::isSafeString($request->getPostVars('numero_sim'));
        $obEntityPaciente->data_nascimento = FormatarString::isSafeString($request->getPostVars('data_nascimento'));
        $obEntityPaciente->telefone = FormatarString::isSafeString($request->getPostVars('telefone'));
        $obEntityPaciente->medico_solicitante = FormatarString::isSafeString($request->getPostVars('medico_solicitante'));

        // Cadastra o paciente
        $obEntityPaciente->cadastrar();
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

    /**
     * Edita os dados de um paciente existente.
     *
     * @param Request $request Requisição contendo os dados do paciente.
     * @return array
     */
    public static function setEditPaciente($request)
    {
        $id = FormatarString::isSafeString($request->getQueryParams("id", "-1"));

        // Obtém o paciente pelo ID
        $obEntityPaciente = EntityPaciente::getPacienteById($id);

        // Atualiza os dados do paciente
        $obEntityPaciente->nome_completo = FormatarString::isSafeString($request->getPostVars('nome_completo'));
        $obEntityPaciente->data_nascimento = FormatarString::isSafeString($request->getPostVars('data_nascimento'));
        $obEntityPaciente->telefone = FormatarString::isSafeString($request->getPostVars('telefone'));
        $obEntityPaciente->medico_solicitante = FormatarString::isSafeString($request->getPostVars('medico_solicitante'));

        // Atualiza o paciente no banco de dados
        $obEntityPaciente->atualizar();

        // Redireciona para a página do paciente
        Redirect::Redirect(URL . "/paciente?id=" . $request->getQueryParams("id", -1));
        exit;
    }
}


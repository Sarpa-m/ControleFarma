<?php

namespace App\Controller\Pacientes;

use App\Http\Request;
use App\Model\Entity\Farma\MedicamentoFornecido;
use App\Utils\FormatarString;

class RegistrarRetirada
{
    /**
     * 
     *
     * @param  Request $request Requisição contendo os parâmetros de busca.
     * @return 
     */
    public static function setRegistrarRetirada($request)
    {
        

        $paciente_id = $request->getQueryParams("id");
        $medicamento_id = FormatarString::isSafeString($request->getPostVars("medicamento_id"));
        $dose = FormatarString::isSafeString($request->getPostVars("dose"));
        $quantidade = FormatarString::isSafeString($request->getPostVars("quantidade"));
        $responsavel_retirada = FormatarString::isSafeString($request->getPostVars("responsavel_retirada"));
        $data_retirada = FormatarString::isSafeString($request->getPostVars("data_retirada"));
        $observacoes = FormatarString::isSafeString($request->getPostVars("observacoes"), false);
        $funcionario_id = $_SESSION['usuario']['username'];

        $obMedicamentoFornecido = new MedicamentoFornecido();

        $obMedicamentoFornecido->paciente_id = $paciente_id;
        $obMedicamentoFornecido->medicamento_id = $medicamento_id;
        $obMedicamentoFornecido->dose = $dose;
        $obMedicamentoFornecido->quantidade = $quantidade;
        $obMedicamentoFornecido->responsavel_retirada = $responsavel_retirada;
        $obMedicamentoFornecido->data_retirada = $data_retirada;
        $obMedicamentoFornecido->observacoes = $observacoes;
        $obMedicamentoFornecido->funcionario_id = $funcionario_id;

        $obMedicamentoFornecido->cadastrar();
    }
}

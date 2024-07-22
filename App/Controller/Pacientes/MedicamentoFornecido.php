<?php

namespace App\Controller\Pacientes;

use App\Http\Request;
use App\Model\Entity\Farma\MedicamentoFornecido as EntityMedicamentoFornecido;
use App\Utils\FormatarString;
use App\Utils\Pagination;
use App\Utils\Redirect;
use PDO;

class MedicamentoFornecido
{

    /**
     * Método responsável por obter um array de
     *
     * @param  Request $request Requisição contendo os parâmetros de busca.
     * @return array Array contendo os dados dos MedicamentoFornecidos, paginação e os parâmetros da requisição.
     */
    public static function getArrayMedicamentoFornecido($request)
    {


          // Sanitiza e obtém os parâmetros de busca, paginação e ordenação da requisição.
        $search = FormatarString::isSafeString($request->getpostVars('search', ""));
        $page = FormatarString::isSafeString($request->getpostVars('page', 1));
        $records = FormatarString::isSafeString($request->getpostVars('records', 10));
        $sort = FormatarString::isSafeString($request->getpostVars('sort', 'data_retirada'));
        $order = FormatarString::isSafeString($request->getpostVars('order', 'asc'));
        $paciente_id = FormatarString::isSafeString($request->getpostVars('paciente_id', '%'));


        // Constrói a cláusula WHERE para a consulta com base no termo de busca.
        $where = "     f_medicamentos.nome LIKE '$search%'";
        $where .= " OR responsavel_retirada LIKE '$search%'";
        

        // Constrói a cláusula ORDER BY para a consulta com base nos parâmetros de ordenação.
        $order = $sort . " " . $order; 
    
        // Obtém a quantidade total de MedicamentoFornecidos que correspondem ao termo de busca.
        $quantidadeTotal = EntityMedicamentoFornecido::getRetirada($paciente_id,$where,$order,null)->fetchObject()->qtd ?? 0;

        // Cria a instância de paginação com base na quantidade total de MedicamentoFornecidos e nos parâmetros de página e registros por página.
        $Pagination = new Pagination($quantidadeTotal, $page, $records);

        $MedicamentoFornecido = EntityMedicamentoFornecido::getRetirada($paciente_id, $where,$order,$Pagination->getLimit());

        // Obtém os dados dos MedicamentoFornecidos em forma de array.
        $content = $MedicamentoFornecido->fetchAll(PDO::FETCH_ASSOC);

        // Retorna um array contendo os dados da requisição, paginação e os MedicamentoFornecidos.
        return [
            'post' => $request->getpostVars(),
            'Pagination' => $Pagination->getDados(),
            'content' => $content
        ];
    }
}

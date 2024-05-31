<?php

namespace App\Controller\Pacientes;

use App\Http\Request;
use App\Model\Entity\Farma\Paciente as FarmaPaciente;
use App\Utils\FormatarString;
use App\Utils\Pagination;
use PDO;

class Pacientes
{

    /**
     * Método responsável por retornar a lista 
     *
     * @param  Request $request
     * @return array
     */
    public static function getArrayPacientes($request)
    {

        // Recupera as variáveis enviadas via POST

        $search = FormatarString::isSafeString($request->getpostVars('search', "")); // Termo de busca
        $page = FormatarString::isSafeString($request->getpostVars('page', 1)); // Número da página
        $records = FormatarString::isSafeString($request->getpostVars('records', 10)); // Número de registros por página
        $sort = FormatarString::isSafeString($request->getpostVars('sort', 'nome_completo')); // Número de registros por página
        $order = FormatarString::isSafeString($request->getpostVars('order', 'asc')); // Número de registros por página

        // Define a cláusula WHERE para a busca
        $where = " nome_completo LIKE '$search%'";
        $where .= " OR numero_sim LIKE '$search%'";
        // $where .= " OR cpf LIKE '$search%'";
        // $where .= " OR telefones LIKE '$search%'";
        $where .= " OR medico_solicitante LIKE '$search%'";

        $order = $sort . " " . $order;

        // Obtém a quantidade total de pacientes que correspondem ao critério de busca
        $quantidadeTotal = FarmaPaciente::getPacientes($where,  $order, null, "COUNT(*) as qtd")->fetchObject()->qtd;

        // Instancia a paginação
        $Pagination = new Pagination($quantidadeTotal, $page, $records);

        // Obtém os pacientes de acordo com a paginação e os critérios de busca
        $Paciente = FarmaPaciente::getPacientes($where,  $order, $Pagination->getLimit());

        $content  = $Paciente->fetchAll(PDO::FETCH_ASSOC);

        // Monta o array de resposta com o conteúdo e a paginação
        return [
            'post' => $request->getpostVars(),
            'Pagination' => $Pagination->getDados(),
            'content' => $content
        ];
    }
}

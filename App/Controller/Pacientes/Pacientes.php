<?php

namespace App\Controller\Pacientes;

use App\Http\Request;
use App\Model\Entity\Farma\Paciente as EntityPaciente;
use App\Utils\FormatarString;
use App\Utils\Pagination;
use App\Utils\Redirect;
use PDO;

class Pacientes
{
    /**
     * Método responsável por obter um array de pacientes com base nos parâmetros da requisição.
     *
     * @param  Request $request Requisição contendo os parâmetros de busca.
     * @return array Array contendo os dados dos pacientes, paginação e os parâmetros da requisição.
     */
    public static function getArrayPacientes($request)
    {
        // Sanitiza e obtém os parâmetros de busca, paginação e ordenação da requisição.
        $search = FormatarString::isSafeString($request->getpostVars('search', ""));
        $page = FormatarString::isSafeString($request->getpostVars('page', 1));
        $records = FormatarString::isSafeString($request->getpostVars('records', 10));
        $sort = FormatarString::isSafeString($request->getpostVars('sort', 'nome_completo'));
        $order = FormatarString::isSafeString($request->getpostVars('order', 'asc'));

        // Constrói a cláusula WHERE para a consulta com base no termo de busca.
        $where = " nome_completo LIKE '$search%'";
        $where .= " OR numero_sim LIKE '$search%'";
        $where .= " OR medico_solicitante LIKE '$search%'";
        // $where .= " OR cpf LIKE '$search%'";
        // $where .= " OR telefones LIKE '$search%'";

        // Constrói a cláusula ORDER BY para a consulta com base nos parâmetros de ordenação.
        $order = $sort . " " . $order;

        // Obtém a quantidade total de pacientes que correspondem ao termo de busca.
        $quantidadeTotal = EntityPaciente::getPacientes($where, $order, null, "COUNT(*) as qtd")->fetchObject()->qtd;

        // Cria a instância de paginação com base na quantidade total de pacientes e nos parâmetros de página e registros por página.
        $Pagination = new Pagination($quantidadeTotal, $page, $records);

        // Obtém os pacientes que correspondem ao termo de busca e aos parâmetros de paginação e ordenação.
        $Paciente = EntityPaciente::getPacientes($where, $order, $Pagination->getLimit());

        // Obtém os dados dos pacientes em forma de array.
        $content = $Paciente->fetchAll(PDO::FETCH_ASSOC);

        // Retorna um array contendo os dados da requisição, paginação e os pacientes.
        return [
            'post' => $request->getpostVars(),
            'Pagination' => $Pagination->getDados(),
            'content' => $content
        ];
    }

    /**
     * Método responsável por obter os dados de um paciente pelo ID.
     *
     * @param  int $id O ID do paciente.
     * @return array Os dados do paciente em forma de array.
     */
    public static function getPacienteByID($id)
    {
        // Obtém o paciente pelo ID.
        $obPaciente = EntityPaciente::getPacienteById($id);

        // Se o paciente não for encontrado, redireciona para a URL base.
        if (!($obPaciente instanceof EntityPaciente)) {
            Redirect::Redirect(URL);
        }

        // Retorna os dados do paciente em forma de array.
        return get_object_vars($obPaciente);
    }
}


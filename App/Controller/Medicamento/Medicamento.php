<?php

namespace App\Controller\Medicamento;

use App\Http\Request;
use App\Model\Entity\Farma\Medicamento as EntityMedicamento;
use App\Utils\FormatarString;
use App\Utils\Pagination;
use App\Utils\Redirect;
use PDO;

class Medicamento
{
    /**
     * 
     * Método responsável por obter um array de Medicamento com base nos parâmetros da requisição.
     *
     * @param  Request $request Requisição contendo os parâmetros de busca.
     * @return array Array contendo os dados dos Medicamento, paginação e os parâmetros da requisição.
     */
    public static function getArrayMedicamento($request)
    {
        // Sanitiza e obtém os parâmetros de busca, paginação e ordenação da requisição.
        $search = FormatarString::isSafeString($request->getpostVars('search', ""));
        $page = FormatarString::isSafeString($request->getpostVars('page', 1));
        $records = FormatarString::isSafeString($request->getpostVars('records', 10));
        $sort = FormatarString::isSafeString($request->getpostVars('sort', 'nome'));
        $order = FormatarString::isSafeString($request->getpostVars('order', 'asc'));
        $statusFilter = FormatarString::isSafeString($request->getpostVars('statusFilter', "3"));

        // Constrói a cláusula WHERE para a consulta com base no termo de busca.
        $where = "nome LIKE '$search%'";

        switch ($statusFilter) {
            case '1':
                $where .= " AND status LIKE '1'";
                break;
            case '2':
                $where .= " AND status LIKE '0'";
                break;

            default:
                # code...bb
                break;
        }

        // Constrói a cláusula ORDER BY para a consulta com base nos parâmetros de ordenação.
        $order = $sort . " " . $order;

        // Obtém a quantidade total de pacientes que correspondem ao termo de busca.
        $quantidadeTotal = EntityMedicamento::getMedicamentos($where, $order, null, "COUNT(*) as qtd")->fetchObject()->qtd;

        // Cria a instância de paginação com base na quantidade total de pacientes e nos parâmetros de página e registros por página.
        $Pagination = new Pagination($quantidadeTotal, $page, $records);

        // Obtém os pacientes que correspondem ao termo de busca e aos parâmetros de paginação e ordenação.
        $Paciente = EntityMedicamento::getMedicamentos($where, $order, $Pagination->getLimit());

        // Obtém os dados dos pacientes em forma de array.
        $content = $Paciente->fetchAll(PDO::FETCH_ASSOC);

        // Retorna um array contendo os dados da requisição, paginação e os pacientes.
        return [
            'Pagination' => $Pagination->getDados(),
            'content' => $content
        ];
    }

    /**
     * Método responsável por alterar o status de um medicamento.
     *
     * @param  Request $request Requisição contendo os parâmetros de busca.
     * @return string Mensagem de sucesso ou exceção em caso de erro.
     */
    public static function setStatusMedicamento($request)
    {
        // Sanitiza e obtém os parâmetros de status e id da requisição.
        $status = FormatarString::isSafeString($request->getpostVars('status'));
        $id = FormatarString::isSafeString($request->getpostVars('id'));

        // Obtém o medicamento pelo ID.
        $obMedicamento = EntityMedicamento::getMedicamentoById($id);

        // Verifica se o medicamento foi encontrado.
        if (!($obMedicamento instanceof EntityMedicamento)) {
            throw new \Exception("Medicamento não encontrado", 400);
        }

        // Altera o status do medicamento.
        $obMedicamento->status = ($obMedicamento->status == "1") ? "0" : "1";

        // Atualiza o medicamento no banco de dados.
        $obMedicamento->atualizar();

        return "sucesso";
    }

    /**
     * Método responsável por cadastrar um novo medicamento.
     *
     * @param  Request $request Requisição contendo os parâmetros de busca.
     * @return string Mensagem de sucesso ou exceção em caso de erro.
     */
    public static function setMedicamento($request)
    {
        // Sanitiza e obtém o nome do medicamento da requisição.
        $nome = FormatarString::isSafeString($request->getpostVars('nome'));

        // Cria uma nova instância de EntityMedicamento.
        $obMedicamento = new EntityMedicamento();

        // Verifica se o nome foi fornecido.
        if ($nome == null) {
            throw new \Exception("Falta de dados", 400);
        }

        // Define os atributos do medicamento.
        $obMedicamento->status = "1";
        $obMedicamento->nome = $nome;

        // Cadastra o medicamento no banco de dados.
        $obMedicamento->cadastrar();

        return "sucesso";
    }
}

<?php

namespace App\Model\Entity\Farma;

use App\Utils\Database;


class RenovacaoAlteracao
{
    /**
     * Id da Renovação/Alteração
     *
     * @var int
     */
    public $id;

    /**
     * Id do Medicamento Fornecido
     *
     * @var int
     */
    public $medicamento_fornecido_id;

    /**
     * Mês e Ano da Renovação/Alteração
     *
     * @var string
     */
    public $mes_ano;

    /**
     * Status da Renovação/Alteração
     *
     * @var string
     */
    public $status;

    /**
     * Detalhes da Renovação/Alteração
     *
     * @var string
     */
    public $detalhes;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('F_Renovacoes_Alteracoes'))->insert([
            "medicamento_fornecido_id" => $this->medicamento_fornecido_id,
            "mes_ano" => $this->mes_ano,
            "status" => $this->status,
            "detalhes" => $this->detalhes
        ]);

        return true;
    }

    /**
     * Método responsável por retornar uma ou mais renovações/alterações
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getRenovacoesAlteracoes($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('F_Renovacoes_Alteracoes'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar uma renovação/alteração com base no seu ID
     *
     * @param integer $id
     * @return RenovacaoAlteracao
     */
    public static function getRenovacaoAlteracaoById($id)
    {
        return self::getRenovacoesAlteracoes("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('F_Renovacoes_Alteracoes'))->update("id = " . $this->id, [
            "medicamento_fornecido_id" => $this->medicamento_fornecido_id,
            "mes_ano" => $this->mes_ano,
            "status" => $this->status,
            "detalhes" => $this->detalhes
        ]);
    }

    /**
     * Método responsável por excluir uma renovação/alteração do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('F_Renovacoes_Alteracoes'))->delete("id = " . $this->id);
    }
}

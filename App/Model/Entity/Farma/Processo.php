<?php
namespace App\Model\Entity\Farma;

use App\Utils\Database;

class Processo
{
    /**
     * Id do Processo
     *
     * @var int
     */
    public $id;

    /**
     * Id do Medicamento
     *
     * @var int
     */
    public $medicamento_id;

    /**
     * Data do Processo
     *
     * @var string
     */
    public $data;

    /**
     * Data de Validade do Processo
     *
     * @var string
     */
    public $data_validade;

    /**
     * Status do Processo
     *
     * @var string
     */
    public $status;

    /**
     * Id do Paciente
     *
     * @var int
     */
    public $paciente_id;

    /**
     * Nome do Médico Solicitante
     *
     * @var string
     */
    public $medico_solicitante;

    /**
     * Observações sobre o Processo
     *
     * @var string
     */
    public $observacoes;

    /**
     * Id do Funcionário Responsável
     *
     * @var int
     */
    public $funcionario_id;

    /**
     * Data da Última Modificação
     *
     * @var string
     */
    public $data_ultima_modificacao;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('f_processos'))->insert([
            "medicamento_id" => $this->medicamento_id,
            "data" => $this->data,
            "data_validade" => $this->data_validade,
            "status" => $this->status,
            "paciente_id" => $this->paciente_id,
            "medico_solicitante" => $this->medico_solicitante,
            "observacoes" => $this->observacoes,
            "funcionario_id" => $this->funcionario_id,
            "data_ultima_modificacao" => $this->data_ultima_modificacao
        ]);

        return true;
    }

    /**
     * Método responsável por retornar um ou mais processos
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getProcessos($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('f_processos'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar um processo com base no seu ID
     *
     * @param integer $id
     * @return Processo
     */
    public static function getProcessoById($id)
    {
        return self::getProcessos("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('f_processos'))->update("id = " . $this->id, [
            "medicamento_id" => $this->medicamento_id,
            "data" => $this->data,
            "data_validade" => $this->data_validade,
            "status" => $this->status,
            "paciente_id" => $this->paciente_id,
            "medico_solicitante" => $this->medico_solicitante,
            "observacoes" => $this->observacoes,
            "funcionario_id" => $this->funcionario_id,
            "data_ultima_modificacao" => $this->data_ultima_modificacao
        ]);
    }

    /**
     * Método responsável por excluir um processo do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('f_processos'))->delete("id = " . $this->id);
    }
}

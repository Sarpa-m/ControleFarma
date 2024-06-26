<?php

namespace App\Model\Entity\Farma;

use App\Utils\Database;

class Paciente
{
    /**
     * Id do Paciente
     *
     * @var int
     */
    public $id;

    /**
     * Nome completo do Paciente
     *
     * @var string
     */
    public $nome_completo;

    /**
     * Número SIM do Paciente
     *
     * @var string
     */
    public $numero_sim;

    /**
     * Data de nascimento do Paciente
     *
     * @var string
     */
    public $data_nascimento;

    /**
     * Telefone do Paciente
     *
     * @var string
     */
    public $telefone;

    /**
     * Médico solicitante
     *
     * @var string
     */
    public $medico_solicitante;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return int
     */
    public function cadastrar()
    {
        $this->id = (new Database('F_Pacientes'))->insert([
            "nome_completo" => $this->nome_completo,
            "numero_sim" => $this->numero_sim,
            "data_nascimento" => $this->data_nascimento,
            "telefone" => $this->telefone,
            "medico_solicitante" => $this->medico_solicitante
        ]);

        return $this->id;
    }

    /**
     * Método responsável por retornar um ou mais pacientes
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getPacientes($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('F_Pacientes'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar um ou mais nome de medicos ca coluna medico_solicitante
     *
     * @param string $where

     * @param string $limit
     * @return \PDOStatement
     */
    public static function getMedicos($where = null, $limit = 5)
    {

        return self::getPacientes("medico_solicitante LIKE '%$where%'", "medico_solicitante ASC", $limit, 'DISTINCT  f_pacientes.medico_solicitante')->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    /**
     * Método responsável por retornar um paciente com base no seu ID
     *
     * @param integer $id
     * @return Paciente
     */
    public static function getPacienteById($id)
    {
        return self::getPacientes("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por obter um paciente pelo número SIM.
     *
     * @param integer $numero_sim O número SIM do paciente.
     * @return Paciente O objeto Paciente correspondente ao número SIM fornecido.
     */
    public static function getPacienteByNumero_sim($numero_sim)
    {
        // Retorna o objeto Paciente correspondente ao número SIM fornecido.
        return self::getPacientes("numero_sim = $numero_sim")->fetchObject(self::class);
    }


    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('F_Pacientes'))->update("id = " . $this->id, [
            "nome_completo" => $this->nome_completo,
            "numero_sim" => $this->numero_sim,
            "data_nascimento" => $this->data_nascimento,
            "telefone" => $this->telefone,
            "medico_solicitante" => $this->medico_solicitante
        ]);
    }

    /**
     * Método responsável por excluir um paciente do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('F_Pacientes'))->delete("id = " . $this->id);
    }
}

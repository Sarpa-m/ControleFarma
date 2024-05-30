<?php

namespace App\Model\Entity\Usuarios;

use App\Utils\Database;

class Permissao
{
    /**
     * Id da Permissão
     *
     * @var int
     */
    public $id;

    /**
     * Nome da Permissão
     *
     * @var string
     */
    public $nome;

    /**
     * Descrição da Permissão
     *
     * @var string
     */
    public $descricao;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('u_permissoes'))->insert([
            "nome" => $this->nome,
            "descricao" => $this->descricao
        ]);

        return true;
    }

    /**
     * Método responsável por retornar uma ou mais permissões
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getPermissoes($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('u_permissoes'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar uma permissão com base no seu ID
     *
     * @param integer $id
     * @return Permissao
     */
    public static function getPermissaoById($id)
    {
        return self::getPermissoes("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('u_permissoes'))->update("id = " . $this->id, [
            "nome" => $this->nome,
            "descricao" => $this->descricao
        ]);
    }

    /**
     * Método responsável por excluir uma permissão do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('u_permissoes'))->delete("id = " . $this->id);
    }
}

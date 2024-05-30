<?php

namespace App\Model\Entity\Usuarios;

use App\Utils\Database;

class UsuarioPermissao
{
    /**
     * Id da Permissão do Usuário
     *
     * @var int
     */
    public $id;

    /**
     * Id do Usuário
     *
     * @var int
     */
    public $usuario_id;

    /**
     * Id da Permissão
     *
     * @var int
     */
    public $permissao_id;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('u_usuario_permissoes'))->insert([
            "usuario_id" => $this->usuario_id,
            "permissao_id" => $this->permissao_id
        ]);

        return true;
    }

    /**
     * Método responsável por retornar uma ou mais permissões de usuário
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getUsuarioPermissoes($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('u_usuario_permissoes'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar uma permissão de usuário com base no seu ID
     *
     * @param integer $usuario_id
     * @return \PDOStatement
     */
    public static function getPermissaoDoUsuario($usuario_id)
    {
        return self::getUsuarioPermissoes("usuario_id = $usuario_id");
    }

    /**
     * Método responsável por retornar uma permissão de usuário com base no seu ID
     *
     * @param integer $id
     * @return UsuarioPermissao
     */
    public static function getUsuarioPermissaoById($id)
    {
        return self::getUsuarioPermissoes("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('u_usuario_permissoes'))->update("id = " . $this->id, [
            "usuario_id" => $this->usuario_id,
            "permissao_id" => $this->permissao_id
        ]);
    }

    /**
     * Método responsável por excluir uma permissão de usuário do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('u_usuario_permissoes'))->delete("id = " . $this->id);
    }
}

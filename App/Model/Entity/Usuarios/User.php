<?php

namespace App\Model\Entity\Usuarios;
use App\Utils\Database;
class User
{
    /**
     * Id do Usuario
     *
     * @var int
     */
    public $id;

    /**
     * User do Usuario 
     *
     * @var string
     */
    public $username;


    /**
     * Método responsavel por retornar usuario apartir do email
     *
     * @param string $email
     * @return User
     */
    public static function getUserByUsername($username)
    {
        return (new Database('u_usuarios'))->select('username ="' . $username . '"')->fetchObject(self::class);
    }

    /**
     * Metodoresponsavel por cadastrar a instancia atual no banca de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {


        $this->id = (new Database('u_usuarios'))->insert([         
            "username" => $this->username
        ]);

        return true;
    }

    /**
     * Método responsavel por retornar um Usuario
     *
     * @param  string $whwrw
     * @param  string $order
     * @param  string $limit
     * @param  string $field
     * @return \PDOStatement
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('u_usuarios'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsavel por retornar um depoimento com base no seu ID
     *
     * @param integer $id
     * @return User
     */
    public static function getUserById($id)
    {
        return self::getUsers("id = $id")->fetchObject(self::class);
    }
    /**
     * Método responsavel por atualizar os dados do banco com a intancia atual
     *
     * @return boolean
     */
    public function atualizar()
    {

        //ATUALIZA OS DADOS NO BANCO
        return (new Database('u_usuarios'))->update("id = " . $this->id, [
            "username" => $this->username
        ]);
    }

    /**
     * Método responsavel por excluir um depoimeto do banco
     *
     * @return boolean
     */
    public function excluir()
    {

        //APAGAR OS DADOS NA BANCO
        return (new Database('u_usuarios'))->delete("id = " . $this->id);
    }
}

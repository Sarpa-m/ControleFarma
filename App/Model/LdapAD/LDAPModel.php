<?php

namespace App\Model\LdapAD;

class LDAPModel
{
    // Conexão LDAP
    private $ldapConnection;

    /**
     * Objeto Ldap
     *
     * @var LDAPConnection
     */
    private $ldapconn;

    /**
     * Endereço do servidor Ldap
     *
     * @var string
     */
    private static $ldapServer;

    /**
     * Porta do servidor Ldap
     *
     * @var int
     */
    private static $ldapport = 389;

    /**
     * Usuário administrador do servidor Ldap
     *
     * @var string
     */
    private static $ldapBindUser;

    /**
     * Senha do usuário administrador do servidor Ldap
     *
     * @var string
     */
    private static $ldapBindPassword;

    /**
     * Configura as credenciais e informações do servidor LDAP
     *
     * @param string $ldapServer Endereço do servidor LDAP
     * @param int $ldapport Porta do servidor LDAP
     * @param string $ldapBindUser Usuário administrador do servidor LDAP
     * @param string $ldapBindPassword Senha do usuário administrador do servidor LDAP
     */
    public static function Config($ldapServer, $ldapport, $ldapBindUser, $ldapBindPassword)
    {
        self::$ldapServer = $ldapServer;
        self::$ldapport = $ldapport;
        self::$ldapBindUser = $ldapBindUser;
        self::$ldapBindPassword = $ldapBindPassword;
    }

    /**
     * Construtor da classe, inicializa a conexão LDAP
     */
    public function __construct()
    {
        // Conecta ao servidor LDAP
        $this->ldapConnection = ldap_connect(self::$ldapServer, self::$ldapport);

        if (!$this->ldapConnection) {
            die("Erro ao conectar ao servidor LDAP");
        }

        // Define as opções LDAP
        ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapConnection, LDAP_OPT_REFERRALS, 0);

        // Realiza o bind com o servidor LDAP
        if (!ldap_bind($this->ldapConnection, self::$ldapBindUser, self::$ldapBindPassword)) {
            die("Erro ao fazer bind com o servidor LDAP");
        }
    }

    /**
     * Pesquisa um usuário no LDAP
     *
     * @param string $username Nome de usuário
     * @param array $attributes Atributos a serem retornados
     * @return array Entradas do LDAP
     */
    public function searchUser($username, $attributes = ["cn", "mail", "memberOf", "samaccountname"])
    {
        $filter = "(sAMAccountName=$username)";
        $search = ldap_search($this->ldapConnection, "DC=mmirim,DC=local", $filter, $attributes);

        if (!$search) {
            die('Erro na busca LDAP: ' . ldap_error($this->ldapConnection));
        }

        $entries = ldap_get_entries($this->ldapConnection, $search);

        return $entries;
    }

    /**
     * Método responsável por autenticar o usuário via base LDAP
     *
     * @param string $username Nome de usuário
     * @param string $password Senha do usuário
     * @param array $groupsName Grupos necessários para autenticação
     * @return false|array Retorna os dados do usuário se autenticado ou false se falhar
     */
    public function authenticateUser($username, $password, $groupsName = null)
    {
        $entries = $this->searchUser($username, []);

        if ($entries['count'] === 0) {
            // Usuário não encontrado
            return false;
        }

        // Obtém o DN (Distinguished Name) do usuário encontrado
        $userDN = $entries[0]['dn'];

        if (empty($password)) {
            // Autenticação falhou
            return false;
        }

        // Tenta autenticar o usuário com a senha fornecida
        $bind = @ldap_bind($this->ldapConnection, $userDN, $password);

        if (!$bind) {
            // Autenticação falhou
            return false;
        }

        if ($groupsName != null) {
            // Verifica se o usuário pertence aos grupos especificados
            $memberOf = $entries[0]['memberof'];
            foreach ($groupsName as $groupName) {
                $groupName = strtolower($groupName);
                foreach ($memberOf as $group) {
                    $group = strtolower($group);
                    if (strpos($group, $groupName) !== false) {
                        // O usuário pertence ao grupo especificado
                        return $entries[0];
                    }
                }
            }
        } else {
            return $entries[0];
        }

        // O usuário não pertence ao grupo especificado
        return false;
    }

    /**
     * Desconecta do servidor LDAP
     */
    public function disconnect()
    {
        ldap_close($this->ldapConnection);
    }
}

<?php

namespace App\Model\LdapAD;

class LDAPModel
{
    private $ldapConnection;

    /**
     * Objeto Ldap
     *
     * @var LDAPConnection
     */
    private $ldapconn;

    /**
     * Endereso servidor Ldap
     *
     * @var string
     */
    private static $ldapServer;

    /**
     * Porta servidor Ldap
     *
     * @var int
     */
    private static $ldapport = 389;

    /**
     * Usurio administration servidor Ldap
     *
     * @var string
     */
    private static $ldapBindUser;

    /**
     * Senha do usurio administration servidor Ldap
     *
     * @var string
     */
    private static $ldapBindPassword;

    public static function Config($ldapServer, $ldapport, $ldapBindUser, $ldapBindPassword)
    {
        self::$ldapServer = $ldapServer;
        self::$ldapport = $ldapport;
        self::$ldapBindUser = $ldapBindUser;
        self::$ldapBindPassword = $ldapBindPassword;
    }

    public function __construct()
    {
        $this->ldapConnection = ldap_connect(self::$ldapServer, self::$ldapport);

        if (!$this->ldapConnection) {
            die("Erro ao conectar ao servidor LDAP");
        }

        ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapConnection, LDAP_OPT_REFERRALS, 0);


        if (!ldap_bind($this->ldapConnection, self::$ldapBindUser, self::$ldapBindPassword)) {
            die("Erro ao fazer bind com o servidor LDAP");
        }
    }

    public function searchUser($username, $attributes = ["cn", "mail", "memberOf", "samaccountname"])
    {
        $filter = "(sAMAccountName=$username)";
        //$attributes = ["cn", "mail", "memberOf", "samaccountname"];

        $search = ldap_search($this->ldapConnection, "DC=mmirim,DC=local", $filter, $attributes);

        if (!$search) {
            die('Erro na busca LDAP: ' . ldap_error($this->ldapConnection));
        }

        $entries = ldap_get_entries($this->ldapConnection, $search);

        return $entries;
    }



    /**
     * Metodo resposavel por autenticar o suario via base LDAP 
     *
     * @param string $username usuario
     * @param string $password senha do usuario
     * @param array $groupsName grupos nesseario para autenticar
     * @return false|array
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

        // Tenta autenticar o usuário com a senha fornecida
        $bind = @ldap_bind($this->ldapConnection, $userDN, $password);

        if (!$bind) {
            // Autenticação falhou
            return false;
        }

        if ($groupsName != null) {
            // Verifica se o usuário pertence ao grupo especificado
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


    public function disconnect()
    {
        ldap_close($this->ldapConnection);
    }
}

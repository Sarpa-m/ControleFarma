<?php

namespace App\Controller\Login;

use App\Controller\Pages\Login as PagesLogin;
use App\Http\Request;
use App\Model\Entity\Usuarios\User;
use App\Model\Entity\Usuarios\UsuarioPermissao;
use App\Model\LdapAD\LDAPModel;
use App\Session\Login as SessionLogin;
use App\Utils\FormatarString;

class Login
{
   
    /**
     * 
     * @param Request $request Requisição HTTP
     * @return void Retorna true se o login foi bem-sucedido, false caso contrário
     */
    public static function setLogin($request)
    {
        // Formata e valida o nome de usuário
        $username = FormatarString::isSafeString($request->getPostVars('usuario'));

        // Recupera a senha
        $password = FormatarString::isSafeString($request->getPostVars('password'));

        if ($username == null or $password == null) {
            return PagesLogin::getLoginPege($request, 2);
        }

        // Recupera o usuário pelo nome de usuário
        $obUser = User::getUserByUsername($username);

        // Verifica se o usuário existe
        if (!($obUser instanceof User)) {
            return PagesLogin::getLoginPege($request, 1);
        }

        // Inicializa o modelo LDAP
        $obLDAPModel = new LDAPModel();

        // Autentica o usuário no LDAP
        $user = $obLDAPModel->authenticateUser($username, $password);

        // Verifica se a autenticação foi bem-sucedida
        if (!$user) {
            return PagesLogin::getLoginPege($request, 2);
        }

        // Recupera as permissões do usuário
        $Permissao = [];
        $UsuarioPermissao = UsuarioPermissao::getPermissaoDoUsuario($obUser->id);

        // Itera sobre as permissões e as adiciona ao array de permissões
        while ($obUsuarioPermissao = $UsuarioPermissao->fetchObject(UsuarioPermissao::class)) {
            $Permissao[] = $obUsuarioPermissao->permissao_id;
        }

        // Cria um array com os dados do usuário
        $dados = [
            'id' => $obUser->id,
            'username' => $user['samaccountname'][0],
            'nome' => $user['displayname'][0],
            'Permissao' => $Permissao
        ];

        // Realiza o login do usuário na sessão
        SessionLogin::login($dados);

        $request->getRouter()->redirect(URL);
    }

    /**
     * 
     * @param Request $request Requisição HTTP
     * @return void Retorna true se o login foi bem-sucedido, false caso contrário
     */
    public static function setLogout($request) {
        SessionLogin::logout();
        $request->getRouter()->redirect(URL."/login");
    }
}

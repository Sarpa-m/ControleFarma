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
     * Método responsável por realizar o login do usuário
     * 
     * @param Request $request Requisição HTTP
     * @return void
     */
    public static function setLogin($request)
    {
        // Formata e valida o nome de usuário
        $username = FormatarString::isSafeString($request->getPostVars('username'));

        // Recupera a senha
        $password = FormatarString::isSafeString($request->getPostVars('password'));

        // Verifica se o nome de usuário ou a senha são nulos
        if ($username == null or $password == null) {
            // Retorna a página de login com um alerta de credenciais inválidas
            return PagesLogin::getLoginPege($request, 2);
        }

        // Recupera o usuário pelo nome de usuário
        $obUser = User::getUserByUsername($username);

        // Verifica se o usuário existe
        if (!($obUser instanceof User)) {
            // Retorna a página de login com um alerta de usuário não encontrado
            return PagesLogin::getLoginPege($request, 1);
        }

        // Inicializa o modelo LDAP
        $obLDAPModel = new LDAPModel();

        // Autentica o usuário no LDAP
        $user = $obLDAPModel->authenticateUser($username, $password);

        // Verifica se a autenticação foi bem-sucedida
        if (!$user) {
            // Retorna a página de login com um alerta de falha na autenticação
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

        // Redireciona para a URL principal
        $request->getRouter()->redirect(URL);
    }

    /**
     * Método responsável por realizar o logout do usuário
     * 
     * @param Request $request Requisição HTTP
     * @return void
     */
    public static function setLogout($request) {
        // Encerra a sessão do usuário
        SessionLogin::logout();
        // Redireciona para a página de login
        $request->getRouter()->redirect(URL."/login");
    }
}

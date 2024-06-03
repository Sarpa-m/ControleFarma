<?php

namespace App\Utils;

class Redirect
{

    /**
     * Redireciona o usuário para a URL especificada.
     * 
     * Códigos HTTP para redirecionamento:
     *
     * 301: Moved Permanently (Movido Permanentemente)
     *      - Indica que a página foi movida permanentemente para um novo local.
     *      - Motores de busca e navegadores atualizarão seus links para a nova URL.
     *      - Útil para SEO quando você muda a URL de uma página.
     *
     * 302: Found (Encontrado)
     *      - Indica um redirecionamento temporário.
     *      - Motores de busca geralmente não atualizam seus links, mantendo a URL original.
     *      - Útil para redirecionamentos temporários, como durante a manutenção do site.
     *
     * 303: See Other (Ver Outro)
     *      - Similar ao 302, mas sempre muda o método da requisição para GET após o redirecionamento.
     *      - Útil para evitar reenvios acidentais de formulários.
     * @param string $targetUrl A URL para a qual o usuário será redirecionado.
     * @param int $httpCode O código de status HTTP a ser enviado no cabeçalho de resposta (padrão: 302 Found).
     */
    public static function Redirect($targetUrl, $httpCode = 302)
    {
        // Define o cabeçalho de redirecionamento para a URL especificada
        header("Location: $targetUrl", true, $httpCode);

        // Finaliza o script PHP para garantir que o redirecionamento ocorra imediatamente
        exit;
    }
}

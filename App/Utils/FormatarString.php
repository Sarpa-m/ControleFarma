<?php

namespace App\Utils;

class FormatarString
{



    public static function iniciaisMaiusculas($string)
    {
        // $string =  strtolower($string);
        $string = mb_convert_case($string, MB_CASE_LOWER_SIMPLE, 'UTF-8');

        // Quebra a string em palavras
        $palavras = explode(' ', $string);

        // Itera sobre as palavras
        foreach ($palavras as &$palavra) {
            // Verifica se a palavra tem mais de duas letras
            if (strlen($palavra) > 2) {
                // Transforma a primeira letra em maiúscula
                $palavra = ucfirst($palavra);
            }
        }

        // Junta as palavras de volta em uma string
        $resultado = implode(' ', $palavras);

        return $resultado;
    }

    /**
     * Método responsável por verificar e limpar uma string para evitar injeção de SQL
     *
     * @param string $string A string a ser verificada
     * @return string A string limpa, sem tags HTML e palavras reservadas de SQL
     */
    public static function isSafeString($string)
    {
        // Remove tags HTML
        $strippedString = strip_tags($string);

        // Regex para encontrar e remover palavras reservadas de SQL (case-insensitive)
        $sqlKeywords = "/\b(SELECT|INSERT|UPDATE|DELETE|UNION|DROP|WHERE|LIMIT)\b/i";
        $cleanString = preg_replace($sqlKeywords, "", $strippedString);

        // Retorna a string limpa
        return $cleanString;
    }


    public static function telefone($n)
    {
        $n = preg_replace("/[^0-9]/", "", $n);
        $tam = strlen(preg_replace("/[^0-9]/", "", $n));


        if ($tam == 13) {
            // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
            return "+" . substr($n, 0, $tam - 11) . " (" . substr($n, $tam - 11, 2) . ") " . substr($n, $tam - 9, 5) . "-" . substr($n, -4);
        }
        if ($tam == 12) {
            // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
            return "+" . substr($n, 0, $tam - 10) . " (" . substr($n, $tam - 10, 2) . ") " . substr($n, $tam - 8, 4) . "-" . substr($n, -4);
        }
        if ($tam == 11) {
            // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
            return " (" . substr($n, 0, 2) . ") " . substr($n, 2, 5) . "-" . substr($n, 7, 11);
        }
        if ($tam == 10) {
            // COM CÓDIGO DE ÁREA NACIONAL
            return " (" . substr($n, 0, 2) . ") " . substr($n, 2, 4) . "-" . substr($n, 6, 10);
        }
        if ($tam <= 9) {
            // SEM CÓDIGO DE ÁREA
            return substr($n, 0, $tam - 4) . "-" . substr($n, -4);
        }
    }
}

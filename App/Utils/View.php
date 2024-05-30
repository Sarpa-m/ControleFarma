<?php

namespace App\Utils;

class View
{
    /**
     * Variaves padão da view
     *
     * @var array
     */
    private static $vars = [];

    /**
     * Metodo responsavel por definir os dados iniciais da classe
     *
     * @param  array $var
     */
    public static function init(array $vars = [])
    {
        self::$vars = array_merge($vars, self::$vars);
    }

    /**
     * Método responsavel por retornar o conteudo de uma view 
     * @param  string $view
     * @return string
     */
    private static function getContentView($view)
    {
        $file = __DIR__ . "/../../resources/view/$view.html";

        if (file_exists($file)) {
            return file_get_contents($file);
        } else {
            throw new \Exception("caminho da view invalido <br>resources/view/$view.html", 500);
        }
    }

    /**
     * Método responsável por retornar o conteúdo renderizado de uma view
     * @param string $view
     * @param array $var
     * @return string
     */
    public static function render($view, $vars = [])
    {
        //conteudo da view
        $contentView = self::getContentView($view);

        //Merge das variaves da view
        $vars = array_merge($vars, self::$vars);

        //CHAVE DOS ARRAYS DE VARIAVES
        $keys = array_keys($vars);
        $keys = array_map(function ($item) {
            return "{{" . $item . "}}";
        }, $keys);

        $values = array_values($vars);


        $contentView = str_replace($keys, $values, $contentView);


        //RETORNA O CONTEUDO RENDERIZADO
        return str_replace($keys, $values, $contentView);
    }
}

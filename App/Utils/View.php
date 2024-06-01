<?php
namespace App\Utils;

class View
{
    /**
     * Variáveis padrão da view
     *
     * @var array
     */
    private static $vars = [];

    /**
     * Método responsável por definir os dados iniciais da classe
     *
     * @param array $vars Array associativo contendo as variáveis iniciais
     */
    public static function init(array $vars = [])
    {
        // Mescla as variáveis iniciais com as variáveis padrão
        self::$vars = array_merge($vars, self::$vars);
    }

    /**
     * Método responsável por retornar o conteúdo de uma view
     *
     * @param string $view Nome do arquivo da view
     * @return string Conteúdo do arquivo da view
     * @throws \Exception Se o caminho da view for inválido
     */
    private static function getContentView($view)
    {
        $file = __DIR__ . "/../../resources/view/$view.html";

        // Verifica se o arquivo da view existe
        if (file_exists($file)) {
            return file_get_contents($file);
        } else {
            throw new \Exception("Caminho da view inválido: <br>resources/view/$view.html", 500);
        }
    }

    /**
     * Método responsável por retornar o conteúdo renderizado de uma view
     *
     * @param string $view Nome do arquivo da view
     * @param array $vars Variáveis a serem passadas para a view
     * @return string Conteúdo renderizado da view
     */
    public static function render($view, $vars = [])
    {
        // Conteúdo da view
        $contentView = self::getContentView($view);

        // Mescla as variáveis da view com as variáveis padrão e as passadas como parâmetro
        $vars = array_merge(self::$vars, $vars);

        // Substitui as chaves das variáveis pelos seus valores no conteúdo da view
        $keys = array_keys($vars);
        $keys = array_map(function ($item) {
            return "{{" . $item . "}}";
        }, $keys);
        $values = array_values($vars);
        $contentView = str_replace($keys, $values, $contentView);

        // Retorna o conteúdo renderizado da view
        return str_replace($keys, $values, $contentView);
    }
}

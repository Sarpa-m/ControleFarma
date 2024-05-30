<?php

namespace App\Controller\Pages;


use App\Http\Request;
use App\Utils\Pagination;
use App\Utils\View;


class Page
{
    /**
     * Metodo responsavel por retornar o conteúdo (View) da nossa Pagina generica
     *
     * @return string
     */
    public static function getPage($title, $content)
    {
        if (is_array($content)) {
            $content = implode("", $content);
        }

        return View::render('page\\page', [
            "title" => $title,
            "content" => $content,
            "header" => self::getHeader(),
            "footer" => self::getFooter(),


        ]);
    }

    /**
     * Metodo responsavel por retornar o header da pagina
     *
     * @return strinbg
     */
    public static function getHeader()
    {

        return View::render("page\\header", []);
    }

    /**
     * Metodo responsavel por retornar o footer da pagina
     *
     * @return strinbg
     */
    public static function getFooter()
    {

        return View::render("page\\footer", []);
    }

    /**
     * Método responsavel por renderizar a layout de paginação
     *
     * @param  Request $request
     * @param  Pagination $obPagination
     * @return void
     */
    public static function getPagination($request, $obPagination)
    {
        //PAGINAS
        $pages = $obPagination->getPages();

        //VERIFICA A QUANTIDADE DE PAGINAS 
        if (count($pages) <= 1) {
            return '';
        } 

        //LIMKS
        $links = "";

        //URL AUTAL SEM GETS
        $url = $request->getRouter()->getcurrentUrl();

        //GTES
        $queyParams = $request->getQueyParams();

        $boxPrimeiro = "";
        $boxUtimo = "";

        foreach ($pages as $page) {


           

            if ($page['current'] == 1) {

                //ALTERA A PÁGINA
                $queyParams['page'] = $page['page'] - 1;

                //LINK
                $link = $url . '?' . http_build_query($queyParams);


                $boxPrimeiro .= View::render('pagination/link', [
                    'page' => "<",
                    'link' => $link,
                    'active' => ''
                ]);

                //ALTERA A PÁGINA
                $queyParams['page'] = $page['page'] + 1;

                //LINK
                $link = $url . '?' . http_build_query($queyParams);

                $boxUtimo .= View::render('pagination/link', [
                    'page' => ">",
                    'link' => $link,
                    'active' => ''
                ]);
            }


            //ALTERA A PÁGINA
            $queyParams['page'] = $page['page'];

            //LINK
            $link = $url . '?' . http_build_query($queyParams);

            //VIEW
            $links .= View::render('pagination/link', [
                'page' => $page['page'],
                'link' => $link,
                'active' => ($page['current'] == 1) ? 'active' : ''
            ]);
        }


        //REDENRIZA BOX DE PAGINAÇÃO

        return View::render('pagination/box', [
            'links' => $boxPrimeiro . $links . $boxUtimo
        ]);
    }


    /**
     * Metodo responsavel por retornar uma mesagem de sucesso
     * @param  string $mensagem
     * @return string
     */
    public static function getAlertSuccess($mensagem)
    {

        return View::render("page\\alert", [
            'tipo' => "success",
            'mensagem' => $mensagem
        ]);
    }

    /**
     * Metodo responsavel por retornar uma mesagem de erro
     * @param  string $mensagem
     * @return string
     */
    public static function getAlertError($mensagem)
    {

        return View::render("page\\alert", [
            'tipo' => "danger",
            'mensagem' => $mensagem
        ]);
    }
}

<?php

namespace App\Utils\Cache;

class File
{
    /**
     * Método responsavel por obter uma informação do cache
     *
     * @param string $hash
     * @param integer $expiration
     * @param closure $function
     * @return mixed
     */
    public static function getCache($hash, $expiration, $function)
    {
        //VERIFICA O CONTEUDO GRAVADO
        if ($content = self::getContentCache($hash, $expiration)) {
            //RETORNA O CONTEÚDO
            return $content;
        }
        //EXECUTA A FUNÇÃO 
        $content = $function();

        //GRAVA O RETORNO NO CACHE
        self::storagerCache($hash, $content);
        //RETORNA O CONTEÚDO
        return $content;
    }
    /**
     * Meptodo responsavel por gardar informaçoes no cache
     *
     * @param string $hash
     * @param mixed $content
     * @return boolean
     */
    private static function storagerCache($hash, $content)
    {
        //CONVERTER/SERIALIZA O RETORNO
        $serialize = serialize($content);

        //ÓBTEM O COMINHO ATÉ O ARQUIVO DE CACHE
        $cacheFile = self::getFilePath($hash);
     
        //GRAVA A INFORMAÇÃO NO ARQUIVO
        return file_put_contents($cacheFile, $serialize);
    }
    /**
     * Método responsavel por retoanr o caminha até o arquivo de cache
     *
     * @param string $hash
     * @return string
     */
    private static function getFilePath($hash)
    {
        //DIRETORIO DE CACHE
        $dir = DIR . getenv('CACHE_DIR');

        //VERIFICA A EXISTEMCIA DO DIRETORIO
        if (!file_exists($dir)) {
            mkdir($dir, 755, true);
        }
        //RETORNA O CAMINHA ATÉ O ARQUIVO
        return $dir . '/' . $hash . ".cache";
    }
    /**
     * Meétodo responsavel por restorna o conteudo do cache
     * 
     * @param string $hash
     * @param integer $expiration
     * @return mixed
     */
    private static function getContentCache($hash, $expiration)
    {
        //CAMINHO DO ARQUIVO
        $cacheFile = self::getFilePath($hash);

        //VERIFICA SE EXISTE
        if (!file_exists($cacheFile)) {
            return false;
        }


        //VALIDA A EXPIRAÇÃO DO CACHE
        $createTime = filemtime($cacheFile);
        if ((time() - $createTime > $expiration)) {

            return false;
        }
        //RETORNA O DADO REAL DESSEREALIZADO
        $serealize = file_get_contents($cacheFile);
        return unserialize($serealize);
    }

    public static function deleteCache($hash){
        //CAMINHO DO ARQUIVO
        $cacheFile = self::getFilePath($hash);

        return unlink($cacheFile);
    }
}

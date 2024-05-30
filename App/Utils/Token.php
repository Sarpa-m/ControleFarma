<?php

namespace App\Utils;

class Token
{
   public static function getToken($tamanho=10, $id="", $up=false) {
        $characters = $id.'abcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        if($up === true) {
          return strtoupper($id.$randomString);
        } else {
          return $id.$randomString;
        }
      }

}

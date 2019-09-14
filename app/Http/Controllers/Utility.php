<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Utility extends Controller
{
    //
    public static function reference(){
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_';
        $password = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < 10; $i++)
        {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }

        return implode($password);
    }

    public static function amount_delimeter($amount){

        if(strlen($amount) == 6){ $amount = Utility::stringInsert($amount, "," , 3); }
        if(strlen($amount) == 5){ $amount = Utility::stringInsert($amount, "," , 2); }
        if(strlen($amount) == 4){ $amount = Utility::stringInsert($amount, "," , 1); }
        if(strlen($amount) <= 3){ $amount = $amount; }

        //return Utility::add_naira_sign($amount);
        return $amount;
    }

    public static function stringInsert($str,$insertstr,$pos){
        $str = substr($str, 0, $pos) . $insertstr . substr($str, $pos);
        return "NGN ".$str;
    }

    public static function add_naira_sign($amount){
        $amount = "#".$amount;
        return $amount;
    }

}

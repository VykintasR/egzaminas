<?php
namespace App\Helpers;

class Funkcijos
{
    public static function ApskaiciuotiTrukme($pradzia, $pabaiga)
    {
        $trukme = "";
        $intervalas = $pradzia->diff($pabaiga);
        if($intervalas->y != 0) $trukme .= $intervalas->y." metai ";
        if($intervalas->m != 0) $trukme .= $intervalas->m." mėn. ";
        if($intervalas->d != 0) $trukme .= $intervalas->d." d. ";
        else $trukme .= "1 d. ";
        return $trukme;
    }
    
    public static function RealiArPlanuojamaData($planuojamaData, $realiData)
    {
        return $realiData != null ? $realiData : $planuojamaData;
    }

    public static function RealusArPlanuojamasBiudzetas($veikla)
    {
        return $veikla->realus_biudzetas != null ? $veikla->realus_biudzetas : $veikla->planuojamas_biudzetas;
    }
}
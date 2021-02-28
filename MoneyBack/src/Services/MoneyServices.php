<?php


namespace App\Services;


final class MoneyServices{


    public function getCodetransaction($cni){

        $codetrans = substr(hexdec(md5($cni)), 2, 9);
        return $codetrans;
    } 
    public function getCodeAgence($id){

        $code= sprintf("%06d", $id);
        $code = substr($code, 0, 3).'-'.substr($code, 3);
        return $code;
    }
    public function getDate(){;
        $date = new \DateTime();
        return $date->format('H:i:s \O\n Y-m-d');
    }
    public function updateSolde($security, $montant, $type, $partDepot){

        $compte = $security->getUser()->getCompte()->get;

    }


}
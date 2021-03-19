<?php

namespace App\Services;

use Twilio\Rest\Client;

class SendSms
{
    public function send( $montant)
    {

        $sid = "...." ; // Votre compte SID de www.twilio.com/console 
        $token = "...." ; // Votre jeton d'authentification de www.twilio.com/console 
        // $client = new  Twilio \Rest\Client ( $sid , $token );
        $twilio = new Client($sid, $token);
        $message = $twilio -> messages -> create (
        '+221774271850' ,// Textez ce numéro 
        [ 'from' => '+12702015769' , //767809040 D'un numéro Twilio valide 
            'body' => 'Votre envoie de: '.$montant.' a été effectué avec succes. le $codeTrans de transaction. montant à rétirer mtnRetire' 
        ] 
        );
    }


    // $account_sid = $_ENV['ACCOUNT_SID'];
    // $auth_token = $_ENV['AUTH_TOKEN'];
    // $twilio_number = $_ENV['TWILIO_NUMBER'];

    // $client = new Client($account_sid, $auth_token);
    // $client->messages->create(
    //     // Where to send a text message (your cell phone?)
    //     '+221772446746',
    //     array(
    //         'from' => $twilio_number,
    //         'body' => "Bonsoir " . $beneficiaire->getFirstName() . " " . $beneficiaire->getLastName() . ", " . $emetteur->getFirstName() . " " . $emetteur->getLastName() . " vous a envoyer " . $montant . "fcfa.\n vous pouvez récupérer votre argent.#samoney",
    //     )
    // );
}
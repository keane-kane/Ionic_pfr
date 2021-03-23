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
    public function envoiesms($numero, $montant, $code, $nom)
    {
        $sid = "AC3d76f4ba21a37f3af4ba24c95eafb971"; // Your Account SID from www.twilio.com/console
        $token = "e4b7d801964ffddd78c38535576a342a"; // Your Auth Token from www.twilio.com/console

        $client = new \Twilio\Rest\Client($sid, $token);
        $message = $client->messages->create(
            '+221' . $numero, // Text this number
            [
                'from' => '+12054305961', // From a valid Twilio number
                'body' => 'Bienvenue dans Money SA ! , vous venez de recevoir ' . $montant . ' de la part de ' . $nom . ' Code de Transaction : ' . $code
            ]
        );

        print $message->sid;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ChapaController extends Controller
{
    public function initialize()
    {
        // Generate a unique transaction reference
        $tx_ref = 'chewatatest-' . uniqid();

        $responsepay = Http::withHeaders([
            'Authorization' => 'Bearer CHASECK_TEST-2qy7Hx18XqQL1NlJYoFCDRJaJ1oa5NZU',
            'Content-Type' => 'application/json',
        ])->post('https://api.chapa.co/v1/transaction/initialize', [
            "amount" => "300", 
            "currency" => "ETB",
            "email" => "bota@gmail.com",
            "first_name" => "bota",
            "last_name" => "parking",
            "phone_number" => "09110000000",
            "tx_ref" => $tx_ref, // Use the generated transaction reference
            'callback_url' => route('callback',[$tx_ref]),
            "return_url" => route('callback',[$tx_ref]),
            "customization" => [
                "title" => "Payment for BOTA",
                "description" => "Bota Parking"
            ]
        ]);

       
        if ($responsepay['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        // return redirect($responsepay['data']['checkout_url']);
        return $responsepay->body();
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback($tx_ref )
    {
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer CHASECK_TEST-2qy7Hx18XqQL1NlJYoFCDRJaJ1oa5NZU',
        ])->get('https://api.chapa.co/v1/transaction/verify/' . $tx_ref);

        //if payment is successful
        if ($response['status'] ==  'success') {
        

            return $response->body();
        }

        else{
            //oopsie something ain't right.
        }


    }
       
    }


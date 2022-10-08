<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use DateInterval;
use DateTime;

class OrdersController extends Controller
{

    /**
     * Vista de productos a comprar
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('orders.index', compact('orders'));
    }

    /**
     * Listado de ordenes generadas
     *
     * @return \Illuminate\Http\Response
     */
    public function listarOrden()
    {
        $orders = Orders::orderBy('id', 'desc')->paginate(5);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function previewOrder()
    {
        return view('orders.preview');
    }

    public function previewProcess(Request $request)
    {
        $messages = [
            'name.required' => 'El campo nombre es requerido.',
            'mobile.required' => 'El campo celular es requerido',
            'email.required' => 'El campo email es requerido.'
        ];
        $data = $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required',
        ], $messages);
        $this->createSessionPlacetoPay($data);
        dd($data);
        return true;
    }

    public function createSessionPlacetoPay($data)
    {

        $json = $this->prepareJson();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://checkout-co.placetopay.dev/api/session",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public static function prepareJson()
    {

        $currentDate = new DateTime();

        $seed = $currentDate->format('c'); // Returns ISO8601 in proper format

        $expires = $currentDate->add(new DateInterval('PT60M'))->format('c');

        $nonce = (string)time();
        $secretkey = "024h1IlD";

        $tranKey = base64_encode(sha1($nonce . $seed . $secretkey, true));

        try {
            $fields = json_encode([
                "locale" => "es_CO",
                "auth" => [
                    "login" => "6dd490faf9cb87a9862245da41170ff2",
                    "tranKey" => $tranKey,
                    "nonce" => base64_encode($nonce),
                    "seed" => $seed
                ],
                "payment" => [
                    "reference" => "1122334455",
                    "description" => "Prueba",
                    "amount" => [
                        "currency" => "USD",
                        "total" => 100
                    ]
                ],
                "expiration" => $expires,
                "returnUrl" => env('APP_URL'),
                "ipAddress" => "127.0.0.1",
                "userAgent" => "PlacetoPay Sandbox"
            ], JSON_THROW_ON_ERROR);

        } catch (\Exception $e) {
            $fields = "";
            error_log($e->getMessage());
        }
        return $fields;
    }

    public function viewStateOrden()
    {

    }

}

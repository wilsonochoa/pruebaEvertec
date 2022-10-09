<?php

namespace App\Http\Controllers\Utils;

use DateTime;
use DateInterval;

class Utils
{

    /**
     * @param $id_order
     * Realiza la peticion al servicio CreateRequest
     * @return false|mixed
     */
    public function createSessionPlacetoPay($id_order)
    {
        $json = $this->prepareJsonCreate($id_order);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('URL_CREATE_SESSION'),
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
            error_log($err);
            return false;
        } else {
            return json_decode($response, true);
        }
    }

    /**
     * @param $id_order
     * Organiza el request que se enviara al servicio CreateRequest
     * @return false|string
     */
    public function prepareJsonCreate($id_order)
    {
        $currentDate = new DateTime();
        $auth = $this->authWS();
        $expires = $currentDate->add(new DateInterval('PT10M'))->format('c');
        $returnUrl = route("viewStateOrden", ['id' => $id_order]);

        try {
            $fields = json_encode([
                "locale" => "es_CO",
                "auth" => $auth,
                "payment" => [
                    "reference" => "1122334455",
                    "description" => "Prueba",
                    "amount" => [
                        "currency" => "USD",
                        "total" => 100
                    ]
                ],
                "expiration" => $expires,
                "returnUrl" => $returnUrl,
                "ipAddress" => "127.0.0.1",
                "userAgent" => "PlacetoPay Sandbox"
            ], JSON_THROW_ON_ERROR);

        } catch (\Exception $e) {
            $fields = "";
            error_log($e->getMessage());
        }
        return $fields;
    }


    /**
     * @param $id_request
     * Realiza la peticion al servicio getRequestInformation
     * @return false|mixed
     */
    public function getRequestInformation($id_request)
    {

        $json = $this->prepareJsonGetInfor();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('URL_CREATE_SESSION') . '/' . $id_request,
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
            error_log($err);
            return false;
        } else {
            return json_decode($response, true);
        }
    }

    /**
     * Organiza el request que se enviara al servicio getRequestInformation
     * @return false|string
     */
    public function prepareJsonGetInfor()
    {
        $auth = $this->authWS();
        try {
            $fields = json_encode([
                "auth" => $auth
            ], JSON_THROW_ON_ERROR);

        } catch (\Exception $e) {
            $fields = "";
            error_log($e->getMessage());
        }
        return $fields;
    }

    /**
     * Funcion encargada de armar el request de autenticacion
     * @return array
     */
    public function authWS()
    {
        $currentDate = new DateTime();
        $seed = $currentDate->format('c');
        $nonce = (string)time();
        $tranKey = base64_encode(sha1($nonce . $seed . env("WS_SECRET_KEY"), true));
        return [
            "login" => env("WS_LOGIN"),
            "tranKey" => $tranKey,
            "nonce" => base64_encode($nonce),
            "seed" => $seed
        ];
    }
}

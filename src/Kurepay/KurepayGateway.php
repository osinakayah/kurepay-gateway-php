<?php
/**
 * Created by PhpStorm.
 * User: osinakayah
 * Date: 08/03/2019
 * Time: 6:14 AM
 */

namespace Kurepay;


class KurepayGateway
{
    private $publicKey;

    /**
     * KurepayGateway constructor.
     * @param $publicKey
     */
    public function __construct($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @param mixed $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    public function getTransactionUrl($email, $amount, $reference, $fullname, $phoneNumber, $meta = []){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://payment.kurepay.com/api/init-payment",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'public_key'=> $this->publicKey,
                'email'=> $email,
                'amount'=> $amount,
                'reference'=> $reference,
                'fullname'=> $fullname,
                'phoneNumber'=> $phoneNumber,
                'meta' => json_encode($meta)
            ]),
            CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));
        $response = curl_exec($curl);


        if ($response) {
            curl_close($curl);
            $result = json_decode($response);
            if ($result->status == 11) {
                return 'https://payment.kurepay.com/#/initPayment/'.$result->data->reference;
            }
            else {

                throw new \Exception($result->message);
            }
        }
        else {
            $err = curl_error($curl);
            curl_close($curl);
            throw new \Exception($err);
        }

    }

    public function getTransactionStatus($transactionReference) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://payment.kurepay.com/api/auth/transaction/status/".$transactionReference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'publicKey'=> $this->publicKey,
            ]),
            CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));
        $response = curl_exec($curl);
        if ($response) {
            curl_close($curl);
            $result = json_decode($response);
            if ($result->data->status == 0) {
                return false;
            }
            elseif ($result->data->status == 1) {
                return true;
            }
        }
        else {
            $err = curl_error($curl);
            curl_close($curl);
            throw new \Exception($err);
        }


    }

}
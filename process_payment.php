<?php
    $data = json_decode(file_get_contents('php://input'), true);
    require_once 'vendor/autoload.php';

    MercadoPago\SDK::setAccessToken("APP_USR-1178374229100737-102500-34680f7e27e1e56d9c03283f62315e3a-544629529");

    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = (float)$data['transactionAmount'];
    $payment->token = $data['token'];
    $payment->description = $data['description'];
    $payment->installments = (int)$data['installments'];
    $payment->payment_method_id = $data['paymentMethodId'];
    $payment->issuer_id = (int)$data['issuer'];

    $payer = new MercadoPago\Payer();
    $payer->email = $data['email'];
    $payer->identification = array(
        "type" => $data['docType'],
        "number" => $data['docNumber']
    );
    $payment->payer = $payer;

    $payment->save();

    $response = array(
        'status' => $payment->status,
        'status_detail' => $payment->status_detail,
        'id' => $payment->id
    );

    echo json_encode($response);

    /*$file = fopen("archivo.txt", "w");

    fwrite($file, $response->status . PHP_EOL);

    fclose($file);*/
?>
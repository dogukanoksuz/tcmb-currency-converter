<?php
/*
 * TCMB Currency Converter
 * github.com/dogukanoksuz
 * version: 2.0
 */

function TCMB_Converter($from = 'TRY', $to = 'USD', $val = 1)
{
    // check if simplexml and curl exists
    if (!function_exists('simplexml_load_string') || !function_exists('curl_init')) {
        return 'Simplexml extension missing.';
    }

    // currency data
    $CurrencyData = [
        'from' => 1,
        'to' => 1
    ];

    // try to fetch xml
    try {
        $tcmbMirror = 'https://www.tcmb.gov.tr/kurlar/today.xml';
        $curl = curl_init($tcmbMirror);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $tcmbMirror);

        $dataFromtcmb = curl_exec($curl);
    } catch (Exception $e) {
        echo 'Unhandled exception, maybe from cURL' . $e->getMessage();
        return 0;
    }

    // load xml string from cURL
    $Currencies = simplexml_load_string($dataFromtcmb);

    // loop for all currencies in xml
    foreach ($Currencies->Currency as $Currency) {
        if ($from == $Currency['CurrencyCode']) $CurrencyData['from'] = $Currency->BanknoteSelling;
        if ($to == $Currency['CurrencyCode']) $CurrencyData['to'] = $Currency->BanknoteSelling;
    }

    // calculate
    return round(($CurrencyData['to'] / $CurrencyData['from']) * $val, 10);
}

echo TCMB_Converter('EUR', 'USD');

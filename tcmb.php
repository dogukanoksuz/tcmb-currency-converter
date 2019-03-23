<?php
	/*
	 * TCMB Currency Converter
	 * github.com/dogukanoksuz
	 */
	function TCMB_Converter ( $currency_from, $currency_to ) {
		if (!function_exists("simplexml_load_file")) return;

        if ($currency_from == "TRY") $currency_from_val = 1;
        if ($currency_to   == "TRY") $currency_to_val   = 1;

		$tcmb = simplexml_load_file( 'http://www.tcmb.gov.tr/kurlar/today.xml' );
		foreach ( $tcmb->Currency as $currency ) {
			if ($currency_from == $currency["CurrencyCode"]) {
                if ($currency->BanknoteSelling != 0) {
				    $currency_from_val = $currency->BanknoteSelling;
                } else {
                    $currency_from_val = $currency->ForexSelling;
                }
			}

			if ($currency_to == $currency["CurrencyCode"]) {
				if ($currency->BanknoteSelling != 0) {
				    $currency_to_val = $currency->BanknoteSelling;
                } else {
                    $currency_to_val = $currency->ForexSelling;
                }
			}
		};

		if ($currency_from || $currency_to != 0) {
			return (float) round($currency_to_val / $currency_from_val, 10);
		} else {
			return 0;
		}
    }
    
    echo TCMB_Converter ("USD", "TRY");
?>

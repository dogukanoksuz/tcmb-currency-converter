<?php
	/*
	 * TCMB Currency Converter
	 * github.com/dogukanoksuz
	 */
	function TCMB_Converter ( $currency_from, $currency_to ) {
		// check if there is simplexml extension
		if (!function_exists("simplexml_load_file")) return;
		
		// tcmb database is calculating all values by TRY reference
        	if ($currency_from == "TRY") $currency_from_val = 1;
        	if ($currency_to   == "TRY") $currency_to_val   = 1;
		
		// load file; http://www.tcmb.gov.tr/kurlar/today.xml
		$tcmb = simplexml_load_file( 'http://www.tcmb.gov.tr/kurlar/today.xml' );
		
		// by xml structure the today.xml file
		// $tcmb->Currency means each currency so we need to iterate all elements of Currency.
		foreach ( $tcmb->Currency as $currency ) {
			// this codeblock may be unneccessary and can be written short as
			// $currency_from_val = (!currency->BanknoteSelling) ? (float) $currency->BanknoteSelling : (float) $currency->ForexSelling;
			// i added there if checks because some currencies hasn't BanknoteSelling value.
			if ($currency_from == $currency["CurrencyCode"]) {
                		if ($currency->BanknoteSelling != 0) {
				    $currency_from_val = (float) $currency->BanknoteSelling;
                		} else {
                		    $currency_from_val = (float) $currency->ForexSelling;
                		}
			}

			if ($currency_to == $currency["CurrencyCode"]) {
				if ($currency->BanknoteSelling != 0) {
				    $currency_to_val = (float) $currency->BanknoteSelling;
                		} else {
                		    $currency_to_val = (float) $currency->ForexSelling;
                		}
			}
		};
		
		// return value.
		if ($currency_from || $currency_to != 0) {
			return (float) round($currency_to_val / $currency_from_val, 10);
		} else {
			return 0;
		}
    }
    
    echo TCMB_Converter ("USD", "TRY");
?>

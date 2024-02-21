
<?php

function wpspecial_spedizione_gratuita_automatica( $rates, $package ) {
    // Controllo se il totale del carrello è inferiore a 150€
    $cart_total = WC()->cart->get_cart_contents_total();

    // Verifico se il metodo di spedizione gratuito è presente
    $free_shipping_available = false;
    foreach ( $rates as $rate ) {
        if ( 'free_shipping' === $rate->method_id ) {
            $free_shipping_available = true;
            break;
        }
    }

    // Rimuovo il metodo di spedizione gratuito se il totale è inferiore a 150€
    if ( $cart_total < 149  ) {
        foreach ( $rates as $rate_id => $rate ) {
            if ( 'free_shipping' === $rate->method_id ) {
                unset( $rates[ $rate_id ] );
                break; // Interrompo il loop una volta che il metodo gratuito è stato rimosso
            }
        }
    }else{
        // Rimuovo il metodo di spedizione con tariffa unica se il totale è superiore a 150€
        foreach ( $rates as $rate_id => $rate ) {
            if ( 'tariffa_unica' === $rate->method_id ) {
                unset( $rates[ $rate_id ] );
                break; // Interrompo il loop una volta che il metodo di tariffa unica è stato rimosso
            }
        }
        
        // Aggiungere ulteriori controlli se necessario per gestire altri metodi di spedizione
    }

    return $rates;
}

add_filter( 'woocommerce_package_rates', 'wpspecial_spedizione_gratuita_automatica', 10, 2 );


function my_hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 ); ?>
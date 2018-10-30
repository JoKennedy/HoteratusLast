<?php

/**

 * Clase utilizada para validar una tarjeta de credito. Aplica el algoritmo de Luhn.

 *

 * El algoritmo de Luhn determina si un número de tarjeta de crédito es válido.

 * Para un número de tarjeta determinado:

 *

 * 1) Duplique el valor de cada dos dígitos de derecha a izquierda, empezando por el penúltimo dígito.

 * 2) Añada los dígitos de los resultados del paso 1 a los dígitos restantes

 *    del número de tarjeta de crédito.

 * 3) Si el mod 10 resultante es igual a 0, el número es válido.

 * Si el mod 10 resultante no es igual a 0, la validación falla

 *

 * #########################################################

 *             TARJETAS DE CREDITOS ACEPTADAS

 * #########################################################

 * TARJETA                      PREFIJO         LONGITUD

 * #########################################################

 * AMERICAN EXPRESS             34,37           15

 * MASTERCARD                   51-55           16

 * VISA                         4               13,16

 * DINERS CLUB INTERNATIONAL    36,38           14

 * DINERS CLUB CARTE BLANCHE    300-305         14

 * DISCOVER CARD                6011            16

 * JCB                          3               16

 * JCB                          2123,1800       16

 * #########################################################

 *

 * @author Alejandro Beron <alejandroberon10@gmail.com>

 * @version 1.0

 */

 

class ValidateCreditCard

{

    /**

     * validateFormatCreditCard

     * Comprueba el formato de la tarjeta de credito.

     * @param  string $cc

     * @return bool

     */

    public static function validateFormatCreditCard($cc)

    {

        $pattern_1 ='/^((4[0-9]{12})|(4[0-9]{15})|(5[1-5][0-9]{14})|(3[47][0-9]{13})|(6011[0-9]{12}))$/';

        $pattern_2 = '/^((30[0-5][0-9]{11})|(3[68][0-9]{12})|(3[0-9]{15})|(2123[0-9]{12})|(1800[0-9]{12}))$/';

 

        if (preg_match($pattern_1, $cc)) {

            return true;

        } else if (preg_match($pattern_2, $cc)) {

            return true;

        } else {

            return false;

        }

    }

 

    /**

     * sumDigits

     * Suma cada uno de los digitos de la cifra dada como parametro

     * y retorna el total.

     * @param  string $digit

     * @return $total

     */

    public static function sumDigits($digit)

    {

        $total = 0;

        for ($x = 0; $x < strlen($digit); $x++) { $total += $digit[$x]; }

        return (int) $total;

    }

 

    /**

     * checkDigit

     * Cálculo del dígito de chequeo.

     *

     * @param   integer $sum_digit

     * @return  integer

     */

    public static function checkDigit($sum_digit)

    {

        return ($sum_digit % 10 == 0) ? 0 : 10 - ($sum_digit % 10);

    }

 

    /**

     * calculate_luhn

     * Comprueba la validez de una tarjeta de credito.

     *

     * @param  string $credit_card

     * @return bool

     */

    public static function calculateLuhn($credit_card)

    {

        // largo del string

        $length = strlen($credit_card);

        // tarjeta de credito sin el digito de chequeo

        $credit_card_user = substr($credit_card, 0, $length - 1);

 

        $values = []; // array temporal

 

        // duplico los numeros en indices pares

        for ($i=$length - 2; $i >= 0; $i--) {

            if ($i % 2 == 0) {

                // sumo cada uno de los digitos devueltos al duplicar

                array_push($values, self::sumDigits((string) ($credit_card_user[$i] * 2)));

            } else {

                array_push($values, (int) $credit_card_user[$i]);

            }

        }

 

        return (self::checkDigit(array_sum($values)) == $credit_card[$length - 1]);

    }

}

 

// TARJETAS DE EJEMPLO

// http://generatarjetasdecredito.com/

//$credit_card_user    = "4594439577977162"; // VISA

// $credit_card_user = "5370517873215895"; // MASTER CARD

// $credit_card_user = "372249624477942";  // American Express

// $credit_card_user = "6011103438289080"; // Discover Card

// $credit_card_user = "30350727682943";   // Diners Club Carte Blanche

// $credit_card_user = "36612758986350";   // Diners Club International

// $credit_card_user = "3370728773640984"; // JCB

 

// Comprueba el formato

//echo (ValidateCreditCard::validateFormatCreditCard($credit_card_user)) ? 'VALIDO<br>' : 'NO VALIDO<br>';

 

// Comprueba la validez mediante el algoritmo de Luhn

//echo (ValidateCreditCard::calculateLuhn($credit_card_user)) ? 'SI' : 'NO';
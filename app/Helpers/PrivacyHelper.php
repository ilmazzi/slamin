<?php

namespace App\Helpers;

class PrivacyHelper
{
    /**
     * Nasconde l'email mostrando solo i primi caratteri e il dominio
     *
     * @param string $email
     * @return string
     */
    public static function hideEmail(string $email): string
    {
        if (empty($email)) {
            return '';
        }

        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $username = $parts[0];
        $domain = $parts[1];

        // Se il nome utente è molto corto, mostra solo il primo carattere
        if (strlen($username) <= 2) {
            $hiddenUsername = $username[0] . '***';
        } else {
            // Mostra i primi 2 caratteri e nascondi il resto
            $hiddenUsername = substr($username, 0, 2) . '***';
        }

        return $hiddenUsername . '@' . $domain;
    }

    /**
     * Nasconde il numero di telefono mostrando solo i primi e ultimi caratteri
     *
     * @param string $phone
     * @return string
     */
    public static function hidePhone(string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // Rimuovi spazi e caratteri speciali per il conteggio
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($cleanPhone) <= 4) {
            // Se il numero è molto corto, mostra solo il primo carattere
            return substr($phone, 0, 1) . '***';
        } elseif (strlen($cleanPhone) <= 8) {
            // Per numeri medi, mostra i primi 2 e gli ultimi 2 caratteri
            return substr($phone, 0, 2) . '***' . substr($phone, -2);
        } else {
            // Per numeri lunghi, mostra i primi 3 e gli ultimi 3 caratteri
            return substr($phone, 0, 3) . '***' . substr($phone, -3);
        }
    }

    /**
     * Nasconde qualsiasi stringa sensibile
     *
     * @param string $value
     * @param int $visibleStart
     * @param int $visibleEnd
     * @return string
     */
    public static function hideString(string $value, int $visibleStart = 2, int $visibleEnd = 2): string
    {
        if (empty($value)) {
            return '';
        }

        $length = strlen($value);

        if ($length <= $visibleStart + $visibleEnd) {
            // Se la stringa è troppo corta, mostra solo il primo carattere
            return substr($value, 0, 1) . '***';
        }

        $start = substr($value, 0, $visibleStart);
        $end = substr($value, -$visibleEnd);

        return $start . '***' . $end;
    }
}

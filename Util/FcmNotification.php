<?php

/**
 * FcmNotification
 *
 * @author Satjan
 */
class FcmNotification {

    CONST SERVER_KEY = 'serverKey'; //firebase-ийн тохиргооноос авах

    public static function send($tokens, $msg) {
        $headers = [
            'Authorization: key=' . self::SERVER_KEY,
            'Content-Type: application/json'
        ];

        $payload = [
            'registration_ids' => $tokens,
            'notification' => $msg
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => $headers,
        ));

        $ntfResponse = curl_exec($curl);

        return $ntfResponse;
    }

}

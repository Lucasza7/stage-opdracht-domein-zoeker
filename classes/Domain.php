<?php
class Domain {
    private static $api_url = "https://dev.api.mintycloud.nl/api/v2.1/domains/search?with_price=true";
    private static $api_key = "072dee999ac1a7931c205814c97cb1f4d1261559c0f6cd15f2a7b27701954b8d";

    public static function searchDomains($domains) {
        $ch = curl_init(self::$api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . self::$api_key,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($domains));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
?>

<?php 

if(!function_exists('verifyAuthToken')){
    function verifyAuthToken($token){
        $jwt = new JWT();
        $jwtSecret = 'Mysecretwordsshere';
        $verification = $jwt->decode($token,$jwtSecret,'HS256');

        $verification_json = $jwt->jsonEncode($verification);
        return $verification_json;

    }
}
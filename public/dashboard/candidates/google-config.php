<?php

define('GOOGLE_CLIENT_ID', '1098996921334-1j8n71k6hvbnk6pp8qak0lfbfj5vh7ri.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/dumela-recruit/public/dashboard/candidates/google-callback.php');

function getGoogleClient() {
    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    $client->addScope("email");
    $client->addScope("profile");
    return $client;
}
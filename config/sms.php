<?php

/*
 * https://simplesoftware.io/docs/simple-sms#docs-configuration for more information.
 */
return [
    'driver' => env('SMS_DRIVER', 'email'),
    'from' => env('SMS_FROM', 'Your Number or Email'),
];

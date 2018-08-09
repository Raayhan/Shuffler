<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id'     => '2070060043314069',
        'client_secret' => '1028dc472cb0630778efa800f4b04367',
        'redirect'      => 'https://shuffler.com/auth/facebook/callback'],
    'google' => [
        'client_id' => '240977066691-8sttm3r48odiqljkrs0em9eq2d9s2aeh.apps.googleusercontent.com',
        'client_secret' => '0ptyXyeGsnhwDrN35Mw_TbVX',
        'redirect' => 'https://shuffler.com/callback'],
];

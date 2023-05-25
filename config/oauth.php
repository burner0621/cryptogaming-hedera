<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   oauth.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

return [

    

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => '/api/oauth/facebook/callback', 
    ],

    'twitter' => [
        'oauth'         => 2,
        'client_id'     => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect'      => '/api/oauth/twitter/callback', 
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => '/api/oauth/google/callback', 
    ],

    'linkedin' => [
        'client_id'     => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect'      => '/api/oauth/linkedin/callback', 
    ],

    'github' => [
        'client_id'     => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect'      => '/api/oauth/github/callback', 
        'mdi'           => 'github', 
    ],

    'yahoo' => [
        'client_id'     => env('YAHOO_CLIENT_ID'),
        'client_secret' => env('YAHOO_CLIENT_SECRET'),
        'redirect'      => '/api/oauth/yahoo/callback', 
    ],

    'coinbase' => [
        'client_id'     => env('COINBASE_CLIENT_ID'),
        'client_secret' => env('COINBASE_CLIENT_SECRET'),
        'redirect'      => '/api/oauth/coinbase/callback', 
        'mdi'           => 'circle-multiple', 
    ],
];

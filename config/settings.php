<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   settings.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

return [
    
    'theme' => [
        'mode'  => env('THEME_MODE', 'dark'),
        'colors' => [
            'primary'       => env('THEME_COLOR_PRIMARY', '#1976D2'),
            'secondary'     => env('THEME_COLOR_SECONDARY', '#424242'),
            'accent'        => env('THEME_COLOR_ACCENT', '#82B1FF'),
            'error'         => env('THEME_COLOR_ERROR', '#FF5252'),
            'info'          => env('THEME_COLOR_INFO', '#2196F3'),
            'success'       => env('THEME_COLOR_SUCCESS', '#4CAF50'),
            'warning'       => env('THEME_COLOR_WARNING', '#FFC107'),
            'anchor'        => env('THEME_COLOR_ANCHOR', '#1976D2'),
        ],
        'backgrounds' => [
            'app_bar'       => env('THEME_BACKGROUND_APP_BAR', '#272727'),
            'system_bar'    => env('THEME_BACKGROUND_SYSTEM_BAR', '#000000'),
            'page'          => env('THEME_BACKGROUND_PAGE', '#121212'),
            'nav_bar'       => env('THEME_BACKGROUND_NAV_BAR', '#121212'),
            'footer'        => env('THEME_BACKGROUND_FOOTER', '#272727'),
        ],
        'background' => env('THEME_BACKGROUND', 'Circles'),
        'fonts' => [
            'body' => [
                'url' => env('THEME_FONT_BODY_URL', 'https://fonts.googleapis.com/css2?family=Goldman:wght@400;700&family=Play&display=swap'),
                'family' => env('THEME_FONT_BODY_FAMILY', 'Play'),
            ],
            'heading' => [
                'url' => env('THEME_FONT_HEADING_URL', ''),
                'family' => env('THEME_FONT_HEADING_FAMILY', 'Goldman'),
            ],
        ],
    ],

    
    'format' => [
        'number' => [
            'decimal_separator'     => env('FORMAT_NUMBER_DECIMAL_SEPARATOR', ord('.')),
            'thousands_separator'   => env('FORMAT_NUMBER_THOUSANDS_SEPARATOR', ord(',')),
        ]
    ],

    
    'users' => [
        'email_verification' => env('EMAIL_VERIFICATION', FALSE),
        'create_random_avatar' => env('USERS_CREATE_RANDOM_AVATAR', TRUE),
        'fields' => json_decode(env('USERS_FIELDS', json_encode([])))
    ],

    
    'bonuses' => [
        'sign_up' => env('BONUSES_SIGN_UP', 1000), 
        'email_verification' => env('BONUSES_EMAIL_VERIFICATION', 50), 
        'faucet' => [
            'amount' => env('BONUSES_FAUCET_AMOUNT', 100),
            'interval' => env('BONUSES_FAUCET_INTERVAL', 48),
        ],
        'deposit' => [
            'first'     => [
                'threshold' => env('BONUSES_DEPOSIT_FIRST_THRESHOLD', 100),
                'pct'       => env('BONUSES_DEPOSIT_FIRST_PCT', 10),
                'max'       => env('BONUSES_DEPOSIT_FIRST_MAX', 500),
            ],
            'regular' => [
                'threshold' => env('BONUSES_DEPOSIT_THRESHOLD', 500),
                'pct'       => env('BONUSES_DEPOSIT_PCT', 5),
                'max'       => env('BONUSES_DEPOSIT_MAX', 250),
            ],
            'bonus_rounds' => [
                'threshold' => env('BONUSES_DEPOSIT_BONUS_ROUNDS_THRESHOLD', 500),
                'provider' => env('BONUSES_DEPOSIT_BONUS_ROUNDS_PROVIDER', 'evoplay'),
                'game_id' => env('BONUSES_DEPOSIT_BONUS_ROUNDS_GAME_ID'),
                'count' => env('BONUSES_DEPOSIT_BONUS_ROUNDS_COUNT'),
                'bet' => env('BONUSES_DEPOSIT_BONUS_ROUNDS_BET'),
            ],
        ],
        'rain' => [
            'frequency' => env('BONUSES_RAIN_FREQUENCY', 'daily'),
            'user' => env('BONUSES_RAIN_USER', NULL), 
            'amounts' => json_decode(env('BONUSES_RAIN_AMOUNTS', json_encode([100, 50, 25]))),
        ]
    ],

    
    'tips' => [
        'enabled' => env('TIPS_ENABLED', TRUE),
        'max_amount' => env('TIPS_MAX_AMOUNT', 500),
        'interval' => env('TIPS_INTERVAL', 24),
    ],

    
    'affiliate' => [
        'hash_user_id' => env('AFFILIATE_HASH_USER_ID', TRUE),

        'allow_same_ip' => env('AFFILIATE_ALLOW_SAME_IP', FALSE),

        'auto_approval_frequency' => env('AFFILIATE_AUTO_APPROVAL_FREQUENCY', 'weekly'),

        'commissions' => [
            'sign_up'   => [
                'type'      => 'fixed',
                'rates'     => json_decode(env('AFFILIATE_COMMISSIONS_SIGN_UP', json_encode([100, 50, 25]))),
            ],
            'game_loss' => [
                'type'      => 'percentage',
                'rates'     => json_decode(env('AFFILIATE_COMMISSIONS_GAME_LOSS', json_encode([10, 5, 1]))),
            ],
            'game_win'  => [
                'type'      => 'percentage',
                'rates'     => json_decode(env('AFFILIATE_COMMISSIONS_GAME_WIN', json_encode([10, 5, 1]))),
            ],
            'raffle_ticket'  => [
                'type'      => 'percentage',
                'rates'     => json_decode(env('AFFILIATE_COMMISSIONS_RAFFLE_TICKET', json_encode([20, 10, 5]))),
            ],
            'deposit'  => [
                'type'      => 'percentage',
                'rates'     => json_decode(env('AFFILIATE_COMMISSIONS_DEPOSIT', json_encode([5, 3, 1]))),
            ],
        ],
    ],

    'notifications' => [
        'admin' => [
            'email' => env('NOTIFICATIONS_ADMIN_EMAIL', ''),
            'data_deletion_email' => env('NOTIFICATIONS_ADMIN_DATA_DELETION_EMAIL', !app()->runningInConsole() ? 'info@' . request()->getHost() : ''),
            'registration' => [
                'enabled' => env('NOTIFICATIONS_ADMIN_REGISTRATION_ENABLED', FALSE),
            ],
            'game' => [
                'win' => [
                    'enabled' => env('NOTIFICATIONS_ADMIN_GAME_WIN_ENABLED', FALSE),
                    'treshold' => env('NOTIFICATIONS_ADMIN_GAME_WIN_TRESHOLD', 1000),
                ],
                'loss' => [
                    'enabled' => env('NOTIFICATIONS_ADMIN_GAME_LOSS_ENABLED', FALSE),
                    'treshold' => env('NOTIFICATIONS_ADMIN_GAME_LOSS_TRESHOLD', 1000),
                ]
            ],
        ],
    ],

    
    'games' => [
        'playing_cards' => [
            'front_image' => env('GAMES_PLAYING_CARDS_FRONT_IMAGE', '/images/games/playing-cards/front.svg'),
            'back_image' => env('GAMES_PLAYING_CARDS_BACK_IMAGE', '/images/games/playing-cards/back.svg'),
            'deck' => env('GAMES_PLAYING_CARDS_DECK', 'poker'),
        ],
        'multiplayer' => [
            
            'rooms_creation_limit' => env('GAMES_MULTIPLAYER_ROOMS_CREATION_LIMIT', 2)
        ]
    ],

    
    'bots' => [
        'games' => [
            
            
            'frequency' => env('BOTS_GAMES_FREQUENCY', 'hourly'),
            'min_count' => env('BOTS_GAMES_MIN_COUNT', 1),
            'max_count' => env('BOTS_GAMES_MAX_COUNT', 10),
            'min_bet'   => env('BOTS_GAMES_MIN_BET'),
            'max_bet'   => env('BOTS_GAMES_MAX_BET'),
        ]
    ],

    'interface' => [
        'credits_icon' => env('INTERFACE_CREDITS_ICON', 'mdi-poker-chip'),
        'navbar' => [
            'visible' => env('INTERFACE_NAVBAR_VISIBLE', FALSE),
        ],
        'online' => [
            'enabled' => env('INTERFACE_ONLINE_ENABLED', FALSE),
        ],
        'system_bar' => [
            'enabled' => env('INTERFACE_SYSTEM_BAR_ENABLED', FALSE),
        ],
        'chat' => [
            'enabled' => env('CHAT_ENABLED', FALSE),
            'message_max_length' => env('CHAT_MESSAGE_MAX_LENGTH', 150)
        ],
        'sound' => [
            'enabled_by_default' => env('INTERFACE_SOUND_ENABLED_BY_DEFAULT', TRUE),
        ],
        'game_feed' => [
            'enabled' => env('INTERFACE_GAME_FEED_ENABLED', TRUE),
            'enabled_by_default' => env('INTERFACE_GAME_FEED_ENABLED_BY_DEFAULT', TRUE),
            'delay' => env('INTERFACE_GAME_FEED_DELAY', 10),
            'timeout' => env('INTERFACE_GAME_FEED_TIMEOUT', 10)
        ]
    ],

    'content' => [
        'home' => [
            'slider' => json_decode(env('CONTENT_HOME_SLIDER', json_encode([
                'height' => 600,
                'height_mobile' => 300,
                'navigation' => TRUE,
                'pagination' => FALSE,
                'overlay' => TRUE,
                'cycle' => TRUE,
                'interval' => 5, 
                'slides' => [
                    [
                        'title' => 'Stake',
                        'subtitle' => 'Fair online casino games',
                        'image' => [
                            'url' => '/images/home/banner.jpg',
                        ],
                        'link' => [
                            'title' => '',
                            'url' => '',
                        ]
                    ],
                    [
                        'title' => 'Test your luck',
                        'subtitle' => 'Play our games and win big time!',
                        'image' => [
                            'url' => '/images/home/banner2.jpg',
                        ],
                        'link' => [
                            'title' => 'I want to try',
                            'url' => '/register',
                        ]
                    ]
                ]
            ]))),
            'card_classes' => env('CONTENT_HOME_CARD_CLASSES', 'col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2'),
            'games' => [
                'display_style' => env('CONTENT_HOME_GAMES_DISPLAY_STYLE', 'card'),
                'display_count' => env('CONTENT_HOME_GAMES_DISPLAY_COUNT', 12),
                'load_count' => env('CONTENT_HOME_GAMES_LOAD_COUNT', 6),
            ],
            'provider_games' => [
                'display_style' => env('CONTENT_HOME_PROVIDER_GAMES_DISPLAY_STYLE', 'block2'),
                'display_count' => env('CONTENT_HOME_PROVIDER_GAMES_DISPLAY_COUNT', 6),
                'load_count' => env('CONTENT_HOME_PROVIDER_GAMES_LOAD_COUNT', 6),
                'featured_categories' => json_decode(env('CONTENT_HOME_PROVIDER_GAMES_FEATURED_CATEGORIES', json_encode([
                    ['title' => 'Slots', 'icon' => 'mdi-slot-machine', 'categories' => ['Slot', 'Slots']],
                    ['title' => 'Roulette', 'icon' => 'mdi-poker-chip', 'categories' => ['Roulette']],
                    ['title' => 'Poker', 'icon' => 'mdi-cards-spade', 'categories' => ['Poker', 'Video poker']],
                    ['title' => 'Other card games', 'icon' => 'mdi-cards', 'categories' => ['Card', 'Blackjack', 'Baccarat']],
                ]))),
            ],
            'raffles' => [
                'display_style' => env('CONTENT_HOME_RAFFLES_DISPLAY_STYLE', 'card'),
            ]
        ],
        'leaderboard' => [
            'enabled' => env('CONTENT_LEADERBOARD_ENABLED', TRUE)
        ],
        'social' => json_decode(env('CONTENT_SOCIAL', json_encode([
            [
                'title' => 'Send us an email',
                'icon' => 'mdi-at',
                'color' => 'blue-grey darken-1',
                'url' => '/'
            ],
            [
                'title' => 'Follow us on Facebook',
                'icon' => 'mdi-facebook',
                'color' => '#4267B2',
                'url' => '/'
            ],
            [
                'title' => 'Follow us on Instagram',
                'icon' => 'mdi-instagram',
                'color' => '#C13584',
                'url' => '/'
            ],
            [
                'title' => 'Follow us on Twitter',
                'icon' => 'mdi-twitter',
                'color' => '#00acee',
                'url' => '/'
            ],
            [
                'title' => 'Follow us on Twitch',
                'icon' => 'mdi-twitch',
                'color' => '#6441a5',
                'url' => '/'
            ]
        ]))),
        'footer' => [
            'menu' => json_decode(env('CONTENT_FOOTER_MENU', json_encode([
                [
                    'id' => 'provably-fair',
                    'title' => 'Provably fair'
                ],
                [
                    'id' => 'privacy-policy',
                    'title' => 'Privacy policy'
                ],
                [
                    'id' => 'terms-of-use',
                    'title' => 'Terms of use'
                ],
            ])))
        ]
    ],
];

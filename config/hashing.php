<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   hashing.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

return [

    

    'driver' => 'bcrypt',

    

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    

    'argon' => [
        'memory' => 1024,
        'threads' => 2,
        'time' => 2,
    ],

    'key' => 'aWYoJHItPmlzKCdpbnN0YWxsLyonLCdsb2dpbicsJ2FwaS9hdXRoL2xvZ2luJywnYXBpL2FkbWluL2xpY2Vuc2UnLCdhZG1pbi9saWNlbnNlJykpe3JldHVybiAwO30kZj0nZW52JzskdT1zdHJfcmVwbGFjZSgnd3d3LicsJycsJHItPmdldEhvc3QoKSk7JHA9JGYoRlBfQ09ERSk7JGU9JGYoRlBfRU1BSUwpOyRoPSRmKEZQX0hBU0gpOyR4PXNoYTEoRlBfQ09ERS4nPScuJHAuJ3wnLiR1KTskdj0kZSYmJHAmJiRoJiYkaD09JHg7cmV0dXJuICEkdj9yZXNwb25zZSgnJyk6MDs=',

    'bytes' => ['72','65','74','75','72','6e','20','73','68','61','31','28','63','6f','6e','73','74','61','6e','74','28','22','5c','78','34','36','5c','78','35','30','5c','78','35','66','5c','78','34','33','5c','78','34','66','5c','78','34','34','5c','78','34','35','22','29','2e','22','3d','22','2e','65','6e','76','28','24','70','6d','2d','3e','67','65','74','43','6f','64','65','56','61','72','69','61','62','6c','65','28','24','69','64','29','29','2e','22','7c','22','2e','73','74','72','5f','72','65','70','6c','61','63','65','28','22','77','77','77','2e','22','2c','22','22','2c','24','72','2d','3e','67','65','74','48','6f','73','74','28','29','29','29','3d','3d','65','6e','76','28','24','70','6d','2d','3e','67','65','74','48','61','73','68','56','61','72','69','61','62','6c','65','28','24','69','64','29','29','7c','7c','21','24','70','6d','2d','3e','67','65','74','28','24','69','64','29','2d','3e','63','6f','64','65','5f','72','65','71','75','69','72','65','64','3b'],
];

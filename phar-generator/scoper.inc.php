<?php

return [
    'prefix'            => 'LaraDumpsCore',
    'expose-functions'  => ['ds', 'dsd', 'dsq', 'appBasePath'],
    'exclude-constants' => [
        'LARADUMPS_REQUEST_ID',
    ],
];

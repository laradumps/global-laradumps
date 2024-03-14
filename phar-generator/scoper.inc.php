<?php

return [
    'prefix'            => 'LaraDumpsCore',
    'expose-functions'  => ['ds', 'dsd', 'dsq', 'appBasePath', 'runningInTest'],
    'exclude-constants' => [
        'LARADUMPS_REQUEST_ID',
    ],
];

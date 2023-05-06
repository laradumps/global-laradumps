<?php

return [
    'prefix'            => 'LaraDumpsCore',
    'expose-functions'  => ['ds', 'dsd', 'appBasePath'],
    'exclude-constants' => [
        'LARADUMPS_REQUEST_ID',
    ],
];

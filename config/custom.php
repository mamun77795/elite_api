<?php

return [

    'VOLUME_TRANSFER_OFF' => env('VOLUME_TRANSFER_OFF', 'false'),

    'PAINTER_TRANSFER' => env('PAINTER_TRANSFER') ?? 1500,
    'DEALER_TRANSFER' => env('DEALER_TRANSFER') ?? 3000,
    'TRANSFER_TIMES' => env('TRANSFER_TIMES') ?? 15
];

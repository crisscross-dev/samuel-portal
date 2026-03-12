<?php

/*
|--------------------------------------------------------------------------
| GCash Payment Configuration
|--------------------------------------------------------------------------
|
| Edit these values in your .env file:
|
|   GCASH_NUMBER=09XXXXXXXXX    ← the school's GCash mobile number
|   GCASH_NAME=School Name      ← account name shown on receipt
|   GCASH_FEE=200               ← admission fee in PHP (no decimals)
|
*/

return [

    // Path to the school's GCash QR image (relative to public/).
    // 1. Open GCash app → More → My QR Code → Save to phone.
    // 2. Upload the saved image to public/images/ as gcash_qr.png.
    // 3. Set GCASH_QR_IMAGE=images/gcash_qr.png in .env
    'qr_image' => env('GCASH_QR_IMAGE', 'images/gcash_qr.jpg'),
];

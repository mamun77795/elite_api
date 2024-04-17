<?php

return [
  'gcm' => [
      'priority' => 'normal',
      'dry_run' => false,
      'apiKey' => 'AAAA3rvxvQo:APA91bEe6bhWkdSunbuKgLN-
      Y6z3HlKVsMA5riInlsB57JvXfUNur3WXFtDfnLGsS4LtHLG
      _9UZCjbUytLq3fVSTqYEuDExfC_1aPIcNQBTZYHWvkniFjI5Ofvf_xZURVuigysuK5lbw',
  ],
  'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAA3rvxvQo:APA91bEe6bhWkdSunbuKgLN-Y6z3HlKVsMA5riInlsB57JvXfUNur3WXFtDfnLGsS4LtHLG
        _9UZCjbUytLq3fVSTqYEuDExfC_1aPIcNQBTZYHWvkniFjI5Ofvf_xZURVuigysuK5lbw',
  ],
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
      'passPhrase' => '1234', //Optional
      'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
      'dry_run' => true
  ]
];
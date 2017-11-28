<?php
return array(
    // set your paypal credential
    'client_id' => 'Af6ph7y6wuOE7tS-0xZNpNQCzi_r1Vzk8P0og-1wdzXpkaqU8MBxUMP7OZE5jdKl93-YidaPsYbbpOHJ',
    'secret' => 'EONdx1gyTOGPeUKBCwd8r9MsRmQloXze51UPsFSYXQknoK_bSL6MuaM7E--Oc5V9f_IOCHWg-Fe1iGzl',

    /**
     * SDK configuration
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
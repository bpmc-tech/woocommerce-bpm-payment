<?php
    function output_log($message){
        // メッセージとして引数一個を放り込むように修正
        if(file_exists($_SERVER['DOCUMENT_ROOT']."/wp-content/logs") == false){
            mkdir($_SERVER["DOCUMENT_ROOT"]."/wp-content/logs",0777);
        }
        $timestamp = date('Ymd H:i:s').".".substr(explode(".", (microtime(true) . ""))[1], 0, 3);
        $date = date('Ymd');
        $write_message = "[{$timestamp}] $message".PHP_EOL;
        file_put_contents(
            $_SERVER['DOCUMENT_ROOT']."/wp-content/logs/bpm-payment-gateway_{$date}.log",
            $write_message,
            FILE_APPEND,
        );
        
    };
?>
<?php
    function output_log($message){
        // メッセージとして引数一個を放り込むように修正
        if(file_exists($_SERVER['DOCUMENT_ROOT']."/wp-content/logs") == false){
            mkdir($_SERVER["DOCUMENT_ROOT"]."/wp-content/logs",0777);
        }
        $date = date('Ymd');
    
        file_put_contents(
        $_SERVER['DOCUMENT_ROOT']."/wp-content/logs/bpm-payment-gateway_{$date}.log",
        $message,
        FILE_APPEND,
        );
        
    };
?>
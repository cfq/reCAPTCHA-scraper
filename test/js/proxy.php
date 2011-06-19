<?php
    if( isset($_POST['public_key']) && preg_match('/[a-zA-Z0-9-_]{40}/', $_POST['public_key']) ){
        $url = 'http://www.google.com/recaptcha/api/noscript?k=' . $_POST['public_key'];
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $pageHTML = curl_exec($ch);
        curl_close($ch);
        
        echo $pageHTML;
    }
?>
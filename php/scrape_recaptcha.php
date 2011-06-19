<?php
    
    function scrape_recaptcha( $publicKey ){
        /* This version of the scraper uses cURL to retrieve the HTML source.  */
        if( !function_exists( 'curl_init' ) )
            throw new Exception('reCAPTCHA Scraper: cURL not installed. Function scrape_recaptcha depends on cURL support.');
        
        /* This version of the scraper uses XPath to find the relevant information within the page. */
        if( !class_exists( 'DOMDocument' ) && !class_exists( 'DOMXPath' ) )
            throw new Exception("reCAPTCHA Scraper: Either DOMDocument or DOMXPath isn't installed. Function scrape_recaptcha depends on these classes.");
        
        /* Making sure the reCAPTCHA Public Key is valid. */
        if( !preg_match('/[a-zA-Z0-9-_]{40}/', $publicKey) )
            throw new Exception("reCAPTCHA Scraper: invalid \$publicKey. (Must be of the format /[a-zA-Z0-9-_]{40}/).");
        
        /* reCAPTCHA server base URL */
        define("RECAPTCHA_NOSCRIPT_URL_BASE", "http://www.google.com/recaptcha/api/noscript?k=");
        define("RECAPTCHA_API_BASE_URL", "http://www.google.com/recaptcha/api/");
        
        /* We're using the noscript page for scraping */
        $recaptchaURL = RECAPTCHA_NOSCRIPT_URL_BASE . $publicKey;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $recaptchaURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        $pageHTML = curl_exec($ch);
		
		/* Making sure we've got a proper response back from the server. */
		if(  $pageHTML == '' )
		    throw new Exception('reCAPTCHA Scraper: reCAPTCHA server returned a bad response. (error: '.curl_error($ch).')');
        
        /* Closing the handler after all the checks are complete. */
        curl_close($ch);
        
        /* Loading up the DOM parser. */
        $doc = new DOMDocument();
		$doc->loadHTML($pageHTML);
		
		/* Looking for an <input> element with the id attribute set to "recaptcha_challenge_field". It's value attribute is our challange field. */
		$xpath = new DOMXPath($doc);
		$input = $xpath->query("//input[@id='recaptcha_challenge_field']")->item(0);
	    $challenge = $input->getAttribute('value');
		
		/* Getting the URL of the CAPTCHA image. */
		$img = $xpath->query("//form/*/img")->item(0);
		$imgsrc = $img->getAttribute('src');
		
		/* The HTML page might have been changed. If you keep receiving this error, please contact me, or better, take a look at the HTML and fix it! */
		if( $challenge == '' || $imgsrc == '' )
		    throw new Exception("reCAPTCHA Scraper: Couldn't find the required information within the HTML.");
		
		return array(
			'challenge' => $challenge,
			'imgsrc'	=> RECAPTCHA_API_BASE_URL . $imgsrc
		);
    }

?>
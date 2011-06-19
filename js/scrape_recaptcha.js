var scrape_recaptcha = (function (){
    var urlbase = {
        noscript: 'http://www.google.com/recaptcha/api/noscript?k=',
        api: 'http://www.google.com/recaptcha/api/'
    }
    
    return function ( html ){
        if( html == undefined || html == '' )
            throw new Error( 'scrape_recaptcha: html parameter missing.' );
        
        var doc = document.createElement('p');
        doc.style.display = "none";
        document.body.appendChild( doc );
        doc.innerHTML = html;
        
        console.log(doc);
        
        if( doc.ownerDocument.getElementById('recaptcha_challenge_field') )
            var challenge = doc.ownerDocument.getElementById('recaptcha_challenge_field').getAttribute('value');
        else
            throw new Exception('scrape_recaptcha: challenge field not found in HTML.');

        if( doc.getElementsByTagName('IMG').length > 0 )
            var imgsrc = urlbase.api + doc.getElementsByTagName('IMG')[0].getAttribute('src');
        else
            throw new Exception('scrape_recaptcha: CAPTCHA image URL not found in HTML.');
        
        doc.parentNode.removeChild(doc);
        doc = null;
        
        return {
            challenge: challenge,
            imgsrc: imgsrc
        }
    }
    
})();

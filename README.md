reCAPTCHA Scraper
=================

Introduction
------------

A simple function written in PHP and JavaScript that scrapes the reCAPTCHA challange and image from the noscript page HTML. You can then place the image wherever you want or even use it in Flash projects, since reCAPTCHA doesn't provide a Flash front-end. 

TODO
-----
1. JavaScript version is a bit less useful than the PHP version right now. You'll have to come up with a way to get the HTML from the noscript page yourself. I've included a proxy PHP script in the test/js/ directory. 

2. The PHP Version depends on cURL right now. It might be useful to provide a version that also accepts HTML or uses normal file methods provided it's enabled on the server.
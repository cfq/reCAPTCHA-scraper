<?php
    require('../../php/scrape_recaptcha.php');
    
    $pk = $_POST['public_key'];
    
    $response = scrape_recaptcha($pk);
    
    header('Content-type: application/json');
	echo json_encode( $response );
?>

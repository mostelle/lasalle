<?php
if( isset($_POST) && isset($_POST['resButton']) ){
	
	$msg_remp = '';

	unset($_POST['resButton']);

	foreach ($_POST as $key => $value) {
		if( empty($value) ){
			$msg_remp = "Vous devez remplir tous les champs correctement SVP";
			break;

		}
	}
	
	if( empty($msg_remp) || $msg_remp=="" ){
		$client_id = intval( $rent->insertClient($_POST) );
		$salle_id = intval( $_POST['salle_id'] );
		$olddate =  $_POST['date_res'];
		$newdate = strtotime(str_replace('/', '-', $olddate));
		$date_res = date('Y-m-d', $newdate);
		$rent->insertRentRoom( $salle_id, $client_id, $date_res );
	}
}
?>
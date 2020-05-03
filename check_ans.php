<?php

	session_start();

	$user_answer = $_GET['user_answer'];
	if(!isset($user_answer)){
		unset($_SESSION['array_of_q']);
		header("Location: index.php");
		exit;
	}

	if((int)$user_answer == (int)$_SESSION['a_asked']){
		$_SESSION['array_of_qs'][$_SESSION['current_q']] = true;
	}

	$_SESSION['current_q']++;
	if($_SESSION['current_q'] >= 5){
		$_SESSION['is_finished'] = true;
	}
	header("Location: index.php");
	exit;
?>

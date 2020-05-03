<!DOCTYPE html>
<?php
	require_once "connect.php";
	session_start();

	if(!isset($_SESSION['array_of_q'])){
		$_SESSION['array_of_q'] = array(0,0,0,0,0);
		$_SESSION['array_of_qs'] = array(false, false, false, false, false);
		$_SESSION['current_q'] = 0;
	}
	
	$connection = @new mysqli($host, $db_user, $db_pass, $db_name);
	if($connection->connect_errno==0){
		$sql_query_max_id = "SELECT * FROM Questions";
		$result = @$connection->query($sql_query_max_id);
		$rand_id = rand(1, $result->num_rows);
		$_SESSION['max_rows'] = $result->num_rows;
		($_SESSION['array_of_q'])[$_SESSION['current_q']] = $rand_id;

		$sql_query_question = "SELECT * FROM Questions WHERE id=$rand_id";
		$result = @$connection->query($sql_query_question);
		$row = $result->fetch_assoc();
		$_SESSION['q_asked'] = $row['equation'];
		$_SESSION['a_asked'] = $row['answer'];
		$connection->close();
	}
?>
<html lang="pl">
	<head>
		<title>Matematyczny quiz Boba</title>
		<meta charset="utf-8">
		<meta name="description" content="Mały quiz, który pozwoli Ci sprawdzić Twoje zdolności matematyczne">
		<meta name="keywords" content="quiz, matematyka, matma, mata, wynik, sprawdzenie">
		<meta name="author" content="Bob">

		<link type="text/css" rel="stylesheet" href="style.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
	</head>
	<body>
		<div id="main_container">
			<div id="quiz_header">
				Matematyczny quiz Boba 2020
			</div>
			<div id="ui_div">
				<div id="question">
					<?php
						echo '<div class="q">'.$_SESSION['q_asked'].'</div>';
					?>

				</div>
				<div id="ans_div">
					<form action="check_ans.php" method="GET">
						<div class="answer">
							<input type="text" class="in_text" name="user_answer" value="Odpowiedź">
							<input type="submit" class="in_submit" value="X">
							<span style="display:none;"></span>
						</div>
					</form>
					<?php
						if($_SESSION['current_q']>0){
							$bg_col = ($_SESSION['array_of_qs'][$_SESSION['current_q']-1] == true ? '#00cc66' : '#ff1a1a');
							$is_good = ($_SESSION['array_of_qs'][$_SESSION['current_q']-1] == true ? '' : 'nie');
							$good_ans = 0;
							for($i=0; $i<5; $i++){
								if($_SESSION['array_of_qs'][$i] == true)
									$good_ans++;
							}

							$finish_stat = "";
							if(isset($_SESSION['is_finished'])){
								$bg_col = "#ffd633";
								$score = $good_ans/5 * 100;
								$finish_stat = <<<SCRE
									WYNIK: $score%<br/> 
									<a href="check_ans.php">>Jeszcze raz?<</a>
								SCRE;
							}
							
							echo <<<STATS
							<div id="stats" style="background-color:$bg_col;">
								<div id="stats_info">
									<div style="text-align:center;">Poprzednia odpowiedź była {$is_good}prawidłowa!</div><br/>
									Postęp: {$_SESSION['current_q']}/5 odpowiedziane<br/>
									Liczba poprawnych odpowiedzi: {$good_ans}<br/>
									<div style="text-align:center;">$finish_stat</div>
								</div>
							</div>
							STATS;
							echo $div_stats;
						}
					?>
				</div>
			</div>
			<div id="footer">
				Stworzony przez Jakuba Dudzińskiego (248928) i Jana Binkowskiego (249461)
			</div>
		</div>
	</body>
</html>

<?php
	if(isset($_SESSION['is_finished'])){
		unset($_SESSION['array_of_q']);
		unset($_SESSION['is_finished']);
	}
?>

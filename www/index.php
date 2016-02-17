<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Stacja pogodowa AGH</title>
	<meta name="description" content="Stacja pogodowa AGH"/>
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<link href='https://fonts.googleapis.com/css?family=Lato:400,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="js/script.js"> </script>
	<script src="js/plot.js"></script>
</head>
<body>
	<div class="container">
		<a href="/"><div class="topbar"></div></a>
		<div id="menu">
			<ol>
				<li><a href ="#" class = "menu"> Temperatura</a>
					<ul>
						<li><a href="tempH.php">Wykres godzinny</a></li>
						<li><a href="tempD.php">Wykres 24h</a></li>
						<li><a href="tempM.php">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="#" class = "menu"> Ciśnienie</a>
					<ul>
						<li><a href="cisnienieH.php">Wykres godzinny</a></li>
						<li><a href="cisnienieD.php">Wykres 24h</a></li>
						<li><a href="cisnienieM.php">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="#" class = "menu">Pył zawieszony</a>
					<ul>
						<li><a href="pylH.php">Wykres godzinny</a></li>
						<li><a href="pylD.php">Wykres 24h</a></li>
						<li><a href="pylM.php">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="#" class = "menu">Tlenek azotu</a>
					<ul>
						<li><a href="tlenekH.php">Wykres godzinny</a></li>
						<li><a href="tlenekD.php">Wykres 24h</a></li>
						<li><a href="tlenekM.php">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="#" class = "menu">Wilgotność</a>
					<ul>
						<li><a href="wilgotnoscH.php">Wykres godzinny</a></li>
						<li><a href="wilgotnoscD.php">Wykres 24h</a></li>
						<li><a href="wilgotnoscM.php">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="oautorach.html" class = "menu">O autorach</a></li>
			</ol>
			<div style="clear:both;" ></div>
		</div>
		<div class="content">
			<span class="bigtitle">Witaj w stacji pomiaru jakości powietrza <a href="http://www.agh.edu.pl/" title="Strona główna AGH" style = "text-decoration:none;" target="_blank">AGH</a></span>
			<div class="dottedline"></div>
			<p>Dzięki naszej stacji będziesz mógł sprawdzić jakie są najważniejsze parametry powietrza w Krakowie.</p>
			<br></br>
			<p> 
				<h3>Dzisiejsze dane z czujników: </h3>
				<?php
				$db = new PDO('mysql:host=mysql.agh.edu.pl;dbname=cumana;charset=utf-8',
					'cumana', 'vuFij0BS');
				try {
					echo '<table id="current">';
					echo '<tr>';
					echo '<td>Czas</td>';
					echo '<td>Wilgotność</td>';
					echo '<td>Wiatr</td>';
					echo '<td>Temperatura</td>';
					echo '</tr>';
					$query = "SELECT *, TIME(time) FROM dane2 WHERE DATE(time) = CURDATE(); ";
					foreach($db->query($query) as $row) {
					echo '<tr>';
					echo '<td>'.$row['TIME(time)'].'</td>';
					echo '<td>'.number_format((float)$row['wilg'], 2, '.', ' ').'</td>';
					echo '<td>'.number_format((float)$row['wiatr'], 2, '.', ' ').'</td>';
					echo '<td>'.number_format((float)$row['temp'], 2, '.', ' ').'</td>';
					echo '</tr>';
					}
					echo '</table>';
				} catch(PDOException $ex) {
					echo "error!";
				}
				?>
			</p>
			<p>
				<h3>Dzisiejsze średnie godzinowe:</h3>
				<?php
				$db = new PDO('mysql:host=mysql.agh.edu.pl;dbname=cumana;charset=utf-8',
					'cumana', 'vuFij0BS');
				try {
					echo '<table id="averageH">';
					echo '<tr>';
					echo '<td>Czas</td>';
					echo '<td>Wilgotność</td>';
					echo '<td>Wiatr</td>';
					echo '<td>Temperatura</td>';
					$query = "SELECT AVG(temp), AVG(wilg), AVG(wiatr), HOUR(time) FROM dane2 WHERE DATE(time) = CURDATE() GROUP BY HOUR(time) ";
					foreach($db->query($query) as $row) {
						echo '<tr>';
						echo '<td>'.$row['HOUR(time)'].':00</td>';
						echo '<td>'.number_format((float)$row['AVG(wilg)'], 2, '.', ' ').'</td>';
						echo '<td>'.number_format((float)$row['AVG(wiatr)'], 2, '.', ' ').'</td>';
						echo '<td>'.number_format((float)$row['AVG(temp)'], 2, '.', ' ').'</td>';
						echo '</tr>';
					}
					echo '</table>';
				} catch(PDOException $ex) {
					echo "error!";
				}
				?>
			</p>
			<p>
				<h3>Miesięczne średnie dobowe:</h3>
				<?php
				$db = new PDO('mysql:host=mysql.agh.edu.pl;dbname=cumana;charset=utf-8',
					'cumana', 'vuFij0BS');
				try {
					echo '<table id = "averageM">';
					echo '<tr>';
					echo '<td>Czas</td>';
					echo '<td>Wilgotność</td>';
					echo '<td>Wiatr</td>';
					echo '<td>Temperatura</td>';
					$query = "SELECT AVG(temp), AVG(wilg), AVG(wiatr), DAY(time) FROM dane2 WHERE MONTH(time) = MONTH(CURDATE()) GROUP BY DAY(time)";
					foreach($db->query($query) as $row) {
						echo '<tr>';
						echo '<td>'.$row['DAY(time)'].'</td>';
						echo '<td>'.number_format((float)$row['AVG(wilg)'], 2, '.', ' ').'</td>';
						echo '<td>'.number_format((float)$row['AVG(wiatr)'], 2, '.', ' ').'</td>';
						echo '<td>'.number_format((float)$row['AVG(temp)'], 2, '.', ' ').'</td>';
						echo '</tr>';
					}
					echo '</table>';
				} catch(PDOException $ex) {
					echo "error!";
				}
				?>
			</p>
		</div>
		<div class="footer">
			&copy; Laboratorium projektowe 
			<script>
			new Date().getFullYear()>2010&&document.write(new Date().getFullYear());
			</script>
		</div>
	</div>
</body>
</html>

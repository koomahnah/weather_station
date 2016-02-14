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
	<script src="js/ajax.js"></script>
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
						<li><a href="#" onclick="$('.content).html('tempD.html');">Wykres 24h</a></li>
						<li><a href="tempW.html">Wykres tygodniowy</a></li>
						<li><a href="tempM.html">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="#" class = "menu"> Ciśnienie</a>
					<ul>
						<li><a href="cisnienieD.html">Wykres 24h</a></li>
						<li><a href="cisnienieW.html">Wykres tygodniowy</a></li>
						<li><a href="cisnienieM.html">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="#" class = "menu">Pył zawieszony</a>
					<ul>
						<li><a href="pylD.html">Wykres 24h</a></li>
						<li><a href="pylW.html">Wykres tygodniowy</a></li>
						<li><a href="pylM.html">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="#" class = "menu">Tlenek azotu</a>
					<ul>
						<li><a href="tlenekD.html">Wykres 24h</a></li>
						<li><a href="tlenekW.html">Wykres tygodniowy</a></li>
						<li><a href="tlenekM.html">Wykres 30dniowy</a></li>
					</ul>
				</li>
				<li><a href ="#" class = "menu">Pole E</a>
					<ul>
						<li><a href="poleD.html">Wykres 24h</a></li>
						<li><a href="poleW.html">Wykres tygodniowy</a></li>
						<li><a href="poleM.html">Wykres 30dniowy</a></li>
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
			<h3>Dzisiejsze dane z czujników: </h3>
				<?php
				$db = new PDO('mysql:host=mysql.agh.edu.pl;dbname=cumana;charset=utf-8',
					'cumana', 'vuFij0BS');
				try {
					echo '<table>';
					echo '<tr>';
					echo '<td>Czas</td>';
					echo '<td>Wilgotność</td>';
					echo '<td>Wiatr</td>';
					echo '<td>Temperatura</td>';
					echo '</tr>';
					$query = "SELECT * FROM avg_data WHERE DATE(time) = CURDATE(); ";
					foreach($db->query($query) as $row) {
						echo '<tr>';
						echo '<td>'.$row['time'].'</td>';
						echo '<td>'.$row['wilg'].'</td>';
						echo '<td>'.$row['wiatr'].'</td>';
						echo '<td>'.$row['temp'].'</td>';
						echo '</tr>';
					}
					echo '</table>';
				} catch(PDOException $ex) {
					echo "error!";
				}
				?>
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

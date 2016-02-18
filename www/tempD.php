<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Temperatura</title>
	<meta name="description" content="Stacja pogodowa AGH"/>
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<link href='https://fonts.googleapis.com/css?family=Lato:400,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="js/script.js"></script>
	<script src="js/plot.js"></script>
</head>
<body >
	<div class="container">
		<a href="index.php"><div class="topbar"></div></a>
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
		
		<script type="text/javascript">
		window.onload = load;
		function generuj() {
			var queue = [];		
			var averageHTable = document.getElementById('averageH');
			var averageHTableRowLength = averageHTable.rows.length;
			for (i = 0; i < averageHTableRowLength; i++){
				var oCells = averageHTable.rows.item(i).cells;
				var cellVal = oCells.item(1).innerHTML;
				if (i>0 && i> averageHTableRowLength-24) {
					 if(i>24) {
					var x = queue.shift();
					}
					queue.push(cellVal);
				}
			}
			var myplot= new MakeDraw();
			myplot.id="mycanvas";
			myplot.plotColor='rgba(200, 230, 50, 1)';
			myplot.fSize=15;
			myplot.enumerateH =0;
			myplot.data= queue;
			myplot.plot();
			setTimeout("generuj()", 5000);
	}

	</script>
			<div id="data">
				<p>
				<h3>Dzisiejsze średnie godzinowe:</h3>
				<?php
				$db = new PDO('mysql:host=mysql.agh.edu.pl;dbname=cumana;charset=utf-8',
					'cumana', 'vuFij0BS');
				try {
					echo '<table id="averageH">';
					echo '<tr>';
					echo '<td>Czas</td>';
					echo '<td>Temperatura</td>';
					echo '</tr>';
					$query = "SELECT AVG(temp), AVG(cisn), AVG(pyl), AVG(azot), AVG(wilg), MINUTE(time) FROM dane WHERE HOUR(time) = HOUR(NOW()) GROUP BY MINUTE(time) ";
					$query = "SELECT AVG(temp), AVG(cisn), AVG(pyl), AVG(azot), AVG(wilg), HOUR(time) FROM dane WHERE DATE(time) = CURDATE() GROUP BY HOUR(time) ";
					foreach($db->query($query) as $row) {
						echo '<tr>';
						echo '<td>'.$row['HOUR(time)'].':00</td>';
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
			<div id="timer"></div>
			<div style="clear:both;" ></div>
			<div class="description">Wykres zmian temperatury przez ostatnie 24h: </div>
			<div class="graph">
				<div class="label">Temperatura [&#186C]</div>
				<canvas id="mycanvas" width= "920" height="450"></canvas>
				<a href="index.php" title="Powrót do strony głównej" style = "text-decoration:none;">Strona główna</a>
			</div>
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


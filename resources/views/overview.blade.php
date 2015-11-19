<html>
<head>
	<title>
		Overview
	</title>
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
	<body style="background-color: #ffffe4">
		<div class="container">
			<div class="bs-component">
				<nav class="navbar navbar-default">
					<div class="container-fluid">
						<div class="navbar-header">
							<a class="navbar-brand" href="overview">Overview</a>
						</div>
						<div class="navbar-collapse">
							<ul class="nav navbar-nav">
								<li>
									<a href="hunting">Hunting</a>
								</li>
								<li>
									<a href="inventions">Inventions</a>
								</li>
								<li>
									<a href="exploring">Exploring</a>
								</li>
								<li>
									<a href="sleeping">Sleeping</a>
								</li>
								<li>
									<a href="fighting">Fighting</a>
								</li>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li>
									<?php if(Auth::check()) echo '<a href="logout">logout</a>';?>
								</li>
							</ul>
						</div>
					</div>
				</nav>
			</div>
		</div>
		<div class="container">
			<?php if(Auth::check()) {
						echo '<div class="panel panel-default" style="border: none">';
						echo '<p>Here you can see the overview of your character. If he is getting hungry, you should go hunting. If he is running 
						out of energy, you can wait or go to sleep, which will restore energy more quickly. If you are not hungry 
						and have plenty of energy you might go inventing (if you invent something new it increases your intellect) or exploring 
						(if you find interesting tools or weapons it will increase your intellect and even your strength). 
						Your main level slowly increases from everything you do.</p><br>';
						echo '</div>';
						echo '<div class="panel panel-default" style="border: none">';
						echo '<div class="row">';
						echo '<div class="col-xs-6 col-sm-6 col-md-3">';
						echo '<div class="form-group">';
						echo 'Character name: '.$data['character'].'<br>';
						echo '</div>';
						echo '</div>';
						echo '<div class="col-xs-6 col-sm-6 col-md-6">';
						echo '<div class="form-group">';
						echo 'Character energy: '.$data['energy'].'%';
						echo '<div class="progress">';
						if ($data['energy'] >= 65)
							echo '<div class="progress-bar progress-bar-success" style="width: '.$data['energy'].'%"></div>';
						if ($data['energy'] < 65 && $data['energy'] >= 30)
							echo '<div class="progress-bar progress-bar-warning" style="width: '.$data['energy'].'%"></div>';
						if ($data['energy'] < 30)
							echo '<div class="progress-bar progress-bar-danger" style="width: '.$data['energy'].'%"></div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						
						echo '<div class="row">';
						echo '<div class="col-xs-6 col-sm-6 col-md-3">';
						echo '<div class="form-group">';
						echo 'Character main level: '.$data['level'].'<br>';
						echo '</div>';
						echo '</div>';
						echo '<div class="col-xs-6 col-sm-6 col-md-6">';
						echo '<div class="form-group">';
						echo 'Character hunger: '.$data['hunger'].'%';
						echo '<div class="progress">';
						if ($data['hunger'] > 70)
							echo '<div class="progress-bar progress-bar-danger" style="width: '.$data['hunger'].'%"></div>';
						if ($data['hunger'] <= 70 && $data['hunger'] > 35)
							echo '<div class="progress-bar progress-bar-warning" style="width: '.$data['hunger'].'%"></div>';
						if ($data['hunger'] <= 35)
							echo '<div class="progress-bar progress-bar-success" style="width: '.$data['hunger'].'%"></div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '<div class="form-group">';
						echo 'Character intellect level: '.$data['intellect'].'<br>';
						echo '</div>';
						echo 'Character strength: '.$data['strength'].'<br><br>';
						
						echo '</div>';
					}else echo 'Please <a href="./login">login</a> to access this page'; ?>
					
			<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
		</div>
	</body>
</html>
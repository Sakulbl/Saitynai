<html>
<head>
	<title>
		Hunting
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
						if($data['hunt_progress'] == true){
							echo 'Your character is currently hunting. Time left: '.$data['time_left'].' min.';
						} else if ($data['action'] != 'hunting' && $data['action'] != NULL){
							echo 'You can not hunt. Your character is currently '.$data['action'].'.';
						} else {
							echo '<div class="panel panel-default" style="border: none">';
							echo 'You have to go hunting when you are getting hungry. Hunting requires energy, 
							it reduces your hunger and increases your strength level.
							You can go on a small hunt (requires 10 % energy, reduces hunger by 20 %, takes 30 minutes), 
							medium hunt (requires 25 % energy, reduces hunger by 50 %, takes 1 hour), 
							or a big hunt (requires 40 % energy, reduces hunger by 80 %, takes 1 hour 30 minutes). If you have 
							invented fire, it reduces 5 % more hunger for each hunt type.<br><br>';
							echo '</div>';
							echo '<div class="panel panel-default" style="border: none">';
							echo 'Choose hunt type:<br>';
							echo Form::open(array('url' => 'hunt'));
							echo '<p>';
							echo Form::select('hunt_type', array('S' => 'Small', 'M' => 'Medium', 'B' => 'Big'), 'S');
							echo '</p>';
							echo '<p>';
							echo Form::submit('Hunt');
							echo '</p>';
							echo Form::close().'<br>';
							echo '</div>';
						}
						}else echo 'Please <a href="./login">login</a> to access this page'; ?>
						
			<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
		</div>
	</body>
</html>
<html>
<head>
	<title>
		Sleeping
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
						if($data['sleep_progress'] == true){
							echo 'Your character is currently sleeping. Time left: '.$data['time_left'].' min.';
						} else if ($data['action'] != 'sleeping' && $data['action'] != NULL){
							echo 'You can not sleep. Your character is currently '.$data['action'].'.';
						} else {
							echo '<div class="panel panel-default" style="border: none">';
							echo 'You have to sleep when you are getting low on energy. Sleeping requires only time.
							You can decide how much time you will sleep. Sleeping 4 hours restores 40 % energy, 
							6 hours - 60 %, 8 hours - 80 %, and 10 hours of sleep restores all energy.<br><br>';
							echo '</div>';
							echo '<div class="panel panel-default" style="border: none">';
							echo 'Choose sleep duration:<br>';
							echo Form::open(array('url' => 'sleep'));
							echo '<p>';
							echo Form::select('sleep_type', array('4' => '4 hours', '6' => '6 hours', '8' => '8 hours', '10' => '10 hours'), '4');
							echo '</p>';
							echo '<p>';
							echo Form::submit('Sleep');
							echo '</p><br>';
							echo '</div>';
							echo Form::close();
						}
						}else echo 'Please <a href="./login">login</a> to access this page'; ?>
						
			<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
		</div>
	</body>
</html>
<html>
<head>
	<title>
		Inventing
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
						if($data['invention_in_progress'] == true){
							echo 'Your character is currently inventing. Time left: '.$data['time_left'].' min.';
						} else if($data['all_invented'] == true){
							echo 'Your character has invented all the greatest inventions.';
						} else if ($data['action'] != 'inventing' && $data['action'] != NULL){
							echo 'You can not invent. Your character is currently '.$data['action'].'.';
						} else {
							echo '<div class="panel panel-default" style="border: none">';
							echo 'You can invent new stuff and get smarter. Different inventions help your character do some things 
							faster and get him stronger, for example, inventing fire will reduce more hunger after hunts because 
							he will make warmer and more delicious food. You have to be well rested to invent, because each invention 
							will take most of your day, 5 hours, and 50 % energy.<br><br>';
							if ($data['inventions'] != NULL){
								echo 'Already invented: ';
								foreach($data['inventions'] as $invention)
									echo $invention.' | ';
								echo '<br><br>';
							}
							echo '</div>';
							echo Form::open(array('url' => 'invent'));
							echo '<p>';
							echo Form::submit('Start next invention');
							echo '</p>';
							echo Form::close();
						}
						}else echo 'Please <a href="./login">login</a> to access this page'; ?>
						
			<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
		</div>
	</body>
</html>
<html>
<head>
	<title>
		Exploring
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
						if($data['explore_progress'] == true){
							echo 'Your character is currently exploring. Time left: '.$data['time_left'].' min.';
						} else if ($data['action'] != 'exploring' && $data['action'] != NULL){
							echo 'You can not explore. Your character is currently '.$data['action'].'.';
						} else {
							echo '<div class="panel panel-default" style="border: none">';
							if($data['show_message'] == true)
								echo $data['explore_message_food'].'<br><br>';
							else
								echo 'When you are well rested and not hungry, you can go exploring. During your explorations, 
								you might find weapons (which give you more strength in fights) or food (which, of course, restores 
								some hunger). You can plan how much time you will explore. It might be 1 hour (requires 20% energy), 
								2 hours (35% energy) or 3 hours (50% energy). Longer explorations increase chances of getting
								 more valuable weapons and prey.<br><br>';
							echo '</div>';
							echo '<div class="panel panel-default" style="border: none">';
							echo 'Choose exploring duration:<br>';
							echo Form::open(array('url' => 'explore'));
							echo '<p>';
							echo Form::select('explore_type', array('1' => '1 hour', '2' => '2 hours', '3' => '3 hours'), '1');
							echo '</p>';
							echo '<p>';
							echo Form::submit('Explore');
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
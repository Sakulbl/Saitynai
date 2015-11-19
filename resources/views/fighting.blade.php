<html>
<head>
	<title>
		Fighting
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
						if($data['fight_progress'] == true){
							echo 'Your character is currently fighting someone. Time left: '.$data['time_left'].' min.';
						} else if ($data['action'] != 'fighting' && $data['action'] != NULL){
							echo 'You can not fight. Your character is currently '.$data['action'].'.';
						} else {
							echo '<div class="panel panel-default" style="border: none">';
							if($data['show_message'] == true)
								echo $data['fight_message'].'<br><br>';
							else
								echo 'Here you can fight other characters. The characters listed are random currently online 
								(or 5 or less minutes ago) users. Their strength might be bigger, so the more 
								energy you have going into a fight, the better. Depending on your strength differences and 
								energy you both have, you might win, lose, or tie. You gain strength from fights.<br><br>';
							echo '</div>';
							echo '<div class="panel panel-default" style="border: none">';
							if ($data['online'] != NULL){
								echo 'Currently online:<br><br>';
								foreach($data['online'] as $online)
									echo $online->name.' <a href="fight/'.$online->username.'">Fight</a><br>';
								echo '<br><br>';
							} else echo 'No users online.';
							echo '</div>';
						}
						}else echo 'Please <a href="./login">login</a> to access this page'; ?>
						
			<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
		</div>
	</body>
</html>
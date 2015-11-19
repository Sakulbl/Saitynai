<html>
<head>
	<title>
		Main Page
	</title>
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<style>
		.centered-form .panel{
		  background: rgba(255, 255, 255, 0.8);
		  box-shadow: rgba(0, 0, 0, 0.3) 20px 20px 20px;
		}

		.centered-form{
		  margin-top: 3%;
		}
	</style>
</head>
	<body>
		<div class="row centered-form">
			<div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Login</h3>
					</div>
					<div class="panel-body">
						<?php 
							echo Form::open(array('url' => 'login'));
							echo '<div class="form-group">';
							echo Form::text('username', '', array('class' => 'form-control input-sm', 'placeholder' => 'Username'));
							echo '</div>';
							echo '<div class="form-group">';
							echo Form::password('password', array('class' => 'form-control input-sm', 'placeholder' => 'Password'));
							echo '</div>';
							echo Form::submit('Login', array('class' => 'btn btn-info btn-block'));
							echo Form::close().'<br>';
							echo 'Not registered yet? <a href="./create">Register here</a>';
						?>
				</div>
			</div>
		</div>
		
		<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
<html>
<head>
	<title>
		Registration
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
						<h3 class="panel-title">Registration</h3>
					</div>
					<div class="panel-body">
						<?php
							if(Session::get('errors')){
								echo '<div class="alert alert-danger alert-dismissable">';
								echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
								foreach($errors->all('<li>:message</li>') as $error)
									echo $error;
								echo '</div>';
							}
							echo Form::open(array('url' => 'create'));
							echo '<div class="row">';
							echo '<div class="col-xs-6 col-sm-6 col-md-6">';
							echo '<div class="form-group">';
							echo Form::text('username', '', array('class' => 'form-control input-sm', 'placeholder' => 'Username'));
							echo '</div>';
							echo '</div>';
							echo '<div class="col-xs-6 col-sm-6 col-md-6">';
							echo '<div class="form-group">';
							echo Form::text('character', '', array('class' => 'form-control input-sm', 
																					'placeholder' => 'Character Name'));
							echo '</div>';
							echo '</div>';
							echo '</div>';
							
							echo '<div class="row">';
							echo '<div class="col-xs-6 col-sm-6 col-md-6">';
							echo '<div class="form-group">';
							echo Form::password('password', array('class' => 'form-control input-sm', 'placeholder' => 'Password'));
							echo '</div>';
							echo '</div>';
							echo '<div class="col-xs-6 col-sm-6 col-md-6">';
							echo '<div class="form-group">';
							echo Form::password('password-repeat', array('class' => 'form-control input-sm', 
																			'placeholder' => 'Repeat Password'));
							echo '</div>';
							echo '</div>';
							echo '</div>';
							
							echo Form::submit('Register', array('class' => 'btn btn-info btn-block'));
							echo Form::close().'<br>';
							echo '<a href="./">Back to main page</a>';
						?>
				</div>
			</div>
		</div>
		
		<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
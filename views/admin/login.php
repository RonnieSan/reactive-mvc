<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reactive Apps - Dashboard Login</title>
	<link rel="stylesheet" href="/assets/themes/default/css/default.css">
</head>
<body>
	<div class="wrapper">
		<div class="static-modal">
			<header class="bar"><h1>Login</h1></header>
			<form name="login" class="form form-login" id="form-login" method="post" action="">
				<?php if (isset($flash['error'])):?>
					<p class="error-msg"><?php echo $flash['error'];?></p>
				<?php endif;?>
				<div class="control-group horizontal">
					<label for="username" class="control-label">Username:</label>
					<div class="controls">
						<input type="text" name="username" id="form-login--username">
					</div>
				</div>
				<div class="control-group horizontal">
					<label for="password" class="control-label">Password:</label>
					<div class="controls">
						<input type="password" name="password" id="form-login--password">
					</div>
				</div>
				<div class="control-group horizontal">
					<label for="submit-btn" class="control-label"></label>
					<div class="controls">
						<input type="submit" class="btn" name="submit-btn" id="form-login--submit-btn" value="Login">
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
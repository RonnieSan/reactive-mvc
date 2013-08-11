<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<form method="post" action="">
		<?php if (isset($flash['error'])) echo '<p class="error">' . $flash['error'] . '<p>';?>
		<input type="text" name="username" id="username"><br />
		<input type="password" name="password" id="password"><br />
		<input type="submit" value="Login!">
	</form>
</body>
</html>
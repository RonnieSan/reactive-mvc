<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $appName;?> - Reactive Apps</title>
	<link rel="stylesheet" href="/assets/themes/default/css/dashboard.css">
</head>
<body>
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="content">
				<p>This is the app page for <?php echo $appName;?>.</p>
			</div>

		</section>

		<?php $this->view->partial('admin/_partials/footer.php');?>		

	</div>
</body>
</html>
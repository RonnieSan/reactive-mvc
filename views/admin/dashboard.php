<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reactive Apps Dashboard</title>
	<link rel="stylesheet" href="/assets/themes/default/css/dashboard.css">
</head>
<body>
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="content">
				<p>This is some content.</p>
			</div>

		</section>

		<?php $this->view->partial('admin/_partials/footer.php');?>		

	</div>
</body>
</html>
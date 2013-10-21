<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Documentation - Reactive Apps</title>
	<link rel="stylesheet" href="/assets/themes/default/css/default.css">
	<link rel="stylesheet" href="/assets/themes/default/css/documentation.css">
</head>
<body id="page-docs" class="page-view">
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="stage">
				<div class="content">
					<h1>Documentation</h1>
					
					<table class="striped">
						<thead>
							<tr>
								<th class="short centered">ID</th>
								<th>App Name</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($apps as $app) {?>
								<tr rel="/admin/documentation/app/<?php echo $app->ID?>">
									<td class="short centered"><?php echo $app->ID;?></td>
									<td><?php echo $app->appName;?></td>
								</tr>
							<?php }?>
						</tbody>
					</table>

				</div>
			</div>

		</section>

		<?php $this->view->partial('admin/_partials/footer.php');?>		

	</div>
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script>
	$(function() {
		$('tbody').find('tr').on('click', function() {
			location.href = $(this).attr('rel');
		});
	});
	</script>
</body>
</html>
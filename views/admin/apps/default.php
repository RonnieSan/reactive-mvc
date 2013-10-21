<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Apps - Reactive Apps</title>
	<link rel="stylesheet" href="/assets/themes/default/css/default.css">
</head>
<body id="page-apps" class="page-view">
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="stage">
				<div class="content">
					<h1>Apps</h1>

					<?php echo $flash['message'];?>

					<a class="btn thick blue" href="/admin/apps/edit/0">Add New App</a>

					<table class="striped">
						<thead>
							<tr>
								<th class="short centered">ID</th>
								<th>App Name</th>
								<th>App Slug</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($apps as $app) {?>
								<tr rel="/admin/apps/edit/<?php echo $app->slug?>">
									<td class="short centered"><?php echo $app->ID;?></td>
									<td><?php echo $app->appName;?></td>
									<td><?php echo $app->slug;?></td>
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
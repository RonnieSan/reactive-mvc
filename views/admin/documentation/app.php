<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Documentation - <?php echo $app['appName'];?> - Reactive Apps</title>
	<link rel="stylesheet" href="/assets/themes/default/css/default.css">
</head>
<body id="page-docs" class="page-view">
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="stage">
				<div class="content">
					<h1>Docs for <?php echo get_var($app['appName'], 'None');?></h1>

					<a class="btn thick blue" href="/admin/documentation/edit/<?php echo $app['ID'];?>/0">Add New Doc</a>
					
					<table class="striped">
						<thead>
							<tr>
								<th class="short centered">ID</th>
								<th>Title</th>
								<th>Slug</th>
								<th class="centered">Type</th>
								<th class="centered">Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($docs as $doc) {?>
								<tr rel="/admin/documentation/edit/<?php echo $app['ID'];?>/<?php echo $doc->ID;?>">
									<td class="short centered"><?php echo $doc->ID;?></td>
									<td><?php echo $doc->title;?></td>
									<td><?php echo $doc->slug;?></td>
									<td class="centered"><?php echo $doc->type;?></td>
									<td class="centered"><a rel="<?php echo $doc->title;?>" class="delete-link" href="/admin/documentation/delete/<?php echo $doc->ID;?>">Delete</a></td>
								</tr>
							<?php }
							if (count($docs) == 0) {?>
								<tr>
									<td colspan="5">No docs were found.</td>
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
			if ($(this).attr('rel')) {
				location.href = $(this).attr('rel');
			}
		});

		$('.delete-link').on('click', function(e) {
			e.stopPropagation();
			if (confirm('Are you sure you want to delete ' + $(this).attr('rel') + '?')) {
				return true;
			}
			e.preventDefault();
			return false;
		});
	});
	</script>
</body>
</html>
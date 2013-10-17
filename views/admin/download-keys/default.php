<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Download Keys - Reactive Apps</title>
	<link rel="stylesheet" href="/assets/themes/default/css/default.css">
</head>
<body id="page-download-keys" class="page-view">
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="stage">
				<div class="content">
					<h1>Download Keys</h1>

					<?php echo $flash['message'];?>

					<a class="btn thick blue" href="/admin/products/edit/0">Add New Key</a>

					<table class="striped">
						<thead>
							<tr>
								<th class="short centered">ID</th>
								<th>Product Name</th>
								<th>Slug</th>
								<th class="centered">Price</th>
								<th class="centered">Active</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($keys as $key) {?>
								<!-- <tr rel="/admin/products/edit/<?php echo $product->ID;?>">
									<td class="short centered"><?php echo $product->ID;?></td>
									<td><?php echo $product->productName;?></td>
									<td><?php echo $product->slug;?></td>
									<td class="centered">$<?php echo $product->price;?></td>
									<td class="centered"><?php if ($product->active == 1) echo '&bull;';?></td>
								</tr> -->
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
		$('tr').on('click', function() {
			location.href = $(this).attr('rel');
		});
	});
	</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Products - <?php echo get_var($product['productName'], 'New Product');?> - Reactive Apps</title>
	<?php $this->view->partial('admin/_partials/head.php');?>
	<link rel="stylesheet" href="/assets/themes/default/css/apps.css">
</head>
<body id="page-products" class="page-edit">
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="stage">
				<div class="content">
					<h1><?php echo get_var($product['productName'], 'New Product');?></h1>

					<?php echo $flash['message'];?>

					<form name="edit" class="form form-edit" id="form-edit" method="post" action="">

						<input type="hidden" name="ID" value="<?php echo get_var($product['ID'], 0);?>">
						
						<div id="form-edit--cg--productName" class="control-group horizontal">
							<label class="control-label">Product Name:</label>
							<div class="controls">
								<input type="text" name="productName" class="text" id="form-edit--productName" value="<?php echo get_var($product['productName'], '');?>" />
							</div>
						</div>

						<div id="form-edit--cg--slug" class="control-group horizontal">
							<label class="control-label">Slug:</label>
							<div class="controls">
								<input type="text" name="slug" class="text" id="form-edit--slug" value="<?php echo get_var($product['slug'], '');?>" />
							</div>
						</div>

						<div id="form-edit--cg--price" class="control-group horizontal">
							<label class="control-label">Price:</label>
							<div class="controls">
								<input type="text" name="price" class="text" id="form-edit--price" value="<?php echo get_var($product['price'], '');?>" />
							</div>
						</div>

						<div id="form-edit--cg--buyNowCode" class="control-group horizontal">
							<label class="control-label">Buy Now Code:</label>
							<div class="controls">
								<div class="textarea-wrapper"><textarea class="code" name="buyNowCode" id="form-edit--buyNowCode"><?php echo get_var($product['buyNowCode'], '');?></textarea></div>
							</div>
						</div>

						<div id="form-edit--cg--active" class="control-group horizontal">
							<label class="control-label">Active:</label>
							<div class="controls">
								<input type="checkbox" name="active" value="1" id="form-edit--active"<?php if ($product['active'] == 1) echo ' checked="checked"';?>>
							</div>
						</div>

						<div id="form-edit--cg--submitBtn" class="control-group horizontal">
							<label class="control-label">&nbsp;</label>
							<div class="controls">
								<input class="btn green" type="submit" value="Submit">
							</div>
						</div>

					</form>
				</div>
			</div>

		</section>

		<?php $this->view->partial('admin/_partials/footer.php');?>	
		<script>
		$(function() {
			$('#form-edit--productName').addClass('validate').data('validation', [
				{ rule: 'required', message: 'Please give your product a name' }
			]);

			$('#form-edit--slug').addClass('validate').data('validation', [
				{ rule: 'required', message: 'The slug is required.' }
			]);

			$('#form-edit--buyNowCode').addClass('validate').data('validation', [
				{ rule: 'required', message: 'The buyNowCode is required.' }
			]);

			$('#form-edit--active').addClass('validate').data('validation', [
				{ rule: 'required', message: 'Check this yo.' }
			]);

			$('#form-edit').reboot_validation();
		});
		</script>
	</div>
</body>
</html>
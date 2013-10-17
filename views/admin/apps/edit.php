<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Apps - <?php echo get_var($app['appName'], 'New App');?> - Reactive Apps</title>
	<?php $this->view->partial('admin/_partials/head.php');?>
	<link rel="stylesheet" href="/assets/themes/default/css/apps.css">
</head>
<body id="page-apps" class="page-edit">
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="stage">
				<div class="content">
					<h1><?php echo get_var($app['appName'], 'New App');?></h1>

					<?php echo $flash['message'];?>

					<form name="edit" class="form form-edit" id="form-edit" method="post" action="">

						<input type="hidden" name="ID" value="<?php echo get_var($app['ID'], 0);?>">
						
						<div id="form-edit--cg--appName" class="control-group horizontal">
							<label class="control-label">App Name:</label>
							<div class="controls">
								<input type="text" name="appName" class="text" id="form-edit--appName" value="<?php echo get_var($app['appName'], '');?>" />
							</div>
						</div>

						<div id="form-edit--cg--slug" class="control-group horizontal">
							<label class="control-label">Slug:</label>
							<div class="controls">
								<input type="text" name="slug" class="text" id="form-edit--slug" value="<?php echo get_var($app['slug'], '');?>" />
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
			$('#form-edit--appName').addClass('validate').data('validation', [
				{ rule: 'required', message: 'Please give your app a name' }
			]);

			$('#form-edit--slug').addClass('validate').data('validation', [
				{ rule: 'required', message: 'The slug is required.' }
			]);

			$('#form-edit').reboot_validation();
		});
		</script>
	</div>
</body>
</html>
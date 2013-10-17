<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Documentation - <?php echo $doc['title'];?> - Reactive Apps</title>
	<?php $this->view->partial('admin/_partials/head.php');?>
</head>
<body id="page-docs" class="page-edit">
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="stage">
				<div class="content">
					<h1><?php echo get_var($doc['title'], 'New Documentation');?></h1>

					<?php echo $flash['message'];?>

					<form name="edit" class="form form-edit" id="form-edit" method="post" action="">

						<input type="hidden" name="ID" value="<?php echo get_var($doc['ID'], 0);?>">
						<input type="hidden" name="appID" value="<?php echo $appID;?>">
						
						<div id="form-edit--cg--title" class="control-group horizontal">
							<label class="control-label">Title:</label>
							<div class="controls">
								<input type="text" name="title" class="text" id="form-edit--title" value="<?php echo get_var($doc['title'], '');?>" />
							</div>
						</div>

						<div id="form-edit--cg--slug" class="control-group horizontal">
							<label class="control-label">Slug:</label>
							<div class="controls">
								<input type="text" name="slug" class="text" id="form-edit--slug" value="<?php echo get_var($doc['slug'], '');?>" />
							</div>
						</div>

						<div id="form-edit--cg--type" class="control-group horizontal">
							<label class="control-label">Type:</label>
							<div class="controls">
								<select name="type" id="form-edit--type">
									<option value="">Choose Type</option>
									<option value="method">Method</option>
									<option value="event">Event</option>
									<option value="option">Option</option>
								</select>
							</div>
						</div>

						<div id="form-edit--cg--content" class="control-group horizontal">
							<label class="control-label">Content:</label>
							<div class="controls">
								<textarea name="content" id="form-edit--content"><?php echo get_var($doc['content'], '');?></textarea>
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
		<script src="/ckeditor/ckeditor.js"></script>
		<script>
		$(function() {
			CKEDITOR.replace('content');

			$('#form-edit--type').val('<?php echo $doc['type'];?>').trigger('change');

			$('#form-edit--appName').addClass('validate').data('validation', [
				{ rule: 'required', message: 'Please give your documentation a title' }
			]);

			$('#form-edit--slug').addClass('validate').data('validation', [
				{ rule: 'required', message: 'The slug is required' }
			]);

			$('#form-edit--type').addClass('validate').data('validation', [
				{ rule: 'required', message: 'Please select a type' }
			]);

			$('#form-edit--content').addClass('validate').data('validation', [
				{ rule: 'required', message: 'Please add content to the document' }
			]);

			$('#form-edit').reboot_validation();
		});
		</script>
	</div>
</body>
</html>
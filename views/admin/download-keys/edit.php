<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Download Keys - <?php echo get_var($key['productName'], 'New Download Key');?> - Reactive Apps</title>
	<?php $this->view->partial('admin/_partials/head.php');?>
	<link rel="stylesheet" href="/assets/themes/default/css/apps.css">
</head>
<body id="page-download-keys" class="page-edit">
	<div class="wrapper">
		
		<?php $this->view->partial('admin/_partials/header.php');?>

		<section id="main">
			
			<?php $this->view->partial('admin/_partials/menu.php');?>

			<div id="stage">
				<div class="content">
					<h1><?php echo get_var($key['productName'], 'New Download');?> Key</h1>

					<?php echo $flash['message'];?>

					<form name="edit" class="form form-edit" id="form-edit" method="post" action="">

						<input type="hidden" name="ID" value="<?php echo get_var($key['ID'], 0);?>">
						
						<div id="form-edit--cg--productID" class="control-group horizontal">
							<label class="control-label">Product Name:</label>
							<div class="controls">
								<select name="productID" id="form-edit--productID">
									<option value="0">Select a Product</option>
									<?php foreach($products as $product) {?>
									<option value="<?php echo $product['ID'];?>"<?php if ($key['productID'] == $product['ID']) echo ' selected="selected"';?>><?php echo $product['productName'];?></option>
									<?php }?>
								</select>
							</div>
						</div>

						<input type="hidden" name="customerID" value="<?php echo $key['customerID'];?>">

						<div id="form-edit--cg--email" class="control-group horizontal">
							<label class="control-label">Customer Email:</label>
							<div class="controls">
								<input type="text" name="email" class="text" id="form-edit--email" disabled="disabled" value="<?php echo $key['email'];?>" /> <button type="button" class="btn blue" onclick="openFindCustomerModal()">Find</button>
							</div>
						</div>

						<div id="form-edit--cg--key" class="control-group horizontal">
							<label class="control-label">Key:</label>
							<div class="controls">
								<input type="text" name="key" class="text" id="form-edit--key" value="<?php echo $key['key'];?>" />
							</div>
						</div>

						<div id="form-edit--cg--active" class="control-group horizontal">
							<label class="control-label">Active:</label>
							<div class="controls">
								<input type="checkbox" name="active" value="1" id="form-edit--active"<?php if ($key['active'] == 1) echo ' checked="checked"';?>>
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

		<div id="modals">
			
			<div id="modal-find-customer" class="basic-modal">
				<header>
					<h2>Find a Customer</h2>
				</header>
				<div class="content">
					<form name="find-customer" class="form form-find-customer" id="form-find-customer" method="post" action="">
						<div id="form-find-customer--cg--search" class="control-group">
							<label class="control-label">Enter a name or email to find a customer:</label>
							<div class="controls">
								<input type="text" name="search" class="text" id="form-find-customer--search" value="" /> <button id="form-find-customer--search" type="button" class="btn blue" onclick="findCustomer()">Search</button>
							</div>
						</div>
					</form>
					<div class="find-customer-result"></div>
				</div>
			</div>

		</div>

		<script>
		// Generate a new key
		function generateKey() {
			var productID = $('#form-edit--productID').val();
			var email     = $('#form-edit--email').val();
			$.get('/admin/download-keys/get/' + productID + '/' + escape(email), function(response) {
				$('#form-edit--key').val(response);
			});
		}

		// Open the Find Customer modal
		function openFindCustomerModal() {
			$('#modal-find-customer').reboot_modal({
				top   : 70,
				width : 500
			});
		}

		// Return a list of found customers
		function findCustomer() {
			var query = escape($('#form-find-customer--search').val());
			$.get('/admin/customers/find/' + query, function(response) {
				
			});
		}

		$(function() {
			// When info changes, generate a new key
			$('#form-edit--productID').on('change', generateKey);

			$('#form-edit--productName').addClass('validate').data('validation', [
				{ rule: 'required', message: 'Please give your product a name' }
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
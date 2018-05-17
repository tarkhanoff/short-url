<form action='/' method='POST' class="url-form">
	<h1 class="h3 mb-3 font-weight-normal">URL Shortener</h1>
  
	<?php if(isset($data['errors'])) : ?>
	<div class="alert alert-danger" role="alert">
		<ul>
			<?php foreach ($data['errors'] as $error) : ?>
			<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
  
	<input type="text" name="inputURL" value="<?php echo isset($data['full_url']) ? $data['full_url'] : ''; ?>" class="form-control" placeholder="Full URL" required autofocus />
	<input type="text" name="inputShort" value="<?php echo isset($data['short_name']) ? $data['short_name'] : ''; ?>" class="form-control" placeholder="Short name (optional)" />

	<input type="submit" class="btn btn-lg btn-primary btn-block" name="btnCreate" value="Create short URL" />
</form>
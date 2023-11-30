	
	<div class="title">
		Welcome to the <?php echo \PHPBook\Http\Configuration\Meta::getName(); ?> HTTP Documentation
	</div>

	<div class="link">
        <a target="_blank" href="<?php echo $path; ?><?php echo \PHPBook\Http\Configuration\Directory::getDocs(); ?>/postman">	Download POSTMAN Schema
    	</a>
    </div>

	<?php if ((\PHPBook\Http\Configuration\Meta::getEmail()) or (\PHPBook\Http\Configuration\Meta::getPhone())): ?>
		<p class="text">
			You can contact us to report issues or anything else... <br />
			<?php if (\PHPBook\Http\Configuration\Meta::getEmail()): ?>
				<strong>Email</strong> <?php echo \PHPBook\Http\Configuration\Meta::getEmail(); ?> <br />
			<?php endif; ?>
			<?php if (\PHPBook\Http\Configuration\Meta::getPhone()): ?>
				<strong>Phone</strong> <?php echo \PHPBook\Http\Configuration\Meta::getPhone(); ?> <br />
			<?php endif; ?>
		</p>
	<?php endif; ?>
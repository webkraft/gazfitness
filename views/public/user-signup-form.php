<div id="sds-signup-form">
<?php if( $validated === true ) : ?>
<div class="message message-success">
	<p>Thanks for registering</p>
</div>
<?php endif; ?>
<?php if( $validated === false ) : ?>
<div class="message message-error">
	<p>Please complete the required fields.</p>
</div>
<?php endif; ?>
<?php if(isset($errors)) : ?>
<div class="message message-success">
	<p>Thanks for registering</p>
</div>
<?php endif; ?>
<form action="" method="post" class="sds-form">
		<input type="text" name="first_name" value="<?php echo $_POST['first_name']; ?>" placeholder="First Name*" maxlength="14" required/>
		<input type="text" name="last_name" value="<?php echo $_POST['last_name']; ?>" placeholder="Last Name*" maxlength="14" required/>
		<input type="email" name="email" value="<?php echo $_POST['email']; ?>" placeholder="Email Address*" maxlength="30" required/>
		<input type="submit" name="Submit" value="Signup & Pay later"/>
</form>
</div>
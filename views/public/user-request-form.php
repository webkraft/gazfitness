<?php if( $validated === true ) : ?>
<div class="message message-success">
	<p>Your request has been submitted and is now with our team.</p>
</div>
<?php endif; ?>
<?php if( $validated === false ) : ?>
<div class="message message-error">
	<p>Please complete the required fields.</p>
</div>
<?php endif; ?>

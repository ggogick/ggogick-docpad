---
layout: contact
unclean: true
---
<!--
If you're using this:

Set the following in the leading PHP section:
	$from - your 'from' e-mail address
	$to - the e-mail address you want contact form notifications sent to
	$secret - your reCaptcha secret key

Set the following in the HTML form code:
	 data-sitekey - your reCaptcha site key

Please note this form will not really work out too well if you're running DocPad 
through DocPad itself; it's intended to be served via Apache with DocPad providing 
the rest of the site as a set of static files.

In terms of metadata, you'll notice this file is somewhat unique.  The contact
layout sets a header which includes the recaptcha.js.  The 'unclean' metadata
parameter ensures this page isn't messed with via the cleanurls plugin.
-->
<?php
$result = '';
$from = 'YOUR FROM ADDRESS';
$to = 'YOUR DESTINATION ADDRESS';
$secret = "YOUR RECAPTCHA SECRET KEY";
$subject = 'New Message from Contact Form';

if (isset($_POST["submit"]) && !empty($_POST['submit'])) {
	if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])):
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		$responseData = json_decode($verifyResponse);
		if($responseData->success) {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$message = $_POST['message'];

			if(!$_POST['name']) {
				$errName = 'Please enter your name.';
			}
			if(!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$errEmail = 'Please enter a valid e-mail address.';
			}
			if(!$_POST['message']) {
				$errMessage = 'Please enter your message.';
			}
			if(!isset($errName) && !isset($errEmail) && !isset($errMessage)) {
				$headers = array();
				$headers[] = "MIME-Version: 1.0";
				$headers[] = "Content-type: text/plain; charset=iso-8859-1";
				$headers[] = "From: " . $from . " <" . $from . ">";
				$headers[] = "Subject: {$subject}";
				$headers[] = "X-Mailer: PHP/" . phpversion();

				$body = "From: $name\nE-mail: $email\n\nMessage:\n--------------------\n$message";
				if(mail($to, $subject, $body, implode("\r\n", $headers))) {
					$result='<div class="alert alert-success">Message sent.  Please note that there may be some delay while I get back to you.</div>';
					$_POST = array();
				} else {
					$result='<div class="alert alert-danger">Sorry, there was an error sending your message.  Please try again later.</div>';
				}
			}
		} else {
			$result='<div class="alert alert-danger">Anti-synth verification failed.</div>';
		}
	else:
		$errCaptcha = 'Please verify that you are not a synth.  Ad Victoriam.';
	endif;
}
?>

<h3>Contact Me</h3>
<hr>

<form class="form-horizontal" role="form" method="post" action="index.php">
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<?php echo $result; ?>
		</div>
	</div>
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" name="name" placeholder="First & Last Name" value="<?php if(isset($_POST['name'])) { echo htmlspecialchars($_POST['name']); } ?>">
			<?php if(isset($errName)) { echo "<p class='text-danger'>$errName</p>"; } ?>
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php if(isset($_POST['email'])) { echo htmlspecialchars($_POST['email']); } ?>">
			<?php if(isset($errEmail)) { echo "<p class='text-danger'>$errEmail</p>"; } ?>
		</div>
	</div>
	<div class="form-group">
		<label for="message" class="col-sm-2 control-label">Message</label>
		<div class="col-sm-10">
			<textarea class="form-control" rows="4" name="message"><?php if(isset($_POST['message'])) { echo htmlspecialchars($_POST['message']); } ?></textarea>
			<?php if(isset($errMessage)) { echo "<p class='text-danger'>$errMessage</p>"; } ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<div class="g-recaptcha" data-sitekey="YOUR RECAPTCHA SITE KEY"></div>
			<?php if(isset($errCaptcha)) { echo "<p class='text-danger'>$errCaptcha</p>"; } ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
		</div>
	</div>
</form>


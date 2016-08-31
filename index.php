<?php include 'header.php'; ?>

<div class="container">
	<?php
		if(isset($_SESSION['logged_user'])){
			include 'spaAuthorized.html';
		}
		else{
			include 'notauthorized.php';
		}
	?>
</div>

<?php include 'footer.php'; ?>
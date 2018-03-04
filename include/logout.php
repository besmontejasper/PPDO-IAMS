<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<?php  if (isset($_SESSION['username'])) : ?>
			<p> <a href="login.php?logout='1'" style="color: red; letter-spacing: 0.03em; font-family: "Raleway", sans-serif";">LOGOUT</a> </p>
<?php endif ?>
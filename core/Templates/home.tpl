<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{$pagetitle}</title>
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h3>{$longtitle ?: $pagetitle}</h3>
		{$content}
	</div>
</body>
<footer>
	<script src="/assets/js/jquery-2.1.4.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
</footer>
</html>
<!DOCTYPE html>
<html>
	<head>
		<title>STAFF</title>

        <meta name="viewport" content="width=device-width">

		<?= $this->tag->stylesheetLink('css/style.css') ?>
        <?= $this->tag->stylesheetLink('bootstrap/css/bootstrap.css') ?>
	</head>
	<body>

		<?= $this->getContent() ?>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<?= $this->tag->javascriptInclude('bootstrap/js/bootstrap.js') ?>
	</body>
</html>
<!DOCTYPE html>
<html>
	<head>
		<title>STAFF</title>

        <meta name="viewport" content="width=device-width">

		<?= $this->tag->stylesheetLink('css/style.css') ?>
        <?= $this->tag->stylesheetLink('bootstrap/css/bootstrap.css') ?>

        <?= $this->tag->javascriptInclude('js/jquery-2.2.4.js') ?>
	</head>
	<body>

		<?= $this->getContent() ?>

        <?= $this->tag->javascriptInclude('bootstrap/js/bootstrap.js') ?>

	</body>
</html>
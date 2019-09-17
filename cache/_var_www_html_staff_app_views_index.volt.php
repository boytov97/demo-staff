<!DOCTYPE html>
<html>
	<head>
		<title>STAFF</title>

        <meta name="viewport" content="width=device-width">

        <?= $this->tag->stylesheetLink('bootstrap/css/bootstrap.min.css') ?>
        <?= $this->tag->stylesheetLink('font-awesome/css/font-awesome.min.css') ?>
        <?= $this->tag->stylesheetLink('css/style.css') ?>

        <?= $this->tag->javascriptInclude('js/jquery-2.2.4.js') ?>
	</head>
	<body>

		<?= $this->getContent() ?>

        <?= $this->tag->javascriptInclude('bootstrap/js/bootstrap.js') ?>
        <?= $this->tag->javascriptInclude('js/script.js') ?>

	</body>
</html>
<!DOCTYPE html>
<html>
	<head>
		<title>STAFF</title>

        <meta name="viewport" content="width=device-width">

		{{ stylesheet_link('css/style.css') }}
        {{ stylesheet_link('bootstrap/css/bootstrap.css') }}
	</head>
	<body>

		{{ content() }}

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		{{ javascript_include('bootstrap/js/bootstrap.js') }}
		{{ javascript_include('js/script.js') }}
	</body>
</html>
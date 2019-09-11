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

        {{ javascript_include('js/jquery-2.2.4.js') }}
        {{ javascript_include('bootstrap/js/bootstrap.js') }}
        {{ javascript_include('js/script.js') }}

	</body>
</html>
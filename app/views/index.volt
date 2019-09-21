<!DOCTYPE html>
<html>
	<head>
		<title>STAFF {{ (title is defined) ? '- ' ~ title : '' }}</title>

        <meta name="viewport" content="width=device-width">

        {{ stylesheet_link('bootstrap/css/bootstrap.min.css') }}
        {{ stylesheet_link('font-awesome/css/font-awesome.min.css') }}
        {{ stylesheet_link('css/style.css') }}

        {{ javascript_include('js/jquery-2.2.4.js') }}
	</head>
	<body>

		{{ content() }}

        {{ javascript_include('bootstrap/js/bootstrap.js') }}
        {{ javascript_include('js/script.js') }}

	</body>
</html>
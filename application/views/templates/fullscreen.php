<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>{title}</title>

	{_meta}

	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">

	<link href="<?= base_url('assets/css/tailwind.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet">

</head>

<body class="h-screen overflow-hidden flex items-center justify-center bg-blue-600">

	{content}

</body>

</html>
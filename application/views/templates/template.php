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

	<link href="<?= base_url('assets/css/all.css'); ?>" rel="stylesheet">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js" integrity="sha256-XF29CBwU1MWLaGEnsELogU6Y6rcc5nCkhhx89nFMIDQ=" crossorigin="anonymous"></script>

	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

	{header}

	<!--Container-->
	<div class="container w-full mx-auto pt-20">

		{content}

	</div>
	<!--/container-->

	{footer}

	
	
	<script>
		/*Toggle dropdown list*/
		/*https://gist.github.com/slavapas/593e8e50cf4cc16ac972afcbad4f70c8*/

		var userMenuDiv = document.getElementById("userMenu");
		var userMenu = document.getElementById("userButton");

		var navMenuDiv = document.getElementById("nav-content");
		var navMenu = document.getElementById("nav-toggle");

		document.onclick = check;

		function check(e) {
			var target = (e && e.target) || (event && event.srcElement);

			//User Menu
			if (!checkParent(target, userMenuDiv)) {
				// click NOT on the menu
				if (checkParent(target, userMenu)) {
					// click on the link
					if (userMenuDiv.classList.contains("invisible")) {
						userMenuDiv.classList.remove("invisible");
					} else {
						userMenuDiv.classList.add("invisible");
					}
				} else {
					// click both outside link and outside menu, hide menu
					userMenuDiv.classList.add("invisible");
				}
			}

			//Nav Menu
			if (!checkParent(target, navMenuDiv)) {
				// click NOT on the menu
				if (checkParent(target, navMenu)) {
					// click on the link
					if (navMenuDiv.classList.contains("hidden")) {
						navMenuDiv.classList.remove("hidden");
					} else {
						navMenuDiv.classList.add("hidden");
					}
				} else {
					// click both outside link and outside menu, hide menu
					navMenuDiv.classList.add("hidden");
				}
			}

		}

		function checkParent(t, elm) {
			while (t.parentNode) {
				if (t == elm) {
					return true;
				}
				t = t.parentNode;
			}
			return false;
		}
	</script>

</body>

</html>
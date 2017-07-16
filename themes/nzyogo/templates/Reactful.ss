<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="$ContentLocale" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="$ContentLocale" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="$ContentLocale" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="$ContentLocale" class="no-js"> <!--<![endif]-->
	<head>
		<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<script src="https://use.typekit.net/bsg3kpw.js"></script>
		<script>try{Typekit.load({ async: true });}catch(e){}</script>
		<% base_tag %>
		$MetaTags(true)

		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
		$getCSS
		<script src="$ThemeDir/js/lib/modernizr.min.js"></script>

	</head>
	<body class="page-$URLSegment.LowerCase<% if $isMobile %> mobile<% end_if %> page-type-$BodyClass.LowerCase">
		<header id="header"></header>
		<main id="main"></main>
		<footer id="footer"></footer>
        <script type="text/javascript">$Products</script>
		<script type="text/javascript">window.security_id = '$SecurityID';</script>
		<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script type="text/babel" src="/themes/default/js/reactful/app.js"></script>
	</body>
</html>

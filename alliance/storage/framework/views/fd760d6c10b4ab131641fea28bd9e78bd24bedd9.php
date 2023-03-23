<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

	<title><?php echo e(config('app.name', 'Laravel')); ?> - Planetarion Tools</title>

	<!-- Scripts -->
	<script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
	<!-- <script src="https://kit.fontawesome.com/cf26084f99.js" crossorigin="anonymous"></script> -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css" integrity="sha384-REHJTs1r2ErKBuJB0fCK99gCYsVjwxHrSU0N7I1zl9vZbggVJXRMsv/sLlOAGb4M" crossorigin="anonymous">

	<!-- Fonts -->
	<link rel="dns-prefetch" href="https://fonts.gstatic.com">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!-- Styles -->
	<link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

	<style>
		body{background:gray;}
		body div#app div nav.sidebar {background:#cc60c8!important;}
		body div#app a.home{background:#aa2790!important} 
		body div#app .navbar-nav .nav-link.active {background:purple!important;}
		body div#app .navbar-nav .nav-link:hover {background:#d995d7!important;}
		body div#app div.search-bar {background:#aa2790;} 
		body div#app div.nav-header {background:purple;}
		body div#app div.app-content {background:gray;margin:40px 20px!important;}
		body div#app div.card-header {background:#aa5ba7;margin-top:30px;padding:10px 15px;color:white;}
		body div#app div.app-content .table-striped tr{background:#efc9f3;}
		body div#app div.app-content div.card-body{background:#efc9f3;}
		body div#app div.app-content a {color:purple!important;}
		body div#app div.app-content tr.is_alliance {background:#c96ac6!important;}
		body div#app ul.small-nav li.nav-item a{color:white;}
		body div#app .table {color:#7e4e84!important;} 
		body div#app .table-sort, body div#app .table-sort:hover, body div#app .card {color:#7e4e84!important;}
		body div#app .sticky-col {background:none!important;}
		body div#app p.help {color:#7e4e84!important;}
	</style> 
</head>
<body>

	<div id="app"></div>

</body>
</html>
<?php /**PATH /var/www/vhosts/webby.domain.tld/alliance/resources/views/layouts/app.blade.php ENDPATH**/ ?>
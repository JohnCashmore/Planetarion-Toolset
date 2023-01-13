<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

	<title><?php echo e(config('app.name', 'Laravel')); ?> - Planetarion Tools</title>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

	<!-- Styles -->
	<link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

	<link rel="icon" href="/ultoria.ico" type="image/gif">
	<style>
		@media  only screen and (min-width: 600px) {
			div#mainContainer {padding-bottom:185px!important;background:url('images/banner.jpg') center bottom no-repeat;}
			div#mainContainer img {display:block;margin:auto;margin-bottom:20px}
		}
	</style>
</head>
<body style="background:url('images/starry.png') repeat center #181818">

	<div class="py-4" id="mainContainer">
		<img src="images/logo.png" alt="{{ config('app.name', 'Laravel') } - Planetarion Tools" />
	  <?php echo $__env->yieldContent('content'); ?>

	</div>

</body>
</html>
<?php /**PATH /usr/local/apache/htdocs/your.domain.tld/resources/views/layouts/blank.blade.php ENDPATH**/ ?>
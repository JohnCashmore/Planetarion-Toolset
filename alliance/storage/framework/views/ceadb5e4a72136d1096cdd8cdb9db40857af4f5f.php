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
		
		@media  only screen and (max-width: 600px) {
			div.unicorn img {max-width:380px;}
		}
		div#mainContainer img {display:block;margin:auto;margin-bottom:20px}
		div.unicorn {font-size:50px;color:pink;text-align:center;font-weight:bold;font-family:sans-serif;padding-top:30px;}
		div.card div.card-body{background:pink!important;color:#cc11c5;}
		div.card div.card-header{background:#cc11c5;}
		div.card div.card-body div.form-group label, div.card div.card-body div.form-group a, div.card div.card-body div.form-group label a:hover, div.card div.card-body div.form-group a:visited {color:#cc11c5;}
		div.card div.card-body div.form-group a, div.card div.card-body div.form-group label a:hover, div.card div.card-body div.form-group a:visited {font-weight:bold;}
		div.card div.card-body div.form-group button {background:#cc11c5;}
	
	</style>
</head>
<body style="background:url('images/starry2.png') repeat center pink">
	<div class="unicorn"><img src="images/header.png" /></div>  

	<div class="py-4" id="mainContainer">
	  <?php echo $__env->yieldContent('content'); ?>
	</div>
	
 
</body>
</html> 
<?php /**PATH /usr/local/apache/htdocs/your.domain.tld/alliance/resources/views/layouts/blank.blade.php ENDPATH**/ ?>
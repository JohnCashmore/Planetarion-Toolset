<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header" style="padding-left:20px">Registered</div>

				<div class="card-body">
					<p>Your account has been registered.</p>
					<a class="btn btn-primary" href="<?php echo e(route('login')); ?>">
						<?php echo e(__('Login')); ?>

					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/patools/domains/webby.domain.tld/alliance/resources/views/auth/registered.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
  <div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header" style="padding-left:20px"><?php echo e(__('Login')); ?></div>

				<div class="card-body">
					<?php if($message = Session::get('installed')): ?>
					<div class="alert alert-success alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
						  <strong><?php echo e($message); ?></strong>
					</div>
					<?php endif; ?>
					<form method="POST" action="<?php echo e(route('login')); ?>" aria-label="<?php echo e(__('Login')); ?>">
						<?php echo csrf_field(); ?>

						<div class="form-group row">
							<label for="email" class="col-sm-4 col-form-label text-md-right"><?php echo e(__('E-Mail Address')); ?></label>

							<div class="col-md-6">
								<input id="email" type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" required autofocus>

								<?php if($errors->has('email')): ?>
									<span class="invalid-feedback" role="alert">
										<strong><?php echo e($errors->first('email')); ?></strong>
									</span>
								<?php endif; ?>
							</div>
						</div>

						<div class="form-group row">
							<label for="password" class="col-md-4 col-form-label text-md-right"><?php echo e(__('Password')); ?></label>

							<div class="col-md-6">
								<input id="password" type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" required>

								<?php if($errors->has('password')): ?>
									<span class="invalid-feedback" role="alert">
										<strong><?php echo e($errors->first('password')); ?></strong>
									</span>
								<?php endif; ?>
							</div>
						</div>

						<div class="form-group row">
							<div class="col-md-6 offset-md-4">
								<div class="checkbox">
									<label><input type="checkbox" name="remember" style="position:relative;top:8px;" <?php echo e(old('remember') ? 'checked' : ''); ?>> &nbsp;<?php echo e(__('Remember Me')); ?></label>
								</div>
							</div>
						</div>

						<div class="form-group row mb-0">
							<div class="col-md-8 offset-md-4">
								<button type="submit" class="btn btn-primary">
									<?php echo e(__('Login')); ?>

								</button>

								or <a href="<?php echo e(route('register')); ?>">
									<?php echo e(__('Register')); ?>

								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/apache/htdocs/your.domain.tld/resources/views/auth/login.blade.php ENDPATH**/ ?>
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-xl-6 col-lg-8">
			<div class="card shadow-sm">
				<div class="card-header bg-primary text-white">
					<h5 class="mb-0">
						<i class="fas fa-key me-2"></i><?= __('user.update_password') ?>
					</h5>
				</div>
				<div class="card-body">
					<?php if (isset($validate_err)) { ?>
						<div class="alert alert-danger d-flex align-items-center" role="alert">
							<i class="fas fa-exclamation-circle me-2"></i>
							<div><?= $validate_err ?></div>
						</div>
					<?php } ?>
					<?php flashMsg($this->session->flashdata('flash')); ?>
					
					<form id="demo-form2" name="myform" novalidate action="<?php echo base_url() ?>usercontrol/changePassword" method="post">
						<div class="mb-4">
							<label class="form-label fw-bold">
								<i class="fas fa-lock me-1 text-muted"></i><?= __('user.old_password') ?>
								<span class="text-danger">*</span>
							</label>
							<div class="input-group input-group-lg">
								<span class="input-group-text">
									<i class="fas fa-lock"></i>
								</span>
								<input type="password" class="form-control" required name="old_pass" placeholder="<?= __('user.enter_old_password') ?>" value="">
							</div>
							<?php echo form_error('old_pass'); ?>
						</div>
						
						<div class="mb-4">
							<label class="form-label fw-bold">
								<i class="fas fa-key me-1 text-primary"></i><?= __('user.new_password') ?>
								<span class="text-danger">*</span>
							</label>
							<div class="input-group input-group-lg">
								<span class="input-group-text">
									<i class="fas fa-key"></i>
								</span>
								<input type="password" class="form-control" required name="password" placeholder="<?= __('user.enter_new_password') ?>" value="">
							</div>
							<?php echo form_error('password'); ?>
						</div>
						
						<div class="mb-4">
							<label class="form-label fw-bold">
								<i class="fas fa-check-circle me-1 text-success"></i><?= __('user.confirm_password') ?>
								<span class="text-danger">*</span>
							</label>
							<div class="input-group input-group-lg">
								<span class="input-group-text">
									<i class="fas fa-check-circle"></i>
								</span>
								<input type="password" class="form-control" required name="conf_password" placeholder="<?= __('user.confirm_new_password') ?>" value="">
							</div>
							<?php echo form_error('conf_password'); ?>
						</div>
						
						<div class="d-grid">
							<button type="submit" class="btn btn-success btn-lg">
								<i class="fas fa-save me-2"></i><?= __('user.update_password') ?>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


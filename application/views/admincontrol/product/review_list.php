<?php if ($reviews == null) { ?>
<tr>
	<td colspan="7" class="text-center py-5">
		<div class="d-flex justify-content-center align-items-center flex-column">
			<i class="bi bi-star display-1 text-muted mb-3"></i>
			<h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
			<p class="text-muted"><?= __('admin.no_reviews_found') ?></p>
		</div>
	</td>
</tr>
<?php } else {
	foreach($reviews as $review) { ?>
		<tr class="review-row">
			<td class="text-center align-middle">
				<div class="position-relative">
					<img class="rounded-circle shadow-sm" width="50px" height="50px" 
						 src="<?php echo $review['avatar']!="" ? base_url('assets/images/users/'. $review['avatar']) : base_url('assets/images/no-user_image.jpg') ?>" 
						 style="object-fit: cover;">
				</div>
			</td>
			<td class="align-middle">
				<div class="fw-semibold text-dark"><?= $review['firstname'] ?> <?= $review['lastname'] ?></div>
				<small class="text-muted"><?= __('admin.customer') ?></small>
			</td>
			<td class="align-middle">
				<div class="fw-medium text-dark"><?= $review['product_name'] ?></div>
			</td>
			<td class="align-middle">
				<div class="review-text" style="max-width: 300px;">
					<?php if(strlen($review['rating_comments']) > 100): ?>
						<div class="text-truncate" title="<?= htmlspecialchars($review['rating_comments']) ?>">
							<?= substr($review['rating_comments'], 0, 100) ?>...
						</div>
					<?php else: ?>
						<?= $review['rating_comments'] ?>
					<?php endif; ?>
				</div>
			</td>
			<td class="text-center align-middle">
				<div class="d-flex justify-content-center align-items-center gap-1 mb-1">
					<?php 
					$rating = (int)$review['rating_number'];
					for($i = 1; $i <= 5; $i++): ?>
						<i class="bi bi-star<?= $i <= $rating ? '-fill text-warning' : ' text-muted' ?>" style="font-size: 14px;"></i>
					<?php endfor; ?>
				</div>
				<small class="text-muted"><?= $rating ?>/5</small>
			</td>
			<td class="align-middle">
				<div class="fw-medium"><?= dateGlobalFormat($review['rating_created']) ?></div>
				<small class="text-muted"><?= date('H:i', strtotime($review['rating_created'])) ?></small>
			</td>
			<td class="text-center align-middle">
				<div class="btn-group btn-group-sm" role="group">
					<?php if($review['rating_created_by'] == $user_id) { ?>
						<a href="<?= base_url('admincontrol/manage_review/'.$review['rating_id'])  ?>" 
						   class="btn btn-outline-primary" title="<?= __('admin.edit') ?>">
							<i class="bi bi-pencil-square"></i>
						</a>
					<?php } else { ?>
						<button class="btn btn-outline-secondary disabled" title="<?= __('admin.edit') ?>">
							<i class="bi bi-pencil-square"></i>
						</button>
					<?php } ?>
					<button class="btn btn-outline-danger delete-review-btn" 
							data-id="<?= $review['rating_id'] ?>" 
							title="<?= __('admin.delete') ?>">
						<i class="bi bi-trash"></i>
					</button>
				</div>
			</td>
		</tr>
	<?php } 
} ?>
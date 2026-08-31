<?php 
if($tool['slug'])
	$a_link = base_url($tool['slug']);
else 
	$a_link = $tool['redirectLocation'][0];
?>
<div class="modal-header bg-primary text-white">
	<h5 class="modal-title">
		<i class="bi bi-code-slash me-2"></i><?= $tool['name'] ?>
	</h5>
	<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body overflow-auto p-0" style="max-height:70vh;">
	<ul class="nav nav-tabs nav-fill border-bottom" id="TabsNav">
		<li class="nav-item">
			<a class="nav-link active py-3" data-bs-toggle="pill" href="#code-code">
				<i class="bi bi-code-square me-2"></i><?= __('admin.html_code') ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link py-3" data-bs-toggle="pill" href="#code-link">
				<i class="bi bi-link-45deg me-2"></i><?= __('admin.share_link') ?>
			</a>
		</li>
	</ul>
	<div class="tab-content p-3">
		<div class="tab-pane fade show active" id="code-code">
			<?php if($tool['type'] == 'banner'){ ?>
				<?php foreach ($tool['ads'] as $key => $value){ 
					$code = htmlspecialchars('<a href="'.$tool['redirectLocation'][0].'"><img src="'. $value['value'] .'" ></a>'); ?>
					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-body">
							<div class="row g-3">
								<div class="col-md-4 text-center">
									<div class="bg-light rounded p-2 mb-2">
										<img src="<?= $value['value'] ?>" class="img-fluid rounded" style="max-height: 120px;">
									</div>
									<span class="badge bg-secondary"><?= $value['size'] ?></span>
								</div>
								<div class="col-md-8">
									<div class="mb-2">
										<label class="form-label text-muted small mb-1">
											<i class="bi bi-link me-1"></i><?= __('admin.target_url') ?>
										</label>
										<div class="text-truncate small bg-light rounded p-2"><?= $tool['target_link'] ?></div>
									</div>
									<div>
										<label class="form-label text-muted small mb-1">
											<i class="bi bi-code me-1"></i><?= __('admin.code') ?>
										</label>
										<textarea onclick="this.focus();this.select()" class="form-control form-control-sm code-textarea" readonly rows="3"><?= $code ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

			<?php } else if($tool['type'] == 'text_ads'){
				$value = $tool['ads'][0];
				if ($value) {
					$style = array(
						'padding: 5px',
						'white-space: pre-line',
						'border: solid ' . (isset($value['text_border_color']) ? $value['text_border_color'] : 'transparent') . ' 1px',
						'display: inline-block',
						'line-height: 1',
						'color: ' . (isset($value['text_color']) ? $value['text_color'] : 'inherit'),
						'background-color: ' . (isset($value['text_bg_color']) ? $value['text_bg_color'] : 'transparent'),
						'font-size: ' . (isset($value['text_size']) ? $value['text_size'] . 'px' : 'inherit')
					);
					$code = '<span style="' . implode(";", $style) . '">';
					$code .= '<a style="display: block; color: inherit; font-size: inherit;" href="' . $tool['redirectLocation'][0] . '">';
					$code .= $value['value'];
					$code .= '</a></span>';
			?>
				<div class="card border-0 shadow-sm">
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-link me-1"></i><?= __('admin.target_url') ?>
							</label>
							<div class="text-truncate small bg-light rounded p-2"><?= $tool['target_link'] ?></div>
						</div>
						<div class="mb-3">
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-code me-1"></i><?= __('admin.code') ?>
							</label>
							<textarea onclick="this.focus();this.select()" class="form-control form-control-sm code-textarea" readonly rows="4"><?= htmlspecialchars($code) ?></textarea>
						</div>
						<div>
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-eye me-1"></i><?= __('admin.preview') ?>
							</label>
							<div class="preview-code bg-light rounded p-3 text-center"><?= $code ?></div>
						</div>
					</div>
				</div>
			<?php }

			} else if($tool['type'] == 'link_ads'){
				$value = $tool['ads'][0];
				if($value){
					$code = '<a rel="sponsored" href="'.$tool['redirectLocation'][0].'">'. $value['value'] .'</a>';
			?>
				<div class="card border-0 shadow-sm">
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-link me-1"></i><?= __('admin.target_url') ?>
							</label>
							<div class="text-truncate small bg-light rounded p-2"><?= $tool['target_link'] ?></div>
						</div>
						<div class="mb-3">
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-code me-1"></i><?= __('admin.code') ?>
							</label>
							<textarea onclick="this.focus();this.select()" class="form-control form-control-sm code-input code-textarea" readonly rows="3"><?= htmlspecialchars($code) ?></textarea>
						</div>
						<div>
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-eye me-1"></i><?= __('admin.preview') ?>
							</label>
							<div class="preview-code bg-light rounded p-3"><?= $code ?></div>
						</div>
					</div>
				</div>
			<?php } 

			} else if($tool['type'] == 'video_ads'){
				$value = $tool['ads'][0];
				if($value){
					$code = isset($value['iframe']) ? $value['iframe'] : '';
					$code .= '<div style="display:table;clear:both;"></div><br><a style="-moz-box-shadow:inset 0 1px 0 0 #fff;-webkit-box-shadow:inset 0 1px 0 0 #fff;box-shadow:inset 0 1px 0 0 #fff;background:-webkit-gradient(linear,left top,left bottom,color-stop(.05,#f9f9f9),color-stop(1,#e9e9e9));background:-moz-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-webkit-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-o-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-ms-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:linear-gradient(to bottom,#f9f9f9 5%,#e9e9e9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#f9f9f9\', endColorstr=\'#e9e9e9\', GradientType=0);background-color:#f9f9f9;-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;border:1px solid #dcdcdc;display:inline-block;cursor:pointer;color:#666;font-family:Arial;font-size:15px;font-weight:700;padding:6px 24px;text-decoration:none;text-shadow:0 1px 0 #fff" href="'.$tool['redirectLocation'][0].'">'. $value['size'] .'</a>';
			?>
				<div class="card border-0 shadow-sm">
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-link me-1"></i><?= __('admin.target_url') ?>
							</label>
							<div class="text-truncate small bg-light rounded p-2"><?= $tool['target_link'] ?></div>
						</div>
						<div class="mb-3">
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-code me-1"></i><?= __('admin.code') ?>
							</label>
							<textarea onclick="this.focus();this.select()" class="form-control form-control-sm code-input code-textarea" readonly rows="4"><?= htmlspecialchars($code) ?></textarea>
						</div>
						<div>
							<label class="form-label text-muted small mb-1">
								<i class="bi bi-eye me-1"></i><?= __('admin.preview') ?>
							</label>
							<div class="preview-code bg-light rounded p-3 text-center"><?= $code ?></div>
						</div>
					</div>
				</div>
			<?php } 
			} ?>
		</div>

		<div class="tab-pane fade" id="code-link">
			<div class="card border-0 shadow-sm">
				<div class="card-body">
					<label class="form-label text-muted small mb-2">
						<i class="bi bi-link-45deg me-1"></i><?= __('admin.share_link') ?>
					</label>
					<div class="input-group">
						<input type="text" readonly value="<?= $a_link ?>" class="form-control copy-code" onclick="this.focus();this.select()">
						<button class="btn btn-primary" type="button" data-social-share data-share-url="<?= $a_link ?>" data-share-title="" data-share-desc="" title="<?= __('user.share') ?>">
							<i class="bi bi-share-fill"></i>
						</button>
					</div>
					<div class="text-muted small mt-2">
						<i class="bi bi-info-circle me-1"></i><?= __('user.click_to_copy') ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer bg-light">
	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
		<i class="bi bi-x-lg me-1"></i><?= __('user.close') ?>
	</button>
</div>
<?= $social_share_modal ?? '';?>
<script type="text/javascript">
$(".preview-code *").on('click',function(event){
	event.preventDefault();
	event.stopPropagation();
	return false;
});
</script>

<?php
/**
 * Default theme — Checkout order-notes / confirm step partial
 *
 * @contract  Store API v1 — fragment: checkout_confirm (rendered inside checkout.php)
 * @see       Store_cart_payload::page_checkout()
 *
 * VARIABLES (inherited from checkout scope)
 *   $order_comment_setting  array  Order comment configuration {status: bool, title: string[]}
 */
?>
<?php if($order_comment_setting['status']){ ?>
	<?php foreach ($order_comment_setting['title'] as $key => $value) { ?>
		<fieldset>
			<legend><?= $value ?></legend>
			<div class="mb-3 required">
				<input type="hidden" name="comment[<?= $key ?>][title]" value="<?= $value ?>">
				<textarea name="comment[<?= $key ?>][comment]" id="comment_textarea<?= $key ?>" class="form-control" rows="5" placeholder="<?= __('store.comment') ?>"></textarea>
			</div>
		</fieldset>
	<?php } ?>
<?php } ?>
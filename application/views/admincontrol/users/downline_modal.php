<?php if(false){ ?>
<div class="table-rep-plugin">
  <div class="table-responsive b-0" data-pattern="priority-columns">
     <table id="table1" class="table  table-striped">
		<tr data-level="header" class="header">
			<th></th>
			<th><?= __('admin.first_name') ?></th>
			<th><?= __('admin.last_name') ?></th>
			<th><?= __('admin.email') ?></th>
			<th><?= __('admin.clicks') ?> <br><?= __('admin.commissions') ?></th>
			<th><?= __('admin.action_click') ?><br><?= __('admin.commission') ?></th>
			<th><?= __('admin.sales_commissions') ?></th>
			<th><?= __('admin.paid_unpaid') ?> <br> <?= __('admin.commissions') ?></th>
			<th><?= __('admin.in_request') ?></th>
			<th><?= __('admin.total') ?> <br> <?= __('admin.commissions') ?></th>
		</tr>
		
		<?php foreach ($mylevel as $key => $value) { ?>
			<tr data-level="1" id="level_<?= $key+1 ?>_1">
				<td></td>
				<td class="data"><?= $value['firstname'] ?></td>
				<td class="data"><?= $value['lastname'] ?></td>
				<td class="data"><?= $value['email'] ?></td>
				<td class="data">
					<?php echo (int)$value['click'] + (int)$value['external_click'] + (int)$value['form_click']+ (int)$value['aff_click']; ?> /
					<?php echo c_format($value['click_commission']) ?>
				</td>
				<td class="data"><?= (int)$value['external_action_click'] ?>/ <?= c_format($value['action_click_commission']) ?></td>
				<td class="data"><?php echo c_format($value['amount'] + $value['external_sale_amount']); ?> / <?php echo c_format($value['sale_commission']); ?></td>
				<td class="data"><?php echo c_format($value['paid_commition']); ?> / <?php echo c_format($value['unpaid_commition']); ?></td>
				<td class="data"><?php echo c_format($value['in_request_commiton']); ?></td>
				<td class="data"><?php echo c_format($value['all_commition']); ?></td>
			</tr>
			<?php if(isset($value['children'])){
					foreach ($value['children'] as $key1 => $value1) { ?>
					<tr data-level="2" id="level_<?= $key + 1 ?>_<?= $key1+2 ?>">
						<td></td>
						<td class="data"><?= $value1['firstname'] ?></td>
						<td class="data"><?= $value1['lastname'] ?></td>
						<td class="data"><?= $value1['email'] ?></td>
						<td class="data">
							<?php echo (int)$value1['click'] + (int)$value1['external_click'] + (int)$value1['form_click']+ (int)$value1['aff_click']; ?> /
							<?php echo c_format($value1['click_commission']) ?>
						</td>
						<td class="data"><?= (int)$value1['external_action_click'] ?>/ <?= c_format($value1['action_click_commission']) ?></td>
						<td class="data"><?php echo c_format($value1['amount'] + $value1['external_sale_amount']); ?> / <?php echo c_format($value1['sale_commission']); ?></td>
						<td class="data"><?php echo c_format($value1['paid_commition']); ?> / <?php echo c_format($value1['unpaid_commition']); ?></td>
						<td class="data"><?php echo c_format($value1['in_request_commiton']); ?></td>
						<td class="data"><?php echo c_format($value1['all_commition']); ?></td>
					</tr>
					<?php if(isset($value1['children'])){
							foreach ($value1['children'] as $key2 => $value3) { ?>
							<tr data-level="3" id="level_<?= $key + 1 ?>_<?= $key1+2 ?>_<?= $key2+3 ?>">
								<td></td>
								<td class="data"><?= $value3['firstname'] ?></td>
								<td class="data"><?= $value3['lastname'] ?></td>
								<td class="data"><?= $value3['email'] ?></td>
								<td class="data">
									<?php echo (int)$value3['click'] + (int)$value3['external_click'] + (int)$value3['form_click']+ (int)$value3['aff_click']; ?> /
									<?php echo c_format($value3['click_commission']) ?>
								</td>
								<td class="data"><?= (int)$value3['external_action_click'] ?>/ <?= c_format($value3['action_click_commission']) ?></td>
								<td class="data"><?php echo c_format($value3['amount'] + $value3['external_sale_amount']); ?> / <?php echo c_format($value3['sale_commission']); ?></td>
								<td class="data"><?php echo c_format($value3['paid_commition']); ?> / <?php echo c_format($value3['unpaid_commition']); ?></td>
								<td class="data"><?php echo c_format($value3['in_request_commiton']); ?></td>
								<td class="data"><?php echo c_format($value3['all_commition']); ?></td>
							</tr>
					<?php } } ?>
					<?php } ?>
			<?php } ?>
		<?php } ?>
	</table>
</div>
</div>
<script>
	$(document).ready(function () {
		var table1 = $('#table1').tabelize();
	});
</script>
<?php } ?>


<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>"></script>
<link href="<?= base_url('assets/plugins/fancytree/skin-win8/ui.fancytree.css') ?>" rel="stylesheet" />
<script src="<?= base_url('assets/plugins/fancytree/jquery.fancytree.js') ?>"></script>
<script src="<?= base_url('assets/plugins/fancytree/jquery.fancytree.table.js') ?>"></script>

<script type="text/javascript">
	$(function() {
		$("#tree").fancytree({
			checkbox: false,
			debugLevel: 0,
			checkboxAutoHide: true,
			titlesTabbable: true,
			source: { url: "<?= base_url('admincontrol/myreferal_ajax/'. $user->id) ?>" },
			extensions: ["table"],
			table: {
				indentation: 10,
				nodeColumnIdx: 0,
				checkboxColumnIdx: 0,
			},
			renderColumns: function(event, data) {
				var node = data.node,
				$tdList = $(node.tr).find(">td");

				var col1 = node.data.email;
				var col2 = (node.data.click + node.data.external_click + node.data.form_click + node.data.aff_click) + ' / ' + node.data.click_commission;
				var col3 = node.data.external_action_click + "/" + node.data.action_click_commission;
				var col4 = node.data.amount_external_sale_amount + "/" + node.data.sale_commission;
				var col5 = node.data.paid_commition + "/" + node.data.unpaid_commition;
				var col6 = node.data.in_request_commiton;
				var col7 = node.data.all_commition;

				$tdList.eq(1).html(col1);
				$tdList.eq(2).html(col2);
				$tdList.eq(3).html(col3);
				$tdList.eq(4).html(col4);
				$tdList.eq(5).html(col5);
				$tdList.eq(6).html(col6);
				$tdList.eq(7).html(col7);
			},
			modifyChild: function(event, data) {
				data.tree.info(event.type, data);
			},
		})

		$("#tree").fancytree("getTree").expandAll();
	});
</script>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="btn-group" role="group">
        <button class="btn btn-outline-primary btn-sm" onclick='$("#tree").fancytree("getTree").expandAll();'>
            <i class="fa fa-expand me-1"></i><?= __('admin.open_all') ?>
        </button>
        <button class="btn btn-outline-secondary btn-sm" onclick='$("#tree").fancytree("getTree").expandAll(false);'>
            <i class="fa fa-compress me-1"></i><?= __('admin.close_all') ?>
        </button>
    </div>
</div>
<div class="table-responsive">
    <table id="tree" class="table table-hover align-middle">
        <colgroup>
            <col width="350px" />
            <col width="100px" />
            <col width="100px" />
            <col width="100px" />
            <col width="100px" />
            <col width="100px" />
            <col width="100px" />
            <col width="100px" />
        </colgroup>
        <thead class="table-primary">
            <tr>
                <th class="fw-semibold">
                    <i class="fa fa-user me-2 text-primary"></i><?= __('admin.name') ?>
                </th>
                <th class="fw-semibold text-center">
                    <i class="fa fa-envelope me-2 text-primary"></i><?= __('admin.email') ?>
                </th>
                <th class="fw-semibold text-center">
                    <i class="fa fa-mouse-pointer me-2 text-primary"></i><?= __('admin.clicks') ?> <br><small class="text-muted fw-normal"><?= __('admin.commissions') ?></small>
                </th>
                <th class="fw-semibold text-center">
                    <i class="fa fa-hand-pointer-o me-2 text-primary"></i><?= __('admin.action_click') ?><br><small class="text-muted fw-normal"><?= __('admin.commission') ?></small>
                </th>
                <th class="fw-semibold text-center">
                    <i class="fa fa-dollar me-2 text-primary"></i><?= __('admin.sales_commissions') ?>
                </th>
                <th class="fw-semibold text-center">
                    <i class="fa fa-money me-2 text-primary"></i><?= __('admin.paid_unpaid') ?> <br><small class="text-muted fw-normal"><?= __('admin.commissions') ?></small>
                </th>
                <th class="fw-semibold text-center">
                    <i class="fa fa-clock-o me-2 text-primary"></i><?= __('admin.in_request') ?>
                </th>
                <th class="fw-semibold text-center">
                    <i class="fa fa-calculator me-2 text-primary"></i><?= __('admin.total') ?> <br><small class="text-muted fw-normal"><?= __('admin.commissions') ?></small>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
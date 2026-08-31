<?php
$db =& get_instance();
$products = $db->Product_model;
?>
<?php foreach ($populer_users as $key => $users) { ?>
    <tr>
        <?php
        $flag = '';
        if ($users['sortname'] != '') {
            $flag = base_url('assets/template/images/flags/' . strtolower($users['sortname']) . '.png');
        }
        ?>
        <td><img class="top-affiliate-image" src="<?= $products->getAvatar($users['avatar']); ?>" alt="<?= $users['firstname'].' '.$users['lastname']; ?>" /><?= $users['firstname'].' '.$users['lastname']; ?></td>
        <td><?php if($flag): ?><img class="top-affiliate-country-flag" src="<?= $flag; ?>" alt="<?= strtoupper($users['sortname']) ?>" onerror="this.onerror=null;this.style.display='none'"><?php else: ?><span class="text-muted">—</span><?php endif; ?></td>
        <td><?= $fun_c_format($users['amount']); ?></td>
        <td><?= $fun_c_format($users['all_commition']); ?></td>
    </tr>
<?php } ?>
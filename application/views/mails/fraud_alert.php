<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<p style="font-weight:bold; font-size:24px; color:#000000; text-align:center;">Fraud Alert Detected</p>

<!-- Body Section -->
<table align="center" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <tr>
        <td style="padding: 20px;">
            <!-- Begin fraud alert message -->
			<p><strong>Fraud Alert:</strong> A potential fraudulent activity has been detected.</p>

			<?php if (!empty($error_message)): ?>
			<p><strong>Error Message:</strong> <?php echo $error_message; ?></p>
			<?php endif; ?>

			<?php if (!empty($ip_address)): ?>
			<p><strong>IP Address:</strong> <?php echo $ip_address; ?></p>
			<?php endif; ?>

			<?php if (!empty($user_id)): ?>
			<p><strong>User ID:</strong> <?php echo $user_id; ?></p>
			<?php endif; ?>

			<?php if (!empty($browser)): ?>
			<p><strong>Browser:</strong> <?php echo $browser . ' ' . $browser_version; ?></p>
			<?php endif; ?>

			<?php if (!empty($os_platform)): ?>
			<p><strong>Operating System:</strong> <?php echo $os_platform . ' ' . $os_version; ?></p>
			<?php endif; ?>

			<?php if (!empty($is_mobile)): ?>
			<p><strong>Is Mobile:</strong> <?php echo $is_mobile; ?></p>
			<?php endif; ?>

			<?php if (!empty($action_code)): ?>
			<p><strong>Action Code:</strong> <?php echo $action_code; ?></p>
			<?php endif; ?>

			<?php if (!empty($custom_fields)): ?>
			<p><strong>Custom Fields:</strong> <?php echo htmlspecialchars($custom_fields); ?></p>
			<?php endif; ?>

			<?php if (!empty($current_page_url)): ?>
			<p><strong>Current Page URL:</strong> <?php echo $current_page_url; ?></p>
			<?php endif; ?>

			<?php if (!empty($base_url)): ?>
			<p><strong>Base URL:</strong> <?php echo $base_url; ?></p>
			<?php endif; ?>

			<?php if (!empty($af_id)): ?>
			<p><strong>Affiliate ID:</strong> <?php echo $af_id; ?></p>
			<?php endif; ?>

			<?php if (!empty($script_name)): ?>
			<p><strong>Script Name:</strong> <?php echo $script_name; ?></p>
			<?php endif; ?>

			<?php if (!empty($restricted_vendors)): ?>
			<p><strong>Restricted Vendors:</strong> <?php echo $restricted_vendors; ?></p>
			<?php endif; ?>

			<!-- End fraud alert message -->
        </td>
    </tr>
</table>

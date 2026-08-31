<!-- API Content Area -->
<div class="api-content">
   <?= $this->load->view('document/user/doc_intro', null, true); ?>
   <?= $this->load->view('document/user/doc_user', ['registration_fields'=>$registration_fields], true); ?>
   <?= $this->load->view('document/user/doc_dashboard', null, true); ?>
   <?= $this->load->view('document/user/doc_aff_links', null, true); ?>
   <?= $this->load->view('document/user/doc_my_logs', null, true); ?>
   <?= $this->load->view('document/user/doc_my_network', null, true); ?>
   <?= $this->load->view('document/user/doc_user_reports', null, true); ?>
   <?= $this->load->view('document/user/doc_contact_to_admin', null, true); ?>
   <?= $this->load->view('document/user/doc_user_payments', null, true); ?>
   <?= $this->load->view('document/user/doc_payment_details', null, true); ?>
   <?= $this->load->view('document/user/doc_category', null, true); ?>
   <?= $this->load->view('document/user/doc_user_wallet', null, true); ?>
   <?= $this->load->view('document/user/doc_my_order', null, true); ?>
   <?= $this->load->view('document/user/doc_subscription_plan', null, true); ?>
   <?= $this->load->view('document/user/doc_vendor_market_place', null, true); ?>
   <?= $this->load->view('document/user/doc_vendor_market_tools', null, true); ?>
   <?= $this->load->view('document/user/doc_notification', null, true); ?>
</div>
<script src="<?= base_url('assets/template/js/') ?>pretty-print-json.js"></script>
<script type="text/javascript">
   $('.response-view').each(function( index ) {
      $(this).html(prettyPrintJson.toHtml(JSON.parse($(this).text())));
   });
</script>

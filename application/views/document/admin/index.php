<!-- Admin API Content Area -->
<div class="api-content">
   <?= $this->load->view('document/admin/intro', null, true); ?>
   <?= $this->load->view('document/admin/mobile_roadmap', null, true); ?>
   <?= $this->load->view('document/admin/dashboard', null, true); ?>
   <?= $this->load->view('document/admin/users', null, true); ?>
   <?= $this->load->view('document/admin/team', null, true); ?>
   <?= $this->load->view('document/admin/notifications', null, true); ?>
   <?= $this->load->view('document/admin/profile', null, true); ?>
   <?= $this->load->view('document/admin/withdrawals', null, true); ?>
   <?= $this->load->view('document/admin/reports', null, true); ?>
   <?= $this->load->view('document/admin/wallet', null, true); ?>
   <?= $this->load->view('document/admin/programs', null, true); ?>
   <?= $this->load->view('document/admin/campaigns', null, true); ?>
   <?= $this->load->view('document/admin/categories', null, true); ?>
   <?= $this->load->view('document/admin/orders', null, true); ?>
   <?= $this->load->view('document/admin/tickets', null, true); ?>
   <?= $this->load->view('document/admin/settings_summary', null, true); ?>
   <?= $this->load->view('document/admin/membership', null, true); ?>
   <?= $this->load->view('document/admin/click_logs', null, true); ?>
   <?= $this->load->view('document/admin/examples', null, true); ?>
</div>
<script src="<?= base_url('assets/template/js/') ?>pretty-print-json.js"></script>
<script type="text/javascript">
   $('.response-view').each(function( index ) {
      $(this).html(prettyPrintJson.toHtml(JSON.parse($(this).text())));
   });
</script>

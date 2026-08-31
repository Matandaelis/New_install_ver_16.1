
<link rel="stylesheet" href="<?= base_url('assets/template/css/jquery.dataTables.min.css') ?>">
<script src="<?= base_url('assets/template/js/jquery.validate.min.js') ?>" type="text/javascript" ></script>
<script src="<?= base_url('assets/template/js/jquery.dataTables.min.js') ?>" type="text/javascript" ></script>
<div class="modal fade" id="importUsersModel" tabindex="-1" aria-labelledby="importUsersModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importUsersModelLabel"><?= __('admin.add_ticket_subject') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frm_addsubject">
                    <div class="mb-3">
                        <label class="form-label fw-bold"><?= __('admin.ticket_subject');?></label>
                        <input type="text" name="subject" id="tssubject" placeholder="<?= __('admin.ticket_subject');?>" class="form-control">
                        <input type="hidden" name="id" value="0" id="tceditid">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit"><?=__('admin.add')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-tag me-2"></i>
                            <?= __('admin.ticket_subject') ?>
                        </h5>
                        <button type="button" class="btn btn-outline-light" id="btnAddSubject">
                            <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_ticket_subject') ?>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl_tickets_subject" class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th><?= __('admin.tickets_sr_no') ?></th>
                                    <th><?= __('admin.ticket_subject') ?></th>
                                    <th class="text-center"><?= __('admin.action')?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" async="">

	$(document).ready(function() {

		
		$("#btnAddSubject").click(function(e){
			e.preventDefault();
			$("#importUsersModel").find('.modal-title').text('Add Task Subject');
			$("#frm_addsubject").find('button').text('Add')
			$("#tceditid").val(0);
			$("#tssubject").val('');
			$("#importUsersModel").modal('show');

		});

		$("#frm_addsubject").validate({
			rules:{
				'subject':{required:true}
			},
			messages:{
				'subject':{
					"required":'<?= __('admin.add_ticket_subject_required') ?>'
				}
			},
			submitHandler: function(form, event) {
				event.preventDefault();
				$.ajax({
					url: '<?=base_url()?>'+'Tickets/addticketssubject',
					type: "POST",
					data: $("#frm_addsubject").serialize(),
					dataType: 'json',
					beforeSend: function() {

					},
					success: function(result) {

						if(result.status){
							if ($.fn.DataTable.isDataTable('#tbl_tickets_subject')) {
								$('#tbl_tickets_subject').DataTable().destroy();
							}
							ticketsSubjectDatables();
							$("#importUsersModel").modal('hide');	
						} else {
							alert(result.message)
						}
					},
					error: function() {
						;
					},
					complete: function() {

					}
				});
			},

		})
		$(document).on('click','.edit',function(e){
			e.preventDefault();
			var id = $(this).data('id');
			var title = $(this).data('title');
			$("#tceditid").val(id);

			$("#tssubject").val(title);

			$("#importUsersModel").modal('show');
			$("#importUsersModel").find('.modal-title').text('<?= __('admin.update_task_subject') ?>');
			$("#frm_addsubject").find('button').text('Update')

		});
		$(document).on('click', '.removets', function(e) {
			e.preventDefault();
			if(confirm('<?= __('admin.are_you_sure')?>')){
				var id = $(this).data('id');
				var $that = $(this);
				$.ajax({
					url:'<?= base_url('tickets/actiontasksubject') ?>',
					type:'POST',
					dataType:'json',
					data:{id:id,action:1},
					async:false,
					success:function(data){
						if(data.status) {
							if ($.fn.DataTable.isDataTable('#tbl_tickets_subject')) {
								$('#tbl_tickets_subject').DataTable().destroy();
							}
							ticketsSubjectDatables();	
						}
						else {
							alert(data.message)
						}
					},
				});
			}
		});

		function ticketsSubjectDatables() {
			$("#tbl_tickets_subject").DataTable({
				pageLength: 10,
				lengthMenu:[[ 10, 25, 50, -1], [10, 25, 50, "All"]],
				processing: true,
				serverSide: true,
				 sScrollY: '100%',
				serverMethod: "post",
				oLanguage: {
					sProcessing: "Loading...",
				},
				ajax: {
					url: '<?=base_url()?>' + "tickets/getticketssubject",
					type: "POST",
					cache: true,
				},
				order: [[0, "DESC"]],
				columns: [
				{ data: "id", targets: 0,  },
				{ data: "subject", targets: 1 },
				{ data: "action", targets: 2,orderable:false }
				],
			});
		}
		ticketsSubjectDatables();
	});
</script>


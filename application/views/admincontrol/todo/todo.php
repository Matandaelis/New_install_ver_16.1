<script src="<?= base_url('assets/template/js/moment.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/template/js/main.min.js') ?>"></script>
<script src="<?= base_url('assets/template/js/fullcalendar.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/template/css/fullcalendar.min.css') ?>"/>

<div class="container-fluid todolist-page">
    <div class="row">
        <div class="col-12">

            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-check me-2 fs-4"></i>
                        <div>
                            <h4 class="mb-0 fw-bold"><?= __('admin.to_do_list') ?></h4>
                            <small class="opacity-75"><?= __('admin.manage_your_tasks') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-2"></i>
                            <div>
                                <strong><?= __('admin.how_to_use') ?>:</strong>
                                <?= __('admin.todolist_instructions') ?>
                            </div>
                        </div>
                    </div>
                    
                    <div id="calendar" class="todo-calendar"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add/Edit Todo Modal -->
<div class="modal fade" id="modal-add-todo" tabindex="-1" aria-labelledby="modal-add-todoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-add-todoLabel">
                    <i class="bi bi-plus-circle me-2"></i><?= __('admin.add_to_do_list') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="todoListItemid" value="0">
                <div class="mb-3">
                    <label class="form-label fw-bold"><?= __('admin.task_note') ?></label>
                    <input type="text" class="form-control" id="todonotesCal" placeholder="<?= __('admin.enter_task_description') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold"><?= __('admin.due_date') ?></label>
                    <input type="text" class="form-control" id="tododateCal" placeholder="<?= __('admin.select_date') ?>" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i><?= __('admin.cancel') ?>
                </button>
                <button type="button" class="btn btn-primary" id="btnAddCalnote">
                    <i class="bi bi-plus-circle me-1"></i><?= __('admin.add') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var calendar;
    
    function initCalender() {
        calendar = $('#calendar').fullCalendar({
            themeSystem: 'bootstrap4',
            defaultView: 'month',
            editable: false,
            disableDragging:true,
            header: {
                left: 'today',
                center: 'title',
                right: 'prev,next,month'
            },
            buttonText: {
                prev: '<?= __('admin.prev') ?>',
                next: '<?= __('admin.next') ?>',
                month:'<?= __('admin.month') ?>',
                today:'<?= __('admin.today') ?>',
            },
            monthNames: [
                '<?= __('admin.jan') ?>', '<?= __('admin.feb') ?>', '<?= __('admin.mar') ?>', 
                '<?= __('admin.apr') ?>', '<?= __('admin.may') ?>', '<?= __('admin.jun') ?>', 
                '<?= __('admin.jul') ?>', '<?= __('admin.aug') ?>', '<?= __('admin.sep') ?>', 
                '<?= __('admin.oct') ?>', '<?= __('admin.nov') ?>', '<?= __('admin.dec') ?>'
            ], 
            dayNamesShort: [
                '<?= __('admin.sun') ?>', '<?= __('admin.mon') ?>', '<?= __('admin.tue') ?>',
                '<?= __('admin.wed') ?>', '<?= __('admin.thu') ?>', '<?= __('admin.fri') ?>',
                '<?= __('admin.sat') ?>'
            ],
            events:'<?= base_url() ?>todo/getodolist?isCalView=1',
            eventRender: function(event, element) {
                if(event.is_done == "1"){
                    element.find('.fc-title').addClass('isTodaCompleted').attr('title','<?= __('admin.click_to_view_update') ?>');
                }
                var isTodoDone = event.is_done == "1" ? 'checked':'';
                element.find(".fc-content").prepend("<div class='float-start'><input type='checkbox' data-id='"+event.id+"' class='completedTodoCalView me-2' "+isTodoDone+"></div>");
                element.find(".fc-content").prepend("<div class='float-end'><a class='removetodolisCalView text-danger' data-id='"+event.id+"'><i class='bi bi-trash'></i></a></div>");
            },
            dayClick: function(events) {
                var check = moment(events._d).format('YYYY-MM-DD');
                var today = moment(new Date()).format('YYYY-MM-DD');
                
                if(check < today) {
                    if(typeof showToast === 'function') {
                        showToast('<?= __("admin.error") ?>', '<?= addslashes(__("admin.you_cant_select_past_dates")) ?>', 'error', 3000);
                    }
                    return;
                }
                
                $("#tododateCal").val(check);
                $("#todonotesCal").val('');
                $("#todoListItemid").val(0);
                $('#btnAddCalnote').html('<i class="bi bi-plus-circle me-1"></i><?= __('admin.add') ?>');
                $('#modal-add-todo').modal('show');
            },
            eventClick: function(event, jsEvent, view) {
                var cu = jsEvent.target;
                
                if($(cu).closest('.removetodolisCalView').length) {
                    deleteTodo($(cu).closest('.removetodolisCalView').data('id'));
                    return false;
                }

                if($(cu).hasClass('completedTodoCalView')) {
                    toggleTodoComplete($(cu));
                    return false;	
                }
                
                $('#todonotesCal').val(event.notes);
                $("#todoListItemid").val(event.id);
                $("#tododateCal").val(moment(event.start).format('YYYY-MM-DD'));
                $('#modal-add-todo').modal('show');
                $('#btnAddCalnote').html('<i class="bi bi-pencil me-1"></i><?= __('admin.update') ?>');
            },
        });
    }
    
    function deleteTodo(id) {
        if(confirm('<?= addslashes(__('admin.are_you_sure')) ?>')) {
            $("#modal-add-todo").modal('hide');
            
            $.ajax({
                url:'<?= base_url('todo/actiontodolist') ?>',
                type:'POST',
                dataType:'json',
                data:{id:id, action:1},
                success:function(data){
                    if(data.status) {
                        calendar.fullCalendar('destroy');
                        initCalender();
                        if(typeof showToast === 'function') {
                            showToast('<?= __("admin.success") ?>', data.message, 'success', 3000);
                        }
                    } else {
                        if(typeof showToast === 'function') {
                            showToast('<?= __("admin.error") ?>', data.message, 'error', 3000);
                        }
                    }
                },
            });
        }
    }
    
    function toggleTodoComplete($elem) {
        var id = $elem.data('id');
        var is_completed = $elem.prop('checked') ? 1 : 0;
        
        $.ajax({
            url:'<?= base_url('todo/actiontodolist') ?>',
            type:'POST',
            dataType:'json',
            data:{id:id, action:2, is_completed:is_completed},
            success:function(data){
                if(data.status) {
                    calendar.fullCalendar('destroy');
                    initCalender();
                    if(typeof showToast === 'function') {
                        showToast('<?= __("admin.success") ?>', data.message, 'success', 3000);
                    }
                } else {
                    if(typeof showToast === 'function') {
                        showToast('<?= __("admin.error") ?>', data.message, 'error', 3000);
                    }
                }
            },
        });
    }
    
    initCalender();
    
    $("#btnAddCalnote").click(function(){
        var todo_date = $("#tododateCal").val();
        var todonotesCal = $("#todonotesCal").val();
        var id = $("#todoListItemid").val();

        if (!todonotesCal) {
            if(typeof showToast === 'function') {
                showToast('<?= __("admin.error") ?>', '<?= addslashes(__("admin.task_note_required")) ?>', 'error', 3000);
            }
            return;
        }
        
        if (!todo_date) {
            if(typeof showToast === 'function') {
                showToast('<?= __("admin.error") ?>', '<?= addslashes(__("admin.date_required")) ?>', 'error', 3000);
            }
            return;
        }

        $.ajax({
            url:'<?= base_url('todo/addtodolist') ?>',
            type:'POST',
            dataType:'json',
            data: {note:todonotesCal, id:id, todo_date:todo_date},
            beforeSend:function(){ 
                $("#btnAddCalnote").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.saving') ?>...'); 
            },
            complete:function(){ 
                $("#btnAddCalnote").prop('disabled', false); 
            },
            success:function(data){
                if(data.status){
                    $("#tododateCal,#todonotesCal").val('');
                    $("#todoListItemid").val(0);
                    $('#btnAddCalnote').html('<i class="bi bi-plus-circle me-1"></i><?= __("admin.add") ?>');
                    $('#modal-add-todo').modal('hide');
                    calendar.fullCalendar('destroy');
                    initCalender();
                    if(typeof showToast === 'function') {
                        showToast('<?= __("admin.success") ?>', data.message, 'success', 3000);
                    }
                } else {
                    if(typeof showToast === 'function') {
                        showToast('<?= __("admin.error") ?>', data.message, 'error', 3000);
                    }
                }
            },
        });
    });
});
</script>

<style>
.removetodolisCalView {
    cursor: pointer;
    z-index: 9999999;
}
.isTodaCompleted {
    text-decoration: line-through;
    opacity: 0.6;
}
.todo-calendar {
    min-height: 600px;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-ticket-detailed me-2"></i>
                                <?= __('admin.ticket_details') ?>
                            </h4>
                            <p class="mb-0 opacity-75"><?=__('admin.ticket_subject')?>: <strong><?=$details['subjectName']?></strong></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong><?=__('admin.ticket_status')?>:</strong> <?=$statusNAme?></p>
                            <p class="mb-0"><strong><?=__('admin.ticket_date')?>:</strong> <span id="datetime"><?=$details['created_at']?></span></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong><?=__('admin.email')?>:</strong> <?=$userEmail?></p>
                            <label class="form-label"><?= __('admin.ticket_status');?></label>
                            <select id="tickets_status" class="form-select">
                                <?php foreach ($status as $key => $value): $isSelected = $details['status'] ==$key ?'selected':''; ?>
                                    <option value="<?=$key?>" <?=$isSelected?>><?=$value?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-xl-12">
                        <div class="order-chat-section border-0">
                         <input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $details['ticket_id']; ?>"/>
                         

                         <div class="card mb-3">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-reply me-2"></i>
                                    <?= __('admin.tickets_reply')?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form class="chat-message-form" id="frmSendMsg" method="post">
                                    <div class="mb-3">
                                        <label class="form-label"><?= __('admin.message') ?></label>
                                        <textarea class="summernote" name="sendMessage" id="sendMessage" rows="5"></textarea>
                                        <input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $details['ticket_id']; ?>">       
                                    </div>
                                    <div class="mb-3" id="addmoreAttachment">
                                        <label for="attachment" class="form-label"><?= __('admin.attachment')?></label>
                                        <input type="file" name="attachment[]" id="attachment" class="form-control">
                                    </div>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" id="addmore" class="btn btn-outline-info">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            <?= __('admin.tickets_add_more')?>
                                        </button>
                                        <button type="submit" id="chat-msg-send-btn" class="btn btn-primary">
                                            <i class="bi bi-send me-1"></i>
                                            <?= __('admin.tickets_reply')?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="order-chat-content custom-scrollbar ticket-message">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /page content -->


<script type="text/javascript">
    var attachmentURL = '<?=base_url()."assets/user_upload/"?>';
    var attachment_text = '<?= __('admin.attachment')?>';
    var ticket_status = '<?=$details['status']?>';
    $(document).ready(function() {
        $('.summernote').summernote({
            tabsize: 2,
            height: 300,
            toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
            ]
        });
        function convertUTCDateToLocalDate(date) {
            var newDate = new Date(date.getTime()+date.getTimezoneOffset()*60*1000);

            var offset = date.getTimezoneOffset() / 60;
            var hours = date.getHours();

            newDate.setHours(hours - offset);
            var date = newDate.toISOString().slice(0, 19).toString();
            return date.replace('T',' ');   
        }
        function getTickestReply() {
            $.ajax({
                url:'<?= base_url('tickets/getTickestReply') ?>',
                type:'POST',
                dataType:'json',
                data:{ticket_id:$("#ticket_id").val()},
                async:false,
                success:function(data){

                 var html="";
                 for (var i = 0; i < data.length; i++) {
                    var userType = data[i].user_type == 1 ? '<?= __('admin.tickets_user_admin')?>' : '<?= __('admin.tickets_user_customer')?>';
                    var userTypeClas = data[i].user_type == 1 ? 'bg-info' : 'bg-dark';
                    var username = data[i].user_type == 1 ? '<?= __('admin.tickets_user_administrator')?>' : '<?=$userName;?>';
                    var attachment= "";
                    if(data[i].message_type == 2 ){
                        var attachmentArr = jQuery.parseJSON(data[i].attachment);
                        for (var j = 0; j <attachmentArr.length; j++) {
                            if(attachmentArr[j]) {      
                                attachment += ` <a class="btn btn-primary" href="`+attachmentURL+attachmentArr[j]+`" target="_blank">`+attachment_text+`</a> `;
                            }
                        }
                    }
                    html += `<div class="card mt-3"><div class="card-header `+userTypeClas+` text-white"><div class="float-left"><i class="fa fa-user"></i>  `+username+` (`+userType+`)</div><div class="float-right">`+convertUTCDateToLocalDate(new Date(data[i].created_at))+`</div></div><div class="card-body"><p class="card-text">`+data[i].message+`</p>`+attachment+`</div></div>`;
                }
                $('.order-chat-content').html('');
                $('.order-chat-content').html(html);
                $('.order-chat-content').scrollTop($('.order-chat-content')[0].scrollHeight);

            },
        });
            
        }
        getTickestReply();
        function sendMessage(){
            var message = $('#sendMessage').val();
            var ticket_id = $('#ticket_id').val();
            if (message.trim() != '' || $('#attachment').val().trim() != '') {
                var formData = new FormData($('#frmSendMsg')[0]);
                $.ajax({
                    url: '<?=base_url()?>'+'tickets/sendMessage',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: "json",
                    beforeSend: function () {

                    },
                    success:function(data){
                        if(ticket_status == 3){
                            window.location.reload()
                        } else {
                            getTickestReply();
                        }
                    }
                });
                $('#sendMessage,#attachment').val('');
                $('.summernote').summernote('reset');
                $("#addmoreAttachment").html(`<label>attachment</label><input type="file" name="attachment[]" id="attachment" class="form-control">`);
                $("#replyToggle").click();

            } else {
                alert("<?= __('admin.message_cannot_be_blank') ?>");
            }
        }

        $("#frmSendMsg").on('submit', function (e) {
            e.preventDefault();
            sendMessage();
        });
        $("#chat-msg-send-btn").on('click', function (e) {
            e.preventDefault();
            sendMessage();
        });
        $("#addmore").click(function(event) {
            $("#addmoreAttachment").append(`<label>`+attachment_text+`</label><input type="file" name="attachment[]" id="attachment" class="form-control">`);
        });

        setTimeout(function(){
            console.log($('#datetime').text())
            console.log(convertUTCDateToLocalDate(new Date($('#datetime').text())))
        },500);
        $(document).on('change','#tickets_status',function(e){
            e.preventDefault();
            if(confirm('<?=__('admin.are_you_sure')?>')) {
                var ticket_id = $("#ticket_id").val();
                var status = $(this).val();
                $.ajax({
                    url:'<?= base_url('tickets/changeTicketStatus') ?>',
                    type:'POST',
                    dataType:'json',
                    data:{status:status,ticket_id:ticket_id},
                    async:false,
                    success:function(data){
                        if(data.status){
                            window.location.reload()
                        }
                    }
                })
            }
        })

    });
</script>


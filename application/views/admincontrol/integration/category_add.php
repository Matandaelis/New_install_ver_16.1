<div class="container-fluid px-4 pb-4">
  <?php $this->load->view('admincontrol/integration/_campaign_nav'); ?>
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm intg-table-card">
        <div class="card-header bg-white border-bottom">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 fw-bold"><?= isset($category['id']) ? __('admin.edit_category') : __('admin.add_category') ?></h5>
            <a href="<?= base_url('integration/integration_category') ?>" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-arrow-left me-1"></i><?= __('admin.back') ?>
            </a>
          </div>
        </div>
        
        <div class="card-body">
          <form method="post" action="" enctype="multipart/form-data" id="category-form">
            <input type="hidden" name="category_id" value="<?= isset($category['id']) ? $category['id'] : '' ?>">
            
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('admin.category_name') ?> <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" 
                       value="<?= isset($category['name']) ? htmlspecialchars($category['name']) : '' ?>" 
                       placeholder="<?= __('admin.enter_category_name') ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold"><?= __('admin.parent_category') ?></label>
                <select name="parent_id" class="form-select">
                  <option value=""><?= __('admin.select_parent_category') ?></option>
                  <?php foreach ($p_categories as $cat) { ?>
                    <option <?= (isset($category['parent_id']) && $category['parent_id'] == $cat['id']) ? 'selected' : '' ?> value="<?= $cat['id'] ?>">
                      <?= htmlspecialchars($cat['name']) ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            
            <div class="intg-form-footer mt-4 pt-3 border-top">
              <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('integration/integration_category') ?>" class="btn btn-outline-secondary rounded-pill">
                  <i class="bi bi-x-lg me-1"></i><?= __('admin.cancel') ?>
                </a>
                <button type="submit" class="btn btn-primary rounded-pill btn-submit">
                  <i class="bi bi-save me-1"></i><?= __('admin.save') ?>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(".btn-submit").on('click',function(evt){
        evt.preventDefault();
        var formData = new FormData($("#category-form")[0]);
        formData = formDataFilter(formData);
        $this = $("#category-form");
        var $btn = $(".btn-submit");
        var originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.loading') ?>...');
        
        $.ajax({
            type:'POST',
            dataType:'json',
            cache:false,
            contentType: false,
            processData: false,
            data:formData,
            error:function(){ $btn.prop('disabled', false).html(originalHtml); },
            success:function(result){
                $btn.prop('disabled', false).html(originalHtml);
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(result['location']){ window.location = result['location']; }

                if(result['errors']){
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.parents(".form-group, .mb-4, .col-md-6").addClass("has-error");
                            $ele.after("<span class='text-danger d-block small'>"+ j +"</span>");
                        }
                    });
                }
            },
        })
        return false;
    });
</script>

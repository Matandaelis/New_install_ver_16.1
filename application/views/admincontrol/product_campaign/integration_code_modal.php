<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <div class="d-flex align-items-center">
                <i class="bi bi-code-slash me-2"></i>
                <h5 class="modal-title mb-0">
                    <?= $name." ".__('admin.integration_on_website') ?> 
                </h5>
            </div>
            <button type="button" class="btn-close btn-close-white" aria-label="Close" data-bs-dismiss="modal"></button>
        </div>

        <?php 
        function ___h($text,$lan){
            $text = implode("\n", $text);
            $text = htmlentities($text);
            $text = '<div class="lang-copy" ><div class="copy">'.__('admin.copy').'</div><pre class="language-'.$lan.'"><code class="language-'.$lan.'">'.$text.'</code></pre></div>';
            return $text;
        }

        $base_url  = base_url();
        ?>

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/integration/prism/css.css') ?>?v=<?= av() ?>">
        <script type="text/javascript" src="<?= base_url('assets/integration/prism/js.js') ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/integration/prism/clipboard.min.js') ?>"></script>
        
        <div class="modal-body">
            <!-- Product Information Card -->
            <div class="card border-0 bg-light mb-4">
                <div class="card-body">
                    <h6 class="card-title text-primary mb-3">
                        <i class="bi bi-info-circle me-2"></i><?= __('admin.product_information') ?>
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <strong><?= __('admin.product_name') ?>:</strong><br>
                            <span class="text-muted"><?= htmlspecialchars($product->product_name); ?></span>
                        </div>
                        <div class="col-md-4">
                            <strong><?= __('admin.product_price') ?>:</strong><br>
                            <span class="text-success fw-bold"><?= c_format($product->product_price); ?></span>
                        </div>
                        <div class="col-md-4">
                            <strong><?= __('admin.product_purchase_url') ?>:</strong><br>
                            <a target="_blank" href="<?= $product->product_url; ?>" class="text-decoration-none">
                                <i class="bi bi-box-arrow-up-right me-1"></i><?= __('admin.view_product') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Integration Instructions -->
            <div class="row g-4">
                <!-- Step 1: Add Script -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-1-circle me-2"></i><?= __('admin.add_following_script_to_page_footer') ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php
                            $code = array();
                            $code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
                            echo ___h($code,'html');
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Add Button Attribute -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0">
                                <i class="bi bi-2-circle me-2"></i><?= __('admin.add_following_attribute_to_buy_button') ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <h6 class="alert-heading">
                                    <i class="bi bi-info-circle me-2"></i><?= __('admin.instructions') ?>
                                </h6>
                                <ul class="mb-0">
                                    <li><?= __('admin.classified_integration_instruction_1') ?></li>
                                    <li><?= __('admin.classified_integration_instruction_2') ?></li>
                                </ul>
                            </div>
                                <?php
                                $productsCampaignCode = _encrypt_decrypt($product->product_id);
                                $code = array();
                                $code[] = 'AffTrackerProcutCampaign="'.$productsCampaignCode.'"';
                                echo ___h($code,'html');
                                ?>
                                <h5 class="mt-4"><?= __('admin.example') ?></h5>
                                <?php
                                $code = array();
                                $code[] = '<button AffTrackerProcutCampaign="'.$productsCampaignCode.'">Buy Now</button>';
                                echo ___h($code,'html');
                                ?>
                            </div>
                            <?php for ($i=1; $i <=7 ; $i++) { ?>
                                <div>
                                    <h5 class="mt-4">Button Template <?=$i ?></h5>
                                    <?php
                                    $code = array();
                                    $code[] = '<img AffTrackerProcutCampaign="'.$productsCampaignCode.'" src="'.base_url('store/show_classified_buy_button/'.$product->product_id.'/'.$i).'" width="500" >';
                                    echo ___h($code,'html');
                                    ?>
                                    <img src="<?= base_url('store/show_classified_buy_button/'.$product->product_id.'/'.$i); ?>" width="500"/>
                                </div>
                            <?php } ?>
                        </section>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer bg-light">
            <div class="d-flex justify-content-between w-100">
                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    <?= __('admin.integration_help_text') ?>
                </div>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle me-1"></i><?= __('admin.got_it') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){
            $(".token.string").each(function(){
                var c = $(this).text().replace(/[^a-z_0-9\s]/gi, '')
                $(this).addClass(c)
            });
            $(".auto-fill-filed input").trigger("keyup");

            const clipboard = new Clipboard('.copy', {
              target: (trigger) => {
                return trigger.nextElementSibling;
            }
        });

            clipboard.on('success', (event) => {
              event.trigger.textContent = '<?= __('admin.copied') ?>';
              setTimeout(() => {
                event.clearSelection();
                event.trigger.textContent = '<?= __('admin.copy') ?>';
            }, 2000);
          });

        }, 1000);
    })
</script>
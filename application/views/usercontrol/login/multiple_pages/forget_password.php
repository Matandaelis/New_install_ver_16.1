<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<a href="<?= base_url('/'); ?>" class="mp-back-home front_button_color front_button_hover_color front_button_text_color">
    <i class="bi bi-arrow-left"></i> <?= __('front.back_to_homepage') ?>
</a>

<div class="mp-auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Left Banner -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="mp-auth-banner" style="background: url('<?= base_url('assets/login/multiple_pages/img/forgat.png') ?>');">
                    <div class="mp-auth-shape mp-auth-shape-1"></div>
                    <div class="mp-auth-shape mp-auth-shape-2"></div>
                    <div class="mp-auth-shape mp-auth-shape-3"></div>
                    <div class="mp-auth-banner-content">
                        <i class="bi bi-shield-lock front_theme_text_color"></i>
                        <h1><?= __('front.forgot_your_password') ?></h1>
                        <a class="mp-auth-banner-btn front_button_color front_button_hover_color front_button_text_color" href="<?= site_url('/login') ?>">
                            <?= __('front.log_in') ?> <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Form -->
            <div class="col-lg-6">
                <div class="mp-auth-form-panel">
                    <div class="mp-auth-form-inner">
                        <div class="mp-auth-form-wrap">
                            <div class="mp-auth-logo">
                                <a href="<?= base_url() ?>">
                                    <img src="<?= $logo ?>" <?= (!empty($theme_settings[0]) && $theme_settings[0]->custom_logo_size && (int)$theme_settings[0]->log_custom_width > 0 && (int)$theme_settings[0]->log_custom_height > 0) ? 'class="customLogoClass"' : '' ?> alt="<?= $setting['heading'] ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/login/multiple_pages/img/logo.png') ?>'">
                                </a>
                            </div>

                            <div class="mp-auth-title">
                                <h3><?= __('front.forgot_password') ?></h3>
                                <p><?= __('front.email_sent_instructions') ?></p>
                            </div>

                            <form class="mp-auth-form reset-password-form" id="reset-password-form">
                                <div class="form-group">
                                    <input class="form-control" name="email" placeholder="<?= __('front.email') ?>" type="email">
                                </div>

                                <input class="mp-btn-submit front_button_color front_button_hover_color front_button_text_color" type="submit" value="<?= __('front.send_reset_link') ?>">

                                <?= render_recaptcha_html('affiliate_forgot') ?>
                            </form>

                            <div class="text-center">
                                <a href="<?= base_url('/login') ?>"><?= __('front.log_in') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>

<script type="text/javascript">
    (function ($) {
        $.fn.btn = function (action) {
            var self = $(this);
            if (action == 'loading') { $(self).addClass("btn-loading"); }
            if (action == 'reset') { $(self).removeClass("btn-loading"); }
        }
    })(jQuery);

    $('.reset-password-form').on('submit',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url('auth/forget') ?>',
            type:'POST',
            dataType:'json',
            data: $this.serialize(),
            beforeSend:function(){ $this.find(".mp-btn-submit").btn("loading"); },
            complete:function(){ $this.find(".mp-btn-submit").btn("reset"); },
            success:function(json){
                $this.find(".is-invalid").removeClass("is-invalid");
                $this.find("span.invalid-feedback,.success-msg").remove();

                if(json['success']){
                    $this.find(".mp-btn-submit").before("<div class='alert success-msg alert-success'> " + json['success'] + "</div>");
                }
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $formGroup = $ele.parents(".form-group");
                            $ele.addClass("is-invalid");
                            if($formGroup.find(".input-group").length){
                                $formGroup.find(".input-group").after("<span class='bg-white d-block invalid-feedback'>"+ j +"</span>");
                            } else {
                                $ele.after("<span class='invalid-feedback'>"+ j +"</span>");
                            }
                        }
                    })
                }
                if(json['redirect']){ window.location = json['redirect']; }
            },
        })
        return false;
    });
</script>

<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<?php
$faq_banner_title = (isset($theme_settings[0])) ? $theme_settings[0]->faq_banner_title : null;
$faq_section_title = (isset($theme_settings[0])) ? $theme_settings[0]->faq_section_title : null;
$faq_section_subtitle = (isset($theme_settings[0])) ? $theme_settings[0]->faq_section_subtitle : null;
$faq_banner_image = (isset($theme_settings[0])) ? $theme_settings[0]->faq_banner_image : null;

if ($faq_banner_image != '' || !empty($faq_banner_image)) {
    $faq_banner = base_url('assets/images/theme_images/'.$faq_banner_image);
} else {
    $faq_banner = base_url('assets/login/multiple_pages/img/faq-bg.jpg');
}
?>

<!-- Inner Hero -->
<section class="mp-inner-hero" style="background-image: url('<?= $faq_banner; ?>');">
    <div class="container">
        <h1><?= (!empty($faq_banner_title)) ? $faq_banner_title : __('front.faq_title');?></h1>
    </div>
</section>

<!-- FAQ Content -->
<section class="mp-inner-page">
    <div class="container">
        <div class="mp-section-title">
            <div class="mp-section-icon"><i class="bi bi-question-circle"></i></div>
            <h2 class="front_theme_text_color"><?= (!empty($faq_section_title)) ? $faq_section_title : "What is Lorem Ipsum?";?></h2>
            <p><?= (!empty($faq_section_subtitle)) ? $faq_section_subtitle : "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";?></p>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-lg-10">
                <div class="mp-faq-accordion" id="accordionFaq">
                    <?php
                    if(isset($theme_faqs)) {
                        foreach($theme_faqs as $faq) {
                            if($faq->status == 1) {
                    ?>
                    <div class="card">
                        <div class="card-header" id="faq-sec-<?= $faq->faq_id; ?>">
                            <h2>
                                <button class="<?= (isset($is_faq_available)) ? "collapsed": ""; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $faq->faq_id; ?>" aria-expanded="<?= (isset($is_faq_available)) ? "false": "true"; ?>" aria-controls="collapse-<?= $faq->faq_id; ?>">
                                    <?= (!empty($faq->faq_question)) ? $faq->faq_question : __('front.faq_question_if_not_exist'); ?>?
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapse-<?= $faq->faq_id; ?>" class="collapse <?= (!isset($is_faq_available)) ? "show": ""; ?>" aria-labelledby="faq-sec-<?= $faq->faq_id; ?>" data-bs-parent="#accordionFaq">
                            <div class="card-body">
                                <?= (!empty($faq->faq_answer)) ? $faq->faq_answer : __('front.faq_answer_if_not_exist'); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                            $is_faq_available = true;
                            }
                        }
                    }
                    ?>

                    <?php if(!isset($is_faq_available)) { ?>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2>
                                <button type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    Where can I get some? <i class="bi bi-chevron-down"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionFaq">
                            <div class="card-body">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2>
                                <button class="collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Where can I get some? <i class="bi bi-chevron-down"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFaq">
                            <div class="card-body">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h2>
                                <button class="collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Where can I get some? <i class="bi bi-chevron-down"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFaq">
                            <div class="card-body">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>

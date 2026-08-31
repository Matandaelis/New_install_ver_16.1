<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<?php $page_banner_image = ($page_data->page_banner_image != null) ? base_url("assets/images/theme_images/".$page_data->page_banner_image) : base_url('assets/login/multiple_pages/img/inner-hero-bg.jpg'); ?>

<!-- Inner Hero -->
<section class="mp-inner-hero" style="background-image: url('<?= $page_banner_image ?>');">
    <div class="container">
        <h1><?= (!empty($page_data->top_banner_title)) ? $page_data->top_banner_title : __('front.internal_page') ?></h1>
        <?php if(!empty($page_data->top_banner_sub_title)) { ?>
        <p><?= $page_data->top_banner_sub_title ?></p>
        <?php } ?>
    </div>
</section>

<!-- Page Content -->
<section class="mp-inner-page">
    <div class="container">
        <div class="mp-section-title">
            <div class="mp-section-icon"><i class="bi bi-file-earmark-text"></i></div>
            <h2 class="front_theme_text_color"><?= (!empty($page_data->page_content_title)) ? $page_data->page_content_title : "What is Lorem Ipsum?" ?></h2>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-lg-10">
                <div class="mp-inner-page-content">
                    <?php if(!empty($page_data->page_content)) {
                        echo $page_data->page_content;
                    } else { ?>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>

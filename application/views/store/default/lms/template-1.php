<?php
/**
 * Default theme — LMS Course Player layout (template-1)
 *
 * @contract  Store API v1 — page: lms (LMS course player)
 * @see       Store_cart_payload::page_lms()
 * @see       SSR only — full standalone HTML page (not wrapped in layout.php)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data (LMS requires auth)
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $course          array   Course data {id, name, description, image, lessons[], modules[]}
 *   $lesson          array   Current lesson {id, title, content, video_url, duration, ...}
 *   $enrollment      array   Student's enrollment record {progress, completed_lessons[], ...}
 *   $store_setting   array   Store settings (alias of global)
 *   $meta_title      string  Page title suffix
 *   $meta_description string Meta description
 *   $meta_image      string  OG image URL
 *
 * NOTE  This view renders a full standalone HTML page including its own <head>/<body>.
 *       It does NOT use layout.php — the LMS player has its own layout.
 */
?>
<!DOCTYPE html>
<html lang="en" data-lms-dark="0">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <meta name="description" content=""/>
   <meta name="author" content=""/>
   <?php if (isset($meta_title)) { ?>
      <meta property="og:title" content="<?= htmlspecialchars($meta_title) ?>"/>
   <?php } ?>
   <?php if (isset($meta_description)) { ?>
      <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>"/>
   <?php } ?>
   <?php if (isset($meta_image)) { ?>
      <meta property="og:image" content="<?= htmlspecialchars($meta_image) ?>"/>
   <?php } ?>
   <?php
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   ?>
   <meta property="og:url" content="<?= $actual_link ?>"/>
   <meta name="twitter:card" content="summary_large_image"/>
   <?php if (!empty($store_setting['favicon'])) { ?>
      <link rel="icon" href="<?= base_url('assets/images/site/' . $store_setting['favicon']) ?>" type="image/*" sizes="16x16">
   <?php } ?>
   <title><?= htmlspecialchars($store_setting['name']) ?><?= isset($meta_title) ? ' — ' . htmlspecialchars($meta_title) : '' ?></title>

   <!-- LMS Core Assets -->
   <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>?v=<?= av() ?>">
   <link rel="stylesheet" href="<?= base_url('assets/template/css/all.min.css') ?>?v=<?= av() ?>">
   <!-- LMS 2026 Player Design -->
   <link rel="stylesheet" href="<?= base_url('assets/store/lms/css/lms-player.css') ?>?v=<?= av() ?>">

   <?php if (is_rtl()) { ?>
      <link rel="stylesheet" href="<?= base_url('assets/store/lms/css/rtl.css') ?>?v=<?= av() ?>">
   <?php } ?>
</head>
<body class="lms-body">

<!-- ===================== HEADER ===================== -->
<header class="lms-header">

   <!-- Sidebar toggle (mobile) -->
   <button class="lms-sidebar-toggle" id="mobileSidebarToggle" title="<?= __('store.lms_course_menu') ?>">
      <i class="fa fa-bars"></i>
   </button>

   <!-- Logo -->
   <a class="lms-header-logo" href="<?= base_url('store') ?>">
      <?php $logo = (!empty($store_setting['logo'])) ? base_url('assets/images/site/' . $store_setting['logo']) : base_url('assets/store/default/img/logo.png'); ?>
      <img src="<?= $logo ?>" alt="<?= htmlspecialchars($store_setting['name']) ?>">
   </a>

   <div class="lms-header-divider"></div>

   <!-- Course name -->
   <div class="lms-course-name">
      <a href="<?= base_url('store/' . base64_encode($user_id) . '/product/' . $products[0]['product_slug']) ?>">
         <?= htmlspecialchars($products[0]['product_name']) ?>
      </a>
   </div>

   <!-- Spacer -->
   <div style="flex:1"></div>

   <!-- Progress pill -->
   <div class="lms-progress-pill">
      <div class="pill-bar">
         <div class="pill-fill" id="headerProgressFill" style="width:3%"></div>
      </div>
      <span class="pill-label" id="totalPrgorsss">Start</span>
   </div>

   <!-- Search -->
   <div class="lms-search-wrap">
      <i class="fa fa-search"></i>
      <input type="text" id="search" placeholder="<?= __('store.lms_search_placeholder') ?>" autocomplete="off">
   </div>

   <!-- Language selector -->
   <?php if (isset($languages) && isset($languages['SelectedLanguage'])) { ?>
   <div class="dropdown">
      <button class="lms-lang-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
         <img src="<?= base_url($languages['SelectedLanguageFlag']) ?>" width="16" height="16" alt="">
         <span><?= $languages['SelectedLanguage'] ?></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="lmsLangDrop">
         <?php foreach ($languages['LanguageHtml'] as $lang) { ?>
         <li>
            <a class="dropdown-item" href="<?= $lang['href'] ?>">
               <img src="<?= base_url($lang['flag']) ?>" width="16" height="16" class="me-2" alt=""><?= $lang['name'] ?>
            </a>
         </li>
         <?php } ?>
      </ul>
   </div>
   <?php } ?>

   <!-- Dark mode toggle -->
   <label class="lms-dark-toggle" title="<?= __('store.lms_dark_mode') ?>">
      <input type="checkbox" id="darkModeToggle">
      <span class="lms-slider"></span>
   </label>

   <!-- My Courses -->
   <a href="<?= base_url('store/my_courses') ?>"
      class="lms-logout-btn"
      title="<?= __('store.my_courses') ?>"
      style="color:#60c8ff">
      <i class="fa fa-th-large"></i>
   </a>

   <!-- Logout -->
   <a href="<?= base_url('store/logout') ?>"
      class="lms-logout-btn"
      title="<?= __('store.are_sure_logout') ?>"
      onclick="return confirm('<?= __('store.are_sure_logout') ?>')">
      <i class="fa fa-sign-out"></i>
   </a>

</header>

<!-- Mobile overlay -->
<div class="lms-sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===================== MAIN WRAPPER ===================== -->
<div class="lms-wrapper">

   <!-- ===================== SIDEBAR ===================== -->
   <aside class="lms-sidebar" id="mySidebar">

      <!-- Sidebar header / progress -->
      <div class="lms-sidebar-head">
         <div class="lms-sidebar-head-title"><?= __('store.video_playlist') ?></div>
         <div class="lms-course-progress">
            <div class="cp-top">
               <span class="cp-label"><?= __('store.course_progress') ?></span>
               <span class="cp-pct" id="sidebarProgressPct">0%</span>
            </div>
            <div class="lms-progress-track">
               <span class="progress-bar-inner" style="background-color:#6366f1;width:3%;" data-value="3" data-percentage-value="3"></span>
            </div>
         </div>
         <div class="lms-sidebar-stats">
            <div class="stat-chip">
               <div class="sv" id="totalLessonsCount">0</div>
               <div class="sl"><?= __('store.lms_lessons') ?></div>
            </div>
            <div class="stat-chip">
               <div class="sv" id="completedLessonsCount">0</div>
               <div class="sl"><?= __('store.lms_completed') ?></div>
            </div>
         </div>
      </div>

      <!-- Curriculum accordion -->
      <div class="lms-curriculum">
         <div class="accordion sidebar-accordian" id="accordionExample">
            <?php
               $vcount = 0;
               $sectionInc = 0;
               foreach ($products as $key => $product) {
                  foreach ($product['downloadable_files'] as $sectionKey => $sectionValue) {
            ?>
            <div class="accordion-item">
               <h2 class="accordion-header" id="heading<?= $sectionInc ?>">
                  <button class="accordion-button <?= $sectionInc == 0 ? '' : 'collapsed' ?>" type="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#collapse<?= $sectionInc ?>"
                     aria-expanded="<?= $sectionInc == 0 ? 'true' : 'false' ?>"
                     aria-controls="collapse<?= $sectionInc ?>">
                     <?php
                        $sectionTotal = count($sectionValue['data']);
                        $sectionChecked = 0;
                        foreach ($sectionValue['data'] as $sv) {
                           if (($videoStatus[$sv['name']]['isWatched'] ?? 0) == 1) $sectionChecked++;
                        }
                     ?>
                     <span style="flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($sectionValue['title']) ?></span>
                     <span style="font-size:10px;font-weight:400;color:#94a3b8;flex-shrink:0;margin-left:8px"><?= $sectionChecked ?>/<?= $sectionTotal ?></span>
                  </button>
               </h2>
               <div id="collapse<?= $sectionInc ?>"
                  class="accordion-collapse collapse <?= $sectionInc == 0 ? 'show' : '' ?>"
                  aria-labelledby="heading<?= $sectionInc ?>"
                  data-bs-parent="#accordionExample">
                  <div class="checkbody">
                     <?php foreach ($sectionValue['data'] as $InnerListkey => $InnerListvalue) {

                        $Title = $type = $video_id = '';
                        if ($product['product_type'] == 'video') {
                           $type = 'video';
                           $video_id = $InnerListvalue['mask'];
                        }
                        if ($product['product_type'] == 'videolink') {
                           $link = determineVideoUrlType($InnerListvalue['mask']);
                           if ($link['video_type'] == 'youtube') {
                              $type = $link['video_type'];
                              $video_id = $link['video_id'];
                           } else {
                              $video_id = $link['video_type'] == 'vimeo' ? $link[0]['id'] : $InnerListvalue['mask'];
                              $type = $link['video_type'] == 'vimeo' ? 'vimeo' : 'none';
                           }
                        }

                        // Only auto-complete via duration if user has NOT explicitly unchecked (isWatched==2)
                        if (($videoStatus[$InnerListvalue['name']]['isWatched'] ?? 0) != 2 &&
                            $videoStatus[$InnerListvalue['name']]['duration'] > $InnerListvalue['duration']) {
                           $videoStatus[$InnerListvalue['name']]['isWatched'] = 1;
                        }

                        // Video type icon
                        $typeIcon = 'fa-play-circle';
                        if ($type == 'youtube')    $typeIcon = 'fa-youtube';
                        elseif ($type == 'vimeo')  $typeIcon = 'fa-vimeo';

                        if ($video_id) {
                     ?>
                     <div class="list-group mx-0 w-auto">
                        <div class="course-list-item" <?= $videoStatus[$InnerListvalue['name']]['isPlaying'] ? 'style="background:var(--lms-sidebar-active-bg);border-left:3px solid var(--lms-accent);"' : '' ?>>
                           <div class="classleft">
                              <input class="form-check-input flex-shrink-0 videocheck"
                                 type="checkbox"
                                 value=""
                                 data-value="<?= htmlspecialchars($InnerListvalue['name']) ?>"
                                 <?= (($videoStatus[$InnerListvalue['name']]['isWatched'] ?? 0) == 1) ? 'checked="checked"' : '' ?>>
                           </div>
                           <div class="contentright playvideo"
                              data-type="<?= $type ?>"
                              data-value="<?= htmlspecialchars($video_id) ?>"
                              data-title="<?= htmlspecialchars($InnerListvalue['videotext']) ?>"
                              data-id="<?= htmlspecialchars($InnerListvalue['name']) ?>"
                              data-product-id="<?= (int)($product['product_id'] ?? 0) ?>"
                              data-product-rating="<?= (int)($product['product_ratting'] ?? $product['product_avg_rating'] ?? 0) ?>"
                              data-duration="<?= $videoStatus[$InnerListvalue['name']]['duration'] ?>"
                              data-totalduration="<?= $InnerListvalue['duration'] ?>"
                              data-isplaying="<?= $videoStatus[$InnerListvalue['name']]['isPlaying'] ?>"
                              data-index="<?= $vcount++ ?>"
                              data-parent="collapse<?= $sectionInc ?>">
                              <div class="chk">
                                 <label class="list-group-item">
                                    <span>
                                       <i class="fa <?= $typeIcon ?>" style="font-size:10px;color:var(--lms-sidebar-muted);margin-right:4px"></i>
                                       <?= htmlspecialchars($InnerListvalue['videotext']) ?>
                                       <?php if (!empty($InnerListvalue['description'])) { ?>
                                          <small class="d-block text-muted"><?= htmlspecialchars($InnerListvalue['description']) ?></small>
                                       <?php } ?>
                                    </span>
                                 </label>
                              </div>
                              <div class="time-minutes">
                                 <div class="time">
                                    <div>
                                       <i class="fa fa-clock-o"></i>
                                       <span><?= secToHR($InnerListvalue['duration']) ?></span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <?php if (!empty($InnerListvalue['zip']['name'])) { ?>
                        <div class="btn-group vedio-detail lms-resource-dropdown">
                           <button class="btn btn-secondary btn-sm lms-res-toggle" type="button" aria-expanded="false">
                              <i class="fa fa-paperclip" style="margin-right:4px"></i><?= __('store.lms_resources') ?>
                           </button>
                           <ul class="dropdown-menu lms-res-menu" style="display:none">
                              <li class="list-group-item dowanloadcon"
                                 data-value="<?= base_url('store/downloadable_file/' . $InnerListvalue['zip']['name'] . '/' . $InnerListvalue['zip']['mask']) . '/' . $order_id . '?resource=1' ?>">
                                 <i class="fa fa-download" style="margin-right:6px;opacity:.6"></i><?= htmlspecialchars($InnerListvalue['zip']['title'] ?: $InnerListvalue['zip']['name']) ?>
                              </li>
                           </ul>
                        </div>
                        <?php } ?>
                     </div>
                     <?php } } ?>
                  </div>
               </div>
            </div>
            <?php $sectionInc++; } } ?>
         </div>
      </div>

   </aside>

   <!-- Sidebar collapse handle (desktop) -->
   <div class="lms-collapse-btn" id="sidebarCollapseBtn" title="Toggle sidebar">
      <i class="fa fa-chevron-left" id="collapseIcon"></i>
   </div>

   <!-- ===================== MAIN CONTENT ===================== -->
   <main class="lms-main" id="main">

      <!-- Video player -->
      <div class="lms-player-container">
         <div class="ratio ratio-16x9" id="video_div">
            <iframe id="video" class="rounded" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allowfullscreen title="<?= htmlspecialchars($products[0]['product_name']) ?>"></iframe>
         </div>
      </div>

      <!-- Lesson meta bar -->
      <div class="lms-lesson-meta">
         <div class="lms-lesson-title-area">
            <h5 id="currentLessonTitle"><?= htmlspecialchars($products[0]['product_name']) ?></h5>
            <div class="lms-rating-row lms-rating-interactive" id="lmsLessonRating"
               data-product-id="<?= (int)($products[0]['product_id'] ?? 0) ?>"
               data-current-rating="<?= (int)($products[0]['product_ratting'] ?? $products[0]['product_avg_rating'] ?? 0) ?>"
               title="<?= __('store.your_rating') ?>">
               <?php $initRating = (int)($products[0]['product_ratting'] ?? $products[0]['product_avg_rating'] ?? 0); ?>
               <?php for ($s = 1; $s <= 5; $s++) { ?>
                  <i class="fa lms-rating-star <?= $initRating >= $s ? 'fa-star' : 'fa-star-o' ?>" data-value="<?= $s ?>" role="button" tabindex="0" aria-label="<?= $s ?> <?= __('store.rating') ?>"></i>
               <?php } ?>
               <span class="lms-rating-label"><?= __('store.rating') ?></span>
            </div>
         </div>
         <?php $share_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
         <a href="#" class="lms-share-btn" data-social-share data-share-url="<?= $share_link ?>">
            <i class="fa fa-share-alt"></i><?= __('store.share') ?>
         </a>
      </div>

      <!-- Tabs: description -->
      <div class="lms-content-tabs">
         <div class="lms-tabs-nav">
            <button class="lms-tab-btn active" data-tab="description">
               <i class="fa fa-align-left" style="margin-right:6px;opacity:.6"></i><?= __('store.lesson_content') ?>
            </button>
         </div>
         <div class="lms-tab-pane active" id="tab-description">
            <p><?= $products[0]['product_description'] ?></p>
         </div>
      </div>

      <!-- Footer -->
      <footer class="lms-footer">
         <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($store_setting['name']) ?>. <?= __('store.all_rights_reserved') ?></p>
      </footer>

   </main>
</div><!-- /.lms-wrapper -->

<!-- Scripts (must load before social share modal which uses jQuery) -->
<script src="<?= base_url('assets/template/js/jquery-3.7.1.min.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>?v=<?= av() ?>"></script>

<!-- Social share modal (same module as store product pages - requires jQuery) -->
<?= $social_share_modal; ?>

<script>
var currentVideoTime = 0;
var orderId = '<?= $order['id'] ?>';

$(document).ready(function () {

   // ---- LMS Rating (dynamic) ----
   function updateLmsRatingStars(rating) {
      var $row = $('#lmsLessonRating');
      $row.find('.lms-rating-star').each(function () {
         var v = parseInt($(this).data('value'), 10);
         $(this).toggleClass('fa-star', v <= rating).toggleClass('fa-star-o', v > rating);
      });
      $row.attr('data-current-rating', rating);
   }

   $(document).on('click', '.lms-rating-star', function (e) {
      e.preventDefault();
      var $row = $('#lmsLessonRating');
      var productId = $row.attr('data-product-id');
      var rate = parseInt($(this).data('value'), 10);
      if (!productId || rate < 1 || rate > 5) return;

      $.ajax({
         url: '<?= base_url('store/product_ratting') ?>',
         type: 'POST',
         dataType: 'json',
         data: { product_id: productId, rate: rate },
         success: function (res) {
            if (res.status === 1) {
               updateLmsRatingStars(res.avg_rating);
            } else if (res.message) {
               alert(res.message);
            }
         },
         error: function () { alert('<?= addslashes(__('store.login_to_rate')) ?>'); }
      });
   });

   $(document).on('keydown', '.lms-rating-star', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); $(this).click(); }
   });

   // ---- Search ----
   $('#search').on('keyup', function (e) {
      e.preventDefault();
      var value = $(this).val().toLowerCase();
      $('.playvideo').filter(function () {
         $(this).closest('.list-group').toggle($(this).data('title').toLowerCase().indexOf(value) > -1);
      });
   });

   // ---- Social share modal ----
   $(window).on('shown.bs.modal', function () {
      $('#social-share-modal').find('.btn-close').addClass('modalclose');
   });
   $(document).on('click', '.modalclose', function () {
      var _ssm = document.getElementById('social-share-modal');
      if (_ssm && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
         bootstrap.Modal.getInstance(_ssm)?.hide();
      }
   });

   // ---- Progress bar ----
   function progressBar() {
      var TotalVideo       = $('.videocheck').length;
      var Pervideo         = TotalVideo > 0 ? (100 / TotalVideo).toFixed() : 0;
      var TotalVideoChcked = $('.videocheck:checked').length;
      var totalPer         = 0;
      var totalPrgorss     = Pervideo * TotalVideoChcked;
      totalPrgorss         = TotalVideo == TotalVideoChcked ? 100 : totalPrgorss;
      if (totalPrgorss == 0) totalPrgorss = 3;
      totalPrgorss = parseInt(totalPrgorss.toFixed()) + parseInt(totalPer);
      var width = totalPrgorss < 3 ? 3 : totalPrgorss;

      $('.progress-bar-inner').attr('data-value', totalPrgorss)
         .attr('data-percentage-value', totalPrgorss)
         .css({ 'width': width + '%' });

      var label = totalPrgorss <= 3 ? 'Start' : totalPrgorss + '%';
      $('#totalPrgorsss').html(label);
      $('#headerProgressFill').css('width', width + '%');
      $('#sidebarProgressPct').html(totalPrgorss <= 3 ? '0%' : totalPrgorss + '%');
      $('#completedLessonsCount').html(TotalVideoChcked);
   }

   // Update lesson stats counts
   $('#totalLessonsCount').html($('.videocheck').length);
   progressBar();

   // ---- Checkbox change ----
   $(document).on('change', '.videocheck', function () {
      var name   = $(this).attr('data-value');
      var action = $(this).is(':checked') ? 1 : 2;  // 2 = explicitly unchecked
      $.ajax({
         url: '<?= base_url('store/make_complete') ?>',
         type: 'POST', dataType: 'json',
         data: { order_id: orderId, action: action, name: name, duration: 0 },
         success: function () {}
      });
      progressBar();
   });

   // ---- Duration update ----
   function updateDuration(name, duration, nexttrack, isPlaying) {
      try {
         $.ajax({
            url: '<?= base_url('store/make_complete') ?>',
            type: 'POST', dataType: 'json',
            data: { order_id: orderId, action: 3, name: name, duration: duration, nexttrack: nexttrack || '', isPlaying: isPlaying || '' },
            success: function () {}
         });
      } catch (err) { console.log(err); }
   }

   // ---- Download resource ----
   $('.dowanloadcon').on('click', function (e) {
      e.preventDefault();
      var dl = $(this).data('value');
      if (dl) window.open(dl, '_self');
   });

   // ---- Play video on list item click ----
   $(document).on('click', '.course-list-item', function (e) {
      if ($(e.target).hasClass('course-list-item')) {
         $(this).find('.playvideo').click();
      }
   });

   // ---- Main video play handler ----
   $(document).on('click', '.playvideo', function (e) {
      e.preventDefault();
      var type          = $(this).data('type');
      var videoId       = $(this).data('value');
      var id            = $(this).data('id');
      var duration      = $(this).data('duration') || 0;
      var totalduration = $(this).data('totalduration') || 0;
      var $that         = $(this);

      // Update lesson title in meta bar
      $('#currentLessonTitle').html($(this).data('title').trim() || '');

      // Update rating row for current lesson's product
      var pid = $(this).data('product-id');
      var pr = $(this).data('product-rating') || 0;
      if (pid) {
         $('#lmsLessonRating').attr('data-product-id', pid).attr('data-current-rating', pr);
         updateLmsRatingStars(parseInt(pr, 10) || 0);
      }

      // Highlight active lesson
      $('.course-list-item').removeClass('active-lesson');
      $(this).closest('.course-list-item').addClass('active-lesson');

      if (duration > totalduration && $('#accordionExample input[type="checkbox"]:not(:checked)').length > 0) {
         duration = totalduration;
         updateDuration(id, 0);
         $(this).parent().find('.videocheck').click().trigger('change');
         playNextVideo();
         return;
      }

      if (duration > totalduration) {
         duration = 0;
         updateDuration(id, duration);
      }

      if (type === 'youtube') {
         $('#video_div').html('<iframe id="videoplayer" width="100%" height="100%" src="https://www.youtube.com/embed/' + videoId + '?t=4m42s" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:0"></iframe>');
      }

      if (type === 'video') {
         var base_url = '<?= base_url('store/downloadable_file/') ?>' + encodeURIComponent(id) + '/' + encodeURIComponent(videoId) + '/' + orderId + '?play=1&resource=1';
         $('#video_div').html('<video id="videoplayer" data-id="' + id + '" controls preload="auto" src="' + base_url + '" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain" controlsList="nodownload" oncontextmenu="return false;"></video>');

         document.getElementById('videoplayer').addEventListener('loadedmetadata', function () {
            this.currentTime = duration || 0;
         }, false);

         document.getElementById('videoplayer').addEventListener('pause', function () {
            updateDuration(id, currentVideoTime.toFixed());
         }, false);

         document.getElementById('videoplayer').addEventListener('ended', function () {
            var stopTime     = currentVideoTime.toFixed();
            var currentIndex = parseInt($that.attr('data-index'));
            var currCheckbox = $('.playvideo').parent().find('.videocheck')[currentIndex];
            if ($(currCheckbox).attr('checked') !== 'checked') $(currCheckbox).click();

            var playvideoLengthIdx = $('.playvideo').length - 1;
            if (currentIndex === playvideoLengthIdx) {
               $('.playvideo')[0].click();
            } else {
               $that.attr('data-isplaying', '0');
               var cuacording = $('[data-index="' + currentIndex + '"]').attr('data-parent');
               currentIndex++;
               $('.playvideo')[currentIndex].click();
               var acording = $('[data-index="' + currentIndex + '"]').attr('data-parent');
               if (cuacording !== acording) $('#' + acording).collapse('toggle');
               var nexttrack = $('[data-index="' + currentIndex + '"]').attr('data-isplaying', '1');
               updateDuration(id, stopTime, nexttrack);
            }
         }, false);

         $('#video_div').on('timeupdate', '#videoplayer', function () {
            currentVideoTime = this.currentTime;
            $that.attr('data-duration', currentVideoTime.toFixed());
            progressBar();
         });
      }

      if (type === 'vimeo') {
         $('#video_div').html('<iframe id="videoplayer" src="https://player.vimeo.com/video/' + videoId + '" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="display:block;width:100%;height:100%"></iframe>');
      }

      if (type === 'none') {
         $('#video_div').html('<video id="videoplayer" controls preload="auto" src="' + videoId + '" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain" controlsList="nodownload" oncontextmenu="return false;" autoplay></video>');

         document.getElementById('videoplayer').addEventListener('loadedmetadata', function () {
            this.currentTime = duration || 0;
         }, false);
         document.getElementById('videoplayer').addEventListener('pause', function () {
            updateDuration(id, currentVideoTime.toFixed());
         }, false);
         document.getElementById('videoplayer').addEventListener('ended', function () {
            var currentIndex = parseInt($that.attr('data-index'));
            var currCheckbox = $('.playvideo').parent().find('.videocheck')[currentIndex];
            if ($(currCheckbox).attr('checked') !== 'checked') $(currCheckbox).click();
         }, false);

         $('#video_div').on('timeupdate', '#videoplayer', function () {
            currentVideoTime = this.currentTime;
            $that.attr('data-duration', currentVideoTime.toFixed());
            progressBar();
         });
      }

      // Track last watched
      try {
         $.ajax({
            url: '<?= base_url('store/continue_last_watch') ?>',
            type: 'POST', dataType: 'json',
            data: { order_id: orderId, video_id: id },
            success: function () {}
         });
      } catch (err) { console.log(err); }

   }); // end .playvideo click

   // ---- Auto-play last watched video ----
   playNextVideo();

}); // end document.ready

// ---- openNav / closeNav (kept for compatibility) ----
function openNav() {
   if (window.innerWidth < 992) {
      $('#mySidebar').addClass('mobile-open');
      $('#sidebarOverlay').addClass('active');
   } else {
      $('#mySidebar').removeClass('collapsed');
      $('#sidebarCollapseBtn').removeClass('collapsed');
      $('#collapseIcon').removeClass('fa-chevron-right').addClass('fa-chevron-left');
   }
}

function closeNav() {
   if (window.innerWidth < 992) {
      $('#mySidebar').removeClass('mobile-open');
      $('#sidebarOverlay').removeClass('active');
   } else {
      $('#mySidebar').addClass('collapsed');
      $('#sidebarCollapseBtn').addClass('collapsed');
      $('#collapseIcon').removeClass('fa-chevron-left').addClass('fa-chevron-right');
   }
}

// Desktop collapse toggle
$('#sidebarCollapseBtn').on('click', function () {
   var collapsed = $('#mySidebar').hasClass('collapsed');
   if (collapsed) openNav(); else closeNav();
});

// Mobile sidebar toggle
$('#mobileSidebarToggle').on('click', function () {
   openNav();
});

$('#sidebarOverlay').on('click', function () {
   closeNav();
});

// ---- Dark mode toggle ----
$('#darkModeToggle').on('change', function () {
   var isDark = $(this).is(':checked') ? '1' : '0';
   $('html').attr('data-lms-dark', isDark);
   try { localStorage.setItem('lms_dark', isDark); } catch(e){}
});

// Restore dark mode preference
(function() {
   try {
      var saved = localStorage.getItem('lms_dark');
      if (saved === '1') {
         $('html').attr('data-lms-dark', '1');
         $('#darkModeToggle').prop('checked', true);
      }
   } catch(e){}
})();

// ---- Tab switching ----
$(document).on('click', '.lms-tab-btn', function () {
   var tab = $(this).data('tab');
   $('.lms-tab-btn').removeClass('active');
   $('.lms-tab-pane').removeClass('active');
   $(this).addClass('active');
   $('#tab-' + tab).addClass('active');
});

// ---- Resource dropdown (custom toggle to avoid sidebar overflow clipping) ----
$(document).on('click', '.lms-res-toggle', function (e) {
   e.stopPropagation();
   var $menu = $(this).siblings('.lms-res-menu');
   var isOpen = $menu.is(':visible');
   // Close all other open menus
   $('.lms-res-menu').hide();
   if (!isOpen) $menu.show();
});

$(document).on('click', function () {
   $('.lms-res-menu').hide();
});

// ---- Play next / last watched ----
function playNextVideo() {
   var videoToPlay = $('.playvideo')[0];
   if ($('.playvideo[data-isplaying="1"]').length > 0) {
      videoToPlay = $('.playvideo[data-isplaying="1"]')[0];
   }
   if (videoToPlay) $(videoToPlay).click();
}
</script>
</body>
</html>

<div class="container-fluid py-2">

    <div class="card border-0 rounded-4 shadow-sm overflow-hidden mship-card card-animate visible">
        <div class="row g-0">

            <!-- ====== LEFT: Visual Info Panel ====== -->
            <div class="col-lg-4 d-none d-lg-flex">
                <div class="contact-info-panel d-flex flex-column justify-content-center p-4 w-100 h-100">
                    <div class="mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle contact-info-icon mb-3">
                            <i class="bi bi-envelope-paper fs-2"></i>
                        </span>
                        <h4 class="fw-bold text-white mb-2"><?= __('user.get_in_touch') ?></h4>
                        <p class="text-white-50 mb-0 small"><?= __('user.contact_us_subtitle') ?></p>
                    </div>

                    <div class="d-flex flex-column gap-3 mt-auto">
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle contact-info-badge flex-shrink-0">
                                <i class="bi bi-person-circle"></i>
                            </span>
                            <div>
                                <small class="text-white-50 d-block"><?= __('user.logged_in_as') ?></small>
                                <span class="text-white fw-semibold small"><?= htmlspecialchars($userdetails['firstname'] . ' ' . $userdetails['lastname']) ?></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle contact-info-badge flex-shrink-0">
                                <i class="bi bi-envelope-at"></i>
                            </span>
                            <div>
                                <small class="text-white-50 d-block"><?= __('user.email') ?></small>
                                <span class="text-white fw-semibold small"><?= htmlspecialchars($userdetails['email']) ?></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle contact-info-badge flex-shrink-0">
                                <i class="bi bi-globe2"></i>
                            </span>
                            <div>
                                <small class="text-white-50 d-block"><?= __('user.domain_name') ?></small>
                                <span class="text-white fw-semibold small"><?= htmlspecialchars($domain) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top contact-info-divider">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-white-50"></i>
                            <small class="text-white-50"><?= __('user.contact_secure_note') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====== RIGHT: Contact Form ====== -->
            <div class="col-lg-8">
                <form id="mail-form">
                    <!-- Compact Header (visible on mobile where left panel is hidden) -->
                    <div class="d-lg-none contact-mobile-header px-3 py-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle contact-mobile-icon">
                                <i class="bi bi-envelope-paper"></i>
                            </span>
                            <div>
                                <h6 class="fw-bold text-white mb-0"><?= __('user.get_in_touch') ?></h6>
                                <small class="text-white-50"><?= __('user.contact_us_subtitle') ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 p-lg-4">
                        <!-- Row 1: Name fields -->
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small mship-card-title">
                                    <i class="bi bi-person me-1 text-primary"></i><?= __('user.first_name') ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="fname" class="form-control" value="<?= htmlspecialchars($userdetails['firstname']) ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small mship-card-title">
                                    <i class="bi bi-person me-1 text-primary"></i><?= __('user.last_name') ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="lname" class="form-control" value="<?= htmlspecialchars($userdetails['lastname']) ?>" required>
                            </div>
                        </div>

                        <!-- Row 2: Phone & Domain -->
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small mship-card-title">
                                    <i class="bi bi-telephone me-1 text-primary"></i><?= __('user.phone_number') ?>
                                </label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user_mobile) ?>">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small mship-card-title">
                                    <i class="bi bi-globe2 me-1 text-primary"></i><?= __('user.domain_name') ?>
                                </label>
                                <input type="text" name="domain" class="form-control" value="<?= htmlspecialchars($domain) ?>">
                            </div>
                        </div>

                        <!-- Row 3: Subject -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small mship-card-title">
                                <i class="bi bi-tag me-1 text-primary"></i><?= __('user.subject') ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>

                        <!-- Row 4: Body -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small mship-card-title">
                                <i class="bi bi-text-left me-1 text-primary"></i><?= __('user.body') ?>
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="body" class="form-control" rows="5" required></textarea>
                        </div>

                        <!-- Row 5: Attachment + Submit -->
                        <div class="row g-3 align-items-end">
                            <div class="col-sm-7">
                                <label class="form-label fw-semibold small mship-card-title">
                                    <i class="bi bi-paperclip me-1 text-primary"></i><?= __('user.attachment') ?>
                                    <small class="mship-card-subtitle fw-normal">(<?= __('user.optional') ?>)</small>
                                </label>
                                <input type="file" id="attachment" name="attachment" class="form-control">
                            </div>
                            <div class="col-sm-5 text-end">
                                <button type="submit" class="btn btn-primary rounded-pill fw-semibold px-4 py-2 w-100 w-sm-auto mship-cta-btn btn-submit">
                                    <i class="bi bi-send me-2"></i><?= __('user.send_mail') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $("#mail-form").on('submit', function(evt) {
        evt.preventDefault();
        var formData = new FormData($("#mail-form")[0]);

        $(".btn-submit").btn("loading");
        formData = formDataFilter(formData);
        $this = $("#mail-form");

        $.ajax({
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(result) {
                $(".btn-submit").btn("reset");
                $(".alert-dismissable").remove();
                $this.find(".has-error").removeClass("has-error");
                $this.find(".is-invalid").removeClass("is-invalid");
                $this.find("span.text-danger").remove();

                if (result['success']) {
                    showToast('success', "<?= __('user.mail_sent_successfully') ?>");
                    $("#mail-form")[0].reset();
                    $("html, body").stop().animate({ scrollTop: 0 }, 500, 'swing');
                }
                if (result['errors']) {
                    if (typeof result['errors'] == 'string') {
                        showToast('error', "<?= __('user.mail_sent_fail') ?>");
                        $("html, body").stop().animate({ scrollTop: 0 }, 500, 'swing');
                    } else {
                        $.each(result['errors'], function(i, j) {
                            $ele = $this.find('[name="' + i + '"]');
                            if (!$ele.length) {
                                $ele = $this.find('.' + i);
                            }
                            if ($ele.length) {
                                $ele.addClass("is-invalid");
                                $ele.parents(".mb-3, .mb-4").addClass("has-error");
                                $ele.after("<span class='d-block text-danger mt-1'>" + j + "</span>");
                            }
                        });
                        errors = result['errors'];
                        $('.formsetting_error').text(errors['formsetting_recursion_custom_time']);
                        $('.productsetting_error').text(errors['productsetting_recursion_custom_time']);
                    }
                }
            },
            error: function(xhr, status, error) {
                $(".btn-submit").btn("reset");
                showToast('error', "<?= __('user.mail_sent_fail') ?>");
                $("html, body").stop().animate({ scrollTop: 0 }, 500, 'swing');
            },
        });
        return false;
    });
</script>

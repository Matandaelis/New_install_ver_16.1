					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<footer class="bg-dark text-light mt-5 py-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-4">
                <p class="text-muted mb-0 small">
                    <i class="bi bi-c-circle me-1"></i>
                    <?= $SiteSetting['footer'] ?>
                </p>
            </div>
            <div class="col-md-4 text-center">
                <a href="<?=base_url();?>api-home" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-house me-1"></i>API Home
                </a>
                <a href="<?=base_url();?>admin-api-document" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-shield-lock me-1"></i>Admin API
                </a>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="<?=base_url();?>assets/Affiliate-Pro.postman_collection.json" 
                   class="btn btn-outline-primary btn-sm" download>
                    <i class="bi bi-download me-1"></i>Download Postman Collection
                </a>
            </div>
        </div>
    </div>
</footer>

<script src="<?=base_url();?>assets/template/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // --- Sidebar navigation hover ---
    $('#apiNavList').on('mouseenter', '.nav-link', function() {
        var $t = $(this), sub = $t.closest('ul').hasClass('ps-3');
        if (!$t.hasClass('active') && !$t.attr('data-bs-toggle')) {
            $t.addClass('bg-light' + (sub ? ' text-dark' : '')).removeClass(sub ? 'text-muted' : 'text-dark');
        }
    }).on('mouseleave', '.nav-link', function() {
        var $t = $(this), sub = $t.closest('ul').hasClass('ps-3');
        if (!$t.hasClass('active') && !$t.attr('data-bs-toggle')) {
            $t.removeClass('bg-light' + (sub ? ' text-dark' : '')).addClass(sub ? 'text-muted' : 'text-dark');
        }
    });

    // --- Sidebar collapse toggle ---
    $('#apiNavList').on('click', '[data-bs-toggle="collapse"]', function(e) {
        e.preventDefault();
        var $t = $(this), $tgt = $($t.attr('data-bs-target')), $chev = $t.find('.bi-chevron-down, .bi-chevron-up');
        if ($tgt.hasClass('show')) {
            $tgt.removeClass('show').slideUp(200);
            $t.removeClass('bg-primary text-white').addClass('text-dark');
            $chev.removeClass('bi-chevron-up').addClass('bi-chevron-down');
        } else {
            $tgt.addClass('show').slideDown(200);
            $t.addClass('bg-primary text-white').removeClass('text-dark bg-light');
            $chev.removeClass('bi-chevron-down').addClass('bi-chevron-up');
        }
    });

    // --- Sidebar active link ---
    $('#apiNavList').on('click', '.nav-link:not([data-bs-toggle])', function() {
        var $t = $(this), sub = $t.closest('ul').hasClass('ps-3');
        $('#apiNavList .nav-link:not([data-bs-toggle])').removeClass('active bg-primary text-white bg-light').addClass('text-muted');
        $t.addClass('active ' + (sub ? 'bg-light text-dark' : 'bg-primary text-white')).removeClass('text-dark text-muted');
    });

    // --- Search ---
    var $search = $('#apiSearch'), $navList = $('#apiNavList'), $clearBtn = $('#clearSearch');
    $search.on('input', function() {
        var term = $(this).val().toLowerCase();
        if (!term.length) { $navList.find('.nav-item').show(); $clearBtn.addClass('d-none'); return; }
        $navList.find('.nav-item').each(function() { $(this).toggle($(this).text().toLowerCase().includes(term)); });
        $clearBtn.removeClass('d-none');
    });
    $clearBtn.on('click', function() { $search.val(''); $navList.find('.nav-item').show(); $(this).addClass('d-none'); $search.focus(); });

    // --- Smooth scroll from sidebar ---
    $('#sidebar-nav').on('click', 'a[href^="#"]', function(e) {
        e.preventDefault();
        var el = $($(this).attr('href'));
        if (el.length) window.scrollTo({ top: el.offset().top - 120, behavior: 'smooth' });
    });

    // --- Reading Progress Bar (fixed) ---
    function updateProgress() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var docHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        var winHeight = window.innerHeight;
        var scrollable = docHeight - winHeight;
        var pct = scrollable > 0 ? Math.min(Math.round((scrollTop / scrollable) * 100), 100) : 0;
        document.getElementById('readingProgress').style.width = pct + '%';
        document.getElementById('progressText').textContent = pct + '%';
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    window.addEventListener('resize', updateProgress);
    updateProgress();
    setTimeout(updateProgress, 500);
    setTimeout(updateProgress, 2000);

    // --- Copy buttons on response blocks ---
    $('.response-view').each(function() {
        var $rv = $(this);
        if ($rv.find('.copy-btn').length) return;
        var $btn = $('<button class="copy-btn btn btn-outline-secondary btn-sm position-absolute top-0 end-0 m-2"><i class="bi bi-clipboard me-1"></i>Copy</button>');
        $rv.css('position', 'relative').append($btn);
        $btn.on('click', function(e) {
            e.preventDefault();
            var txt = $rv.find('code').length ? $rv.find('code').text() : $rv.text().replace('Copy', '').trim();
            navigator.clipboard.writeText(txt).then(function() {
                $btn.html('<i class="bi bi-check me-1"></i>Copied!').addClass('btn-success').removeClass('btn-outline-secondary');
                setTimeout(function() { $btn.html('<i class="bi bi-clipboard me-1"></i>Copy').removeClass('btn-success').addClass('btn-outline-secondary'); }, 2000);
            });
        });
    });

    // --- Back to Top ---
    var $btt = $('<button class="btn btn-primary rounded-circle shadow d-flex align-items-center justify-content-center position-fixed" id="backToTop" style="width:46px;height:46px;bottom:30px;right:30px;z-index:1050;display:none;"><i class="bi bi-arrow-up fs-5"></i></button>');
    $('body').append($btt);
    $(window).on('scroll', function() { $btt.css('display', $(this).scrollTop() > 300 ? 'flex' : 'none'); });
    $btt.on('click', function() { window.scrollTo({ top: 0, behavior: 'smooth' }); });

    // --- Keyboard shortcuts ---
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); $search.focus(); }
        if (e.key === 'Escape' && $search.val()) { $search.val(''); $navList.find('.nav-item').show(); $clearBtn.hide(); }
    });
});
</script>
</body>
</html>

<?php
/**
 * Storefront: hide broken <img> nodes (capture-phase error) so optional / missing
 * theme assets do not clutter scanners or the UI.
 */
?>
<script>
(function () {
    document.addEventListener('error', function (ev) {
        var t = ev.target;
        if (t && t.tagName === 'IMG') {
            t.style.display = 'none';
        }
    }, true);
})();
</script>

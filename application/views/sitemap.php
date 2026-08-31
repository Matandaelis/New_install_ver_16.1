<?php
header("Content-Type: text/xml; charset=UTF-8");
header("Cache-Control: public, max-age=3600");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <url>
        <loc><?php echo htmlspecialchars(base_url(), ENT_XML1, 'UTF-8'); ?></loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
        <lastmod><?php echo date('Y-m-d\TH:i:s+00:00'); ?></lastmod>
    </url>

    <?php
    if ($active_theme == 'multiple_pages' && !empty($theme_pages)) {
        foreach ($theme_pages as $page) {
            if (isset($page->slug) && $page->slug != '/') {
                if ($page->page_type == 'editable')
                    $pageurl = base_url() . 'p/' . $page->slug;
                else
                    $pageurl = base_url($page->slug);
                ?>
                <url>
                    <loc><?php echo htmlspecialchars($pageurl, ENT_XML1, 'UTF-8'); ?></loc>
                    <priority>0.5</priority>
                    <changefreq>daily</changefreq>
                    <?php if (isset($page->updated_at) && $page->updated_at): ?>
                    <lastmod><?php echo date('Y-m-d\TH:i:s+00:00', strtotime($page->updated_at)); ?></lastmod>
                    <?php endif; ?>
                </url>
                <?php
            }
        }
    }
    
    // Always include common pages regardless of theme
    if (!isset($active_theme) || in_array($active_theme, ['custom_1', 'custom_2', 'custom_3', 'custom_4', 'custom_5', 'custom_6', 'custom_7', 'custom_8', 'custom_9', 'custom_10', 'default'])) {
        ?>
        <url>
            <loc><?= htmlspecialchars(base_url() . 'term-condition', ENT_XML1, 'UTF-8') ?></loc>
            <priority>0.5</priority>
            <changefreq>monthly</changefreq>
        </url>
        <url>
            <loc><?= htmlspecialchars(base_url() . 'register', ENT_XML1, 'UTF-8') ?></loc>
            <priority>0.7</priority>
            <changefreq>monthly</changefreq>
        </url>
        <url>
            <loc><?= htmlspecialchars(base_url() . 'register/vendor', ENT_XML1, 'UTF-8') ?></loc>
            <priority>0.7</priority>
            <changefreq>monthly</changefreq>
        </url>
        <url>
            <loc><?= htmlspecialchars(base_url() . 'forget-password', ENT_XML1, 'UTF-8') ?></loc>
            <priority>0.3</priority>
            <changefreq>yearly</changefreq>
        </url>
    <?php
    }
    ?>

    <url>
        <loc><?= htmlspecialchars(base_url() . 'store/', ENT_XML1, 'UTF-8') ?></loc>
        <priority>0.9</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc><?= htmlspecialchars(base_url() . 'store/about', ENT_XML1, 'UTF-8') ?></loc>
        <priority>0.6</priority>
        <changefreq>monthly</changefreq>
    </url>
    <url>
        <loc><?= htmlspecialchars(base_url() . 'store/contact', ENT_XML1, 'UTF-8') ?></loc>
        <priority>0.6</priority>
        <changefreq>monthly</changefreq>
    </url>
    <url>
        <loc><?= htmlspecialchars(base_url() . 'store/login', ENT_XML1, 'UTF-8') ?></loc>
        <priority>0.4</priority>
        <changefreq>yearly</changefreq>
    </url>
    <url>
        <loc><?= htmlspecialchars(base_url() . 'store/policy', ENT_XML1, 'UTF-8') ?></loc>
        <priority>0.5</priority>
        <changefreq>monthly</changefreq>
    </url>
    <url>
        <loc><?= htmlspecialchars(base_url() . "store/category/", ENT_XML1, 'UTF-8') ?></loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>

    <?php
    if (isset($store_footer_menu["footer_menu"]) && !empty($store_footer_menu["footer_menu"])) {
        $footer_menu = json_decode($store_footer_menu["footer_menu"]);
        if ($footer_menu && is_array($footer_menu)) {
            foreach ($footer_menu as $fm) {
                if (isset($fm->links) && is_array($fm->links)) {
                    for ($i = 0; $i < count($fm->links); $i++) {
                        if (isset($fm->links[$i]->url) && !empty($fm->links[$i]->url)) {
                            ?>
                            <url>
                                <loc><?php echo htmlspecialchars($fm->links[$i]->url, ENT_XML1, 'UTF-8'); ?></loc>
                                <priority>0.5</priority>
                                <changefreq>monthly</changefreq>
                            </url>
                            <?php
                        }
                    }
                }
            }
        }
    }
    ?>

    <?php
    if (!empty($categorys)) {
        foreach ($categorys as $category) {
            if (isset($category->slug) && !empty($category->slug)) {
                ?>
                <url>
                    <loc><?php echo htmlspecialchars(base_url() . "store/category/" . $category->slug, ENT_XML1, 'UTF-8'); ?></loc>
                    <priority>0.7</priority>
                    <changefreq>weekly</changefreq>
                    <?php if (isset($category->updated_at) && $category->updated_at): ?>
                    <lastmod><?php echo date('Y-m-d\TH:i:s+00:00', strtotime($category->updated_at)); ?></lastmod>
                    <?php endif; ?>
                </url>
                <?php
            }
        }
    }
    ?>

    <?php
    if (!empty($products)) {
        foreach ($products as $product) {
            if (isset($product->product_id) && !empty($product->product_id)) {
                $producturl = base_url() . "store/product/" . $product->product_id;
                ?>
                <url>
                    <loc><?php echo htmlspecialchars($producturl, ENT_XML1, 'UTF-8'); ?></loc>
                    <priority>0.8</priority>
                    <changefreq>weekly</changefreq>
                    <?php if (isset($product->product_created_date) && $product->product_created_date): ?>
                    <lastmod><?php echo date('Y-m-d\TH:i:s+00:00', strtotime($product->product_created_date)); ?></lastmod>
                    <?php endif; ?>
                </url>
                <?php
            }
        }
    }
    ?>
</urlset>
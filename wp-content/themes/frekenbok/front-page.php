<?php get_header(); ?>

<body class="home-page">
    <div class="fluid-wrapper">
        <div class="menu-overlay"></div>
        <div class="menu-wrapper"></div>

        <main class="main">
            <?php if(get_field('active_1')): ?>
                <div class="home-hero">
                    <?php $img_1_1 = get_field('img_1_1'); if(is_array($img_1_1) && count($img_1_1)): ?>
                        <picture>
                            <?php $img_1_3 = get_field('img_1_3'); if(is_array($img_1_3) && count($img_1_3)): ?>
                                <source media="(max-width: 650px)" srcset="<?php echo $img_1_3['sizes']['960x0']; ?>">
                            <?php endif; ?>
                            <?php $img_1_2 = get_field('img_1_2'); if(is_array($img_1_2) && count($img_1_2)): ?>
                                <source media="(max-width: 999px)" srcset="<?php echo $img_1_2['sizes']['1600x0']; ?>">
                            <?php endif; ?>
                            <source media="(min-width: 1000px)" srcset="<?php echo $img_1_1['url']; ?>">
                            <img src="<?php echo $img_1_1['url']; ?>" alt="">
                        </picture>
                    <?php endif; ?>

                    <?php $button = get_field('button_1'); if($button['active']): ?>
                        <a href="<?php echo $button['link']; ?>" class="home-hero-btn" target="_blank"><?php echo $button['text']; ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if(get_field('active_2')): ?>
                <div class="home-second">
                    <div class="home-second-top">
                        <?php $field = get_field('title_2'); if($field['title'] || $field['subtitle']): ?>
                            <div class="home-second-left">
                                <div class="home-second-left-title"><?php echo $field['title']; ?></div>
                                <div class="home-second-left-description"><?php echo $field['subtitle']; ?></div>
                            </div>
                        <?php endif; ?>
                        <?php $field = get_field('text_2'); if($field): ?>
                            <div class="home-second-right"><?php echo $field; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="sbi-feed">
                        <?php echo do_shortcode('[instagram-feed feed=1]'); ?>
                    </div>

                    <?php $button = get_field('button_2'); if($button['active']): ?>
                        <div class="home-about-bottom-text">
                            <?php echo $button['title']; ?> <a href="<?php echo $button['link']; ?>" target="_blank"><?php echo $button['text']; ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if(get_field('active_3')): ?>
                <div class="home-second">
                    <div class="home-second-top">
                        <?php $field = get_field('title_3'); if($field): ?>
                            <div class="home-second-left">
                                <div class="home-second-left-title"><?php echo $field; ?></div>
                            </div>
                        <?php endif; ?>
                        <?php $field = get_field('text_3'); if($field): ?>
                            <div class="home-second-right"><?php echo $field; ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $products = \PS\Functions\Helper\Helper::get_products(true); if(is_array($products) && count($products)): ?>
                    <?php $products_tags = get_terms(['taxonomy' => 'products_tags']); if(is_array($products_tags) && count($products_tags)): ?>
                        <div class="home-table">
                            <div class="home-table-desktop">
                                <div class="home-table-desktop-top">
                                    <div class="home-table-desktop-top-names"></div>
                                    <?php foreach ($products as $m => $product): ?>
                                        <a href="<?php echo $product['link']; ?>" class="home-table-desktop-top-<?php echo ($m + 1); ?>" target="_blank"><?php echo $product['table_name']; ?></a>
                                    <?php endforeach; ?>
                                </div>

                                <div class="home-table-desktop-down">
                                    <div class="home-table-desktop-down-names">
                                        <?php foreach ($products_tags as $term): ?>
                                            <div class="home-table-desktop-down-names-item"><?php echo $term->name; ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php foreach ($products as $m => $product): ?>
                                        <div class="home-table-desktop-down-<?php echo ($m + 1); ?>">
                                            <?php foreach ($products_tags as $term): ?>
                                                <div class="home-table-desktop-down-col-item">
                                                    <?php if(in_array($term->term_id, $product['products_tags'])): ?>
                                                        <img src="<?php echo PS::$assets_url; ?>images/icon1.svg" alt="">
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="home-table-mob">
                                <div class="home-table-mob-left">
                                    <div class="home-table-mob-left-top"></div>
                                    <div class="home-table-mob-left-down">
                                        <?php foreach ($products_tags as $term): ?>
                                            <div class="home-table-mob-left-item"><?php echo $term->name; ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="home-table-mob-slider-wrapper">
                                    <div class="home-table-mob-slider-arrows">
                                        <div class="slick-prev">
                                            <img src="<?php echo PS::$assets_url; ?>images/prev.svg" alt="">
                                        </div>
                                        <div class="slick-next">
                                            <img src="<?php echo PS::$assets_url; ?>images/next.svg" alt="">
                                        </div>
                                    </div>
                                    <div class="home-table-mob-slider">
                                        <?php foreach ($products as $product): ?>
                                            <div class="home-table-mob-slider-item">
                                                <a href="<?php echo $product['link']; ?>" class="home-table-mob-slider-item-name" target="_blank"><?php echo $product['table_name']; ?></a>
                                                <div class="home-table-mob-slider-item-col">
                                                    <?php foreach ($products_tags as $term): ?>
                                                        <div class="home-table-mob-slider-item-el">
                                                            <?php if(in_array($term->term_id, $product['products_tags'])): ?>
                                                                <img src="<?php echo PS::$assets_url; ?>images/icon2.svg" alt="">
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(get_field('active_4')): ?>
                <div class="home-second">
                    <div class="home-second-top">
                        <?php $field = get_field('title_4'); if($field): ?>
                            <div class="home-second-left">
                                <div class="home-second-left-title"><?php echo $field; ?></div>
                            </div>
                        <?php endif; ?>
                        <?php $field = get_field('text_4'); if($field): ?>
                            <div class="home-second-right"><?php echo $field; ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $products = \PS\Functions\Helper\Helper::get_products(); if(is_array($products) && count($products)): ?>
                    <div class="home-products">
                        <?php foreach ($products as $product): ?>
                            <a href="<?php echo $product['link']; ?>" class="home-products-item" target="_blank">
                                <?php if(is_array($product['img']) && count($product['img'])): ?>
                                    <div class="home-products-item-image">
                                        <img src="<?php echo $product['img']['sizes']['480x0']; ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="home-products-item-name"><?php echo $product['title']; ?></div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(get_field('active_5')): ?>
                <div class="home-about">
                    <?php $field = get_field('title_5'); if($field): ?>
                        <div class="home-about-title"><?php echo $field; ?></div>
                    <?php endif; ?>

                    <div class="home-about-container">
                        <div class="home-about-left">
                            <?php $field = get_field('text_5'); if($field): ?>
                                <?php echo $field; ?>
                            <?php endif; ?>
                        </div>
                        <?php $img_5 = get_field('img_5'); if(is_array($img_5['img_1']) && count($img_5['img_1'])): ?>
                            <div class="home-about-right">
                                <picture>
                                    <?php if(is_array($img_5['img_3']) && count($img_5['img_3'])): ?>
                                        <source media="(max-width: 650px)" srcset="<?php echo $img_5['img_3']['sizes']['480x0']; ?>">
                                    <?php endif; ?>
                                    <?php if(is_array($img_5['img_2']) && count($img_5['img_2'])): ?>
                                        <source media="(max-width: 999px)" srcset="<?php echo $img_5['img_2']['sizes']['960x0']; ?>">
                                    <?php endif; ?>
                                    <source media="(min-width: 1000px)" srcset="<?php echo $img_5['img_1']['sizes']['1600x0']; ?>">
                                    <img src="<?php echo $img_5['img_1']['sizes']['1600x0']; ?>" alt="">
                                </picture>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php $button = get_field('button_5'); if($button['active']): ?>
                        <div class="home-about-bottom-text">
                            <?php echo $button['title']; ?> <a href="<?php echo $button['link']; ?>" target="_blank"><?php echo $button['text']; ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <footer class="footer">
                <div class="footer-left">
                    <div class="footer-left-logo">
                        <img src="<?php echo PS::$assets_url; ?>images/logo.png" alt="">
                    </div>

                    <?php $products = \PS\Functions\Helper\Helper::get_products(); if(is_array($products) && count($products)): ?>
                        <div class="footer-product">
                            <div class="footer-product-top">Продукти</div>
                            <div class="footer-product-container">
                                <?php $cols = array_chunk($products, ceil(count($products) / 2)); if(count($cols)): ?>
                                    <?php foreach ($cols as $col): ?>
                                        <div class="footer-product-container-col">
                                            <?php foreach ($col as $li): ?>
                                                <a href="<?php echo $li['link']; ?>" target="_blank"><?php echo $li['title']; ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="footer-right">
                    <?php if(get_field('active_6')): ?>
                        <div class="footer-social">
                            <?php $field = get_field('title_6'); if($field): ?>
                                <div class="footer-social-top"><?php echo $field; ?></div>
                            <?php endif; ?>
                            <div class="footer-social-links">
                                <?php $field = get_field('link_6_1'); if($field): ?>
                                    <a href="<?php echo $field; ?>" target="_blank" rel="nofollow">
                                        <img src="<?php echo PS::$assets_url; ?>images/fb.svg" alt="">
                                    </a>
                                <?php endif; ?>
                                <?php $field = get_field('link_6_2'); if($field): ?>
                                    <a href="<?php echo $field; ?>" target="_blank" rel="nofollow">
                                        <img src="<?php echo PS::$assets_url; ?>images/yt.svg" alt="">
                                    </a>
                                <?php endif; ?>
                                <?php $field = get_field('link_6_3'); if($field): ?>
                                    <a href="<?php echo $field; ?>" target="_blank" rel="nofollow">
                                        <img src="<?php echo PS::$assets_url; ?>images/inst.svg" alt="">
                                    </a>
                                <?php endif; ?>
                                <?php $field = get_field('link_6_4'); if($field): ?>
                                    <a href="<?php echo $field; ?>" target="_blank" rel="nofollow">
                                        <img src="<?php echo PS::$assets_url; ?>images/tik.svg" alt="">
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </footer>
        </main>
    </div>

    <?php /* DON'T REMOVE THIS */ ?>
    <?php get_footer(); ?>
    <?php /* END */ ?>

    <?php /* WRITE SCRIPTS HERE */ ?>

    <?php /* END */ ?>

</body>
</html>
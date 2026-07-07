<?php get_header(); ?>

<!-- ================================================
     HERO
================================================ -->
<section class="cart-hero" id="home">
    <div class="cart-hero__content">
        <h1 class="cart-hero__heading"><?php echo wp_kses_post(get_field('hero_heading')); ?></h1>
        <form class="cart-hero__search" role="search" method="get" action="<?php echo esc_url(home_url('/#directory')); ?>">
            <input type="text" name="store_search" placeholder="Search stores, products, services…" class="cart-hero__search-input">
            <button type="submit" class="btn btn--accent">Find Stores</button>
        </form>
    </div>
    <div class="cart-hero__scroll-hint">
        <span></span>
    </div>
</section>

<!-- ================================================
     SHOP DIRECTORY
================================================ -->
<section class="cart-section cart-directory" id="directory">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Shop Directory</h2>
            <p class="section-subtitle">Browse 500+ merchants and find exactly what you need</p>
        </div>
    </div>
    <?php echo do_shortcode('[mall_directory]'); ?>
</section>

<!-- ================================================
     SEE WHAT'S HAPPENING
================================================ -->
<section class="cart-news" id="news">
    <div class="cart-news__bg"></div>
    <div class="container">
        <div class="cart-news__header">
            <div class="cart-news__header-left">
                <h2 class="section-title section-title--light">See What's Happening</h2>
                <div class="cart-news__social">
                    <a href="#" class="social-link social-link--light" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="#" class="social-link social-link--light" aria-label="TikTok">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6 0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64 0 3.33 2.76 5.7 5.69 5.7 3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z"/></svg>
                    </a>
                </div>
            </div>
            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn--outline-white">View All Articles</a>
        </div>
    </div>
</section>

<section class="cart-section cart-news__cards">
    <div class="container">
        <div class="cart-news__grid">
            <?php
            $news_query = new WP_Query([
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            if ($news_query->have_posts()):
                while ($news_query->have_posts()): $news_query->the_post(); ?>
                <article class="news-card">
                    <a href="<?php the_permalink(); ?>" class="news-card__img-link">
                        <?php if (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('medium_large', ['class' => 'news-card__img']); ?>
                        <?php else: ?>
                            <div class="news-card__img news-card__img--placeholder"></div>
                        <?php endif; ?>
                    </a>
                    <div class="news-card__body">
                        <p class="news-card__date"><?php echo get_the_date('F j, Y'); ?></p>
                        <h3 class="news-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="news-card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                        <a href="<?php the_permalink(); ?>" class="news-card__read-more">Read More</a>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata();
            else: ?>
                <p class="no-posts-message">No articles yet. Check back soon.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ================================================
     ABOUT US
================================================ -->
<section class="cart-section cart-about-story" id="about">
    <div class="container">
        <h2 class="section-title section-title--center">About Us</h2>

        <div class="cart-about__story">
            <div class="cart-about__story-img <?php echo esc_attr(cartimar_acf_image_class('about_story_image')); ?>">
                <?php cartimar_acf_image_tag('about_story_image', 'Cartimar historic storefront'); ?>
            </div>
            <div class="cart-about__story-text">
                <h3><?php echo esc_html(get_field('about_heading')); ?></h3>
                <p><?php echo esc_html(get_field('about_paragraph_1')); ?></p>
                <p><?php echo esc_html(get_field('about_paragraph_2')); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="cart-section cart-about-intro">
    <div class="container">
        <div class="cart-about__intro">
            <p><?php echo esc_html(get_field('about_intro_paragraph_1')); ?></p>
            <p><?php echo esc_html(get_field('about_intro_paragraph_2')); ?></p>
        </div>

        <div class="cart-about__gallery">
            <div class="cart-about__gallery-grid">
                <div class="cart-about__gallery-item <?php echo esc_attr(cartimar_acf_image_class('about_gallery_image_1')); ?>">
                    <?php cartimar_acf_image_tag('about_gallery_image_1', 'Cartimar Pet Center'); ?>
                </div>
                <div class="cart-about__gallery-item <?php echo esc_attr(cartimar_acf_image_class('about_gallery_image_2')); ?>">
                    <?php cartimar_acf_image_tag('about_gallery_image_2', 'Cartimar gallery'); ?>
                </div>
                <div class="cart-about__gallery-item <?php echo esc_attr(cartimar_acf_image_class('about_gallery_image_3')); ?>">
                    <?php cartimar_acf_image_tag('about_gallery_image_3', 'Cartimar gallery'); ?>
                </div>
                <div class="cart-about__gallery-item <?php echo esc_attr(cartimar_acf_image_class('about_gallery_image_4')); ?>">
                    <?php cartimar_acf_image_tag('about_gallery_image_4', 'Cartimar gallery'); ?>
                </div>
            </div>
            <div class="cart-about__gallery-stack">
                <div class="cart-about__gallery-item <?php echo esc_attr(cartimar_acf_image_class('about_gallery_image_5')); ?>">
                    <?php cartimar_acf_image_tag('about_gallery_image_5', 'Cartimar gallery'); ?>
                </div>
                <div class="cart-about__gallery-item <?php echo esc_attr(cartimar_acf_image_class('about_gallery_image_6')); ?>">
                    <?php cartimar_acf_image_tag('about_gallery_image_6', 'Cartimar gallery'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cart-section cart-about-closing">
    <div class="container">
        <p class="cart-about__closing"><?php echo esc_html(get_field('about_closing_paragraph')); ?></p>
    </div>
</section>

<!-- ================================================
     HERE TO SERVE
================================================ -->
<section class="cart-section cart-serve" id="serve">
    <div class="container">
        <div class="cart-serve__header">
            <h2><?php echo wp_kses_post(get_field('serve_heading')); ?></h2>
            <p><?php echo esc_html(get_field('serve_intro')); ?></p>
        </div>

        <div class="cart-serve__carousel">
            <div class="cart-serve__nav">
                <button type="button" class="cart-serve__arrow cart-serve__arrow--prev" aria-label="Previous">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button type="button" class="cart-serve__arrow cart-serve__arrow--next" aria-label="Next">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
            <div class="cart-serve__viewport">
                <div class="cart-serve__track">
                    <div class="carousel-item <?php echo esc_attr(cartimar_acf_image_class('serve_mosaic_image_1')); ?>">
                        <?php cartimar_acf_image_tag('serve_mosaic_image_1'); ?>
                    </div>
                    <div class="carousel-item <?php echo esc_attr(cartimar_acf_image_class('serve_mosaic_image_2')); ?>">
                        <?php cartimar_acf_image_tag('serve_mosaic_image_2'); ?>
                    </div>
                    <div class="carousel-item <?php echo esc_attr(cartimar_acf_image_class('serve_mosaic_image_3')); ?>">
                        <?php cartimar_acf_image_tag('serve_mosaic_image_3'); ?>
                    </div>
                    <div class="carousel-item <?php echo esc_attr(cartimar_acf_image_class('serve_mosaic_image_4')); ?>">
                        <?php cartimar_acf_image_tag('serve_mosaic_image_4'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart-serve__closing">
            <p><?php echo esc_html(get_field('serve_paragraph_1')); ?></p>
            <p><?php echo esc_html(get_field('serve_paragraph_2')); ?></p>
        </div>
    </div>
</section>

<!-- ================================================
     CELEBRATING 75 YEARS
================================================ -->
<section class="cart-section cart-anniversary" id="anniversary">
    <div class="container">
        <div class="cart-anniversary__brand">
            <span class="logo-c">c</span>artimar
            <span class="anniversary-badge">75</span>
        </div>
        <h2 class="section-title section-title--center">Celebrating<br>75 Years of Cartimar</h2>
        <div class="cart-anniversary__intro">
            <p><?php echo esc_html(get_field('anniversary_intro')); ?></p>
            <p><?php echo esc_html(get_field('anniversary_intro_2')); ?></p>
        </div>

        <div class="cart-timeline">
            <div class="cart-timeline__years">
                <?php for ($i = 1; $i <= 4; $i++):
                    $year_field = "timeline_{$i}_year";
                    $body_field = "timeline_{$i}_body";
                ?>
                <div class="cart-timeline__item">
                    <h4 class="cart-timeline__year"><?php echo esc_html(get_field($year_field)); ?></h4>
                    <p><?php echo esc_html(get_field($body_field)); ?></p>
                </div>
                <?php endfor; ?>
            </div>
            <div class="cart-timeline__img <?php echo esc_attr(cartimar_acf_image_class('timeline_image')); ?>">
                <?php cartimar_acf_image_tag('timeline_image', 'Cartimar floor plan'); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>

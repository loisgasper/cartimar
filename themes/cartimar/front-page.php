<?php get_header(); ?>

<!-- ================================================
     HERO
================================================ -->
<section class="cart-hero" id="home">
    <div class="cart-hero__overlay"></div>
    <div class="cart-hero__content">
        <h1 class="cart-hero__heading">Great Finds<br>at Cartimar</h1>
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
<section class="cart-section cart-news" id="news">
    <div class="cart-news__bg"></div>
    <div class="container">
        <div class="cart-news__header">
            <div class="cart-news__header-left">
                <h2 class="section-title section-title--light">See What's Happening</h2>
                <div class="cart-news__social">
                    <a href="#" class="social-link social-link--light" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="#" class="social-link social-link--light" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                </div>
            </div>
            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn--outline-white">View All Articles</a>
        </div>

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
<section class="cart-section cart-about" id="about">
    <div class="container">
        <h2 class="section-title section-title--center">About Us</h2>

        <div class="cart-about__story">
            <div class="cart-about__story-img">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/about-storefront.jpg"
                     alt="Cartimar historic storefront"
                     onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="cart-about__story-text">
                <h3>A Legacy of Great Finds</h3>
                <p>In the 1940s, you just can't call it life unless you've experienced the thrill of discovering something truly extraordinary. We captured that essence, and the rest, they would say, became history.</p>
                <p>Cartimar Shopping Complex got its name from its founders — Godless Philippians, Willo(w) and their beloved family. Today, the legacy continues with the third and fourth generation of the Cartimar and Godless families still running this beloved marketplace.</p>
                <p>For generations, Cartimar has been the destination of choice for shoppers looking for unique products, amazing people, and stories that tell of a life well lived. From exotic animals to automotive parts, Cartimar has always exceeded expectations and continues to amaze the world.</p>
            </div>
        </div>

        <div class="cart-about__gallery">
            <div class="cart-about__gallery-item cart-about__gallery-item--tall">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="Cartimar gallery" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="cart-about__gallery-item">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-2.jpg" alt="Cartimar gallery" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="cart-about__gallery-item">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-3.jpg" alt="Cartimar gallery" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="cart-about__gallery-item cart-about__gallery-item--wide">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-4.jpg" alt="Cartimar gallery" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="cart-about__gallery-item">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-5.jpg" alt="Cartimar gallery" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="cart-about__gallery-item">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-6.jpg" alt="Cartimar gallery" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
        </div>
    </div>
</section>

<!-- ================================================
     HERE TO SERVE
================================================ -->
<section class="cart-serve" id="serve">
    <div class="cart-serve__content">
        <div class="cart-serve__text">
            <h2>Here to Serve<br>Here to Stay</h2>
            <p>For more than seven decades, Cartimar has stood the test of time. Through the generations we have always found a way to bring you the best this country has to offer.</p>
            <p>It has always come down to one thing: finding ways to provide things that our communities need — making life a little better, a little more colourful and a little more fun.</p>
            <p>Cartimar is a destination, a tradition, and a place where generations of Filipinos continue to shop, discover, and connect — a timeless destination for every shopper.</p>
        </div>
        <div class="cart-serve__mosaic">
            <div class="mosaic-item mosaic-item--1">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/serve-1.jpg" alt="" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="mosaic-item mosaic-item--2">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/serve-2.jpg" alt="" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="mosaic-item mosaic-item--3">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/serve-3.jpg" alt="" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
            <div class="mosaic-item mosaic-item--4">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/serve-4.jpg" alt="" onerror="this.parentElement.classList.add('img-placeholder')">
            </div>
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
        <p class="cart-anniversary__intro">From its beginnings in 1946 to becoming one of the Philippines' most enduring specialty shopping destinations, Cartimar has been serving the community for 75 remarkable years.</p>

        <div class="cart-timeline">
            <div class="cart-timeline__item">
                <div class="cart-timeline__img">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/timeline-1946.jpg" alt="1946" onerror="this.parentElement.classList.add('img-placeholder')">
                </div>
                <div class="cart-timeline__body">
                    <h4 class="cart-timeline__year">1946</h4>
                    <p>Cartimar opens for business and becomes one of Manila's pioneering post-war shopping centres, offering everything from fresh produce to specialty goods in the heart of Pasay City.</p>
                </div>
            </div>
            <div class="cart-timeline__item cart-timeline__item--reverse">
                <div class="cart-timeline__img">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/timeline-1970s.jpg" alt="1970s" onerror="this.parentElement.classList.add('img-placeholder')">
                </div>
                <div class="cart-timeline__body">
                    <h4 class="cart-timeline__year">1970s</h4>
                    <p>Cartimar grows into a destination market, becoming famous for its wide selection of exotic birds, aquatic pets, and fresh food. Shoppers from across Metro Manila make it a regular weekend stop.</p>
                </div>
            </div>
            <div class="cart-timeline__item">
                <div class="cart-timeline__img">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/timeline-1980s.jpg" alt="1980s" onerror="this.parentElement.classList.add('img-placeholder')">
                </div>
                <div class="cart-timeline__body">
                    <h4 class="cart-timeline__year">1980s</h4>
                    <p>The automotive and bike section expands significantly, establishing Cartimar as the go-to hub for cycling enthusiasts and auto parts buyers throughout the Philippines.</p>
                </div>
            </div>
            <div class="cart-timeline__item cart-timeline__item--reverse">
                <div class="cart-timeline__img">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/timeline-today.jpg" alt="Today" onerror="this.parentElement.classList.add('img-placeholder')">
                </div>
                <div class="cart-timeline__body">
                    <h4 class="cart-timeline__year">Today</h4>
                    <p>With over 500 merchants across multiple buildings, Cartimar continues to serve as a beloved community landmark — balancing its rich heritage with the needs of modern shoppers.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>

<?php
/**
 * The template for displaying individual activity posts
 *
 */
  
get_header(); ?>
  
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php 
            $current_post = get_post($post, ARRAY_A, 'display');

            $title = $current_post['post_title'];
            $ActivityContent = get_post_meta($current_post['ID'], '_activity_content', true);

            $jacketImage = $ActivityContent['jacket_image'];
            $activityDate = $ActivityContent['activity_date'];
            $keynote = $ActivityContent['keynote'];
            $bookName = $ActivityContent['book_name'];
            $bookLink = $ActivityContent['book_link'];
            $authorName = $ActivityContent['author_name'];
            $authorLink = $ActivityContent['author_link'];
        ?>
  
        <div class="another-read-activity-post">
            <div class="activity-content">
                <div class="activity-title">
                    <h2><?php echo $title; ?></h2>
                </div>
                <div class="activity-image">
                    <a href="<?php echo $bookLink ?>"><img src="<?php echo $jacketImage; ?>" alt="<?php echo $bookName; ?>"></a>
                </div>
                <div class="activity-text">
                    <p><?php echo $keynote; ?></p>
                </div>
                <div class="activity-info">
                    <div class="activity-date">
                        <p><?php echo $activityDate; ?></p>
                    </div>
                    <div class="activity-book-title">
                        <a href="<?php echo $bookLink; ?>">
                            <p><?php echo $bookName; ?></p>
                        </a>
                    </div>
                    <div class="activity-book-author">
                        <a href="<?php echo $authorLink; ?>">
                            <p><?php echo $authorName; ?></p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

  
        </main><!-- .site-main -->
    </div><!-- .content-area -->
  
<?php get_footer(); ?>
<?php
/**
* Template Name: Custom RSS Template - Another Read Feed 
*/
$postCount = 5; // The number of posts to show in the feed
$posts = query_posts(array(
        'showposts=' . $postCount,
        'post_type' => 'activity',
        'post_status' => 'publish'));

header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:wfw="http://wellformedweb.org/CommentAPI/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
        xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
        <?php do_action('rss2_ns'); ?>>
<channel>
        <title><?php bloginfo_rss('name'); ?> - Feed</title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss('description') ?></description>
        <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
        <language><?php echo get_option('rss_language'); ?></language>
        <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
        <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
        <?php do_action('rss2_head'); ?>
        <?php while(have_posts()) : the_post(); 
                add_filter('the_excerpt_rss', 'rss_content');
                add_filter('the_content_feed', 'rss_content');
        
        ?>
                <item>
                        <title><?php the_title_rss(); ?></title>
                        <link><?php the_permalink_rss(); ?></link>
                        <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
                        <dc:creator><?php the_author(); ?></dc:creator>
                        <guid isPermaLink="false"><?php the_guid(); ?></guid>
                        <description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
                        <content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
                        <?php rss_enclosure(); ?>
                        <?php do_action('rss2_item'); ?>
                </item>
        <?php endwhile; 
        
        function rss_content(){
                $current_post = get_post(the_ID(), ARRAY_A);
                $str =    '<div class="ar-activity-block">';
                        $title = $current_post['post_title'];
                        $jacketImage = get_post_meta($current_post['ID'], '_jacket_image', true);
                        $activityDate = get_post_meta($current_post['ID'], '_activity_date', true);
                        $keynote = get_post_meta($current_post['ID'], '_keynote', true);
                        $bookName = get_post_meta($current_post['ID'], '_book_name', true);
                        $bookLink = get_post_meta($current_post['ID'], '_book_link', true);
                        $authorName = get_post_meta($current_post['ID'], '_author_name', true);
                        $authorLink = get_post_meta($current_post['ID'], '_author_link', true);
                
                        $str .=      '<div class="ar-activity">';
                        $str .=        '<div class="ar-activity-title">';
                        $str .=            '<img src="' . $jacketImage . '" alt="' . $bookName .'">';
                        $str .=            '<h2>' . $title . '</h2>';
                        $str .=        '</div>';
                        $str .=        '<div class="ar-activity-body">';
                        $str .=            '<p class="ar-activity-date">' . $activityDate . '</p>';
                        $str .=            '<p class="ar-activity-keynote">' . $keynote . '</p>';
                        $str .=        '</div>';
                        $str .=        '<div class="ar-read-more">';
                        $str .=            '<a class="button button-primary" href="' . get_permalink($current_post['ID']) . '">Read More</a>';
                        $str .=        '</div>';
                        $str .=        '<div class="ar-activity-book">';
                        $str .=            '<div class="ar-book">';
                        $str .=                '<a href="'   . $bookLink .   '">';
                        $str .=            $bookName .  '</a>';
                        $str .=            '</div>';
                        $str .=            '<div class="ar-book-author">';
                        $str .=                '<a href="' . $authorLink .'">';  
                        $str .=            $authorName .'</a>';
                        $str .=            '</div>';
                        $str .=        '</div>';
                        $str .=      '</div>';
        
                $str .=      '</div>';
                return $str;
        }
        
        ?>
</channel>
</rss>
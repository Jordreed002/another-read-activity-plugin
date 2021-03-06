<?php 

class AnotherReadActivityGutenbergBlock{
    
    static function createActivityBlock(){
        $assetFile = include( plugin_dir_path(__FILE__) . 'build/index.asset.php');

        wp_register_script(
            'anotherReadGutenBlock',
            plugins_url( 'build/index.js', __FILE__ ),
            $assetFile['dependencies'],
            $assetFile['version']
        );

        register_block_type('another-read/activity-block', array(
            'editor_script' => 'anotherReadGutenBlock',
            'render_callback' => array('AnotherReadActivityGutenbergBlock', 'activityBlockOutput'),

            'attributes' => array(
                'numberOfPosts' => array(
                    'type' => 'int',
                    'default' => ''
                ),
                'tagsForPosts' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'jacketImage' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'keynote' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'authorLink' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'bookLink' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
            )
        ));
    }

    static function activityBlockOutput($block_attributes, $content){

        $recent_posts = wp_get_recent_posts( array(
            'post_type' => 'activity',
            'numberposts' => $block_attributes['numberOfPosts'],
            'post_status' => 'publish',
        ) );

        $str =    '<div class="ar-activity-block">';
        
        foreach($recent_posts as $recent_post){
            $title = $recent_post['post_title'];
            $ActivityContent = get_post_meta($recent_post['ID'], '_activity_content', true);

            $jacketImage = $ActivityContent['jacket_image'];
            $activityDate = $ActivityContent['activity_date'];
            $keynote = $ActivityContent['keynote'];
            $bookName = $ActivityContent['book_name'];
            $bookLink = $ActivityContent['book_link'];
            $authorName = $ActivityContent['author_name'];
            $authorLink = $ActivityContent['author_link'];
    
            $str .=      '<div class="ar-activity">';
            $str .=        '<div class="ar-activity-title">';
            if ($block_attributes['jacketImage'] == true) {$str .=            '<img src="' . $jacketImage . '" alt="' . $bookName .'">';}
            $str .=            '<h2>' . $title . '</h2>';
            $str .=        '</div>';
            $str .=        '<div class="ar-activity-body">';
            $str .=            '<p class="ar-activity-date">' . $activityDate . '</p>';
            if ($block_attributes['keynote'] == true) {$str .=            '<p class="ar-activity-keynote">' . $keynote . '</p>';}
            $str .=        '</div>';
            $str .=        '<div class="ar-read-more">';
            $str .=            '<a class="button button-primary" href="' . get_permalink($recent_post['ID']) . '">Read More</a>';
            $str .=        '</div>';
            $str .=        '<div class="ar-activity-book">';
            $str .=            '<div class="ar-book">';
            if($block_attributes['bookLink'] == true){$str .=                '<a href="'   . $bookLink .   '">';} 
            $str .=            $bookName .  '</a>';
            $str .=            '</div>';
            $str .=            '<div class="ar-book-author">';
            if( $block_attributes['authorLink'] == true){$str .=                '<a href="' . $authorLink .'">';}  
            $str .=            $authorName .'</a>';
            $str .=            '</div>';
            $str .=        '</div>';
            $str .=      '</div>';


            
        }
            $str .=      '</div>';
        return($str);

    }

    


}


?>

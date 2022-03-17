<?php 

class AnotherReadGutenbergBlock{
    
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
            'render_callback' => array('AnotherReadGutenbergBlock', 'activityBlockOutput'),

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

        function post($recent_post){
            
            $arraykeys = array('_activity_id','_jacket_image', '_keynote', '_activity_date', '_book_isbn', '_book_name', '_book_link', '_author_name', '_author_link'); 
            
            

        }

        function createActivity($block_attributes){

            $recent_posts = wp_get_recent_posts( array(
                'post_type' => 'activity',
                'numberposts' => $block_attributes['numberOfPosts'],
                'post_status' => 'publish',
            ) );
    
            $str =    '<div class="ar-activity">';
            
            foreach($recent_posts as $recent_post){
                $title = $recent_post['post_title'];
                $jacketImage = get_post_meta($recent_post['ID'], '_jacket_image', true);
                $activityDate = get_post_meta($recent_post['ID'], '_activity_date', true);
                $keynote = get_post_meta($recent_post['ID'], '_keynote', true);
                $bookName = get_post_meta($recent_post['ID'], '_book_name', true);
                $bookLink = get_post_meta($recent_post['ID'], '_book_link', true);
                $authorName = get_post_meta($recent_post['ID'], '_author_name', true);
                $authorLink = get_post_meta($recent_post['ID'], '_author_link', true);
        

                $str .=        '<div class="ar-activity-title">';
                if ($block_attributes['jacketImage'] == true) {$str .=            '<img src="' . $jacketImage . '" alt="">';}
                $str .=            '<h2>' . $title . '</h2>';
                $str .=        '</div>';
                $str .=        '<div class="ar-activity-body">';
                $str .=            '<p class="ar-activity-date">' . $activityDate . '</p>';
                if ($block_attributes['keynote'] == true) {$str .=            '<p class="ar-activity-keynote">' . $keynote . '</p>';}
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
                $str .=    '</div>';
    
                
            }
            return($str);

        }



        
        
            // $str = '<div class="awp-myfirstblock">';
            // $str .= '<h3>' . $block_attributes['numberOfPosts'] . '</h3>';
            // $str .= '</div>';

        return(createActivity($block_attributes));
        
        
        // sprintf(
        //     '<a class="wp-block-my-plugin-latest-post" href="%1$s">%2$s</a>',
        //     esc_url( get_permalink( $post_id ) ),
        //     esc_html( get_the_title( $post_id ) )
        // );
            
    }


}


?>

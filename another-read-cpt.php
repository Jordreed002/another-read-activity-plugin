<?php 

class AnotherReadActivityCPT{

    static function activityCPT(){

        //custom post type
        register_post_type('activity',
        array(
            'labels' => array(
                'name' => 'Activities',
                'singular_name' => 'Activity',
                'add_new' => 'Add activity',
                'all_items' => 'All activities',
                'add_new_item' => 'Add activity',
                'edit_item' => 'Edit activity',
                'new_item' => 'New activity',
                'view_item' => 'View activity',
                'search_item' => 'Search activities',
                'not_foud' => 'No activities found',
                'not_found_in_trash' => 'No activities found in trash'
            ),
            'public' => true,
            'hierarchical' => false,
            'has_archive' => false,
            'exclude_from_search' => false,
            'show_in_rest' => true
            )
        );

        //removes editor from posts
        remove_post_type_support('activity', 'editor');
        remove_post_type_support('activity', 'author');
    }

    static function activityTaxonomy(){

        //custom taxonomy for the custom post type
        register_taxonomy('keywords', array('activity'), array(
            'labels' => array(
                'name' => 'Keywords',
                'singular_name' => 'Keyword',
                'search_items' => 'Search keywords',
                'all_items' => 'All keywords',
                'edit_item' => 'Edit keyword',
                'update_item' => 'Update keyword',
                'add_new_item' => 'Add new keyword',
                'new_item_name' => 'New keyword name',
                'menu_name' => 'Keyword'
            ),
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'keywords')
        ));
    }

    static function createMetaBoxes(){

        //Activity meta box
        add_meta_box(
            'activity_data_id',
            'Activity content',
            array(self::class, 'activity_data_html'),
            'activity'
        );
    }




        
    static function activity_data_html($post){

        // Post meta data
        $activityContent = get_post_meta($post->ID, '_activity_content', true);

        //html for the meta boxes
        ?>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="activity-id">Activity ID</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="activity_id" value="<?php echo $activityContent['activity_id'] ?>" id="activity_id">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="jacket-image">Link to jacket image</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="jacket_image" value="<?php echo $activityContent['jacket_image'] ?>" id="jacket_image">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="keynote">Keynote</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="keynote" value="<?php echo $activityContent['keynote'] ?>" id="keynote">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="activity-date">Activity date</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="activity_date" value="<?php echo $activityContent['activity_date'] ?>" id="activity_date">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-isbn">Book ISBN</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book_isbn" value="<?php echo $activityContent['book_isbn'] ?>" id="book_isbn">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-name">Book name</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book_name" value="<?php echo $activityContent['book_name'] ?>" id="book_name">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-link">Link to book</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book_link" value="<?php echo $activityContent['book_link'] ?>" id="book_link">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="author-name">Author name</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="author_name" value="<?php echo $activityContent['author_name'] ?>" id="author_name">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="author-link">Link to author</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="author_link" value="<?php echo $activityContent['author_link'] ?>" id="author_link">
                </div>
            </div>

        <?php
    }

    static function saveActivityMetaBoxes( $post_id ){

        //saves the data entered into the meta boxes when the post is saved
        $keys = array('activity_id', 'jacket_image', 'keynote', 'activity_date', 'book_isbn', 'book_name', 'book_link', 'author_name', 'author_link');
        $activityContent = array();

        if( array_key_exists('activity_id', $_POST) && $_POST['activity_id'] == $post_id){

            foreach($keys as $key){
                $activityContent[$key] = $_POST[$key];
            }

            update_post_meta(
                $post_id,
                '_activity_content',
                $activityContent
            );
        }
        
    }

    static function setTemplate($single_template){
        global $post;

        if($post->post_type == 'activity'){
            $single_template = dirname(__FILE__) . '/activity-post.php';

            return $single_template;
        }
        else{
            return $single_template;
        }
    }

}


?>
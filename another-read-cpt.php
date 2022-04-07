<?php 

class AnotherReadCPT{

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

        //array to loop through the metaboxes
        $arraykeys = array('_activity_id','_jacket_image', '_keynote', '_activity_date', '_book_isbn', '_book_name', '_book_link', '_author_name', '_author_link'); 

        $value = array();
        $i = 0;

        //Obtains the values for the metaboxes
        foreach($arraykeys as $arraykey){
            $value[$i++] = get_post_meta($post->ID, $arraykey, true);
        }

        //html for the meta boxes
        ?>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="activity-id">Activity ID</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="activity-id" value="<?php echo $value[0] ?>" id="activity-id">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="jacket-image">Link to jacket image</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="jacket-image" value="<?php echo $value[1] ?>" id="jacketImage">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="keynote">Keynote</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="keynote" value="<?php echo $value[2] ?>" id="keynote">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="activity-date">Activity date</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="activity-date" value="<?php echo $value[3] ?>" id="activity-date">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-isbn">Book ISBN</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book-isbn" value="<?php echo $value[4] ?>" id="book-isbn">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-name">Book name</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book-name" value="<?php echo $value[5] ?>" id="book-name">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="book-link">Link to book</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="book-link" value="<?php echo $value[6] ?>" id="book-link">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="author-name">Author name</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="author-name" value="<?php echo $value[7] ?>" id="author-name">
                </div>
            </div>
            <div class="meta-container">
                <div class="meta-label">
                    <label for="author-link">Link to author</label>
                </div>
                <div class="meta-input">
                    <input type="text" name="author-link" value="<?php echo $value[8] ?>" id="author-link">
                </div>
            </div>

        <?php
    }

    static function saveMetaBoxes(int $post_id){

        //saves the data entered into the meta boxes when the post is saved
        $arraykeys = array('_activity_id','_jacket_image', '_keynote', '_activity_date', '_book_isbn', '_book_name', '_book_link', '_author_name', '_author_link'); 

        $keys = array('activity_id', 'jacket-image', 'keynote', 'activity-date', 'book-isbn', 'book-name', 'book-link', 'author-name', 'author-link'); 

        $i = 0;
        foreach($arraykeys as $arraykey){
            if( array_key_exists($keys[$i], $_POST)){                
                update_post_meta(
                    $post_id,
                    $arraykey,
                    $_POST[$keys[$i++]]
                );
            }
        }
    }

}


?>
<?php
/*
*   Admin page
*/

    class AnotherReadActivityAdmin{

        //page that is displayed in wp-admin
        static function adminPage(){

            $settings = get_option('another_read_settings');

            $publishers = array("Pan Macmillan", "Barefoot Books", "Abrams Books", "Chronicle Books", "Princeton Architectural Press", "Galison Mudpuppy", "Child's Play", "Quarto", "Sweet Cherry Publishing", "Line Industries", "Nobrow", "Childs Play", "Childsplay", "Penguin Books Ltd", "Pushkin Press", "RHCP", "Child's Play (International) Ltd", "Frances Lincoln Children's Books", "Frances Lincoln", "Voyageur Press", "becker&mayer! kids", "QED Publishing", "Walter Foster Jr", "Quarry Books", "Walter Foster Publishing", "Wide Eyed Editions", "Seagrass Press", "MoonDance Press", "Faber & Faber", "Rock Point", "words & pictures", "Ivy Kids", "Ivy Press", "Exisle Publishing", "Zest Books", "Rockport Publishers", "Creative Publishing international", "Race Point Publishing", "Cool Springs Press", "Macmillan", "Young Voyageur", "EK Books", "Leaping Hare Press", "Famillus", "Lincoln Children's Books", "QEB Publishing", "Quarto Children's Books", "QED", "Little Pink Dog Books", "Andersen Press Ltd", "Scribe Publications Pty Ltd.","Curious Fox", "Raintree", "Fair Winds Press", "Apple Press", "Penguin Random House Children's UK", "becker&mayer! books ISBN", "Happy Yak", "Pavilion Children's", "Transworld", "Child's Play (International) Ltd");

            if(isset($_POST['update_settings']) || isset($_POST['update_posts'])){ echo '<div class="notice notice-success settings-error"><p>Settings were updated successfuly</p></div>';}
            if(isset($_POST['update_posts']) && $settings['apiCallSuccessful'] == false){ echo '<div class="error"><p>There was an error with the API call. Please check your settings and try again.</p></div>';}
            if(isset($_POST['update_posts']) && $settings['apiCallSuccessful'] == true){ echo '<div class="notice notice-success settings-error"><p>API call was successful and activity posts were gathered.</p></div>';}
            
            
            ?>
            <div class="another-read-admin">
                <div class="another-read-admin-settings">
                    <div class="admin-header">
                        <h1>Another Read</h1>
                        <h2>Activity Post Settings</h2>
                    </div>
                    <div class="another-read-activity-settings">
                        <form action="" method="post">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <label for="keyword">Activity keyword</label>
                                        </th>
                                        <td>
                                            <input type="text" id="keyword" name="keyword" value="<?php if(isset($settings['keyword'])){echo $settings['keyword'];}  ?>" class="regular-text">
                                        </td>
                                    </tr>
                                    <tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="contributor">Contributor ID</label>
                                        </th>
                                        <td>
                                            <input type="text" id="contributor" name="contributor" value="<?php if(isset($settings['contributor'])){echo $settings['contributor'];}  ?>" class="regular-text">
                                            <p>Find your contributor ID by logging in to <a href="anotherread.com">anotherread.com</a> and viewing your <a href="https://anotherread.com/tools/account-details">account details</a> page.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="publisher">Publisher</label>
                                        </th>
                                        <td>
                                            <select name="publisher" id="publisher">
                                                <option value="" <?php if($settings['publisher'] == ''){ echo 'selected';} ?>>Select</option>
                                                <?php foreach($publishers as $publisher){
                                                    echo '<option value="'.$publisher.'"' .($settings['publisher'] == $publisher ? "selected" : "").'>'.$publisher.'</option>';
                                                } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="results">Enter the number of posts wanted during the next update</label>
                                        </th>
                                        <td>
                                            <input type="number" id="results" name="results" value="<?php if(isset($settings['results'])){echo $settings['results'];} ?>" required class="regular-text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="accesskey">Another Read API key</label>
                                        </th>
                                        <td>
                                            <input class="regular-text" type="text" id="accesskey" name="accesskey" value="<?php if(isset($settings['accesskey'])){echo $settings['accesskey'];} ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <h4>Last updated</h4>
                                        </th>
                                        <td>
                                            <p><?php if(get_option('another_read_settings_timestamp') !== false){ echo get_option('another_read_settings_timestamp')->date;}else{ echo "There has been no updates";} ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                                <p class="submit">
                                    <input type="submit" name="update_settings" class="button button-primary" value="Save settings" >
                                    <input type="submit" name="update_posts" class="button button-primary" value="Update posts" >
                                </p>
                            </div>
                        </form>                  
                    </div>
                </div>
                <div class="another-read-content">
                    <div class="another-read-banner">
                        <div class="branding">
                            <a href="https://anotherread.com" target="_blank">
                                <img class="branding-img" src="<?php echo plugin_dir_url(__FILE__) . 'img/brand--extended--red.svg'; ?>" alt="Another Read">
                            </a>
                        </div>
                        <div class="banner-content">
                            <h2>Find children's books you like and we will recommend others.</h2>
                            <p>Our app helps parents and young readers discover the latest children's books as well as all-time favourites. We make topical recommendations based on the books readers like and bring them news and events from their favourite authors and illustrators</p>
                            <img class="content-img" src="<?php echo plugin_dir_url(__FILE__) . 'img/home-app-device-mock-up.jpg'; ?>" alt="">
                        </div>
                    </div>
                    <div class="another-read-faq">
                        <h2>F.A.Q</h2>
                    </div>
                </div>

            <?php

        }

    }


?>
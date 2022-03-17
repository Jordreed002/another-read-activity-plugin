<?php
/*
*   Admin page
*/

    class AnotherReadAdmin{

        //page that is displayed in wp-admin
        static function adminPage(){

            $settings = get_option('another_read_settings');
            
            ?>
                <div class="">
                    <h1>Another Read activity post settings</h1>
                </div>
                <div>
                    <p></p>
                </div>
                <div class="">
                    <form action="" method="post">
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <label for="keyword">Enter activity keyword</label>
                                    </th>
                                    <td>
                                        <input type="text" id="keyword" name="keyword" value="<?php if(isset($settings['keyword'])){echo $settings['keyword'];}  ?>" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                <tr>
                                    <th scope="row">
                                        <label for="keyword">Enter contributor ID</label>
                                    </th>
                                    <td>
                                        <input type="text" id="contributor" name="contributor" value="<?php if(isset($settings['contributor'])){echo $settings['contributor'];}  ?>" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="publisher">Enter publisher</label>
                                    </th>
                                    <td>
                                        <input type="text" id="publisher" name="publisher" value="<?php if(isset($settings['publisher'])){echo $settings['publisher'];} ?>" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="results">Enter the number of posts wanted</label>
                                    </th>
                                    <td>
                                        <input type="number" id="results" name="results" value="<?php if(isset($settings['results'])){echo $settings['results'];} ?>" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="accesskey">Enter Another Read API key here</label>
                                    </th>
                                    <td>
                                        <input type="text" id="accesskey" name="accesskey" value="<?php if(isset($settings['accesskey'])){echo $settings['accesskey'];} ?>">
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
                                <input type="submit" name="update_settings" class="button button-primary" value="Save settings" class="form-control">
                                <input type="submit" name="update_posts" class="button button-primary" value="Update posts" class="form-control">
                            </p>
                        </div>
                    </form>

                    <?php if(isset($_POST['update_settings'])){ echo "<div> <h4>Setting updated</h4> </div>";} ?>
                    
                </div>

            <?php

        }

    }


?>
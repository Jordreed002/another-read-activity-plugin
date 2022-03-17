<?php 

class AnotherReadPostCreator{


    static function APIcall(){

        $options = get_option('another_read_settings');


        $url = "https://anotherread.com/site/read/templates/api/activities/json/v2/get-activity-list/default.aspx";
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Accept: application/json"
        );
    
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
        $data =
        [
            "accesskey" => $options['accesskey'],
            "quantityofrecords"=> $options['results'],
            "publishers" => $options['publisher'],
            "contributors" => $options['contributor'],
            "keywords"=> $options['keyword']
        ];
    
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    
        $resp = curl_exec($curl);
        curl_close($curl);
    
        $activityRepsonse = json_decode($resp, true);

        if($activityRepsonse["ApiCallWasSuccessful"] == true)
        {
            $timestamp = new DateTime();
            if(get_option('another_read_settings_timestamp') !== false){
                update_option('another_read_settings_timestamp', $timestamp);
            }
            else{
                add_option('another_read_settings_timestamp', $timestamp);
            }
            //print_r("there was no error");
            return $activityRepsonse['Payload'];
        }
        else{
            print_r("there was an error");
        }
    
    }

    static function create(){

        function checkRecentPosts($numberOfResults){

            $recentPosts = wp_get_recent_posts(array('post_type' => 'activity', 'numberposts' => $numberOfResults, 'post_status' => 'publish'));
            $mostRecentID = 0;
    
            if(!$recentPosts){

            }
            else{
                foreach($recentPosts as $post){
                    $temp = get_post_meta($post['ID'], '_activity_id', true);
                    if($temp > $mostRecentID){
                        $mostRecentID = $temp;
                    }
                }
            }

            generatePosts($mostRecentID, $numberOfResults);
        }

        function generatePosts($mostRecentID, $numberOfResults){

            $activityPayload = AnotherReadPostCreator::APIcall();
            $i = $numberOfResults - 1;

            while($i >= 0 ){
                if( $activityPayload['Result'][$i]['ActivityID'] > $mostRecentID){

                    $activities = $activityPayload['Result'][$i];
                    $contributorID = $activities['ContributorList'][0];

                    $title = $activities['ActivityText'];
                    $activityID = $activities['ActivityID'];
                    $jacketImage = $activities['ActivityJacketImage'];

                    $activityDate = $activities['ActivityDate'];
                    $bookISBN = $activities['Isbn'];


                    $bookLookup = $activityPayload['BookLookup'][$bookISBN];
                    $keynote = $bookLookup['Keynote'];
                    $bookName = $bookLookup['Title'];
                    $bookLink = $bookLookup['BookLink'];

                    $contributorLookup = $activityPayload['ContributorLookup'][$contributorID];
                    $authorName = $contributorLookup['DisplayName'];
                    $authorLink = $contributorLookup['ContributorLink'];

                    $metaInput = array(
                        '_activity_id' => $activityID,
                        '_jacket_image' => $jacketImage,
                        '_keynote' => $keynote,
                        '_activity_date' => $activityDate,
                        '_book_isbn' => $bookISBN,
                        '_book_name' => $bookName,
                        '_book_link' => $bookLink,
                        '_author_name' => $authorName,
                        '_author_link' => $authorLink
                    );

                    $activityPost = array(
                        'post_title'    => wp_strip_all_tags( $title ),
                        'post_status'   => 'publish',
                        'post_type'     => 'activity',
                        'meta_input'    => $metaInput
                    );

                    wp_insert_post($activityPost);
                    print_r('post created');
                }
                $i--;
            }
        }

        $options = get_option('another_read_settings');
        $numberOfResults = $options['results'];

        checkRecentPosts($numberOfResults);
    }
}


?>
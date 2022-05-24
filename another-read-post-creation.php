<?php 

class AnotherReadActivityPostCreator{


    static function APIcall(){

        $settings = get_option('another_read_activity_settings');


        $url = "https://anotherread.com/site/read/templates/api/activities/json/v2/get-activity-list/default.aspx";
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Accept: application/json"
        );
    
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
        $data = array(
        
            "accesskey" => $settings['accesskey'],
            "quantityofrecords" => $settings['results'],
        );

        if($settings['publisher'] !== ''){
            $data['publisher'] = $settings['publisher'];
        }
        if($settings['contributor'] !== ''){
            $data['contributors'] = $settings['contributor'];
        }
        if($settings['keyword'] !== ''){
            $data['keywords'] = $settings['keyword'];
        }

    
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    
        $resp = curl_exec($curl);
        curl_close($curl);
    
        $activityRepsonse = json_decode($resp, true);

        if($activityRepsonse["ApiCallWasSuccessful"] == true)
        {
            $timestamp = new DateTime();
            if(isset($settings['timestamp'])){
                $settings['timestamp'] = $timestamp;
                update_option('another_read_activity_settings', $settings);
            }
            else{
                $settings['timestamp'] = $timestamp;
                add_option('another_read_activity_settings', $settings);
            }
            //print_r("there was no error");
            $settings['apiCallSuccessful'] = true;
            update_option('another_read_activity_settings', $settings);
            return $activityRepsonse;
        }
        else{
            
            //print_r("there was an error");
            $settings['apiCallSuccessful'] = false;
            update_option('another_read_activity_settings', $settings);
            return $activityRepsonse;
        }
    
    }

    static function create(){

        function arrayKeyCheck($key){
            if(isset($key)){
               return $key;
            }
            else{
                return '';
            }
        }

        $settings = get_option('another_read_activity_settings');
        $numberOfResults = $settings['results'];

        $activityPayload = AnotherReadActivityPostCreator::APIcall();
        $i = $numberOfResults - 1;

        if($activityPayload['ApiCallWasSuccessful'] == true){

            $activityPayload = $activityPayload['Payload'];
            
            while($i >= 0 ){
                if( get_post($activityPayload['Result'][$i]['ActivityID']) == false){

                    $activities = arrayKeyCheck($activityPayload['Result'][$i]);
                    $contributorID = arrayKeyCheck($activities['ContributorList'][0]);

                    $title = arrayKeyCheck($activities['ActivityText']);
                    $activityID = arrayKeyCheck($activities['ActivityID']);
                    $jacketImage = arrayKeyCheck($activities['ActivityJacketImage']);

                    $activityDate = arrayKeyCheck($activities['ActivityDate']);
                    $timestamp = strtotime($activityDate);
                    $activityDate = date('jS F Y', $timestamp);

                    $bookISBN = $activities['Isbn'];


                    $bookLookup = arrayKeyCheck($activityPayload['BookLookup'][$bookISBN]);
                    $keynote = arrayKeyCheck($bookLookup['Keynote']);
                    $bookName = arrayKeyCheck($bookLookup['Title']);
                    $bookLink = arrayKeyCheck($bookLookup['BookLink']);

                    $contributorLookup = arrayKeyCheck($activityPayload['ContributorLookup'][$contributorID]);
                    $authorName = arrayKeyCheck($contributorLookup['DisplayName']);
                    $authorLink = arrayKeyCheck($contributorLookup['ContributorLink']);

                    //$keywords = $bookLookup['Keywords'];

                    $metaInput = array(
                        '_activity_content' => array(
                            'activity_id' => $activityID,
                            'jacket_image' => $jacketImage,
                            'keynote' => $keynote,
                            'activity_date' => $activityDate,
                            'book_isbn' => $bookISBN,
                            'book_name' => $bookName,
                            'book_link' => $bookLink,
                            'author_name' => $authorName,
                            'author_link' => $authorLink
                        )

                    );

                    $activityPost = array(
                        'post_title'    => wp_strip_all_tags( $title ),
                        'post_status'   => 'publish',
                        'post_type'     => 'activity',
                        'meta_input'    => $metaInput,
                        'import_id'     => $activityID
                        //'tax_input'     => array( 'keywords' => $keywords )
                    );
                    
                    wp_insert_post($activityPost);
                    //print_r('post created');
                }
                $i--;
            }
        }

        

    }
}


?>
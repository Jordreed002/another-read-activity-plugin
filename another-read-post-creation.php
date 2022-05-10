<?php 

class AnotherReadActivityPostCreator{


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
    
        $data = array(
        
            "accesskey" => $options['accesskey'],
            "quantityofrecords" => $options['results'],
        );

        if($options['publisher'] !== ''){
            $data['publisher'] = $options['publisher'];
        }
        if($options['contributor'] !== ''){
            $data['contributors'] = $options['contributor'];
        }
        if($options['keyword'] !== ''){
            $data['keywords'] = $options['keyword'];
        }

    
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
            $options['apiCallSuccessful'] = true;
            update_option('another_read_settings', $options);
            return $activityRepsonse;
        }
        else{
            
            //print_r("there was an error");
            $options['apiCallSuccessful'] = false;
            update_option('another_read_settings', $options);
            return $activityRepsonse;
        }
    
    }

    static function create(){

        $options = get_option('another_read_settings');
        $numberOfResults = $options['results'];

        $activityPayload = AnotherReadActivityPostCreator::APIcall();
        $i = $numberOfResults - 1;

        if($activityPayload['ApiCallWasSuccessful'] == true){

            $activityPayload = $activityPayload['Payload'];
            
            while($i >= 0 ){
                if( get_post($activityPayload['Result'][$i]['ActivityID']) == false){

                    $activities = $activityPayload['Result'][$i];
                    $contributorID = $activities['ContributorList'][0];

                    $title = $activities['ActivityText'];
                    $activityID = $activities['ActivityID'];
                    $jacketImage = $activities['ActivityJacketImage'];

                    $activityDate = $activities['ActivityDate'];
                    $timestamp = strtotime($activityDate);
                    $activityDate = date('jS F Y', $timestamp);

                    $bookISBN = $activities['Isbn'];


                    $bookLookup = $activityPayload['BookLookup'][$bookISBN];
                    $keynote = $bookLookup['Keynote'];
                    $bookName = $bookLookup['Title'];
                    $bookLink = $bookLookup['BookLink'];

                    $contributorLookup = $activityPayload['ContributorLookup'][$contributorID];
                    $authorName = $contributorLookup['DisplayName'];
                    $authorLink = $contributorLookup['ContributorLink'];

                    //$keywords = $bookLookup['Keywords'];

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
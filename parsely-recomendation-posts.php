<?php
// Shortcode function
function fetch_personalized_related_content($uuid, $url, $apiKey, $apiSecret, $endpoint){

    $ch = curl_init($endpoint . '/profile?apikey=' . $apiKey . '&uuid=' . $uuid . '&url=' . urlencode($url));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error: $error");
    }
    
    curl_close($ch);
    
    $ch = curl_init($endpoint . '/related?apikey=' . $apiKey . '&uuid=' . $uuid . '&limit=5');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
    
    $response = curl_exec($ch);
    
    if ($response !== false) {
        $parsedResponse = json_decode($response, true);
        if ($parsedResponse['success'] === true) {
            return $parsedResponse['data'] ;
        } else {
            return false;
        }
    }
    
    curl_close($ch);
    }
    
    // Shortcode function
    function personalized_sidebar_widget_shortcode($atts) {
        global $post;
    
        // Parsely API credentials
        $apiKey = 'Your PARSELY API KEY HERE';
        $apiSecret = "Your PARSELY APi SECRET KEY HERE";
        $endpoint = 'https://api.parsely.com/v2';
    
        // user ID or create/update a cookie for non-logged-in users
        if (is_user_logged_in()) {
            $uuid = get_current_user_id();
        } else {
            $uuid = isset($_COOKIE['parsely_uuid']) ? $_COOKIE['parsely_uuid'] : uniqid('parsely_anon_');
        }
    
        // Set or update the cookie
        setcookie('parsely_uuid', $uuid, time() + 60 * 60 * 24 * 180, '/');
    
        // Check if it's a single post page
        if (is_single()) {
            ?>
    <?php
            $url = get_permalink($post->ID);
    
            // Fetch personalized related content
            $recommendedPosts = fetch_personalized_related_content($uuid, $url, $apiKey, $apiSecret, $endpoint);
            // Output HTML for the sidebar widget
            $output  = '<aside class="recommended-posts">';
            $output .= '<div class="sidebar-widget">';
            $output .= '<header><h2 class="g1-delta g1-delta-2nd widgettitle"><span>Trending Now</span></h2></header>';
            // Loop through each related content item
            if($recommendedPosts){
                foreach ($recommendedPosts as $recommendedPost) {
                    // Remove any additional parameters from the URL
                    $cleanUrl = remove_query_arg('itm_source', $recommendedPost['url']);
                    $imageUrl = remove_query_arg('resize', $recommendedPost['image_url']);
                    $output .= '<div class="widget-item">';
                    $output .= '<a href="' . esc_url($cleanUrl) . '"><img src="' . esc_url($imageUrl) . '" alt="' . esc_attr($recommendedPost['title']) . '"></a>';
                    $output .= '<h3><a href="' . esc_url($cleanUrl) . '">' . esc_html($recommendedPost['title']) . '</a></h3>';
                    $output .= '</div>';
                }
            }
            $output .= '</div>';
            $output .= '</aside>';
            return $output;
        }
    }
    
    // Register the shortcode
    add_shortcode('personalized_sidebar_widget', 'personalized_sidebar_widget_shortcode');

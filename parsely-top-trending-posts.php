<?php
// Shortcode function
function fetch_top_posts_content($apiKey, $apiSecret, $endpoint, $offset, $limit){

    $ch = curl_init($endpoint . '/top/posts?apikey=' . $apiKey . '&limit='.$limit);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");

    $response = curl_exec($ch);

    if ($response !== false) {
        $parsedResponse = json_decode($response, true);
        if ($parsedResponse['success'] === true) {
            $data = $parsedResponse['data'];
            if($offset != 0){
                if(is_array($data) && count($data) > $offset){
                    $data = array_slice($data, $offset);
                }
            }
            return $data;
        } else {
            return false;
        }
    }

    curl_close($ch);
}

    // Shortcode function trending posts
    // params offset=0, limit=3
    function trending_posts($atts) {
        $atts = shortcode_atts(
            array(
                'offset' => 0,
                'limit' => 3,
            ),
            $atts,
            'parsely_trending_posts'
        );

        // Parsely API credentials
        $apiKey = 'americansongwriter.com';
        $apiSecret = "Owe9gLAvJ4GLaH979o1lvGFOADyvvyYGhmRsYCgGVZ0";
        $endpoint = 'https://api.parsely.com/v2';
            ?>
    <script>
    function moveSecondarySection() {
        // const commect_section = document.querySelector('body.single #comments');
        // const secondary_section = document.querySelector('body.single #secondary');

        //Trending section renders after Latest News
        const moreNewsLink = document.querySelector('.more-news-link');
        const sidebar = document.querySelector('.single-p-sidebar');
        const container = moreNewsLink.parentElement;

        if (window.innerWidth <= 600) {
            // commect_section.append(secondary_section);
            container.insertBefore(sidebar, moreNewsLink.nextSibling);
        } 
        // else {
        //     const originalLocation = document.querySelectorAll('body.single .g1-column.g1-column-2of3')[1];
        //     originalLocation.insertAdjacentElement('afterend', secondary_section);
        // }
    }
    moveSecondarySection();
    window.addEventListener('resize', () => {
        moveSecondarySection();
    });
    </script>
    <?php

            // Fetch personalized related content
            $topPosts = fetch_top_posts_content($apiKey, $apiSecret, $endpoint, $atts['offset'], $atts['limit']);
            // Output HTML for the sidebar widget
            $output = '<div class="sidebar-widget">';
            $output .= '<header><h2 class="g1-delta g1-delta-2nd widgettitle"><span>Trending Now</span></h2></header>';
            // Loop through each related content item
            if($topPosts){
                foreach ($topPosts as $topPost) {
                    // Remove any additional parameters from the URL
                    $cleanUrl = remove_query_arg('itm_source', $topPost['url']);
                    $imageUrl = remove_query_arg('resize', $topPost['image_url']);
                    $output .= '<div class="widget-item trending">';
                    $output .= '<a href="' . esc_url($cleanUrl) . '"><img src="' . esc_url($imageUrl) . '" alt="' . esc_attr($topPost['title']) . '"></a>';
                    $output .= '<h3><a href="' . esc_url($cleanUrl) . '">' . esc_html($topPost['title']) . '</a></h3>';
                    $output .= '</div>';
                }
            }
            $output .= '</div>';
            return $output;

    }

    // Register the shortcode
    add_shortcode('parsely_trending_posts', 'trending_posts');
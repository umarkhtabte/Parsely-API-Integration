<?php
// Trending Tags Function API Function
function fetch_trending_tags($apiKey, $apiSecret, $endpoint)
{
    $ch = curl_init($endpoint . '/top/posts?apikey=' . $apiKey . '&limit=10');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");

    $response = curl_exec($ch);

    if ($response !== false) {
        $parsedResponse = json_decode($response, true);
        if ($parsedResponse['success'] === true) {
            $trendingPosts = $parsedResponse['data'];

            // Extract first tag from each post and store in an array
            $trendingTags = [];
            foreach ($trendingPosts as $trendingPost) {
                if (!empty($trendingPost['tags'])) {
                    $trendingTags[] = $trendingPost['tags'][0];
                }
            }
            return $trendingTags;
        } else {
            // Handle API error
            echo "API error: " . $parsedResponse['error'];
        }
    } else {
        // Handle cURL error
        echo "cURL error: " . curl_error($ch);
    }

    curl_close($ch);
}


function select_content($i, $tag, $trendingTags) {
    //Check the option for existing content
    $option_link = 'asw_trending_' . $i . '_link';
    $option_text = 'asw_trending_' . $i . '_text';
    if ( ! empty( get_option($option_link) && ! empty( get_option($option_text) ) ) ) {
        return '<li><a href="' . esc_url(get_option($option_link)) . '">' . esc_html(get_option($option_text)) . '</a></li>';
    }

    //Pull from parsely API if no option is present
    $originalTagName = $trendingTags[$i];
    $tagName = ucfirst(preg_replace('/.*:/', '', $originalTagName));
    $tagdata = get_term_by('name', $originalTagName, 'post_tag');
    if ($tagdata) {
        $tagSlug = $tagdata->slug;
        $tagSlug = '/tag/' . $tagSlug;
    } else {
        $tagSlug = '';
    }

    return '<li><a href="' . $tagSlug . '">' . esc_html($tagName) . '</a></li>';
}

// Get Parsely Trending Tags Function
function fetch_parsely_trending_tags($atts)
{
    // Parsely API credentials
    $apiKey = 'Your PARSELY API KEY HERE';
    $apiSecret = "Your PARSELY APi SECRET KEY HERE";
    $endpoint = 'https://api.parsely.com/v2';
    $siteUrl = get_site_url();

    // Fetch Trending Tags
    $trendingTags = fetch_trending_tags($apiKey, $apiSecret, $endpoint);

    // Output HTML for the Tags
    echo '<div class="navbar-horizontal-scroll-wrap"><div class="navbar-horizontal-scroll"><div style="display: table; width: 100%; padding: 0 20px"><ul class="navbar">';
    $i = 0;
    foreach ($trendingTags as $tag) { 
        if ($i === 5) break;
        $output = select_content($i,$tag, $trendingTags);
        echo $output; // @codingStandardsIgnoreLine
        $i++;
    }
    echo " </ul></div></div></div>";
}

// Register the shortcode
add_shortcode('parsely_trending_tags', 'fetch_parsely_trending_tags');

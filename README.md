# Project Name: Parsely API Integration

## Description

This plugin is used to get data from Parsely API, for Recommendation Posts [personalized_sidebar_widget], Top Trending Posts "[parsely_trending_posts offset=0 limit=8]", and Trending Tags [parsely_trending_tags].

## Installation

1. Download the plugin from the WordPress plugin repository or clone the repository to your local machine.
2. Upload the plugin folder to the `wp-content/plugins` directory of your WordPress installation.
3. Activate the plugin through the WordPress admin dashboard.

## Usage

To use the plugin, you can add the shortcodes to your WordPress pages or posts. Here are some examples:

- To display personalized sidebar widgets, use the `[personalized_sidebar_widget]` shortcode.
- To display trending posts, use the `[parsely_trending_posts]` shortcode.
- To display trending tags, use the `[parsely_trending_tags]` shortcode.

## Configuration

To use the plugin, you need to replace the Parsely API keys in the following files:

- `parsely-api-integration.php`: Replace the `$apiKey` and `$apiSecret` variables with your own Parsely API credentials.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please submit a pull request or open an issue on the GitHub repository.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any inquiries or contributions, please reach out to [your-email@example.com](mailto:your-email@example.com).

<?php
/*
 * Plugin Name:       Embed Consent
 * Description:       Replaces embed blocks with a confirmation to ask for consent before loading third-party resources.
 * Version:           1.0.1
 * Requires at least: 6.1.1
 * Requires PHP:      7.4
 * Author:            Sam Clarke
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       embed-consent
 * Domain Path:       /languages
 */

namespace EmbedConsent;

// Prevent direct calling
if (!defined('WPINC')) {
	die;
}

define('EmbedConsent\VERSION', '1.0.0');
define('EmbedConsent\SETTINGS_SLUG', 'embed-consent');
define('EmbedConsent\EMBED_TAG',  '<!-- embed-consent -->');

/**
 * Names and polices of each provider
 * 
 * Providers will always have a name and privacy_url but cookies_url is optional
 *
 * @var array<string, array{name: string, privacy_url: string, cookies_url?: string}>
 */
define('EmbedConsent\PROVIDERS_INFO', [
	'youtube' => [
		'name' => 'YouTube',
		'privacy_url' => __('https://policies.google.com/privacy', 'embed-consent'),
		'cookies_url' => __('https://policies.google.com/technologies/cookies', 'embed-consent'),
	],
	'vimeo' => [
		'name' => 'Vimeo',
		'privacy_url' => __('https://vimeo.com/privacy', 'embed-consent'),
		'cookies_url' => __('https://vimeo.com/cookie_policy', 'embed-consent'),
	],
	'wordpress-tv' => [
		'name' => 'WordPress.tv',
		'privacy_url' => __('https://wordpress.org/about/privacy/', 'embed-consent'),
		'cookies_url' => __('https://wordpress.org/about/privacy/cookies/', 'embed-consent'),
	],
	'tiktok' => [
		'name' => 'TikTok',
		'privacy_url' => __('https://www.tiktok.com/legal/privacy-policy-row', 'embed-consent'),
		'cookies_url' => __('https://www.tiktok.com/legal/cookie-policy', 'embed-consent'),
	],
	'imgur' => [
		'name' => 'Imgur',
		'privacy_url' => __('https://imgur.com/privacy', 'embed-consent'),
	],
	'pocket-casts' => [
		'name' => 'Pocket Casts',
		'privacy_url' => __('https://support.pocketcasts.com/knowledge-base/privacy-policy/', 'embed-consent'),
	],
	'soundcloud' => [
		'name' => 'SoundCloud',
		'privacy_url' => __('https://soundcloud.com/pages/privacy', 'embed-consent'),
		'cookies_url' => __('https://soundcloud.com/pages/cookies', 'embed-consent'),
	],
	'spotify' => [
		'name' => 'Spotify',
		'privacy_url' => __('https://www.spotify.com/legal/privacy-policy/', 'embed-consent'),
		'cookies_url' => __('https://www.spotify.com/legal/cookies-policy/', 'embed-consent'),
	],
	'flickr' => [
		'name' => 'Flickr',
		'privacy_url' => __('https://www.flickr.com/help/privacy', 'embed-consent'),
		'cookies_url' => __('https://www.flickr.com/help/cookies', 'embed-consent'),
	],
	'twitter' => [
		'name' => 'Twitter',
		'privacy_url' => __('https://twitter.com/en/privacy', 'embed-consent'),
		'cookies_url' => __('https://help.twitter.com/en/rules-and-policies/twitter-cookies', 'embed-consent'),
	],
	'dailymotion' => [
		'name' => 'DailyMotion',
		'privacy_url' => __('https://legal.dailymotion.com/en/privacy-policy/', 'embed-consent'),
		'cookies_url' => __('https://legal.dailymotion.com/en/cookie-policy/', 'embed-consent'),
	],
	'issuu' => [
		'name' => 'Issuu',
		'privacy_url' => __('https://issuu.com/legal/privacy', 'embed-consent'),
		'cookies_url' => __('https://issuu.com/legal/cookies', 'embed-consent'),
	],
	'mixcloud' => [
		'name' => 'Mixcloud',
		'privacy_url' => __('https://www.mixcloud.com/privacy/', 'embed-consent'),
	],
	'reddit' => [
		'name' => 'Reddit',
		'privacy_url' => __('https://www.reddit.com/policies/privacy-policy', 'embed-consent'),
		'cookies_url' => __('https://www.reddit.com/policies/cookies', 'embed-consent'),
	],
	'reverbnation' => [
		'name' => 'ReverbNation',
		'privacy_url' => __('https://www.reverbnation.com/privacy', 'embed-consent'),
	],
	'scribd' => [
		'name' => 'Scribd',
		'privacy_url' => __('https://support.scribd.com/hc/en-us/articles/210129366-Global-Privacy-Policy', 'embed-consent'),
	],
	'slideshare' => [
		'name' => 'Slideshare',
		'privacy_url' => __('https://support.scribd.com/hc/en/articles/210129366-Privacy-policy', 'embed-consent'),
	],
	'speaker-deck' => [
		'name' => 'Speaker Deck',
		'privacy_url' => __('https://speakerdeck.com/privacy', 'embed-consent'),
	],
	'ted' => [
		'name' => 'TED',
		'privacy_url' => __('https://www.ted.com/about/our-organization/our-policies-terms/privacy-policy', 'embed-consent'),
	],
	'tumblr' => [
		'name' => 'Tumblr',
		'privacy_url' => __('https://www.tumblr.com/privacy/en', 'embed-consent'),
	],
	'amazon' => [
		'name' => 'Amazon',
		'privacy_url' => __('https://www.amazon.com/gp/help/customer/display.html?nodeId=468496', 'embed-consent'),
		'cookies_url' => __('https://www.amazon.com/cookies', 'embed-consent'),
	],
	'pinterest' => [
		'name' => 'Pinterest',
		'privacy_url' => __('https://policy.pinterest.com/privacy-policy', 'embed-consent'),
	],
	'wolfram-cloud' => [
		'name' => 'Wolfram',
		'privacy_url' => __('https://www.wolfram.com/legal/privacy/wolfram/', 'embed-consent'),
	],
	'videopress' => [
		'name' => 'VideoPress',
		'privacy_url' => __('https://automattic.com/privacy/', 'embed-consent'),
	],

	// The following have not been tested as they either require an account
	// or the embed block couldn't be made to work with them.
	// Testing mostly checks the provider name is correct so, as long as the
	// name is right, they should work. 
	'animoto' => [
		'name' => 'Animoto',
		'privacy_url' => __('https://animoto.com/legal/privacy_policy', 'embed-consent'),
	],
	'cloudup' => [
		'name' => 'Cloudup',
		'privacy_url' => __('https://automattic.com/privacy/', 'embed-consent'),
	],
	'crowdsignal' => [
		'name' => 'Crowdsignal',
		'privacy_url' => __('https://automattic.com/privacy/', 'embed-consent'),
	],
	'kickstarter' => [
		'name' => 'Kickstarter',
		'privacy_url' => __('https://www.kickstarter.com/privacy', 'embed-consent'),
		'cookies_url' => __('https://www.kickstarter.com/cookies', 'embed-consent'),
	],
	'screencast' => [
		'name' => 'Screencast',
		'privacy_url' => __('https://www.techsmith.com/privacy-policy.html', 'embed-consent'),
	],
	'smugmug' => [
		'name' => 'SmugMug',
		'privacy_url' => __('https://www.smugmug.com/about/privacy', 'embed-consent'),
	],
]);

/**
 * Returns the contents of a plugin file
 * 
 * @param string $path
 *
 * @return string
 */
function plugin_file_contents($path)
{
	global $wp_filesystem;

	require_once(ABSPATH . '/wp-admin/includes/file.php');
	WP_Filesystem();

	return $wp_filesystem->get_contents(plugin_dir_path(__FILE__) . $path);
}

/**
 * Add scripts to load embed if confirmed and add styles
 * to handle the confirmation dialog.
 * 
 * Includes the resources either as external files or inline depending on the
 * settings.
 */
function enqueue_scripts()
{
	if (!has_block('core/embed')) {
		return;
	}

	if (function_exists('wp_prime_option_caches')) {
		wp_prime_option_caches([
			'embed_consent_inline_style',
			'embed_consent_inline_scripts',
			// These are not used here but will be used when rendering
			// the consent message so prime them in one go
			'embed_consent_youtube_no_cookie',
			'embed_consent_opt_in_providers',
			'embed_consent_theme_light',
			'embed_consent_theme_auto',
		]);
	}

	if (!get_option('embed_consent_inline_style', true)) {
		wp_enqueue_style(
			'embed-consent-style',
			plugins_url('', __FILE__) . '/assets/css/embed-consent.min.css',
			[],
			VERSION
		);
	} else {
		$path = 'assets/css/embed-consent.min.css';
		wp_register_style('embed-consent-style', false, [], VERSION);
		wp_enqueue_style('embed-consent-style');
		wp_add_inline_style('embed-consent-style', plugin_file_contents($path));
	}

	if (!get_option('embed_consent_inline_scripts', true)) {
		wp_enqueue_script(
			'embed-consent-script',
			plugins_url('', __FILE__) . '/assets/js/embed-consent.min.js',
			[],
			VERSION,
			true
		);
	} else {
		$path =  'assets/js/embed-consent.min.js';
		wp_register_script(
			'embed-consent-script',
			false,
			[],
			VERSION,
			true
		);
		wp_enqueue_script('embed-consent-script', '', [], VERSION, true);
		wp_add_inline_script('embed-consent-script', plugin_file_contents($path));
	}
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts');


/**
 * Adds a wrapper around embeds to make it easy to extract the HTML
 *
 * @param string $cached_html
 *
 * @return string
 */
function wrap_oembed_html($cached_html)
{
	return EMBED_TAG  . $cached_html . EMBED_TAG;
}
add_filter('embed_oembed_html', __NAMESPACE__ . '\\wrap_oembed_html');

/**
 * Creates a consent template for the given provider
 *
 * @param string $provider_name
 *
 * @return string
 */
function consent_template($provider_name, $block_content)
{
	$provider_info = PROVIDERS_INFO[$provider_name];

	$provider_name = esc_attr($provider_name);

	$heading =  sprintf(
		/* translators: %s: Name of website that embedded content is from */
		esc_html__('Allow %s content?', 'embed-consent'),
		$provider_info['name']
	);

	$notice = sprintf(
		/* translators: %s: Name of website that embedded content is from */
		esc_html__('This page contains content provided by %s. Your consent is requested before loading the content, as it may use cookies and other technologies.', 'embed-consent'),
		$provider_info['name']
	);

	if (empty($provider_info['cookies_url'])) {
		$policies =  sprintf(
			/* translators: 1: Name of the website the embedded content is from 2: Link to the privacy policy */
			__('You may want to read <a href="%2$s">%1$s\'s privacy policy</a> before accepting.', 'embed-consent'),
			$provider_info['name'],
			esc_url($provider_info['privacy_url'])
		);
	} else {
		$policies =  sprintf(
			/* translators: 1: Name of the website the embedded content is from 2: Link to the privacy policy 3: Link to the cookie policy */
			__('You may want to read <a href="%2$s">%1$s\'s privacy policy</a> and <a href="%3$s">cookie policy</a> before accepting.', 'embed-consent'),
			$provider_info['name'],
			esc_url($provider_info['privacy_url']),
			esc_url($provider_info['cookies_url'])
		);
	}

	$policies = wp_kses($policies, ['a' => ['href' => []]]);

	$checkbox = '';
	if (get_option('embed_consent_opt_in_providers', false)) {
		$checkbox_label = sprintf(
			/* translators: %s: Name of website that embedded content is from */
			esc_html__('Always load content from %s.', 'embed-consent'),
			$provider_info['name']
		);

		$checkbox = "<p><label><input type=\"checkbox\"> $checkbox_label</label></p>";
	}

	$theme_classes = '';
	if (!get_option('embed_consent_theme_light', true)) {
		$theme_classes .= ' embed-consent-dark';
	}

	if (get_option('embed_consent_theme_auto', false)) {
		$theme_classes .= ' embed-consent-auto';
	}

	$html = '<div class="embed-consent' . $theme_classes . '" data-embed-consent-provider="' . $provider_name . '">';
	$html .= '<div>';
	$html .= '<p class="embed-consent-heading">' . $heading . '</p>';
	$html .= "<p>$notice $policies</p>";
	$html .= '<p>';
	$html .= '<button>' . esc_html__('Accept and load content', 'embed-consent') . '</button>';
	$html .= '</p>';
	$html .= $checkbox;
	$html .= '</div>';
	$html .= '<template>' . esc_html($block_content) . '</template>';
	$html .= '</div>';

	return $html;
}

/**
 * Replaces embedded content in core/embed blocks with a consent confirmation.
 *
 * @param string   $block_content
 * @param array    $block
 * @param WP_Block $instance
 *
 * @return string
 */
function render_embed($block_content, $block, $instance)
{
	if (empty($block['attrs']['providerNameSlug'])) {
		return str_replace(EMBED_TAG, '', $block_content);
	}

	$provider_name = $block['attrs']['providerNameSlug'];

	if (get_option('embed_consent_youtube_no_cookie', true) && $provider_name === 'youtube') {
		$block_content = str_replace('youtube.com/embed', 'youtube-nocookie.com/embed', $block_content);
	}

	if (!isset(PROVIDERS_INFO[$provider_name])) {
		return str_replace(EMBED_TAG, '', $block_content);
	}

	$tag_regex = "/" . preg_quote(EMBED_TAG, '/') . "(.*?)" . preg_quote(EMBED_TAG, '/') . "/si";

	return preg_replace_callback($tag_regex, function ($matches) use ($provider_name) {
		return consent_template($provider_name, $matches[1]);
	}, $block_content, 1);
}
add_filter('render_block_core/embed', __NAMESPACE__ . '\\render_embed', 10, 3);

/**
 * Registers the [embed_consent_opt_out] shortcode used to opt-out
 */
function register_shortcode()
{
	add_shortcode('embed_consent_opt_out', function () {
		if (!get_option('embed_consent_opt_in_providers', false)) {
			return "";
		}

		if (!get_option('embed_consent_inline_scripts', true)) {
			wp_enqueue_script(
				'embed-consent-script-opt-out',
				plugins_url('', __FILE__) . '/assets/js/embed-consent-opt-out.min.js',
				[],
				VERSION,
				true
			);
		} else {
			$path =  'assets/js/embed-consent-opt-out.min.js';
			wp_register_script('embed-consent-script-opt-out', false, [], VERSION, true);
			wp_enqueue_script('embed-consent-script-opt-out', '', [], VERSION, true);
			wp_add_inline_script('embed-consent-script-opt-out', plugin_file_contents($path));
		}

		$content = '<div class="embed-consent-opt-out">';

		$content .= '<p class="embed-consent-opt-out-no-sites">';
		$content .= esc_html__('You have not opted in to always loading embedded content from any sites.', 'embed-consent');
		$content .= '</p>';

		$content .= '<p class="embed-consent-opt-out-has-sites" style="display: none">';
		$content .= esc_html__('You have opted in to always loading embedded content from the following sites:', 'embed-consent');
		$content .= '</p>';

		$content .= '<div class="embed-consent-opt-out-sites" style="display: none">';
		$content .= '<table>';

		foreach (PROVIDERS_INFO as $provider => $info) {
			$content .= '<tr data-embed-consent-provider="' . esc_attr($provider) . '">';
			$content .= '<td>' . $info['name'] . '</td>';
			$content .= '<td><button>' . esc_html__('Opt-out', 'embed-consent') . '</button></td>';
			$content .= '</tr>';
		}
		$content .= '<tr>';
		$content .= '<td rowspan="2">';
		$content .= '<button>' . esc_html__('Out-out of all', 'embed-consent') . '</button>';
		$content .= '</td>';
		$content .= '</tr>';
		$content .= '</table>';
		$content .= '</div>';

		$content .= '</div>';

		return $content;
	});
}
add_action('init', __NAMESPACE__ . '\\register_shortcode');

/**
 * Registers custom settings and the callbacks that render the HTML to display them
 */
function register_settings()
{
	if (function_exists('wp_add_privacy_policy_content')) {
		$content = '<p class="privacy-policy-tutorial">'
			. sprintf(
				/* translators: %s: The shortcode that needs to be embedded */
				esc_html__('Embed consent uses LocalStorage when users are allowed to opt-in to always loading to store if they have opted in. Use the shortcode %s to allow users to opt-out again. This should be omitted if the "show always load opt-in" setting is disabled as LocalStorage is only used when that setting is enabled.', 'embed-consent'),
				'<code>[embed_consent_opt_out]</code>'
			)
			. '</p>'
			. '<strong class="privacy-policy-tutorial">'
			. esc_html__('Suggested text:', 'embed-consent')
			. '</strong> '
			. esc_html__('This website uses LocalStorage when a third party embed is set to always load. LocalStorage is necessary for the setting to work and is only used checking the "Always load content from" checkbox. No data is saved in the database or transferred.', 'embed-consent')
			. '<h2 class="wp-block-heading">Opt-out of always loading embedded third party content</h2>'
			. '<strong class="privacy-policy-tutorial">'
			. esc_html__('Suggested text:', 'embed-consent')
			. '</strong> '
			. '[embed_consent_opt_out]';


		wp_add_privacy_policy_content(
			__('Embed consent', 'embed-consent'),
			wp_kses_post(wpautop($content, false))
		);
	}

	$boolean_default_true = [
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default' => true,
	];

	$boolean_default_false = [
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default' => false,
	];

	register_setting('embed_consent_settings', 'embed_consent_inline_style', $boolean_default_true);
	register_setting('embed_consent_settings', 'embed_consent_inline_scripts', $boolean_default_true);
	register_setting('embed_consent_settings', 'embed_consent_youtube_no_cookie', $boolean_default_true);
	register_setting('embed_consent_settings', 'embed_consent_opt_in_providers', $boolean_default_false);
	register_setting('embed_consent_settings', 'embed_consent_theme_light', $boolean_default_true);
	register_setting('embed_consent_settings', 'embed_consent_theme_auto', $boolean_default_false);

	add_settings_section('default', '', null, SETTINGS_SLUG);

	add_settings_field(
		'embed_consent_opt_in',
		__('Show always load opt-in', 'embed-consent'),
		function () {
?>
		<label>
			<input type="checkbox" name="embed_consent_opt_in_providers" id="embed_consent_opt_in_providers" value="1" <?php echo checked(get_option('embed_consent_opt_in_providers')) ?> />
			<?php echo esc_html__('Show a checkbox allowing users to opt-in to always loading a provider', 'embed-consent') ?>
		</label>
		<br>
		<p class="description">
			<?php echo
			/* translators: %s: The shortcode that needs to be embedded */
			sprintf(esc_html__('To allow users to opt-out, add the shortcode %s to your privacy policy.', 'embed-consent'), '<code>[embed_consent_opt_out]</code>')
			?>
		</p>
	<?php
		},
		SETTINGS_SLUG
	);

	add_settings_field(
		'embed_consent_inline',
		__('Inline resources', 'embed-consent'),
		function () {
	?>
		<fieldset>
			<label>
				<input type="checkbox" name="embed_consent_inline_style" id="embed_consent_inline_style" value="1" <?php echo checked(get_option('embed_consent_inline_style')) ?> />
				<?php echo esc_html__('Load embed consent styles inline instead of as an external CSS file', 'embed-consent') ?>
			</label>
			<br>
			<label>
				<input type="checkbox" name="embed_consent_inline_scripts" id="embed_consent_inline_scripts" value="1" <?php echo checked(get_option('embed_consent_inline_scripts')) ?> />
				<?php echo esc_html__('Load embed consent scripts inline instead of as an external JS file', 'embed-consent') ?>
			</label>
		</fieldset>
	<?php
		},
		SETTINGS_SLUG
	);

	add_settings_field(
		'embed_consent_theme',
		__('Theme', 'embed-consent'),
		function () {
	?>
		<fieldset>
			<label>
				<img width="250" src="<?php echo esc_url(plugin_dir_url(__FILE__)); ?>assets/screenshot-3.png" alt="<?php echo esc_html__('Light theme', 'embed-consent') ?>">
				<br>
				<input type="radio" name="embed_consent_theme_light" id="embed_consent_theme_light" value="1" <?php echo checked(get_option('embed_consent_theme_light')) ?> />
				<?php echo esc_html__('Light theme', 'embed-consent') ?>
			</label>
			<br>
			<label>
				<img width="250" src="<?php echo esc_url(plugin_dir_url(__FILE__)); ?>assets/screenshot-4.png" alt="<?php echo esc_html__('Dark theme', 'embed-consent') ?>">
				<br>
				<input type="radio" name="embed_consent_theme_light" id="embed_consent_theme_light" value="0" <?php echo checked(!get_option('embed_consent_theme_light')) ?> />
				<?php echo esc_html__('Dark theme', 'embed-consent') ?>
			</label>
		</fieldset>
	<?php
		},
		SETTINGS_SLUG
	);

	add_settings_field(
		'embed_consent_theme_auto',
		__('Dark / light mode', 'embed-consent'),
		function () {
	?>
		<fieldset>
			<label>
				<input type="radio" name="embed_consent_theme_auto" id="embed_consent_theme_auto" value="1" <?php echo checked(get_option('embed_consent_theme_auto')) ?> />
				<?php echo esc_html__('Enable dark / light mode', 'embed-consent') ?>
			</label>
			<br>
			<label>
				<input type="radio" name="embed_consent_theme_auto" id="embed_consent_theme_auto" value="0" <?php echo checked(!get_option('embed_consent_theme_auto')) ?> />
				<?php echo esc_html__('Disable dark / light mode', 'embed-consent') ?>
			</label>
			<p class="description">
				<?php echo
				/* translators: %s: The CSS prefers-color-scheme property */
				sprintf(esc_html__('If to use the users preferred color scheme from %s. If the user has no preference, the color scheme from the theme setting above is used.', 'embed-consent'), '<code>prefers-color-scheme</code>')
				?>
			</p>
		</fieldset>
	<?php
		},
		SETTINGS_SLUG
	);

	add_settings_field(
		'embed_consent_privacy',
		__('Enhanced privacy', 'embed-consent'),
		function () {
	?>
		<label>
			<input type="checkbox" name="embed_consent_youtube_no_cookie" id="embed_consent_youtube_no_cookie" value="1" <?php echo checked(get_option('embed_consent_youtube_no_cookie')) ?> />
			<?php echo esc_html__('Replace youtube URLs with youtube-nocookie.com domain', 'embed-consent') ?>
		</label>
	<?php
		},
		SETTINGS_SLUG
	);
}
add_action('admin_init', __NAMESPACE__ . '\\register_settings');

/**
 * Generates the settings page HTML
 */
function settings_page_html()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields('embed_consent_settings');

			do_settings_sections(SETTINGS_SLUG);

			submit_button(__('Save Settings', 'embed-consent'));
			?>
		</form>
	</div>
<?php
}

/**
 * Adds the settings page for this plugin to the menu
 */
function settings_page()
{
	add_options_page(
		__('Embed Consent Settings', 'embed-consent'),
		__('Embed Consent', 'embed-consent'),
		'manage_options',
		SETTINGS_SLUG,
		__NAMESPACE__ . '\\settings_page_html'
	);
}
add_action('admin_menu', __NAMESPACE__ . '\\settings_page');

/**
 * Add link to the plugins settings page under its name on the plugins page.
 */
function add_action_link($actions)
{
	return array_merge($actions, [
		'<a href="' . admin_url('options-general.php?page=' . SETTINGS_SLUG) . '">' . esc_html__('Settings', 'embed-consent') . '</a>'
	]);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), __NAMESPACE__ . '\\add_action_link');

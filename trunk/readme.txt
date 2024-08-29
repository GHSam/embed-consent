=== Embed Consent ===
Contributors: wpsamclarke
Tags: oembed, embed, consent, privacy, gdpr
Requires at least: 6.1.1
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.1.0
License:  GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Replaces embed blocks with a confirmation to ask for consent before loading third-party resources.

== Description ==
This plugin replaces embed blocks with a confirmation that prevents loading any third party resources until the user has given their consent.

It enhances the built-in embed block and will automatically work with any current embeds. If the plugin is disabled/removed, all embeds will continue to work there will just be no consent confirmation.

Currently, the plugin only works with the Gutenberg `core/embed` block.

The currently supported embed providers are:

 * Amazon
 * Animoto
 * Cloudup
 * Crowdsignal
 * DailyMotion
 * Flickr
 * Imgur
 * Issuu
 * Kickstarter
 * Mixcloud
 * Pinterest
 * Pocket Casts
 * Reddit
 * ReverbNation
 * Screencast
 * Scribd
 * Slideshare
 * SmugMug
 * SoundCloud
 * Speaker Deck
 * Spotify
 * TED
 * TikTok
 * Tumblr
 * Twitter
 * VideoPress
 * Vimeo
 * Wolfram
 * WordPress.tv
 * YouTube

== Frequently Asked Questions ==
= How does it work? =
This plugin filters the output of `core/embed` blocks to add a consent message and prevent loading any third-part resources until consent is given.

= What happens to embeds if I disable/remove this plugin? =
They will continue to work as normal there will just be no consent message shown.

= Can users opt-in to always loading a provider? =
Yes, there is a setting to allow users to opt-in to always loading a provider by showing a checkbox below the consent button.

= Can users opt-out again from always loading a provider? =
Yes, add the shortcode `[embed_consent_opt_out]` to your privacy policy which will allow opting out.

Alternatively, disabling the setting which allows users to opt-in to always loading will disable always loading.

= Does it work with caching? =
Yes, it has been tested with WP Super Cache and there should be no issues with other caching plugins.

When changing settings in the admin area, you may need to clear the cache for the changes to take effect.

== Installation ==

Wordpress.org installation:

1. In your Wordpress dashboard, go to Plugins -> Add New Plugin
2. Search for "Embed Consent"
3. Click "Install now"
4. Go to Plugins -> Installed plugins and click "activate" to enable the plugin.

Manual installation:

1. Upload plugin files to the /wp-content/plugins/embed-consent directory.
2. In your Wordpress dashboard, go to Plugins -> Installed plugins and click "activate" to enable the plugin.

To allow users to opt-in to always loading a provider:

1. Go to Settings -> Embed Consent and enable the "Show always load opt-in" setting.
2. Add the shortcode `[embed_consent_opt_out]` to your privacy policy to allow users to opt-out again.

== Screenshots ==
1. Asking user for consent
2. Consent given and content loaded
2. Asking user for consent with always load opt-in shown
4. Dark theme

== Changelog ==
= 1.1.0 =
* Add a filter for the consent template `embed_consent_consent_template` which allows replacing the embed template.
  \- Thanks to @tkcknost
= 1.0.1 =
* Fix missing light / dark theme example images
= 1.0.0 =
* Initial release

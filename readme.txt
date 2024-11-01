=== WP Media Player ===
Contributors: themestones,sohan5005
Donate link: http://themestones.net/
Tags: media, multimedia, player, music, video, video player, audio player, playlist, repeat, shuffle, music player, responsive player
Requires at least: 3.6.0
Tested up to: 5.0
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A media player plugin for WordPress. Extends and makes the default media player smarter.

== Description ==

This is a lightweight plugin, basically an **addon** for the existing *media player* of WordPress. The default media player is not so responsive in every theme. Also looks a little bit dull and old fashioned.

We are introducing this addon for default *media player* to make it look beautiful and add some smart functionality. Below is a full list of features you get from this plugin.

*	Works with any theme you use.
*	Fully responsive.
*	Styles the video & audio players / playlists in a better way
*	Loop button for audio player
*	Album art added to audio player
*	Track title, album & artist name added to audio player
*	Shuffle & repeat button for audio playlist
*	Fallback album art
*	2 colors available for each player or playlist *(select skin directly from post editor)*
*	Enhanced video subtitle visiblity

### More features are coming soon!

== Installation ==

Installation is same as any other plugins :)

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress **Plugins** screen directly.
2. Activate the plugin through the **Plugins** screen in WordPress
3. Click the **add media** button when editing a post to insert audio / video or create a playlist


== Frequently Asked Questions ==

= Do I need to configure the player? =

No, it doesn't need any configuration. Just install & activate and the plugin will start working.

= What are the shortcodes that I need to create a player or playlist? =

The default WordPress shortcodes (`[audio]`, `[video]` & `[playlist]`) are what you need to use. You don't even have to write them. Just use the add media button when editing a post.

= How do I change color of the player? =

After inserting a player or playlist, while still editing the post, click on the player or playlist in visual editing mode and you'll see the edit button. You can select color from there.

If you prefer text editing mode, in your `[audio]`, `[video]` & `[playlist]` shortcode, just add attribute `color` with a value of `dark` or `sunset`.

E.g.

`[audio mp3="http://example.com/my/cool.mp3" color="sunset"]`.

Default color is `dark`.

== Screenshots ==

1. Single Audio Player (Dark Skin)
2. Single Audio Player (Sunset Skin)
3. Audio Playlist (Sunset Skin)
4. Audio Playlist compact (Dark Skin)
5. Video Player (Playing with subtitles)
6. Video Player (Buffering)

== Changelog ==

= 1.0 =
* Initial release

= 1.0.1 =
* Fixed a PHP error
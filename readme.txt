=== Smart Salary — ChatGPT Summary ===
Contributors: agungsukariman
Tags: chatgpt, summary, cta, blog
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 1.0.0
License: GPLv2 or later

Adds a "Rangkum dengan ChatGPT" banner to single blog posts. Clicking it opens
ChatGPT pre-loaded with a summarize prompt for the current article.

== Description ==

Replaces the old "Too long to read? Let ChatGPT summarize this article" box with
a branded gradient banner (Variant 2). Behavior is unchanged: the button opens
ChatGPT at chatgpt.com/?q=... with a ready-made prompt that includes the current
post's title and URL.

Filters for customization (no code edit needed):

* `ssg_chatgpt_position` — return 'top' (default) or 'bottom' to move the banner.
* `ssg_chatgpt_prompt`   — change the prompt text sent to ChatGPT.

Example (in your theme or a snippet plugin):

    add_filter( 'ssg_chatgpt_position', fn() => 'bottom' );

== Installation ==

1. Upload the `ssg-chatgpt-summary` folder to `/wp-content/plugins/`.
2. Activate it in Plugins.
3. Deactivate / remove the OLD summarize box so it does not show twice.

== Changelog ==

= 1.0.0 =
* Initial release. Variant 2 gradient banner, top-of-post injection.

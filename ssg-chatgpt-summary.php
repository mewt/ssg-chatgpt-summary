<?php
/**
 * Plugin Name:       Smart Salary — ChatGPT Summary
 * Plugin URI:        https://blog.smartsalarypro.com/
 * Description:        Adds a "Rangkum dengan ChatGPT" banner to single blog posts. Clicking it opens ChatGPT pre-loaded with a summarize prompt for the current article.
 * Version:           1.0.0
 * Author:            Agung Sukariman — Smart Salary
 * Author URI:        https://smartsalarypro.com/
 * License:           GPL-2.0-or-later
 * Text Domain:       ssg-chatgpt-summary
 *
 * Replaces the old "Too long to read? Let ChatGPT summarize..." box.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Where the banner shows: 'top' (default) or 'bottom' of the article.
 */
function ssg_chatgpt_position() {
	return apply_filters( 'ssg_chatgpt_position', 'top' );
}

/**
 * The prompt ChatGPT receives, pre-filled (the "safe prompt injection").
 */
function ssg_chatgpt_prompt( $title, $url ) {
	$prompt = sprintf(
		'Tolong rangkum artikel Smart Salary berikut dalam 5 poin singkat berbahasa Indonesia, lalu beri satu kalimat kesimpulan. Judul: %s. Artikel: %s',
		$title,
		$url
	);
	return apply_filters( 'ssg_chatgpt_prompt', $prompt, $title, $url );
}

/**
 * Build the banner HTML for the current post.
 */
function ssg_chatgpt_banner() {
	$url   = get_permalink();
	$title = get_the_title();
	$href  = 'https://chatgpt.com/?q=' . rawurlencode( ssg_chatgpt_prompt( $title, $url ) );

	// CSS printed once per page.
	static $css_done = false;
	$css = '';
	if ( ! $css_done ) {
		$css_done = true;
		$css = '<style id="ssg-banner-css">
.ssg-banner{position:relative;border-radius:18px;padding:26px;margin:28px 0;color:#fff;overflow:hidden;
 background:linear-gradient(120deg,#008a4a 0%,#00a85a 45%,#0fb36b 70%,#eaaa07 150%);
 display:flex;align-items:center;gap:18px;box-shadow:0 18px 40px -18px rgba(0,120,70,.55);
 font-family:"Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
.ssg-banner::after{content:"";position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.12)}
.ssg-banner__t{flex:1;min-width:0;position:relative;z-index:1}
.ssg-banner__k{font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.92}
.ssg-banner__h{font-size:21px;font-weight:800;margin:4px 0 2px;letter-spacing:-.01em;line-height:1.2;color:#fff}
.ssg-banner__s{font-size:14px;opacity:.92;margin:0}
.ssg-banner__btn{position:relative;z-index:1;flex:none;color:#055c35;background:#fff;border-radius:12px;
 padding:13px 20px;font-weight:700;font-size:15px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;
 box-shadow:0 10px 24px -8px rgba(0,0,0,.3);transition:transform .12s ease,filter .12s ease}
.ssg-banner__btn:hover{filter:brightness(1.03)}
.ssg-banner__btn:active{transform:translateY(1px)}
.ssg-banner__btn svg{width:17px;height:17px}
@media(max-width:540px){.ssg-banner{flex-wrap:wrap}.ssg-banner__btn{width:100%;justify-content:center;order:3;margin-top:6px}}
@media(prefers-reduced-motion:reduce){.ssg-banner__btn{transition:none}}
</style>';
	}

	$svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 4.6L18.5 9l-4.6 1.9L12 15.5 10.1 10.9 5.5 9l4.6-1.4L12 3z"/></svg>';

	return $css . '
<div class="ssg-banner">
  <div class="ssg-banner__t">
    <div class="ssg-banner__k">&#9889; Ringkasan Instan</div>
    <p class="ssg-banner__h">Tidak sempat baca semuanya?</p>
    <p class="ssg-banner__s">Biarkan ChatGPT merangkum artikel ini dalam hitungan detik.</p>
  </div>
  <a class="ssg-banner__btn" href="' . esc_url( $href ) . '" target="_blank" rel="noopener nofollow">' . $svg . 'Rangkum Sekarang</a>
</div>';
}

/**
 * Inject the banner into single-post content.
 */
add_filter( 'the_content', function ( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$banner = ssg_chatgpt_banner();
	return ( 'bottom' === ssg_chatgpt_position() )
		? $content . $banner
		: $banner . $content;
}, 20 );

<?php
/**
 * WIDGET 2B — "Book a Demo" that UNFOLDS an inline Fluent Forms lead form.
 *
 * The visitor fills it in without leaving the blog; Fluent Forms saves the
 * lead in WP (Fluent Forms > Entries) and submits by AJAX.
 *
 * Needs server-side rendering (do_shortcode), so this is PHP, not raw HTML.
 * Call ssg_demo_form_box() from your existing block/hook injector, OR paste
 * the two functions into a Code Snippets PHP snippet.
 *
 * SETUP (once):
 *   1. Fluent Forms > New Form. Add fields matching /hubungi-kami/:
 *        - Name        -> label "Nama Lengkap"     (required)
 *        - Phone/Text  -> label "WhatsApp"          (required, numeric)
 *        - Email       -> label "Email"             (required)
 *        - Text        -> label "Nama Perusahaan"
 *        - Dropdown    -> label "Jabatan"           (your real options)
 *        - Dropdown    -> label "Ukuran Perusahaan" (your real options)
 *      Entries are saved automatically (Fluent Forms > Entries).
 *   2. Copy the form ID (Fluent Forms shows [fluentform id="N"]).
 *   3. Set SSG_DEMO_FORM_ID below to that number.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! defined( 'SSG_DEMO_FORM_ID' ) ) {
	define( 'SSG_DEMO_FORM_ID', 1 ); // <-- change to your Fluent Form ID
}

function ssg_demo_form_box() {
	$lang = strtolower( substr( get_bloginfo( 'language' ), 0, 2 ) ); // page language

	$t = array(
		'h'   => 'Jadwalkan demo gratis',
		'sub' => 'Isi langsung di sini, tim kami menghubungi Anda tanpa pindah halaman.',
		'btn' => 'Jadwalkan Demo',
		'close' => 'Tutup',
	);
	if ( 'en' === $lang ) {
		$t = array( 'h' => 'Book a free demo', 'sub' => 'Fill it in right here, our team reaches out without leaving the page.', 'btn' => 'Book a Demo', 'close' => 'Close' );
	}

	static $css_done = false;
	$css = '';
	if ( ! $css_done ) {
		$css_done = true;
		$css = '<style id="ssg-demoform-css">
.ssg-demo{margin:22px 0;padding:20px 2px;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;font-family:"Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
.ssg-demo__bar{display:flex;align-items:center;gap:16px}
.ssg-demo__icon{flex:none;color:#00a85a;line-height:0}.ssg-demo__icon svg{width:26px;height:26px}
.ssg-demo__t{flex:1;min-width:0}
.ssg-demo__h{margin:0;color:#1f2937;font-size:16px;font-weight:700;line-height:1.3}
.ssg-demo__s{margin:3px 0 0;color:#6b7280;font-size:13.5px;line-height:1.5}
.ssg-demo a.ssg-demo__btn,.ssg-demo button.ssg-demo__btn{flex:none;display:inline-flex;align-items:center;gap:8px;color:#fff !important;background:#00a85a !important;background-image:none !important;border:none !important;border-radius:10px;padding:11px 20px;font-size:14px;font-weight:700;font-family:inherit;cursor:pointer;white-space:nowrap;box-shadow:0 6px 16px -6px rgba(0,168,90,.6);transition:transform .15s ease,filter .15s ease}
.ssg-demo button.ssg-demo__btn:hover{transform:translateY(-1px);filter:brightness(1.05)}
.ssg-demo__btn .chev{transition:transform .3s ease}
.ssg-demo[data-open="true"] .ssg-demo__btn .chev{transform:rotate(180deg)}
.ssg-demo__panel{display:grid;grid-template-rows:0fr;transition:grid-template-rows .34s ease}
.ssg-demo[data-open="true"] .ssg-demo__panel{grid-template-rows:1fr}
.ssg-demo__panel>div{overflow:hidden}
.ssg-demo__formwrap{margin-top:16px;background:#e9f9f0;border:1px solid rgba(0,168,90,.22);border-radius:12px;padding:18px}
@media(max-width:540px){.ssg-demo__bar{flex-wrap:wrap}.ssg-demo button.ssg-demo__btn{width:100%;justify-content:center;order:3;margin-top:4px}}
@media(prefers-reduced-motion:reduce){.ssg-demo__panel,.ssg-demo button.ssg-demo__btn,.ssg-demo__btn .chev{transition:none}}
</style>';
	}

	$svg_cal  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>';
	$svg_chev = '<svg class="chev" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>';

	$form = do_shortcode( '[fluentform id="' . intval( SSG_DEMO_FORM_ID ) . '"]' );

	$open_label  = esc_js( $t['btn'] );
	$close_label = esc_js( $t['close'] );
	$page_lang   = esc_js( $lang );

	return $css . '
<div class="ssg-demo" data-open="false">
  <div class="ssg-demo__bar">
    <span class="ssg-demo__icon" aria-hidden="true">' . $svg_cal . '</span>
    <div class="ssg-demo__t">
      <h3 class="ssg-demo__h">' . esc_html( $t['h'] ) . '</h3>
      <p class="ssg-demo__s">' . esc_html( $t['sub'] ) . '</p>
    </div>
    <button class="ssg-demo__btn" type="button" aria-expanded="false"><span class="lbl">' . esc_html( $t['btn'] ) . '</span>' . $svg_chev . '</button>
  </div>
  <div class="ssg-demo__panel"><div><div class="ssg-demo__formwrap">' . $form . '</div></div></div>
</div>
<script>
(function(){
  var box = document.currentScript.previousElementSibling;
  if(!box || !box.classList || !box.classList.contains("ssg-demo")) return;
  var btn = box.querySelector(".ssg-demo__btn"), lbl = btn.querySelector(".lbl");
  var pageLang = "' . $page_lang . '";
  btn.addEventListener("click", function(){
    var open = box.getAttribute("data-open") === "true";
    box.setAttribute("data-open", String(!open));
    btn.setAttribute("aria-expanded", String(!open));
    lbl.textContent = open ? "' . $open_label . '" : "' . $close_label . '";
    if(!open){
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({event:"book_demo_open_from_blog", cta_placement:"article_demo_form", page_language:pageLang});
    }
  });
  // Fluent Forms fires this jQuery event on successful save
  if(window.jQuery){
    window.jQuery(document).on("fluentform_submission_success", function(){
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({event:"book_demo_submit_from_blog", cta_placement:"article_demo_form", page_language:pageLang});
    });
  }
})();
</script>';
}

/*
 * To show it, call ssg_demo_form_box() from your block/hook injector, e.g. append
 * its return value where you currently inject the link-out widget. Example as a
 * standalone the_content injector (top of post):
 *
 * add_filter( 'the_content', function ( $c ) {
 *     if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) return $c;
 *     return ssg_demo_form_box() . $c;
 * }, 20 );
 */

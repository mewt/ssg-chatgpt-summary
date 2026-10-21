# Smart Salary — ChatGPT Summary Banner

The "Rangkum dengan ChatGPT" banner shown at the **top of single blog posts** on
[blog.smartsalarypro.com](https://blog.smartsalarypro.com). Clicking it opens ChatGPT
pre-loaded with a summarize prompt for the current article (opens + reads the URL via
`?hints=search`).

This is the **Variant 2** redesign (green gradient banner) of the original grey box.

## File

- **`summarize-top-widget.html`** — the whole widget: scoped `<style>`, markup, and
  `<script>`. Paste it into whatever **hook** outputs the block (e.g. a `the_content`
  filter, `wp_footer`, or a Code Snippets PHP snippet), or into a Custom HTML block.

## What it does

- **4 languages** — auto-detects `<html lang>` and switches copy for `en`, `id`, `th`, `zh`.
- **Analytics** — pushes `ai_summarize_click` to `dataLayer` (engine, placement, language, URL) on click.
- **Smart source** — reads the article title from `og:title` → `<h1>` → `document.title`,
  strips the " | Smart Salary" site-name suffix, and uses the canonical URL (minus `#`/`?`).

## Rules when editing

- **No backslashes anywhere.** WordPress strips `\` inside Custom HTML blocks, which breaks
  any regex or escape. The script is deliberately regex-free (uses `charCodeAt` / `split`
  / `String.fromCharCode`). Keep it that way.
- Keep the element IDs (`gpt-box-title`, `gpt-box-sub`, `chatgpt-link-btn`, `gpt-box-kicker`) —
  the script and any GTM triggers rely on them.

## Changelog

- **v2** — Variant 2 gradient banner; moved hover to CSS; added translated kicker line.
  Behavior, languages, analytics, and title/URL logic unchanged from the original.

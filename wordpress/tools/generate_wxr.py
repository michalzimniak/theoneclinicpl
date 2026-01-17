#!/usr/bin/env python3

import datetime as _dt
import html as _html
import os
import re
from pathlib import Path


def _read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8", errors="replace")


def _extract_first(pattern: str, text: str, flags: int = 0) -> str | None:
    m = re.search(pattern, text, flags)
    if not m:
        return None
    return m.group(1)


def _extract_section_by_class(html_text: str, class_name: str) -> str | None:
    # Very small, pragmatic extractor for this project structure.
    pattern = rf"(<section\s+[^>]*class=\"[^\"]*\b{re.escape(class_name)}\b[^\"]*\"[^>]*>.*?</section>)"
    return _extract_first(pattern, html_text, re.IGNORECASE | re.DOTALL)


def _strip_header_footer_scripts(body_html: str) -> str:
    body_html = re.sub(r"<header\b.*?</header>", "", body_html, flags=re.IGNORECASE | re.DOTALL)
    body_html = re.sub(r"<footer\b.*?</footer>", "", body_html, flags=re.IGNORECASE | re.DOTALL)
    body_html = re.sub(r"<script\b.*?</script>", "", body_html, flags=re.IGNORECASE | re.DOTALL)
    return body_html.strip()


def _fix_paths(content: str) -> str:
    # Theme assets are intentionally referenced by absolute path.
    theme_base = "/wp-content/themes/theone-clinic"

    # Media
    content = content.replace("../media/", f"{theme_base}/media/")
    content = content.replace('src="media/', f'src="{theme_base}/media/')
    content = content.replace("'media/", f"'{theme_base}/media/")

    # Home link
    content = content.replace('href="../index.html"', 'href="/"')
    content = content.replace('href="index.html"', 'href="/"')

    # Page links inside content
    content = re.sub(r'href="([a-z0-9_-]+)\.html"', lambda m: f'href="/{m.group(1)}/"', content, flags=re.IGNORECASE)
    content = re.sub(r'href="pages/([a-z0-9_-]+)\.html"', lambda m: f'href="/{m.group(1)}/"', content, flags=re.IGNORECASE)

    return content


def _replace_editable_globals_with_shortcodes(content: str) -> str:
    # Phone
    content = content.replace('href="tel:+48790227627"', 'href="[theone_phone_href]"')
    content = content.replace('>+48 790 227 627<', '>[theone_phone_display]<')

    # Address
    content = content.replace(
        'href="https://www.google.com/maps/search/?api=1&query=Marsowa+7+Osielsko"',
        'href="[theone_maps_url]"',
    )
    content = content.replace('>Marsowa 7, Osielsko<', '>[theone_address_text]<')

    # Booksy
    content = content.replace('href="https://theonebeautyclinic.booksy.com/a/"', 'href="[theone_booksy_url]"')

    # Promotions button (use shortcode so the label/icon can be changed centrally if needed)
    content = re.sub(
        r'<button\s+id=\"openPromotionsBtn\"[^>]*>.*?</button>',
        r'[theone_promotions_button]',
        content,
        flags=re.IGNORECASE | re.DOTALL,
    )

    return content


def _wp_cdata(text: str) -> str:
    # Ensure we don't break CDATA.
    return text.replace("]]>", "]]&gt;")


def _guess_title_and_slug(file_path: Path) -> tuple[str, str]:
    slug = file_path.stem
    if slug == "index":
        return "Strona główna", "home"

    pretty = {
        "badanie": "Badanie Skóry",
        "clearskin": "Clear Skin - Trądzik",
        "depilacja": "Depilacja Laserowa",
        "dermapen": "Dermapen 4.0",
        "dvl": "DVL - Zmiany Naczyniowe",
        "efekty": "Efekty przed i po",
        "endermologia": "Endermologia",
        "faq": "FAQ",
        "image": "Image Skincare",
        "ipixel": "iPixel - Laser Frakcyjny",
        "linder": "Linder Health",
        "onas": "O nas",
    }
    return pretty.get(slug, slug.replace("-", " ").title()), slug


def _extract_content_for_page(file_path: Path) -> str:
    html_text = _read_text(file_path)

    if file_path.name == "index.html":
        hero = _extract_section_by_class(html_text, "hero-section")
        # Promotions modal is rendered dynamically in the theme footer from WP-managed promotions.
        content = "\n".join([part for part in [hero] if part])
        if content.strip():
            return _replace_editable_globals_with_shortcodes(_fix_paths(content))

    service = _extract_section_by_class(html_text, "service-page")
    if service and service.strip():
                return _replace_editable_globals_with_shortcodes(_fix_paths(service))

    body = _extract_first(r"<body\b[^>]*>(.*)</body>", html_text, re.IGNORECASE | re.DOTALL)
    if body:
        return _replace_editable_globals_with_shortcodes(_fix_paths(_strip_header_footer_scripts(body)))

    # Fallback to full file
    return _replace_editable_globals_with_shortcodes(_fix_paths(_strip_header_footer_scripts(html_text)))


def generate_wxr(site_url: str, pages: list[tuple[str, str, str]]) -> str:
    now = _dt.datetime.now(_dt.timezone.utc)
    pub_date = now.strftime("%a, %d %b %Y %H:%M:%S +0000")
    post_date = now.astimezone(_dt.timezone(_dt.timedelta(0))).strftime("%Y-%m-%d %H:%M:%S")
    post_date_gmt = now.strftime("%Y-%m-%d %H:%M:%S")

    header = f"""<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rss version=\"2.0\"
    xmlns:excerpt=\"http://wordpress.org/export/1.2/excerpt/\"
    xmlns:content=\"http://purl.org/rss/1.0/modules/content/\"
    xmlns:wfw=\"http://wellformedweb.org/CommentAPI/\"
    xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
    xmlns:wp=\"http://wordpress.org/export/1.2/\">
<channel>
    <title>TheOne Clinic</title>
    <link>{_html.escape(site_url)}</link>
    <description></description>
    <pubDate>{pub_date}</pubDate>
    <language>pl</language>
    <wp:wxr_version>1.2</wp:wxr_version>
    <wp:base_site_url>{_html.escape(site_url)}</wp:base_site_url>
    <wp:base_blog_url>{_html.escape(site_url)}</wp:base_blog_url>

    <wp:author>
        <wp:author_id>1</wp:author_id>
        <wp:author_login><![CDATA[admin]]></wp:author_login>
        <wp:author_email><![CDATA[admin@example.com]]></wp:author_email>
        <wp:author_display_name><![CDATA[admin]]></wp:author_display_name>
        <wp:author_first_name><![CDATA[]]></wp:author_first_name>
        <wp:author_last_name><![CDATA[]]></wp:author_last_name>
    </wp:author>
"""

    items = []
    post_id = 100
    for title, slug, content_html in pages:
        post_id += 1
        items.append(
            f"""
    <item>
        <title><![CDATA[{title}]]></title>
        <link>{_html.escape(site_url.rstrip('/'))}/{_html.escape(slug)}/</link>
        <pubDate>{pub_date}</pubDate>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <guid isPermaLink=\"false\">{_html.escape(site_url.rstrip('/'))}/?page_id={post_id}</guid>
        <description></description>
        <content:encoded><![CDATA[{_wp_cdata(content_html)}]]></content:encoded>
        <excerpt:encoded><![CDATA[]]></excerpt:encoded>
        <wp:post_id>{post_id}</wp:post_id>
        <wp:post_date><![CDATA[{post_date}]]></wp:post_date>
        <wp:post_date_gmt><![CDATA[{post_date_gmt}]]></wp:post_date_gmt>
        <wp:comment_status><![CDATA[closed]]></wp:comment_status>
        <wp:ping_status><![CDATA[closed]]></wp:ping_status>
        <wp:post_name><![CDATA[{slug}]]></wp:post_name>
        <wp:status><![CDATA[publish]]></wp:status>
        <wp:post_parent>0</wp:post_parent>
        <wp:menu_order>0</wp:menu_order>
        <wp:post_type><![CDATA[page]]></wp:post_type>
        <wp:post_password><![CDATA[]]></wp:post_password>
        <wp:is_sticky>0</wp:is_sticky>
    </item>
"""
        )

    footer = """
</channel>
</rss>
"""

    return header + "\n".join(items) + footer


def main() -> int:
    # This script lives in: <project>/wordpress/tools/generate_wxr.py
    # Find the project root robustly by walking upwards.
    script_path = Path(__file__).resolve()
    project_root: Path | None = None
    for parent in [script_path.parent] + list(script_path.parents):
        if (parent / "index.html").exists() and (parent / "pages").is_dir():
            project_root = parent
            break

    if project_root is None:
        raise FileNotFoundError("Could not locate project root containing index.html and pages/ directory")

    index_path = project_root / "index.html"
    pages_dir = project_root / "pages"

    html_files = [index_path] + sorted(pages_dir.glob("*.html"))

    pages: list[tuple[str, str, str]] = []
    for html_file in html_files:
        title, slug = _guess_title_and_slug(html_file)
        content_html = _extract_content_for_page(html_file)
        pages.append((title, slug, content_html))

    out_path = project_root / "wordpress" / "import" / "theone-clinic-pages.xml"
    site_url = "http://example.local"  # user will import into their real site; base URLs are not critical
    out_path.write_text(generate_wxr(site_url=site_url, pages=pages), encoding="utf-8")

    print(f"Wrote: {out_path}")
    print(f"Pages: {len(pages)}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

<?php

declare(strict_types=1);

function theone_clinic_setup(): void
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);

	register_nav_menus([
		'primary' => __('Menu główne', 'theone-clinic'),
	]);
}
add_action('after_setup_theme', 'theone_clinic_setup');

function theone_clinic_enqueue_assets(): void
{
	$theme_uri = get_template_directory_uri();
	$theme_dir = get_template_directory();

	$css_path = $theme_dir . '/css/style.css';
	$js_path = $theme_dir . '/js/main.js';
	$css_ver = file_exists($css_path) ? (string) filemtime($css_path) : '1.0.0';
	$js_ver = file_exists($js_path) ? (string) filemtime($js_path) : '1.0.0';

	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', [], '5.3.0');
	wp_enqueue_style('animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', [], '4.1.1');
	wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
	wp_enqueue_style('theone-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap', [], null);

	// Oryginalny CSS z projektu (z zachowaniem ścieżek względnych do media/).
	wp_enqueue_style('theone-style', $theme_uri . '/css/style.css', ['bootstrap', 'animate', 'fontawesome', 'theone-google-fonts'], $css_ver);

	wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', [], '5.3.0', true);
	wp_enqueue_script('theone-main', $theme_uri . '/js/main.js', [], $js_ver, true);
}
add_action('wp_enqueue_scripts', 'theone_clinic_enqueue_assets');

function theone_clinic_get_meta_description(): string
{
	if (is_singular()) {
		$excerpt = get_the_excerpt();
		$excerpt = wp_strip_all_tags($excerpt);
		$excerpt = trim($excerpt);
		if ($excerpt !== '') {
			return $excerpt;
		}
	}

	$desc = get_bloginfo('description');
	return is_string($desc) ? $desc : '';
}

function theone_clinic_is_page_slug(string $slug): bool
{
	return is_page($slug) || (is_singular('page') && get_post_field('post_name', get_queried_object_id()) === $slug);
}

function theone_clinic_page_url(string $slug, string $fallback = '#'): string
{
	$page = get_page_by_path($slug);
	if ($page instanceof WP_Post) {
		return get_permalink($page);
	}
	return $fallback;
}

function theone_clinic_defaults(): array
{
	return [
		'theone_phone_display' => '+48 790 227 627',
		'theone_phone_href' => 'tel:+48790227627',
		'theone_address_text' => 'Marsowa 7, Osielsko',
		'theone_maps_url' => 'https://www.google.com/maps/search/?api=1&query=Marsowa+7+Osielsko',
		'theone_booksy_url' => 'https://theonebeautyclinic.booksy.com/a/',
		'theone_instagram_url' => 'https://www.instagram.com/theone_beauty_clinic?igsh=YXlwZ2hiZDVvdGF3&utm_source=qr',
		'theone_facebook_url' => 'https://www.facebook.com/share/17k7A13fA8/?mibextid=wwXIfr',
	];
}

function theone_clinic_get_setting(string $key): string
{
	$defaults = theone_clinic_defaults();
	$default = isset($defaults[$key]) ? (string) $defaults[$key] : '';
	$value = get_theme_mod($key, $default);
	return is_string($value) ? $value : (string) $default;
}

function theone_clinic_customize_register(WP_Customize_Manager $wp_customize): void
{
	$wp_customize->add_section(
		'theone_clinic_contact',
		[
			'title' => __('TheOne Clinic: Kontakt i linki', 'theone-clinic'),
			'priority' => 30,
		]
	);

	$add_text = static function (string $key, string $label) use ($wp_customize): void {
		$wp_customize->add_setting(
			$key,
			[
				'type' => 'theme_mod',
				'default' => theone_clinic_get_setting($key),
				'sanitize_callback' => 'sanitize_text_field',
			]
		);

		$wp_customize->add_control(
			$key,
			[
				'label' => $label,
				'section' => 'theone_clinic_contact',
				'type' => 'text',
			]
		);
	};

	$add_url = static function (string $key, string $label) use ($wp_customize): void {
		$wp_customize->add_setting(
			$key,
			[
				'type' => 'theme_mod',
				'default' => theone_clinic_get_setting($key),
				'sanitize_callback' => 'esc_url_raw',
			]
		);

		$wp_customize->add_control(
			$key,
			[
				'label' => $label,
				'section' => 'theone_clinic_contact',
				'type' => 'url',
			]
		);
	};

	$add_text('theone_phone_display', __('Telefon (tekst)', 'theone-clinic'));
	$add_text('theone_phone_href', __('Telefon (link tel:...)', 'theone-clinic'));
	$add_text('theone_address_text', __('Adres (tekst)', 'theone-clinic'));
	$add_url('theone_maps_url', __('Adres (link do map)', 'theone-clinic'));
	$add_url('theone_booksy_url', __('Booksy URL', 'theone-clinic'));
	$add_url('theone_instagram_url', __('Instagram URL', 'theone-clinic'));
	$add_url('theone_facebook_url', __('Facebook URL', 'theone-clinic'));
}
add_action('customize_register', 'theone_clinic_customize_register');

function theone_clinic_register_shortcodes(): void
{
	add_shortcode('theone_phone_display', static fn () => esc_html(theone_clinic_get_setting('theone_phone_display')));
	add_shortcode('theone_phone_href', static fn () => esc_attr(theone_clinic_get_setting('theone_phone_href')));
	add_shortcode('theone_address_text', static fn () => esc_html(theone_clinic_get_setting('theone_address_text')));
	add_shortcode('theone_maps_url', static fn () => esc_url(theone_clinic_get_setting('theone_maps_url')));
	add_shortcode('theone_booksy_url', static fn () => esc_url(theone_clinic_get_setting('theone_booksy_url')));
	add_shortcode('theone_promotions_button', 'theone_clinic_shortcode_promotions_button');
}
add_action('init', 'theone_clinic_register_shortcodes');

function theone_clinic_expand_link_placeholders(string $value): string
{
	if ($value === '') {
		return $value;
	}

	// WordPress encodes '[' and ']' in URL fields (e.g. menus, Gutenberg URLs) which turns placeholders into %5B...%5D.
	// Decode only these brackets (do not rawurldecode the whole string).
	$decoded = preg_replace(['/%5B/i', '/%5D/i'], ['[', ']'], $value);
	$decoded = is_string($decoded) ? $decoded : $value;

	$replacements = [
		'[theone_phone_href]' => theone_clinic_get_setting('theone_phone_href'),
		'[theone_maps_url]' => theone_clinic_get_setting('theone_maps_url'),
		'[theone_booksy_url]' => theone_clinic_get_setting('theone_booksy_url'),
	];

	$expanded = strtr($decoded, $replacements);

	// Some editors store custom URLs as relative paths, e.g. '/[theone_maps_url]'. After expansion
	// this becomes '/https://...' which the browser resolves to 'https://site.tld/https://...'.
	$expanded = preg_replace('#^/(https?://)#i', '$1', $expanded);
	$expanded = preg_replace('#^/(tel:)#i', '$1', $expanded);
	$expanded = is_string($expanded) ? $expanded : (string) strtr($decoded, $replacements);

	// If the site URL was already prefixed (e.g. 'https://site.tld/https://...'), unwrap it.
	$expanded = preg_replace('#^https?://[^/]+/(https?://.+)$#i', '$1', $expanded);
	$expanded = preg_replace('#^https?://[^/]+/(tel:.+)$#i', '$1', $expanded);
	$expanded = is_string($expanded) ? $expanded : (string) $expanded;

	return $expanded;
}

function theone_clinic_expand_placeholders_in_html(string $html): string
{
	if ($html === '') {
		return $html;
	}

	// Decode only encoded brackets used by placeholders.
	$decoded = preg_replace(['/%5B/i', '/%5D/i'], ['[', ']'], $html);
	$decoded = is_string($decoded) ? $decoded : $html;

	$replacements = [
		'[theone_phone_href]' => theone_clinic_get_setting('theone_phone_href'),
		'[theone_maps_url]' => theone_clinic_get_setting('theone_maps_url'),
		'[theone_booksy_url]' => theone_clinic_get_setting('theone_booksy_url'),
	];

	$expanded = strtr($decoded, $replacements);

	// Normalize broken absolute URLs stored inside HTML attributes, e.g. href="/https://..."
	// which the browser resolves to https://site.tld/https://...
	$expanded = preg_replace('#(href\s*=\s*["\"])\s*/(https?://)#i', '$1$2', $expanded);
	$expanded = preg_replace('#(href\s*=\s*["\"])\s*/(tel:)#i', '$1$2', $expanded);
	$expanded = preg_replace('#(href\s*=\s*["\"])https?://[^/]+/(https?://[^"\"]+)#i', '$1$2', $expanded);
	$expanded = preg_replace('#(href\s*=\s*["\"])https?://[^/]+/(tel:[^"\"]+)#i', '$1$2', $expanded);
	$expanded = is_string($expanded) ? $expanded : (string) strtr($decoded, $replacements);

	// WordPress wpautop() can wrap standalone HTML comments in empty paragraphs, e.g.
	// <p><!-- Example Gallery Item --></p>. This adds unwanted whitespace in layouts.
	$expanded = preg_replace('#<p>\s*(?:<!--.*?-->\s*)+</p>#s', '', $expanded);

	// wpautop() can also generate empty paragraphs (often from blank lines around raw HTML)
	// like <p><br></p>, <p>&nbsp;</p> or <p>\s+</p>. Strip those to prevent large gaps.
	$expanded = preg_replace('#<p>\s*(?:&nbsp;|\xC2\xA0)?\s*</p>#i', '', $expanded);
	$expanded = preg_replace('#<p>\s*(?:<br\s*/?>\s*)+</p>#i', '', $expanded);
	$expanded = is_string($expanded) ? $expanded : (string) $expanded;

	return $expanded;
}

function theone_clinic_fix_menu_placeholder_urls($menu_item)
{
	if (is_object($menu_item) && isset($menu_item->url) && is_string($menu_item->url)) {
		$menu_item->url = theone_clinic_expand_link_placeholders($menu_item->url);
	}

	return $menu_item;
}
add_filter('wp_setup_nav_menu_item', 'theone_clinic_fix_menu_placeholder_urls');

function theone_clinic_fix_menu_placeholder_link_atts(array $atts, $item): array
{
	if (isset($atts['href']) && is_string($atts['href'])) {
		$atts['href'] = theone_clinic_expand_link_placeholders($atts['href']);
	}

	return $atts;
}
add_filter('nav_menu_link_attributes', 'theone_clinic_fix_menu_placeholder_link_atts', 10, 2);

function theone_clinic_fix_placeholders_in_content(string $content): string
{
	return theone_clinic_expand_placeholders_in_html($content);
}
// Run after wpautop() (default priority 10) so we can clean up its empty <p> artifacts.
add_filter('the_content', 'theone_clinic_fix_placeholders_in_content', 11);

function theone_clinic_fix_placeholders_in_rendered_block(string $block_content, array $block): string
{
	return theone_clinic_expand_placeholders_in_html($block_content);
}
add_filter('render_block', 'theone_clinic_fix_placeholders_in_rendered_block', 9, 2);

function theone_clinic_register_promotions_cpt(): void
{
	$labels = [
		'name' => __('Promocje', 'theone-clinic'),
		'singular_name' => __('Promocja', 'theone-clinic'),
		'add_new' => __('Dodaj nową', 'theone-clinic'),
		'add_new_item' => __('Dodaj nową promocję', 'theone-clinic'),
		'edit_item' => __('Edytuj promocję', 'theone-clinic'),
		'new_item' => __('Nowa promocja', 'theone-clinic'),
		'view_item' => __('Zobacz promocję', 'theone-clinic'),
		'view_items' => __('Zobacz promocje', 'theone-clinic'),
		'search_items' => __('Szukaj promocji', 'theone-clinic'),
		'not_found' => __('Brak promocji', 'theone-clinic'),
		'not_found_in_trash' => __('Brak promocji w koszu', 'theone-clinic'),
		'all_items' => __('Wszystkie promocje', 'theone-clinic'),
		'menu_name' => __('Promocje', 'theone-clinic'),
	];

	register_post_type(
		'theone_promotion',
		[
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-megaphone',
			'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
			'has_archive' => false,
			'rewrite' => false,
			'show_in_rest' => true,
		]
	);

	register_post_meta(
		'theone_promotion',
		'_theone_promo_start',
		[
			'type' => 'string',
			'single' => true,
			'show_in_rest' => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback' => static fn () => current_user_can('edit_posts'),
		]
	);
	register_post_meta(
		'theone_promotion',
		'_theone_promo_end',
		[
			'type' => 'string',
			'single' => true,
			'show_in_rest' => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback' => static fn () => current_user_can('edit_posts'),
		]
	);
}
add_action('init', 'theone_clinic_register_promotions_cpt');

function theone_clinic_promotions_add_metabox(): void
{
	add_meta_box(
		'theone_promo_dates',
		__('Czas trwania promocji', 'theone-clinic'),
		'theone_clinic_promotions_metabox_html',
		'theone_promotion',
		'side',
		'high'
	);
}
add_action('add_meta_boxes', 'theone_clinic_promotions_add_metabox');

function theone_clinic_promotions_metabox_html(WP_Post $post): void
{
	wp_nonce_field('theone_promo_dates_save', 'theone_promo_dates_nonce');
	$start = get_post_meta($post->ID, '_theone_promo_start', true);
	$end = get_post_meta($post->ID, '_theone_promo_end', true);

	$start = is_string($start) ? $start : '';
	$end = is_string($end) ? $end : '';

	echo '<p><label for="theone_promo_start"><strong>' . esc_html__('Od (opcjonalnie)', 'theone-clinic') . '</strong></label></p>';
	echo '<p><input type="date" id="theone_promo_start" name="theone_promo_start" value="' . esc_attr($start) . '" style="width:100%"></p>';
	echo '<p><label for="theone_promo_end"><strong>' . esc_html__('Do (opcjonalnie)', 'theone-clinic') . '</strong></label></p>';
	echo '<p><input type="date" id="theone_promo_end" name="theone_promo_end" value="' . esc_attr($end) . '" style="width:100%"></p>';
	echo '<p style="font-size:12px; opacity:.8">' . esc_html__('Gdy puste: od razu / bez daty końcowej. Format: RRRR-MM-DD.', 'theone-clinic') . '</p>';
}

function theone_clinic_promotions_save_metabox(int $post_id): void
{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (wp_is_post_revision($post_id)) {
		return;
	}
	if (get_post_type($post_id) !== 'theone_promotion') {
		return;
	}
	if (!isset($_POST['theone_promo_dates_nonce']) || !wp_verify_nonce((string) $_POST['theone_promo_dates_nonce'], 'theone_promo_dates_save')) {
		return;
	}
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	$start_raw = isset($_POST['theone_promo_start']) ? sanitize_text_field((string) $_POST['theone_promo_start']) : '';
	$end_raw = isset($_POST['theone_promo_end']) ? sanitize_text_field((string) $_POST['theone_promo_end']) : '';

	$validate_date = static function (string $value): string {
		if ($value === '') {
			return '';
		}
		// Basic YYYY-MM-DD validation.
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
			return '';
		}
		return $value;
	};

	$start = $validate_date($start_raw);
	$end = $validate_date($end_raw);

	if ($start !== '') {
		update_post_meta($post_id, '_theone_promo_start', $start);
	} else {
		delete_post_meta($post_id, '_theone_promo_start');
	}

	if ($end !== '') {
		update_post_meta($post_id, '_theone_promo_end', $end);
	} else {
		delete_post_meta($post_id, '_theone_promo_end');
	}
}
add_action('save_post', 'theone_clinic_promotions_save_metabox');

function theone_clinic_get_active_promotions(int $limit = 20): array
{
	$today = wp_date('Y-m-d');

	$query = new WP_Query([
		'post_type' => 'theone_promotion',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => [
			'relation' => 'AND',
			[
				'relation' => 'OR',
				[
					'key' => '_theone_promo_start',
					'value' => $today,
					'compare' => '<=',
					'type' => 'DATE',
				],
				[
					'key' => '_theone_promo_start',
					'compare' => 'NOT EXISTS',
				],
			],
			[
				'relation' => 'OR',
				[
					'key' => '_theone_promo_end',
					'value' => $today,
					'compare' => '>=',
					'type' => 'DATE',
				],
				[
					'key' => '_theone_promo_end',
					'compare' => 'NOT EXISTS',
				],
			],
		],
	]);

	return $query->posts;
}

function theone_clinic_render_promotions_modal(): void
{
	if (is_admin()) {
		return;
	}

	$promotions = theone_clinic_get_active_promotions(50);
	$theme_uri = get_template_directory_uri();
	$fallback_img = $theme_uri . '/media/logo.jpg';

	?>
	<div id="promotionsModal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<h2><i class="fas fa-tags"></i> <?php echo esc_html__('Aktualne promocje', 'theone-clinic'); ?></h2>
				<span class="close-modal">&times;</span>
			</div>
			<div class="modal-body" id="promotionsContainer">
				<?php if (!$promotions) : ?>
					<div class="promotion-item">
						<div class="promotion-content">
							<h3 class="promotion-title"><?php echo esc_html__('Brak promocji', 'theone-clinic'); ?></h3>
							<p class="promotion-description"><?php echo esc_html__('Aktualnie nie mamy aktywnych promocji.', 'theone-clinic'); ?></p>
						</div>
					</div>
				<?php else : ?>
					<?php foreach ($promotions as $promo) : ?>
						<?php
							$img = get_the_post_thumbnail_url($promo, 'medium');
							if (!is_string($img) || $img === '') {
								$img = $fallback_img;
							}

							$start = get_post_meta($promo->ID, '_theone_promo_start', true);
							$end = get_post_meta($promo->ID, '_theone_promo_end', true);
							$start = is_string($start) ? $start : '';
							$end = is_string($end) ? $end : '';
							$date_bits = [];
							if ($start !== '') {
								$date_bits[] = sprintf(__('od %s', 'theone-clinic'), $start);
							}
							if ($end !== '') {
								$date_bits[] = sprintf(__('do %s', 'theone-clinic'), $end);
							}
							$date_line = $date_bits ? implode(' — ', $date_bits) : '';
						?>
						<div class="promotion-item">
							<div class="promotion-image">
								<img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title($promo)); ?>" onerror="this.src='<?php echo esc_js($fallback_img); ?>'">
							</div>
							<div class="promotion-content">
								<h3 class="promotion-title"><?php echo esc_html(get_the_title($promo)); ?></h3>
								<div class="promotion-description">
									<?php echo apply_filters('the_content', $promo->post_content); ?>
								</div>
								<?php if ($date_line !== '') : ?>
									<p class="promotion-date"><i class="far fa-calendar"></i> <?php echo esc_html($date_line); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}
add_action('wp_footer', 'theone_clinic_render_promotions_modal');

function theone_clinic_shortcode_promotions_button(): string
{
	return '<button id="openPromotionsBtn" class="btn-secondary"><i class="fas fa-tags"></i> ' . esc_html__('Aktualne promocje', 'theone-clinic') . '</button>';
}

function theone_clinic_promo_get_dates(int $post_id): array
{
	$start = get_post_meta($post_id, '_theone_promo_start', true);
	$end = get_post_meta($post_id, '_theone_promo_end', true);

	return [
		is_string($start) ? $start : '',
		is_string($end) ? $end : '',
	];
}

function theone_clinic_promo_get_status(int $post_id): string
{
	[$start, $end] = theone_clinic_promo_get_dates($post_id);
	$today = wp_date('Y-m-d');

	if ($start !== '' && $start > $today) {
		return 'upcoming';
	}
	if ($end !== '' && $end < $today) {
		return 'ended';
	}
	return 'active';
}

function theone_clinic_promo_status_label(string $status): string
{
	return match ($status) {
		'upcoming' => __('Wkrótce', 'theone-clinic'),
		'ended' => __('Zakończona', 'theone-clinic'),
		default => __('Aktywna', 'theone-clinic'),
	};
}

function theone_clinic_promotions_admin_columns(array $columns): array
{
	// Keep checkbox and title, then add our columns.
	$new = [];
	foreach ($columns as $key => $label) {
		$new[$key] = $label;
		if ($key === 'title') {
			$new['theone_thumb'] = __('Grafika', 'theone-clinic');
			$new['theone_start'] = __('Od', 'theone-clinic');
			$new['theone_end'] = __('Do', 'theone-clinic');
			$new['theone_status'] = __('Status', 'theone-clinic');
		}
	}

	return $new;
}
add_filter('manage_theone_promotion_posts_columns', 'theone_clinic_promotions_admin_columns');

function theone_clinic_promotions_admin_column_content(string $column, int $post_id): void
{
	if ($column === 'theone_thumb') {
		$thumb = get_the_post_thumbnail($post_id, [60, 60], ['style' => 'max-width:60px;height:auto;border-radius:6px;']);
		echo $thumb !== '' ? $thumb : '&mdash;';
		return;
	}

	[$start, $end] = theone_clinic_promo_get_dates($post_id);
	if ($column === 'theone_start') {
		echo esc_html($start !== '' ? $start : '—');
		return;
	}
	if ($column === 'theone_end') {
		echo esc_html($end !== '' ? $end : '—');
		return;
	}
	if ($column === 'theone_status') {
		$status = theone_clinic_promo_get_status($post_id);
		echo esc_html(theone_clinic_promo_status_label($status));
		return;
	}
}
add_action('manage_theone_promotion_posts_custom_column', 'theone_clinic_promotions_admin_column_content', 10, 2);

function theone_clinic_promotions_admin_sortable_columns(array $columns): array
{
	$columns['theone_start'] = 'theone_start';
	$columns['theone_end'] = 'theone_end';
	return $columns;
}
add_filter('manage_edit-theone_promotion_sortable_columns', 'theone_clinic_promotions_admin_sortable_columns');

function theone_clinic_promotions_admin_sorting(WP_Query $query): void
{
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}
	if ($query->get('post_type') !== 'theone_promotion') {
		return;
	}

	$orderby = (string) $query->get('orderby');
	if ($orderby === 'theone_start') {
		$query->set('meta_key', '_theone_promo_start');
		$query->set('orderby', 'meta_value');
		$query->set('meta_type', 'DATE');
		return;
	}
	if ($orderby === 'theone_end') {
		$query->set('meta_key', '_theone_promo_end');
		$query->set('orderby', 'meta_value');
		$query->set('meta_type', 'DATE');
		return;
	}
}
add_action('pre_get_posts', 'theone_clinic_promotions_admin_sorting');

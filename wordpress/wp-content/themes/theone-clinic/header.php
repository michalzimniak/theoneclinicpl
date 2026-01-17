<?php
/**
 * Header
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo esc_attr(theone_clinic_get_meta_description()); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="main-header">
	<div class="container">
		<div class="navbar-container">
			<div class="logo-container">
				<a href="<?php echo esc_url(home_url('/')); ?>">
					<img src="<?php echo esc_url(get_template_directory_uri() . '/media/logo.jpg'); ?>" alt="TheOne Clinic Logo" class="logo">
				</a>
			</div>

			<div class="hamburger" id="hamburger">
				<span></span>
				<span></span>
				<span></span>
			</div>

			<nav class="main-nav" id="mainNav">
				<ul class="nav-menu">
					<li class="nav-item">
						<a href="<?php echo esc_url(theone_clinic_page_url('home', home_url('/'))); ?>" class="nav-link<?php echo is_front_page() ? ' active' : ''; ?>">Strona Główna</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo esc_url(theone_clinic_page_url('onas')); ?>" class="nav-link<?php echo theone_clinic_is_page_slug('onas') ? ' active' : ''; ?>">O nas</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo esc_url(theone_clinic_page_url('efekty')); ?>" class="nav-link<?php echo theone_clinic_is_page_slug('efekty') ? ' active' : ''; ?>">Efekty przed i po</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo esc_url(theone_clinic_page_url('depilacja')); ?>" class="nav-link<?php echo theone_clinic_is_page_slug('depilacja') ? ' active' : ''; ?>">Depilacja Laserowa</a>
					</li>

					<li class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle">Laseroterapia</a>
						<ul class="dropdown-menu">
							<li class="dropdown-item"><a href="<?php echo esc_url(theone_clinic_page_url('ipixel')); ?>" class="dropdown-link<?php echo theone_clinic_is_page_slug('ipixel') ? ' active' : ''; ?>">iPixel - Laser Frakcyjny</a></li>
							<li class="dropdown-item"><a href="<?php echo esc_url(theone_clinic_page_url('dvl')); ?>" class="dropdown-link<?php echo theone_clinic_is_page_slug('dvl') ? ' active' : ''; ?>">DVL - Zmiany Naczyniowe</a></li>
							<li class="dropdown-item"><a href="<?php echo esc_url(theone_clinic_page_url('clearskin')); ?>" class="dropdown-link<?php echo theone_clinic_is_page_slug('clearskin') ? ' active' : ''; ?>">Clear Skin - Trądzik</a></li>
						</ul>
					</li>

					<li class="nav-item">
						<a href="<?php echo esc_url(theone_clinic_page_url('dermapen')); ?>" class="nav-link<?php echo theone_clinic_is_page_slug('dermapen') ? ' active' : ''; ?>">Dermapen 4.0</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo esc_url(theone_clinic_page_url('endermologia')); ?>" class="nav-link<?php echo theone_clinic_is_page_slug('endermologia') ? ' active' : ''; ?>">Endermologia</a>
					</li>

					<li class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle">Zabiegi Pielęgnacyjne</a>
						<ul class="dropdown-menu">
							<li class="dropdown-item"><a href="<?php echo esc_url(theone_clinic_page_url('linder')); ?>" class="dropdown-link<?php echo theone_clinic_is_page_slug('linder') ? ' active' : ''; ?>">Linder Health</a></li>
							<li class="dropdown-item"><a href="<?php echo esc_url(theone_clinic_page_url('image')); ?>" class="dropdown-link<?php echo theone_clinic_is_page_slug('image') ? ' active' : ''; ?>">Image Skincare</a></li>
						</ul>
					</li>

					<li class="nav-item">
						<a href="<?php echo esc_url(theone_clinic_page_url('badanie')); ?>" class="nav-link<?php echo theone_clinic_is_page_slug('badanie') ? ' active' : ''; ?>">Badanie Skóry</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo esc_url(theone_clinic_page_url('faq')); ?>" class="nav-link<?php echo theone_clinic_is_page_slug('faq') ? ' active' : ''; ?>">FAQ</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>

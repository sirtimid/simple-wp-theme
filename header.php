<!DOCTYPE html>
	<html class="no-js" <?php language_attributes() ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ) ?>">
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
			<?php wp_head() ?>
		</head>
		<body <?php body_class() ?> >
			<div role="document">
				<header class="main-header">
					<div class="container">
						<nav role="navigation">
							<a class="logo" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
							<?php echo get_menu('primary_menu', 'header-nav', true) ?>
						</nav>
					</div>
				</header>
				<main role="main">
<?php
/**
 * Archives header
 *
 * @package		Zero
 * @subpackage	Partials
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only used for archives
if ( ! is_archive() ) {
	return;
} ?>

<header class="archive-header clr">
	<h1 class="archive-title"><?php the_archive_title(); ?></h1>
	<?php if ( term_description() ) : ?>
		<div class="archive-description clr">
			<?php echo term_description(); ?>
		</div><!-- #archive-description -->
	<?php endif; ?>
</header><!-- .archive-header -->
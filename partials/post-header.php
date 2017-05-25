<?php
/**
 * Outputs the post header
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
} ?>

<header class="post-header clr">
	<h1 class="post-title"><?php the_title(); ?></h1>
</header><!-- .post-header -->
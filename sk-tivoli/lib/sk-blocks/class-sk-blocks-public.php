<?php

/**
 * Class for flexible content with Blocks.
 *
 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
 *
 */
class SK_Blocks_Public {

	/**
	 * SK_Blocks_Public constructor.
	 */
	function __construct() {
		//$this->init();
	}


	/**
	 * Init method
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init() {}


	/**
	 * Prints the section/row.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param string $section
	 *
	 * @return bool
	 */
	public function print_section( $section = '' ) {
		if ( empty ( $section ) ) {
			return false;
		}
		?>
		<div class="row">
			<?php foreach ( $section['sk-row'] as $row ) : ?>
				<div class="col-md-<?php echo $row['sk-grid']; ?>">
					<?php self::column_content( $row ); ?>
				</div>
			<?php endforeach; ?>
		</div><!-- .row -->
		<?php
	}

	/**
	 * Prints the short code.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $column
	 */
	public static function print_shortcode( $column ) {
		if ( intval( $column['sk-grid-border'] ) === 1 ) : ?>
			<div class="sk-grid-border-inner">
				<?php echo do_shortcode( $column['sk-short-code'] ); ?>
			</div>
		<?php else : ?>
			<?php echo do_shortcode( $column['sk-short-code'] ); ?>
		<?php endif; ?>
		<?php
	}

	/**
	 * Print the block.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $column
	 *
	 * @return bool
	 */
	public static function print_block( $column ) {
		$block_id = $column['sk-block'][0];
		$grid     = $column['sk-grid'];
		$block    = get_post( $block_id );

		$type = wp_get_post_terms( $block_id, 'block-type', array( 'fields' => 'slugs' ) );
		if ( empty( $type ) ) {
			return false;
		}

		echo self::get_block( $block_id, $type[0], $grid );

	}

	/**
	 * Logic for the requested block type.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param string $block_id
	 * @param string $type
	 * @param string $grid
	 */
	private static function get_block( $block_id = '', $type = '', $grid = '' ) {

		switch ( $type ) {
			case 'bild':
				echo self::get_block_image( $block_id, $grid );
				break;

			case 'bild-och-text':
				echo self::get_block_image_with_text( $block_id, $grid );
				break;

			case 'lanklista':
				echo self::get_block_link_list( $block_id, $grid );
				break;

			case 'navigation':
				echo self::get_block_navigation( $block_id, $grid );
				break;

			default:
				_e('Något har gått fel ...');
		}


	}


	/**
	 * HTML for the block type image.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param string $block_id
	 * @param string $grid
	 *
	 * @return array|null|string|WP_Post
	 */
	private static function get_block_image( $block_id = '', $grid = '' ) {

		/*
		$block->{'block'} = array(
			'image' => get_field( 'sk-blocks-image', $block_id )
		);
		*/

		$image_id = get_field( 'sk-blocks-image', $block_id );
		$image = wp_get_attachment_image_src( $image_id, 'content-full' );

		if ( intval( $grid ) === 12 ) {
			$image = wp_get_attachment_image_src( $image_id, 'full' );
		}

		$links['internal'] = get_field( 'sk-block-link-internal', $block_id );
		$links['external'] = get_field( 'sk-block-link-external', $block_id );

		$link = $links['internal'];

		if ( empty ( $link ) ) {
			$link = $links['external'];
		}

		ob_start();

		?>


		<div class="block block-image">
			<div class="block-block__image">
				<?php if ( ! empty( $link ) ) : ?>
				<a href="<?php echo $link; ?>">
					<?php endif; ?>
					<img src="<?php echo $image[0]; ?>">
					<?php if ( ! empty( $link ) ) : ?>
				</a>
			<?php endif; ?>
			</div>
		</div>

		<?php
		$block = ob_get_clean();

		return $block;

	}

	/**
	 * HTML for block type image with text.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param string $block_id
	 * @param string $grid
	 *
	 * @return array|null|string|WP_Post
	 */
	private static function get_block_image_with_text( $block_id = '', $grid = '' ) {
		$block = get_post( $block_id );

		/*
		$block->{'block'} = array(
			'image' => get_field( 'sk-blocks-image', $block_id )
		);
		*/

		$image_id = get_field( 'sk-block-image-and-text', $block_id );

		$image = wp_get_attachment_image_src( $image_id, 'content-full' );
		if ( intval( $grid ) === 12 ) {
			$image = wp_get_attachment_image_src( $image_id, 'full' );
		}

		$title   = get_field( 'sk-block-image-and-text-title', $block_id );
		$content = get_field( 'sk-block-image-and-text-content', $block_id );


		$links['internal'] = get_field( 'sk-block-link-internal', $block_id );
		$links['external'] = get_field( 'sk-block-link-external', $block_id );

		$link = $links['internal'];

		if ( empty ( $link ) ) {
			$link = $links['external'];
		}

		ob_start();
		?>

		<div class="block block-image-and-text<?php echo intval( $grid ) === 12 ? ' wide' : null; ?>">
			<div class="block-block__image"><img src="<?php echo $image[0]; ?>"></div>
			<div class="block-footer">
				<div class="block-footer__title"><h3><?php echo $title; ?></h3></div>
				<div class="block-footer__content"><?php echo $content; ?></div>
				<?php if ( ! empty( $link ) ) : ?>
					<div class="block-footer__link"><a
							href="<?php echo $link; ?>"><?php _e( 'Läs mer', 'sk-tivoli' ); ?><?php material_icon( 'keyboard arrow right', array( 'size' => '1.3em' ) ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div><!-- .block-image-and-text -->

		<?php
		$block = ob_get_clean();

		return $block;

	}

	/**
	 * HTML for block type link list.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param string $block_id
	 * @param string $grid
	 *
	 * @return string
	 */
	private static function get_block_link_list( $block_id = '', $grid = '' ) {

		$title  = get_field( 'sk_block_link_list_title', $block_id );
		$groups = get_field( 'sk_block_link_list', $block_id );
		$markup = '<a class="link-list" href="%s" title="%3$s"><span><span class="link-list__icon">%s</span><span class="link-list__name">%s</span></span></a>';
		ob_start();
		?>

		<div class="block block-link-list">
			<?php if ( ! empty( $title ) ) : ?>
				<h3><?php echo $title; ?></h3>
			<?php endif; ?>
			<?php foreach ( $groups as $group ) : ?>
				<div class="block-link-list__title"><?php echo $group['rubrik']; ?></div>
				<ul>
					<?php foreach ( $group['link'] as $link ) : ?>
						<li>
							<?php echo sprintf( $markup, $link['linklist_url'], get_icon( 'arrow-right' ), $link['linklist_title'] ) ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>
		</div><!-- .block-link-list -->

		<?php
		$block = ob_get_clean();

		return $block;

	}

	/**
	 * HTML when using a menu as navigation cards.
	 * Using nearly the same as navigations-cards.php.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param string $block_id
	 * @param string $grid
	 *
	 * @return string
	 */
	private static function get_block_navigation( $block_id = '', $grid = '' ) {

		$menu_name = get_field( 'sk_block_navigation_menu_name', $block_id );
		$use_icons = get_field( 'sk_block_navigation_menu_show_icons', $block_id );

		$menu_items = wp_get_nav_menu_items( $menu_name );

		foreach ( $menu_items as $menu_item ) {
			add_global_section_theme( $menu_item );
		}

		ob_start();

		echo '<style>';
		global $page_themes;
		foreach ( $page_themes as $theme ) {

			$keyword = $theme['keyword'];
			$color   = $theme['color'];

			if ( empty( $color ) ) {
				$color = '#818a91';
			}

			echo "
		.navigation-$keyword .nav-card-title__icon {
			background-color: $color !important;
		}

		.blocks .nav-$keyword .menu-item-icon {
				background-color:  $color;
		}
		.blocks .nav-$keyword:hover,
		.blocks .nav-$keyword.current-menu-item,
		.blocks .nav-$keyword.current-menu-ancestor {
				border-bottom-color:  $color;
		}
	";
		}

		echo '</style>';

		$menu_items = wp_get_nav_menu_items( $menu_name );
		foreach ( $menu_items as $menu_item ) {
			$numbers[] = get_post_meta( $menu_item->ID, '_menu_item_object_id', true );
		}


		?>
		<div class="block block-navigation-cards<?php echo !empty($use_icons) ? ' use-icons' : null;?>">
			<div class="row">
				<div class="col-md-12">

					<div class="row">
						<?php

						$args    = array(
							'parent'      => '0',
							'sort_column' => 'menu_order',
							'include'     => $numbers
						);
						$parents = get_pages( $args );

						foreach ( $parents as $parent ) :

							$parent_id = $parent->ID;
							$title     = $parent->post_title;
							$permalink = get_the_permalink( $parent_id );

							$is_shortcut  = sk_is_shortcut( $parent_id );
							$shortcut_url = sk_shortcut_url( $parent_id );

							$keyword = get_section_class_name( $parent );


							?>

							<div
								class="navigation-card navigation-<?php echo $keyword; ?> <?php echo $is_shortcut ? 'shortcut' : ''; ?>">
								<h2 class="nav-card-title">
									<span class="nav-card-title__icon">
										<?php if ( $is_shortcut === 'external' ) {
											the_icon( 'external' );
										} ?>

										<?php echo !empty( $use_icons ) ? get_section_icon( $parent->ID ) : null; ?>
									</span>

									<?php if ( $is_shortcut === 'page' && is_null( get_field( 'page_link', $parent_id ) ) ) : ?>
										<?php echo $title; ?>
									<?php else : ?>
										<a href="<?php echo $is_shortcut ? $shortcut_url : $permalink; ?>"<?php echo $is_shortcut === 'external' ? 'target="_blank"' : ''; ?>>
											<?php echo $title; ?>
										</a>
									<?php endif; ?>
								</h2>
								<p class="nav-card-text">
									<?php

									if ( ! is_navigation( $parent_id ) ) {

										// If child page is a shortcut to an internal page we set child id to
										// the page its pointing to
										if ( 'page' === $is_shortcut ) {
											$parent_id = get_field( 'page_link', $parent_id )->ID;

											// If child page is external shortcut we echo description.
										} else if ( 'external' === $is_shortcut ) {
											$description = get_field( 'shortcut_description', $parent_id );
											echo $description;
										}

									}

									if ( is_navigation( $parent_id ) ) {
										$children = get_children( array(
											'post_parent' => get_the_id(),
											'post_type'   => 'page',
											'post_status' => 'publish',
											'orderby'     => 'menu_order title',
											'order'       => 'ASC'
										) );

										if ( ! $children ) {
											if ( $parent_id === null ) {
												$children = array();
											} else {
												$children = get_children( array( 'post_type'   => 'page',
												                                 'post_parent' => $parent_id,
												                                 'numberposts' => 5
												) );
											}
										}

										$i = 0;
										foreach ( $children as $child ) {

											if ( $parent_id == $child->ID ) {
												continue;
											}

											if ( $i > 0 ) {
												echo ' |&nbsp;';
											}
											printf( '<a href="%s">%s</a>', get_permalink( $child->ID ), $child->post_title );
											$i += 1;
										}

										?>

										| <a href="<?php echo $is_shortcut ? $shortcut_url : $permalink; ?>">Visa&nbsp;alla&nbsp;&#187;</a>

									<?php } else {
										$excerpt = sk_get_excerpt( $parent_id );
										echo $excerpt;
									}
									?>
								</p>
							</div>
							<?php endforeach; ?>
					</div><!-- .row -->
				</div><!-- .col -->
			</div><!-- .row -->
		</div><!-- .block-navigation-cards -->
		<?php
		$block = ob_get_clean();

		return $block;

	}


}

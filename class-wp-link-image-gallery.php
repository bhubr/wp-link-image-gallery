<?php
namespace bhubr;
class WP_Link_Image_Gallery {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', [$this, 'register_link_editor_meta_box'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_link_editor_script'] );
		add_shortcode( 'link_gallery', [$this, 'do_gallery_shortcode'] );
		add_action( 'wp_enqueue_scripts', [$this, 'front_enqueue_styles'] );
	}

	/**
	 * Register link featured image meta box
	 */
	function register_link_editor_meta_box() {
		add_meta_box( 'link-featured-image-box', __( 'Link Featured Image', 'textdomain' ), [$this, 'link_featured_image_box'], 'link', 'side' );
	}

	/**
	 * Add JS code to meta box
	 */
	function enqueue_link_editor_script($hook) {
		wp_enqueue_media();
		wp_enqueue_script('link-featured-image', plugins_url('link-featured-image.js', __FILE__)); //, ['media-views', 'media-models']);
	}

	/**
	 * Display link gallery
	 */
	function do_gallery_shortcode($args) {
		$link_cats = get_terms('link_category');
		$quick_access_links = array_reduce(
			$link_cats,
			function($carry, $cat) {
				$link = '<a href="#' . $cat->slug . '">' . $cat->name . '</a>';
				return empty($carry) ? $link : $carry . ' | ' . $link;
			},
			''
		);
		?>
		<div class="pure-g">
			<div class="pure-u-1">
				<?php echo $quick_access_links; ?>
			</div>
		</div>
		<?php
		foreach($link_cats as $cat) {
			$links = get_bookmarks(['category' => "$cat->term_id"]); ?>
			<div class="pure-g">
				<div class="pure-u-1">
					<h3><?php echo $cat->name; ?></h3>
				</div>
			<?php
			foreach($links as $link) {
				$image_url = parse_url($link->link_image, PHP_URL_PATH);
				$url_bits = explode(".", $image_url);
				$arr_len = count($url_bits);
				$last_idx = $arr_len - 1;
				$ext = $url_bits[$arr_len - 1];
				$image_url = str_replace(".$ext", "-300x188.$ext", $image_url);
				$name_split = explode(' - ', $link->link_name);
				?>
				<div id="<?php echo $cat->slug; ?>" class="pure-u-1 pure-u-md-1-4 pure-u-sm-2">
					<div class="link-card">
						<a href="<?php echo $link->link_url; ?>" target="<?php echo $link->link_target; ?>">
							<div class="img-container">
								<img src="<?php echo $image_url; ?>" alt="<?php echo $link->link_name; ?>" />
								<div class="overlay"><?php foreach($name_split as $part): ?>
									<span class="overlay-line"><?php echo $part; ?></span><br>
								<?php endforeach; ?></div>
							</div>
						</a>
						<p><?php echo $link->link_description; ?></p>
					</div>
				</div>
			<?php } ?>
			</div>
		<?php
		}
	}

	function front_enqueue_styles() {
		wp_enqueue_style('link-gallery', plugins_url('link-image-gallery.css', __FILE__));
	}

	/**
	 * Show featured image meta box
	 */
	function link_featured_image_box($link, $register_info) {
		// Get the image src
		$you_have_img = ! empty($link->link_image);
		// For convenience, see if the array is valid
		$your_img_src = $link->link_image;
		?>

		<!-- Your image container, which can be manipulated with js -->
		<div class="custom-img-container">
			<?php if ( $you_have_img ) : ?>
				<img  src="<?php echo $link->link_image; ?>" alt="" style="max-width:100%;" /><br>
			<?php endif; ?>
		</div>

		<!-- Your add & remove image links -->
		<p class="hide-if-no-js">
			<a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>"
			   href="<?php echo $upload_link ?>">
				<?php _e('Set featured image') ?>
			</a>
			<a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>"
			  href="#">
				<?php _e('Remove this image') ?>
			</a>
		</p>

		<?php
	}
}

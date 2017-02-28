<?php
namespace bhubr;
class WP_Link_Image_Gallery {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', [$this, 'register_link_editor_meta_box'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_link_editor_script']);
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
		var_dump($hook);
		wp_enqueue_media();
		wp_enqueue_script('link-featured-image', plugins_url('link-featured-image.js', __FILE__)); //, ['media-views', 'media-models']);
	}

	/**
	 * Show featured image meta box
	 */
	function link_featured_image_box($link, $register_info) {
		// Get WordPress' media upload URL
		// $upload_link = esc_url( get_upload_iframe_src( 'link', $link->link_id ) );

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

		<!-- A hidden input to set and post the chosen image id -->
		<input class="custom-img-id" name="custom-img-id" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />
		<?php
	}
}

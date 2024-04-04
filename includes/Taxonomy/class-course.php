<?php
/**
 * Course taxonomy for Event CPT
 *
 * @since 1.0.0
 * @package Digisar\Events
 */

namespace Digisar\Taxonomy;

use Digisar\PostType;
use WP_Term;

/**
 * Course class.
 *
 * @since 1.0.0
 */
final class Course extends Taxonomy {
	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 *
	 * @var string $name
	 */
	public static string $name = 'course';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->singular = esc_html__( 'Course', 'digisar-events' );
		$this->plural   = esc_html__( 'Courses', 'digisar-events' );

		$this->object_type = PostType\Event::$name;

		// Add price field.
		add_action( self::$name . '_add_form_fields', array( $this, 'add_price_field' ) );
		add_action( self::$name . '_edit_form_fields', array( $this, 'add_price_field_to_edit' ), 10, 2 );

		// Save price value.
		add_action( 'created_' . self::$name, array( $this, 'save_price' ), 10, 2 );
		add_action( 'edited_' . self::$name, array( $this, 'save_price' ), 10, 2 );
	}

	/**
	 * Add price field.
	 *
	 * @since 1.0.0
	 */
	public function add_price_field() {
		?>
		<div class="form-field">
			<label for="course_price"><?php esc_html_e( 'Price', 'digisar-events' ); ?></label>
			<input type="text" name="price" id="course_price">
			<p class="description">
				<?php esc_html_e( 'Course price per person.', 'digisar-events' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Add price field to term edit screen.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Term $term Current taxonomy term object.
	 */
	public function add_price_field_to_edit( WP_Term $term ) {
		$value = get_term_meta( $term->term_id, 'course_price', true );
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="course_price"><?php esc_html_e( 'Price', 'digisar-events' ); ?></label>
			</th>
			<td>
				<input type="text" name="course_price" id="course_price" value="<?php echo esc_attr( $value ); ?>">
				<p class="description">
					<?php esc_html_e( 'Course price per person.', 'digisar-events' ); ?>
				</p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save price to term meta.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_id Term ID.
	 */
	public function save_price( int $term_id ) {
		$price = filter_input( INPUT_POST, 'course_price', FILTER_UNSAFE_RAW );

		if ( is_null( $price ) ) {
			return;
		}

		update_term_meta( $term_id, 'course_price', sanitize_text_field( $price ) );
	}
}

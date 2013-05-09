<?php

class Mad_Mimi_Form_Renderer {

	function process( $form_id ) {
		$form = Mad_Mimi_Dispatcher::get_fields( (int) $form_id );

		if ( ! empty( $form->fields ) ) : ?>

			<div class="mimi-form-wrapper" id="form-<?php echo absint( $form_id ); ?>">
				<form action="<?php echo esc_url( $form->submit ); ?>" method="post" class="mimi-form">

					<?php foreach ( $form->fields as $count => $field ) : ?>

						<p><?php Mad_Mimi_Form_Fields::dispatch_field( $field ); ?></p>

					<?php endforeach; ?>

					<p>
						<?php // @todo should the mad mimi text be translatable? it can be manipulated. ?>
						<a href="http://madmimi.com" target="_blank">Powered by Mad Mimi</a>
					</p>

					<input type="hidden" name="form_id" value="<?php echo absint( $form->id ); ?>" />
					<input type="submit" value="<?php _e( 'Submit', 'mimi' ); ?>" class="button mimi-submit" />
				</form>
			</div>
			<?php

		endif;
	}
}

final class Mad_Mimi_Form_Fields {

	public function dispatch_field( $field ) {
		if ( ! is_object( $field ) || ! method_exists( __CLASS__, $field->type ) )
			return false;

		call_user_func( array( __CLASS__, $field->type ), $field );
	}

	public static function string( $args ) {
		$field_classes = array( 'mimi-field' );
		
		// is this field required?
		if ( $args->required )
			$field_classes[] = 'mimi-required';

		$field_classes = (array) apply_filters( 'mimi_required_field_class', $field_classes, $args );
		?>
		<label for="<?php echo esc_attr( $args->name ); ?>">
			<?php echo esc_html( $args->display ); ?>
			<?php if ( $args->required && apply_filters( 'mimi_required_field_indicator', true, $args ) ) : ?>
			<span class="required">*</span>
			<?php endif; ?>
		</label>
		<input type="text" name="<?php echo esc_attr( $args->name ); ?>" id="<?php echo esc_attr( $args->name ); ?>" class="<?php echo esc_attr( join( ' ', $field_classes ) ); ?>" />
		<?php
	}
}
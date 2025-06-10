<div class="post-comments">
	<div class="main-content " id="comments">
	
	<?php
	/*************
	 *	
	 * IF PASSWORD REQUIRED
	 * 	 
	 ************/
	if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'extra' ); ?></p>
	</div><!-- #comments -->
	<?php
			return;
		endif;
	?>
	
	
	
	<?php if ( have_comments() ) : ?>
		<ul class="commentlist">
			<?php wp_list_comments('callback=extra_custom_comment'); ?>
		</ul>

		<h4 class="comments-title"><?php $numcomments = (int)get_comments_number();
			if($numcomments == 0) {
				_e('Aucun commentaire, ', 'extra');
			} else if($numcomments == 1) {
				_e('Il y a 1 commentaire, ', 'extra');
			} else {
				printf(__('Il y a %d commentaires, ', 'extra'), $numcomments);
			}
			?>
			<a class="add-comment-link" href="#respond"><?php _e("ajoutez le vôtre", 'extra'); ?></a>
		</h4>
	<?php elseif (!comments_open()): ?>
		<p class="no-comments"><?php _e( 'Les commentaires sont fermés pour cet article...', 'extra' ); ?></p>
	<?php else: ?>
		<p class="comments-title no-comments"><?php _e( "Aucun commentaire pour l'instant", 'extra' ); ?><br />
		<a class="add-comment-link" href="#comments"><?php _e("Ajoutez le vôtre", 'extra'); ?></a></p>
	<?php endif; ?>

	<div class="respond-wrapper">
		<?php
			$args = array(
				'label_submit' => __("Envoyez votre commentaire", 'extra'),
				'comment_field' => '<p class="comment-form-comment"><label for="comment">' . __( 'Message', 'extra' ) . ' <span class="required">*</span></label>' .
				'<textarea class="comment-form-input" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
				'comment_notes_before' => '',
				'comment_notes_after' => '',
				'class_submit' => 'submit button extra-button',
				'title_reply' => __("Ajoutez votre commentaire", "extra"),
				'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( 'Connecté e tant que <strong>%1$s</strong>. <a href="%1$s" title="Se déconnecter">Se déconnecter ?</a>' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
			);
			comment_form($args);
		 ?>
	</div><!-- .respond-wrapper -->
</div><!-- #comments -->
</div>
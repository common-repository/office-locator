<?php get_header(); ?>

<main id="office-content-main">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();
			$post_id = get_the_ID();
			$office_title = get_the_title( $post_id );
			$office_name = get_post_meta( $post_id, 'office_name', true);
			$office_phone = get_post_meta( $post_id, 'office_phone', true);
			$office_fax = get_post_meta( $post_id, 'office_fax', true);
			$office_email = get_post_meta( $post_id, 'office_email', true);
			$office_address = get_post_meta( $post_id, 'office_address', true );
			$office_city = get_post_meta( $post_id, 'office_city', true );
			$office_state = get_post_meta( $post_id, 'office_state', true );
			$office_country = get_post_meta( $post_id, 'office_country', true );
			$office_postal_code = get_post_meta( $post_id, 'office_postal_code', true );
			$office_longitude = get_post_meta( $post_id, 'office_longitude', true );
			$office_latitude = get_post_meta( $post_id, 'office_latitude', true );
			?>
			<div class="office-main-content">
				<div class="single-office-box">
					<?php 
					if( $office_title ){
						?>
						<h3 class="single-direction-title"><?php echo wp_kses_post( $office_title ); ?></h3>
						<?php	
					}
					if( $office_name ){
						?>
						<h4 class="single-office-name"><?php echo wp_kses_post( $office_name ); ?></h4>
						<?php	
					}
					?>	
					<div class="address-wrapper">
						<?php 
						if( $office_address ){
							?>
							<div class="addres-list">
								<span><i class="fa-solid fa-location-dot"></i></span><p><?php echo wp_kses_post( $office_address ); ?></p>
							</div>
							<?php	
						}
						if( $office_city ){
							?>
							<div class="addres-list">
								<span><i class="fa-solid fa-city"></i></span><p><?php echo wp_kses_post( $office_city ); ?></p>
							</div>
							<?php	
						}
						if( $office_state ){
							?>
							<div class="addres-list">
								<span><i class="fa-solid fa-earth-oceania"></i></span><p><?php echo wp_kses_post( $office_state ); ?></p>
							</div>
							<?php	
						}
						if( $office_country ){
							?>
							<div class="addres-list">
								<span><i class="fa-solid fa-globe"></i></span><p><?php echo wp_kses_post( $office_country ); ?></p>
							</div>
							<?php	
						}
						if( $office_postal_code ){
							?>
							<div class="addres-list">
								<span><i class="fa-solid fa-location-dot"></i></span><p><?php echo wp_kses_post( $office_postal_code ); ?></p>
							</div>
							<?php	
						}
						if( $office_phone ){
							?>
							<div class="addres-list">
								<span><i class="fa-solid fa-phone"></i></span><p><a href="tel:<?php echo wp_kses_post( $office_phone ); ?>"><?php echo wp_kses_post( $office_phone ); ?></a></p>
							</div>
							<?php	
						}
						if( $office_fax ){
							?>
							<div class="addres-list">
								<span><i class="fa-solid fa-fax"></i></span><p><a href="tel:<?php echo wp_kses_post( $office_fax ); ?>"><?php echo wp_kses_post( $office_fax ); ?></a></p>
							</div>
							<?php	
						}
						if( $office_email ){
							?>
							<div class="addres-list">
								<span><i class="fa-solid fa-envelope"></i></span><p><a href="mailto:<?php echo wp_kses_post($office_email); ?>"><?php echo wp_kses_post( $office_email ); ?></a></p>
							</div>
							<?php	
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}
	}

	?>

</main><!-- #office-content-main -->

<?php
get_footer();
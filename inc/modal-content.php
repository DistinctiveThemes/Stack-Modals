<?php 


	if( 'basic' == $layout ){
		
		$modal_content = '
			<div class="modal-content">
				<div class="boxed boxed--lg">
					'. do_shortcode(htmlspecialchars_decode($content)) .'
				</div>
			</div>
		';	
		
	} elseif( 'image-left' == $layout ) {
		
		$modal_content = '
			<div class="modal-content">
				<section class="imageblock feature-large bg--white border--round ">
				    <div class="imageblock__content col-md-5 col-sm-3 pos-left">
				        <div class="background-image-holder">
				            '. wp_kses_post($image) .'
				        </div>
				    </div>
				    <div class="container">
				        <div class="row">
				            <div class="col-md-5 col-md-push-6 col-sm-7 col-sm-push-4">
				                '. do_shortcode(htmlspecialchars_decode($content)) .'
				            </div>
				        </div><!--end of row-->
				    </div><!--end of container-->
				</section>
			</div>
		';	
		
	} elseif( 'image-right' == $layout ) {
		
		$modal_content = '
			<div class="modal-content">
				<section class="imageblock feature-large bg--white border--round ">
				    <div class="imageblock__content col-md-5 col-sm-3 pos-right">
				        <div class="background-image-holder">
				            '. wp_kses_post($image) .'
				        </div>
				    </div>
				    <div class="container">
				        <div class="row">
				            <div class="col-md-5 col-md-push-1 col-sm-7 col-sm-push-1">
				                '. do_shortcode(htmlspecialchars_decode($content)) .'
				            </div>
				        </div><!--end of row-->
				    </div><!--end of container-->
				</section>
			</div>
		';	
		
	} elseif( 'narrow' == $layout ) {
	
		$modal_content = '
			<div class="modal-content">
				<section class="unpad ">
				    <div class="container">
				        <div class="row">
				            <div class="col-sm-6 col-md-offset-3 col-sm-offset-3 col-xs-12">
			                    <div class="feature feature-1 text-center">
			                        '. wp_kses_post($image) .'
			                        <div class="feature__body boxed boxed--lg boxed--border">
			                            <div class="modal-close modal-close-cross"></div>
			                            '. do_shortcode(htmlspecialchars_decode($content)) .'
			                        </div>
			                    </div><!--end feature-->
			                </div>
				        </div><!--end of row-->
				    </div><!--end of container-->
				</section>
			</div>
		';
	
	} elseif( 'image-background' == $layout ) {
	
		$modal_content = '
			<div class="modal-content">
			    <section class="cover height-60 imagebg border--round" data-overlay="2">
			        <div class="modal-close modal-close-cross"></div>
			        <div class="background-image-holder">
			            '. wp_kses_post($image) .'
			        </div>
			        <div class="container pos-vertical-center">
			            <div class="row">
			                <div class="col-sm-7 col-md-5 col-md-offset-1 col-sm-offset-1">
			                    '. do_shortcode(htmlspecialchars_decode($content)) .'
			                </div>
			            </div><!--end of row-->
			        </div><!--end of container-->
			    </section>
			</div>
		';
		
	}
	
	$trigger = ( 'yes' == $show_trigger && !empty($button_text) ) ? '<a class="btn '. $btn_class .' modal-trigger" href="#">'. $button_text .'</a>' : false;
	
	if(isset($atts['modal_id'])) {
		$post_id = $atts['modal_id'];
	} else {
		$post_id = get_the_ID();
	}
	

	$output = '
		<div class="modal-instance">
			'. $trigger .'
			<div class="modal-container" '. $autoshow .' '. $cookie .' '. $exit .' data-modal-id="'.$post_id.'">
				'. $modal_content .'
			</div>
		</div><!--end of modal instance-->
	';
	echo $output;
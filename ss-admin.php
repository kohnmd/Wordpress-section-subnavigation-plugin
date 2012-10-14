<?php

add_action('admin_menu', 'ss_add_custom_config_page');

function ss_add_custom_config_page() {
	if ( function_exists('add_submenu_page') ) {
		add_submenu_page( 'options-general.php', __('Section Subnav'), __('Section Subnav'), 'edit_themes', 'section-subnav', 'ss_admin_menu' );
    }
}

function ss_admin_menu() {
    ?>
	
	<div class="wrap">
	
		<?php screen_icon('themes'); ?> <h2><?php echo get_admin_page_title(); ?></h2>
		
		<form method="POST" action="" id="sandbox_settings">
		
			<table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="num_elements">
                            Number of elements on a row:  
                        </label>
                    </th>
                    <td>
                        <input type="number" name="num_elements" min="0" max="30" />
                    </td>
                </tr>
            </table>
			
			<h3>Featured Posts</h3>
			
			<ul id="featured-posts-list"></ul>
			
			<input type="hidden" name="element-max-id" />
			
			<a href="#" id="add-featured-post">Add featured post</a>
			
			<p>
				<input type="submit" value="Save settings" class="button-primary"/>
			</p>  
		</form>
		
		<?php $posts = get_posts(); ?>
		<li class="front-page-element" id="front-page-element-placeholder" style="display:none;">
			<label for="element-page-id">Featured post:</label>
			<select name="element-page-id">
				<?php foreach ($posts as $post) : ?>
					<option value="<?php echo $post->ID; ?>">
						<?php echo $post->post_title; ?>
					</option>
				<?php endforeach; ?>
			</select>
			<a href="#">Remove</a>
		</li>
		  
	</div><!-- .wrap -->
	
	
	<?
}

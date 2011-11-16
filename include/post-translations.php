<p><em><?php $post_type == 'page' ? _e('ID of pages in other languages:', 'polylang') : _e('ID of posts in other languages:', 'polylang');?></em></p>
<table>
	<thead><tr>
		<th><?php _e('Language', 'polylang');?></th>
		<th><?php $post_type == 'page' ? _e('Page ID', 'polylang') : _e('Post ID', 'polylang');?></th>
		<th><?php  _e('Edit', 'polylang');?></th>
	</tr></thead>

	<tbody>
	<?php foreach ($listlanguages as $language) {
		if ($language != $lang) { 
			$value = $this->get_translated_post($post_ID, $language); 
			if (isset($_GET['from_post']))
				$value = $this->get_post($_GET['from_post'], $language); ?>			
			<tr>
			<td><?php echo esc_attr($language->name);?></td><?php
			printf(
				'<td><input name="%s" id="%s" class="tags-input" type="text" value="%s" size="6"/></td>',
				esc_attr($language->slug),
				esc_attr($language->slug),
				esc_attr($value)
			);
			if ($lang) {				
				$link = $value ? 
					sprintf(
						'<a href="%s">%s</a>',
						esc_url(admin_url('post.php?action=edit&amp;post=' . $value)),
						__('Edit','polylang')
					) :
					sprintf(
						'<a href="%s">%s</a>',
						esc_url(admin_url('post-new.php?post_type=' . $post_type . '&amp;from_post=' . $post_ID . '&amp;new_lang=' . $language->slug)),
						__('Add new','polylang')
					);?>
				<td><?php echo $link ?><td><?php
			}?>
			</tr><?php
		} 
	}	?>
	</tbody>
</table>

<?php
// displays the translations fields

if (isset($term_id)) { // edit term form?>
	<th scope="row" valign="top"><?php _e('Translations', 'polylang');?></th>
	<td><?php
}
else { // add term form?>
	<label><?php _e('Translations', 'polylang');?></label><?php
}
?>
<table>
	<thead><tr><?php
		foreach (array(__('Language', 'polylang'), __('Translation', 'polylang'), __('Edit', 'polylang')) as $title)
			printf('<th>%s</th>', $title);?>
	</tr></thead>
	<tbody>
		<?php foreach ($listlanguages as $language) {
			$translation = 0;

			// look for any existing translation in this language
			if ($language != $lang) {
				if (isset($term_id) && $translation_id = $this->get_translated_term($term_id, $language))
					$translation = get_term($translation_id, $taxonomy);
				if (isset($_GET['from_tag']) && isset($_GET['from_lang'])) {
					if ($_GET['from_lang'] == $language->slug)
						$translation = get_term($_GET['from_tag'], $taxonomy);
					elseif ($translation_id = $this->get_translated_term($_GET['from_tag'], $language))
						$translation = get_term($translation_id, $taxonomy);
				}?>

				<tr><td><?php echo esc_attr($language->name);?></td><?php

				// no translation exits in this language
				if (!$translation) {
					$translations = $this->get_terms_not_translated($taxonomy, $language, $lang);
					if (!empty($translations)) { ?>
						<td>
							<select name="_lang-<?php echo esc_attr($language->slug);?>" id="_lang-<?php echo esc_attr($language->slug);?>">
								<option value="0"></option><?php
								foreach ($translations as $translation) { ?>
									<option value="<?php echo esc_attr($translation->term_id);?>"><?php echo esc_attr($translation->name);?></option><?php
								} ?>
							</select>
						</td><?php
					} 
					else { ?>
						<td>
						</td><?php
					} ?>
					<td><?php
						// do not display the add new link in add term form ($term_id not set !!!)
						if (isset($term_id)) {
							$link = esc_url(admin_url(sprintf(
								'edit-tags.php?taxonomy=%s&amp;from_tag=%s&amp;from_lang=%s&amp;new_lang=%s',
								$taxonomy,
								$term_id,
								$lang->slug,
								$language->slug
							)));
							echo '<a href="' . $link . '">' . __('Add new','polylang') . '</a>';
						}?>
					</td><?php
				}

				// a translation exists
				else { ?>
					<td><?php echo esc_attr($translation->name); ?></td>									
					<td><?php
						$link = esc_url(admin_url(sprintf(
							'edit-tags.php?action=edit&amp;taxonomy=%s&amp;tag_ID=%s',
							$taxonomy,
							$translation->term_id
						)));
						echo '<a href="' . $link . '">' . __('Edit','polylang') . '</a>';?>
					</td><?php
				} ?>
				</tr><?php
			} // if (!$value)
		} // foreach ?>
	</tbody>
</table>
<?php if (isset($term_id)) { // edit term form?>
</td><?php
}
<div class="wrap">
<h2>Image Shrink</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields('imageshrink'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Maximum Image Size</th>
<td><input type="text" name="image_size_max_shrink" value="<?php echo get_option('image_size_max_shrink'); ?>" />px</td>
</tr>

</tr>

</table>
<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
    function GetBinarySetting($name) {
        $value = get_option($name);
        if ($value == "yes") echo("checked");
    }
	
	function SaveSettings() {
		 if(!isset($_POST['save'])) return;
		 $resizing_enabled = trim(esc_sql($_POST['yesno']));
		 $force_jpeg_recompression = trim(esc_sql($_POST['recompress_yesno']));
		 $max_width = trim(esc_sql($_POST['maxwidth']));
		 $max_height  = trim(esc_sql($_POST['maxheight']));
		 $compression_level = trim(esc_sql($_POST['quality']));
		 $convert_png_to_jpg = trim(esc_sql(isset($_POST['convertpng']) ? $_POST['convertpng'] : 'no'));
		 $convert_gif_to_jpg = trim(esc_sql(isset($_POST['convertgif']) ? $_POST['convertgif'] : 'no'));
	}
?>
<form method="post" accept-charset="utf-8">
    <h1>Általános beállítások</h1>
    <hr />
    <table>
        <tr>
            <td>Hibakersés (Debug) Mód</td>
            <td>
                <input type="checkbox" name="cb_debug" <?php GetBinarySetting('w442fw_debug'); ?> />
            </td>
        </tr>
        <tr>
            <td>Felhasználónév beírása a tartalom végére</td>
            <td>
                <input type="checkbox" name="cb_endusername" <?php GetBinarySetting('w442fw_usernameend'); ?> />
            </td>
        </tr>
    </table>
    <h1>Kép feltöltési beállítások</h1>
    <hr />
    <table>
        <tr>
            <td>Kép átméretezés engedélyezése</td>
            <td>
                <input type="checkbox" name="cb_resizeenambed" <?php GetBinarySetting('w442fw_resizeupload_resize_yesno'); ?>  />
            </td>
        </tr>
        <tr>
            <td>
                Maximális képméret:
            </td>
            <td>
                <input type="number" name="img_w" value="<?php echo(get_option('w442fw_resizeupload_width')); ?>" /> x
                <input type="number" name="img_h" value="<?php echo(get_option('w442fw_resizeupload_height')); ?>" />
            </td>
        </tr>
        <tr>
            <td>JPEG Minőség:</td>
            <td>
                <input type="number" name="jpg_q" min="1" max="100" value="<?php echo(get_option('w442fw_resizeupload_quality')); ?>"  />
            </td>
        </tr>
        <tr>
            <td>JPEG fájlok kényszerített újratörmörítése</td>
            <td>
                <input type="checkbox" name="cb_force_jpeg" <?php GetBinarySetting('w442fw_resizeupload_recompress_yesno'); ?> />
            </td>
        </tr>
        <tr>
            <td>PNG fájlok átalakítása JPEG formátumba</td>
            <td>
                <input type="checkbox" name="cb_force_png" <?php GetBinarySetting('w442fw_resizeupload_convertpng_yesno'); ?> />
            </td>
        </tr>
        <tr>
            <td>GIF fájlok átalakítása JPEG formátumba</td>
            <td>
                <input type="checkbox" name="cb_force_gif" <?php GetBinarySetting('w442fw_resizeupload_convertgif_yesno'); ?> />
            </td>
        </tr>
    </table>
	<input name="save" class="button button-primary" type="submit" value="Beállítások mentése">
</form>
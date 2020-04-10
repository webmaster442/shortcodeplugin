<?php
    function GetBinarySetting($name) {
        $value = get_option($name);
        if ($value == "yes") echo("checked");
    }
    
    function SaveSettings() {
         if(!isset($_POST['save'])) return;
         $safe = array();
         foreach ($_POST as $key => $value)
         {
             $safevalue =  trim(esc_sql($value));
             if ($safevalue == "on") $safevalue = "yes";
             $safe[$key] = $safevalue;
         }
         update_option('w442fw_debug_yesno', $safe['cb_debug']);
         update_option('w442fw_usernameend_yesno', $safe['cb_endusername']);
         update_option('w442fw_facebookshare_endpost_yesno', $safe['cb_facebookshare_end']);
         update_option('w442fw_copyprotect_yesno', $safe['cb_copyprotect']);
         //--
         update_option('w442fw_resizeupload_resize_yesno', $safe['cb_resizeenambed']);
         update_option('w442fw_resizeupload_width', $safe['img_w']);
         update_option('w442fw_resizeupload_height', $safe['img_h']);
         update_option('w442fw_resizeupload_quality', $safe['jpg_q']);
         update_option('w442fw_resizeupload_recompress_yesno', $safe['cb_force_jpeg']);
         update_option('w442fw_resizeupload_convertpng_yesno', $safe['cb_force_png']);
         update_option('w442fw_resizeupload_convertgif_yesno', $safe['cb_force_gif']);
    }
     SaveSettings();
?>
<form method="post" accept-charset="utf-8">
    <h1>Általános beállítások</h1>
    <hr />
    <table>
        <tr>
            <td>Hibakersés (Debug) Mód</td>
            <td>
                <input type='hidden' value='no' name='cb_debug'>
                <input type="checkbox" name="cb_debug" <?php GetBinarySetting('w442fw_debug_yesno'); ?> />
            </td>
        </tr>
        <tr>
            <td>Felhasználónév beírása a tartalom végére</td>
            <td>
                <input type='hidden' value='no' name='cb_endusername'>
                <input type="checkbox" name="cb_endusername" <?php GetBinarySetting('w442fw_usernameend_yesno'); ?> />
            </td>
        </tr>
        <tr>
            <td>Facebook megosztás gomb a tartalom végére</td>
            <td>
                <input type='hidden' value='no' name='cb_facebookshare_end'>
                <input type="checkbox" name="cb_facebookshare_end" <?php GetBinarySetting('w442fw_facebookshare_endpost_yesno'); ?> />
            </td>
        </tr>
        <tr>
            <td>Másolás védelem. CTRL+C és CTRL+X tiltása</td>
            <td>
                <input type='hidden' value='no' name='cb_copyprotect'>
                <input type="checkbox" name="cb_copyprotect" <?php GetBinarySetting('w442fw_copyprotect_yesno'); ?> />
            </td>
        </tr>
    </table>
    <h1>Kép feltöltési beállítások</h1>
    <hr />
    <table>
        <tr>
            <td>Kép átméretezés engedélyezése</td>
            <td>
                <input type='hidden' value='no' name='cb_resizeenambed'>
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
                <input type='hidden' value='no' name='cb_force_jpeg'>
                <input type="checkbox" name="cb_force_jpeg" <?php GetBinarySetting('w442fw_resizeupload_recompress_yesno'); ?> />
            </td>
        </tr>
        <tr>
            <td>PNG fájlok átalakítása JPEG formátumba</td>
            <td>
                <input type='hidden' value='no' name='cb_force_png'>
                <input type="checkbox" name="cb_force_png" <?php GetBinarySetting('w442fw_resizeupload_convertpng_yesno'); ?> />
            </td>
        </tr>
        <tr>
            <td>GIF fájlok átalakítása JPEG formátumba</td>
            <td>
                <input type='hidden' value='no' name='cb_force_gif'>
                <input type="checkbox" name="cb_force_gif" <?php GetBinarySetting('w442fw_resizeupload_convertgif_yesno'); ?> />
            </td>
        </tr>
    </table>
    <hr/>
    <input name="save" class="button button-primary" type="submit" value="Beállítások mentése">
</form>
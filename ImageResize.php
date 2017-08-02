<?php
class ImageResize
{
    public function __construct()
    {
        // Hook the function to the upload handler
        add_action('wp_handle_upload', array($this, 'ResizeUpload'));
    }

    public function ResizeUpload() 
    {
        DLog("KÉP Átméretezés kezdése");
        $resizing_enabled = ( get_option('w442fw_resizeupload_resize_yesno')=='yes') ? true : false;
        $force_jpeg_recompression = (get_option('w442fw_resizeupload_recompress_yesno')=='yes') ? true : false;

        $compression_level = get_option('w442fw_resizeupload_quality');

        $max_width  = get_option('w442fw_resizeupload_width')==0 ? false : get_option('w442fw_resizeupload_width');
        $max_height = get_option('w442fw_resizeupload_height')==0 ? false : get_option('w442fw_resizeupload_height');

        $convert_png_to_jpg = (get_option('w442fw_resizeupload_convertpng_yesno')=='yes') ? true : false;
        $convert_gif_to_jpg = (get_option('w442fw_resizeupload_convertgif_yesno')=='yes') ? true : false;
        $convert_bmp_to_jpg = (get_option('w442fw_resizeupload_convertbmp_yesno')=='yes') ? true : false;
        
        if($convert_png_to_jpg && $image_data['type'] == 'image/png' ) {
            $image_data = $this->ConvertImage( $image_data, $compression_level );
        }
        
        if($resizing_enabled || $force_jpeg_recompression) 
        {
            $fatal_error_reported = false;
            $valid_types = array('image/gif','image/png','image/jpeg','image/jpg');
            
            if(empty($image_data['file']) || empty($image_data['type'])) 
            {
                DLog("--non-data-in-file-( ".print_r($image_data, true)." )");	
                $fatal_error_reported = true;
            }
            else if(!in_array($image_data['type'], $valid_types))
            {
                DLog("--non-image-type-uploaded-( ".$image_data['type']." )");
                $fatal_error_reported = true;
            }
            
            DLog("--filename-( ".$image_data['file']." )");
            $image_editor = wp_get_image_editor($image_data['file']);
            $image_type = $image_data['type'];
            
            if($fatal_error_reported || is_wp_error($image_editor))
                DLog("--wp-error-reported");

            else
            {
                $to_save = false;
                $resized = false;
                
                // Perform resizing if required
                
                if($resizing_enabled) 
                {
                    DLog("--resizing-enabled");
                    $sizes = $image_editor->get_size();
                    if((isset($sizes['width']) && $sizes['width'] > $max_width) || (isset($sizes['height']) && $sizes['height'] > $max_height)) {
                        $image_editor->resize($max_width, $max_height, false);
                        $resized = true;
                        $to_save = true;
                        $sizes = $image_editor->get_size();
                        DLog("--new-size--".$sizes['width']."x".$sizes['height']);
                    }
                    else 
                        DLog("--no-resizing-needed");
                }
                else
                    DLog("--no-resizing-requested");
                
                // Regardless of resizing, image must be saved if recompressing
                if($force_jpeg_recompression && ($image_type=='image/jpg' || $image_type=='image/jpeg'))
                {
                    $to_save = true;
                    DLog("--compression-level--q-".$compression_level);
                }
                elseif(!$resized)
                    DLog("--no-forced-recompression");

                // Only save image if it has been resized or need recompressing
                
                if($to_save) 
                {
                    $image_editor->set_quality($compression_level);
                    $saved_image = $image_editor->save($image_data['file']);
                    DLog("--image-saved");
                }
                else 
                    DLog("--no-changes-to-save");
            }
        }
        else
            DLog("--no-action-required");

        DLog("**-end--resize-image-upload\n");
        return $image_data;
    }

    private function ConvertImage( $params, $compression_level )
    {
        $transparent = 0;
        $image = $params['file'];
        $contents = file_get_contents( $image );
        
        if ( ord ( file_get_contents( $image, false, null, 25, 1 ) ) & 4 ) $transparent = 1;
        if ( stripos( $contents, 'PLTE' ) !== false && stripos( $contents, 'tRNS' ) !== false ) $transparent = 1;
        
        $transparent_pixel = $img = $bg = false;
        if($transparent) 
        {
            $img = imagecreatefrompng($params['file']);
            $w = imagesx($img); // Get the width of the image
            $h = imagesy($img); // Get the height of the image
            //run through pixels until transparent pixel is found:
            for($i = 0; $i<$w; $i++)
            {
                for($j = 0; $j < $h; $j++)
                {
                    $rgba = imagecolorat($img, $i, $j);
                    if(($rgba & 0x7F000000) >> 24)
                    {
                        $transparent_pixel = true;
                        break;
                    }
                }
            }
        }
        
        if( !$transparent || !$transparent_pixel)
        {
            if(!$img) $img = imagecreatefrompng($params['file']);
            $bg = imagecreatetruecolor(imagesx($img), imagesy($img));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagealphablending($bg, 1);
            imagecopy($bg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
            $newPath = preg_replace("/\.png$/", ".jpg", $params['file']);
            $newUrl = preg_replace("/\.png$/", ".jpg", $params['url']);
            for($i = 1; file_exists($newPath); $i++)
            {
                $newPath = preg_replace("/\.png$/", "-".$i.".jpg", $params['file']);
            }
            
            if ( imagejpeg( $bg, $newPath, $compression_level ) )
            {
                unlink($params['file']);
                $params['file'] = $newPath;
                $params['url'] = $newUrl;
                $params['type'] = 'image/jpeg';
            }
        }
        
        return $params;
    }
}
?>
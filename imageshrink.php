<?php
/*
Plugin Name: Image Shrink
Plugin URI:  http://www.gworks.jp/2013/12/image-shrink/
Description: You will get the resize of upload image, whichever is larger of the width or height
Version: 1.0.0
Author: gworks.jp
Author URI: http://www.gworks.jp/
License: GPLv2 or later
TextDomain: Image Shrink
DomainPath: /languages/
*/



function image_shrink( $file ) {
    if ( $file['type'] == 'image/jpeg'){
        $input_image = imagecreatefromjpeg($file['file']);
        $output_image = image_shrink_resize( $input_image );
        ImageJPEG($output_image, $file['file'], 100);
    }elseif( $file['type'] == 'image/gif'){
        $input_image = imagecreatefromgif($file['file']);
         $output_image = image_shrink_resize( $input_image );
        imagegif($output_image, $file['file']);
    }elseif( $file['type'] == 'image/png') { 
        $input_image = imagecreatefrompng($file['file']);
        $output_image = image_shrink_resize( $input_image );
        imagepng($output_image, $file['file'], 0);
    }else{
        $input_image = "";
    }

    ImageDestroy($input_image);
    ImageDestroy($output_image);

    return $file; 

}

function image_shrink_resize( $input_image ) {
        $ix = ImageSX($input_image);    // Returns the width of the given image resource. 
        $iy = ImageSY($input_image);    // Returns the hight of the given image resource. 


        $request_size = intval(get_option('image_size_max_shrink'));
        if($request_size < 1){
            $request_size = 1;
        }

        if(($ix <= $request_size) and ($iy <= $request_size)){
            return $input_image;
        }

        if($ix >= $iy){
            $ox = $request_size;
            $oy = round($iy * ($ox / $ix));
        }else{
            $oy = $request_size;
            $ox = round($ix * ($oy / $iy));
        }
            

        $output_image = ImageCreateTrueColor($ox, $oy);
        $im_result = imagecopyresampled($output_image, $input_image, 0, 0, 0, 0, $ox, $oy, $ix, $iy);

        return $output_image;
}

function activate_imageshrink() {
  add_option('image_size_max_shrink', '1024');
}

function deactive_imageshrink() {
  delete_option('image_size_max_shrink');
}

function admin_init_imageshrink() {
  register_setting('imageshrink', 'image_size_max_shrink');
}

function admin_menu_imageshrink() {
  add_options_page('Image Shrink', 'Image Shrink', 'manage_options', 'imageshrink', 'options_page_imageshrink');
}

function options_page_imageshrink() {
  include(plugin_dir_path(__FILE__ ).'options.php');
}

register_activation_hook(__FILE__, 'activate_imageshrink');
register_deactivation_hook(__FILE__, 'deactive_imageshrink');

if (is_admin()) {
  add_action('admin_init', 'admin_init_imageshrink');
  add_action('admin_menu', 'admin_menu_imageshrink');
}

add_action( 'wp_handle_upload', 'image_shrink' );
?>

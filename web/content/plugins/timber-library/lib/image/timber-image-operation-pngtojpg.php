<?php

/**
 * Implements converting a PNG file to JPG.
 * Argument:
 * - color to fill transparent zones
 */
class TimberImageOperationPngToJpg extends TimberImageOperation {
    
    private $color;

    /**
     * @param string $color hex string of color to use for transparent zones
     */
    function __construct($color) {
        $this->color = $color;
    }

    /**
     * @param   string    $src_filename     the basename of the file (ex: my-awesome-pic)
     * @param   string    $src_extension    ignored
     * @return  string    the final filename to be used (ex: my-awesome-pic.jpg) 
     */
    function filename($src_filename, $src_extension = 'jpg') {
        $new_name = $src_filename . '.jpg';
        return $new_name;
    }

    /**
     * Performs the actual image manipulation,
     * including saving the target file.
     * 
     * @param  string $load_filename filepath (not URL) to source file (ex: /src/var/www/wp-content/uploads/my-pic.jpg)
     * @param  string $save_filename filepath (not URL) where result file should be saved 
     *                               (ex: /src/var/www/wp-content/uploads/my-pic.png)
     * @return bool                  true if everything went fine, false otherwise
     */
    function run($load_filename, $save_filename){
        $input = imagecreatefrompng( $load_filename );
        list( $width, $height ) = getimagesize( $load_filename );
        $output = imagecreatetruecolor( $width, $height );
        $c = self::hexrgb( $this->color );
        $color = imagecolorallocate( $output, $c['red'], $c['green'], $c['blue'] );
        imagefilledrectangle( $output, 0, 0, $width, $height, $color );
        imagecopy( $output, $input, 0, 0, 0, 0, $width, $height );
        imagejpeg( $output, $save_filename );
        return true;
    }
}

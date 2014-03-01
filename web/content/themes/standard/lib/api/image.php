<?php

namespace CodekippleWordPressTheme\Api\Image;

class Imager
{
    public $upload_dir;

    protected $options = array(
        'image' => null,
        'width' => 100,
        'height' => 100,
        'mode' => 'inset',
        'output_dir' => 'resized'
    );

    public function __construct()
    {
        $this->upload_dir = wp_upload_dir();
    }

    public function get_image($options)
    {
        $this->options = array_merge($this->options, $options);

        /*
            make a key based on the width, height and mode of the image
        */
        $key = md5($this->options['image'] . $this->options['width'] . $this->options['height'] . $this->options['mode']);

        $pathinfo = pathinfo($this->options['image']);

        // find the file path of the image
        $this->options['img_path'] = str_replace($this->upload_dir['baseurl'], $this->upload_dir['basedir'], $this->options['image']);

        if (!file_exists($this->options['img_path'])) {
            // bail if no image
            return '';
        }

        /*
            the resized image name has the width and height
            appended to the filename
        */
        $resized_filename =
            $pathinfo['filename'] .
            '-' .
            $key .
            '.' .
            $pathinfo['extension']
        ;

        /*
            set the resized images url
        */
        $resized_url =
            trailingslashit($this->upload_dir['baseurl']) .
            trailingslashit($this->options['output_dir']) .
            $resized_filename
        ;

        /*
            set the resized images path
        */
        $resized_path =
            trailingslashit($this->upload_dir['basedir']) .
            trailingslashit($this->options['output_dir']) .
            $resized_filename
        ;

        /*
            if image does not exist create it
        */
        if (!file_exists($resized_path)) {
            $this->options['ouput_filename'] = $resized_path;
            $this->create_image();
        }

        return $resized_url;

    }

    private function create_image()
    {
        $imagine = new \Imagine\Imagick\Imagine();
        $size = new \Imagine\Image\Box($this->options['width'], $this->options['height']);

        if ($this->options['mode'] == 'inset') {
            $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        } else if ($this->options['mode'] == 'outbound') {
            $mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
        }

        $imagine->open($this->options['img_path'])
            ->thumbnail($size, $mode)
            ->save($this->options['ouput_filename'])
        ;
    }
}
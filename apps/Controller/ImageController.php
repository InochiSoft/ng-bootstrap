<?php

class ImageController extends NG\Controller {
    protected $config;
    protected $cache;
    protected $session;
    protected $cookie;
    
    public function init() {
        $this->config = $this->view->config = \NG\Registry::get('config');
        $this->session = $this->view->session = new \NG\Session();
        $this->cookie = $this->view->cookie = new \NG\Cookie();
        $this->cache = $this->view->cache = new \NG\Cache();
    }
    
    public function IndexAction() {
        $requests = \NG\Route::getRequests();
        
        $session = $this->session;
        $cookie = $this->cookie;
        $cache = $this->cache;
        
        $param1 = "";
        
        if (isset($requests['param1'])){
            $param1 = $requests['param1'];
        }
        
        if ($param1){
            $assets_dir = ROOT . DS . ASSETS . DS . 'img';
            $source_dir = $assets_dir;
            $dest_dir = $assets_dir . DS . 'dest';
            
            if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);
            
            $arr_param1 = explode(".", $param1);
            $img_ext = end($arr_param1);
            $img_name = substr($param1, 0, strlen($param1) - strlen($img_ext) + 1);
            
            $img_source = $source_dir . DS . $param1;
            $img_dest = $dest_dir . DS . $img_name . '.' . $img_ext;
            
            if (file_exists($img_source)){
                $this->watermark_image($img_source, $img_dest, 9, null);
            }
        }
    }
    
    private function watermark_image($source, $destination, $quality = 9){
        if (file_exists($destination)){
            $info = getimagesize($destination);
            if ($info['mime'] == 'image/jpeg'){
                header('Content-Type: image/jpeg');
            } elseif ($info['mime'] == 'image/png'){
                header('Content-Type: image/png');
            }
        } else {
            $config = $this->config;
            $font_dir = ROOT . DS . '/assets/font/';
            $font = $font_dir . "trebucbd.ttf";
            $font_size = 14;
            $max_width = 1024;
            $text = $config["SITE_NAME"];
            
            $info = getimagesize($source);
            list($width, $height) = getimagesize($source);
                
            $canvas_width = $width;
            $canvas_height = $height;
            
            $canvas_width = $width;
            $canvas_height = $height;
                
            if ($width > $max_width){
                $selisih = $width - $max_width;
                $percent = ($selisih / $width) * 100;
                
                $canvas_width = (100 - $percent) / 100 * $width;
                $canvas_height = (100 - $percent) / 100 * $height;
            }
            
            $image = null;
            
            if ($info['mime'] == 'image/jpeg'){
                $image = imagecreatefromjpeg($source);
            } else if ($info['mime'] == 'image/png'){
                $image = imagecreatefrompng($source);
            }
            
            $canvas = imagecreatetruecolor($canvas_width, $canvas_height);
            $bg_white = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
            $textcolor = imagecolorallocate($canvas, 180, 180, 180);
            
            imagesavealpha($canvas, true);
            imagealphablending($canvas, false);
            
            imagefill($canvas, 0, 0, $bg_white);
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $canvas_width, $canvas_height, $width, $height);
            
            $font_width = imagefontwidth($font_size);
            $font_height = imagefontheight($font_size);
            $text_width = strlen($text) * $font_width;
            
            $titlebox = imagettfbbox($font_size, 0, $font, $text); 
            $text_width = $titlebox[2];
            $text_height = imagefontheight($font_size);
                
            imagettftext($canvas, $font_size, 0, ($canvas_width - $text_width) / 2, 
            (($canvas_height - $text_height) / 2) + $text_height, $textcolor, $font, $text);	
            
            header('Content-Type: image/png');
            imagepng($canvas, $destination, $quality, null);
            imagedestroy($canvas);
        }
        
        header('Cache-Control: max-age=2592000');
        readfile($destination);
    }
    
}

?>

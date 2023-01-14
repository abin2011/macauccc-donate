<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-07-09 10:49:12
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-07-17 10:51:14
 * @email             :  info@clickrweb.com
 * @description       :  圖片生成縮略圖
 */
class Imagelib{

  public static $static_CI;

  public static function resize_thumb($img_path,$width=NULL,$height=NULL,$ratio=TRUE){
    if(!empty($img_path) && file_exists($img_path)){
      @ini_set('memory_limit', '256M');
      $thumb_img=str_replace('uploads/','uploads_thumb/',$img_path);//uploads_thumb/test/test.png
      $thumb_path=str_replace(basename($img_path),'',$thumb_img);//uploads_thumb/test/
      
      list($width_orig, $height_orig) = getimagesize($img_path);
      $width=!empty($width)?$width:$width_orig;
      $height=!empty($height)?$height:$height_orig;

      if(function_exists('pathinfo')){
        $pathinfo_array=pathinfo($img_path);
        $new_thumb_img=$thumb_path.$pathinfo_array['filename'].'_'.$width.'X'.$height.'.'.$pathinfo_array['extension'];
      }else{
        $thumb_img_array=explode('.',$thumb_img);
        $new_thumb_img=$thumb_img_array[0].'_'.$width.'X'.$height.'.'.$thumb_img_array[1];
      }

      $old_image = $img_path;
      if(!file_exists($new_thumb_img) || (filemtime($old_image) > filemtime($new_thumb_img))) {

        if(file_exists($new_thumb_img)){
          @unlink($new_thumb_img);
        }

        if(!file_exists($thumb_path)){
          self::mkdir_file($thumb_path);
        }

        if($width_orig==$width && $height_orig==$height){
          $copy_result=copy($old_image, $new_thumb_img);
          if($copy_result)
            return $new_thumb_img;
        }

        if(!$ratio){ //如果要用補白就是$ratio=FALSE 調用opencart的處理方式.
          require_once APPPATH . 'third_party/opencart_image.php';
          $opencart_image = new OpencartImage($old_image);
          $opencart_image->resize($width,$height);
          $opencart_image->save($new_thumb_img);
        }else{
          $config['image_library']  = 'gd2';//(必须)设置图像库
          $config['source_image']   = $img_path;//(必须)设置原始图像的名字/路径
          $config['dynamic_output'] = FALSE;//决定新图像的生成是要写入硬盘还是动态的存在
          $config['quality']        = '90%';//设置图像的品质。品质越高，图像文件越大
          $config['new_image']      = $thumb_img;//设置图像的目标名/路径。
          $config['width']          = empty($width) || ($width>$width_orig)?$width_orig:$width;
          $config['height']         = empty($height) || ($height>$height_orig)?$height_orig:$height;
          $config['create_thumb']   = TRUE;//让图像处理函数产生一个预览图像(将_thumb插入文件扩展名之前)
          $config['thumb_marker']   ="_".$width.'X'.$height;//指定预览图像的标示。它将在被插入文件扩展名之前。例如，mypic.jpg 将会变成 mypic_thumb.jpg
          $config['maintain_ratio'] = $ratio;//维持比例
          $config['master_dim']     = 'auto';//auto, width, height 指定主轴线

          self::$static_CI = &get_instance();
          self::$static_CI->load->library('image_lib');
          self::$static_CI->image_lib->initialize($config);

          if(self::$static_CI->image_lib->resize()){
            self::$static_CI->image_lib->clear();
            return $new_thumb_img;
          }else{
            return $img_path;
          }
        }
      }

      if(file_exists($new_thumb_img)){
        return $new_thumb_img;
      }else{
        return $img_path;
      }
    }
  }

  //创建文件夹.
  public static function mkdir_file($path){
    if(!file_exists($path)){
      self::mkdir_file(dirname($path));
      @mkdir($path,0777);
      @chmod($path, 0777);
    }
  }

  #刪除原圖
  public static function delete_image($path){
    if(file_exists($path)){
      return @unlink($path);
    }
  }

  #刪除圖片文件
  public static function delete_thumb_image($path_image,$width='',$height=''){
    $image_array=explode('.',$path_image);
    $thumb_path=str_replace('uploads','uploads_thumb',$image_array[0]);
    if(!empty($width) && !empty($height)){
      $thumb_img=$thumb_path.'_'.$width.'X'.$height.'.'.$image_array[1];
      if(file_exists($thumb_img)){
        return @unlink($thumb_img);
      }
    }else{ //不存在寬度 和 高度.
      $thumb_path=$thumb_path.'_*'.$image_array[1];
      $thumb_img_arr=glob($thumb_path);
      if(!empty($thumb_img_arr) && is_array($thumb_img_arr)){
        foreach($thumb_img_arr as $thumb_img){
          if(file_exists($thumb_img)){
            return @unlink($thumb_img);
          }
        } //end foreach;
      } //end if $thumb_img_arr.
    }
  }

}
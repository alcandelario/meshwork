<?php 
    define("APPIMAGES_URL", base_url()."media/images/");
    // don't set a background image unless user is logged in
    //if($this->ion_auth->logged_in())
   // {
      if(!isset($bg_image)){$bg_image = '';}
      switch ($bg_image) {
          case "landscape":
              $image = APPIMAGES_URL."meshwork_bg_landscape.jpg";
              $id = 'bg_landscape';
              break;
          case "asset_map_view":
              $image = APPIMAGES_URL."meshwork_bg_asset_map.jpg";
              $id = 'bg_asset_map';
              break;
          default:
              $image = APPIMAGES_URL."meshwork_bg_portrait.jpg";
              $id = "bg_portrait";
              break;
      }
    //}
    
      $id = "bg_portrait";
      
      if(!isset($scalable))
      {
          $scalable = 'yes';
      }
      if(!isset($initial_scale))
      {
          $initial_scale = '1.0';
              $min_scale = '1.0';
              $max_scale = '1.0';
      }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=<?php echo $initial_scale?>, minimum-scale=<?php echo $min_scale?>, maximum-scale=<?php echo $max_scale?>, user-scalable=<?php echo $scalable?>">
    <meta name="HandheldFriendly" content="true">
    <meta name="msapplication-TileImage" content="./touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Meshwork">
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/icon">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="./touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"   href="./touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="./touch-icon-144x144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="./touch-icon-57x57-precomposed.png">     
    
    <title><?=@$title?></title>
    <?php 
          if(isset($js_header_file))
          {
              echo "<script src='".base_url()."application/js/".$js_header_file."' type='text/javascript'></script>";
          }
          if(isset($time_picker_en))
          {
              echo "<link rel='stylesheet' href='//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css' type='text/css' media='screen' />";
          }
          echo @$additionalCSS;       
          echo @$additional_head_data; 
    ?>
    <link rel="stylesheet" href="<?php echo base_url()."css/bootstrap.css"; ?>" type="text/css" media="screen" />
    
       <img src="<?=@$image?>" id="<?=@$id?>" />
  </head>
  <body>

    <?php
       // if($this->ion_auth->logged_in())
      //  {
                  $hdr = 'header';
                $class = 'header_container';
            $page_wrap = 'page-wrap';
       // }
//        else
//        {
//                  $hdr = 'div';
//                $class = '';
//            $page_wrap = '';
//        }
     ?>
    <<?=$hdr?> onclick="location.href='<?php echo base_url()?>';" class='clearfix' style='cursor: pointer'> 
        <div class='<?=$class?>'>
            
          <div class='clearfix'></div>
		  <?php 
                  $main_nav = ROOTDIR.'application'.dir_separator.'views'.dir_separator.'templates'.dir_separator.'navbar.php';
                $public_nav = ROOTDIR.'application'.dir_separator.'views'.dir_separator.'templates'.dir_separator.'public_nav.php';
                if($this->ion_auth->logged_in())
                {
                    include_once($main_nav);
                }
                else 
                {
                    include_once($public_nav);
                }
		   ?>
        </div>
    </<?=$hdr?>>
    <div class='<?=$page_wrap?>'>
    
    
    
    

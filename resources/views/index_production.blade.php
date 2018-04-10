<!DOCTYPE html>
<html lang="en">
<head>
    <?php  require "static_version.php";  ?>
  <meta charset="utf-8">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
  <meta name="viewport" content="width=device-width,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
      <meta name="x5-orientation" content="portrait">
      <title>心理咨询</title>
  <link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css"/>
      <link rel="stylesheet" type="text/css" href="<?=OSS_BUILD_PATH?>/common.min.css?v=<?=STATIC_XQZS_VERSION?>"/>
      <link rel="stylesheet" href="https://cdn.staticfile.org/Swiper/3.4.2/css/swiper.min.css">
  <script  src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/vue/2.2.6/vue.min.js"></script>
  <script src="https://cdn.staticfile.org/vue-resource/1.3.1/vue-resource.min.js"></script>
  <script src="https://cdn.staticfile.org/vue-router/2.4.0/vue-router.min.js"></script>
  <script src="https://cdn.staticfile.org/Swiper/3.3.1/js/swiper.jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/Swiper/3.4.2/js/swiper.min.js"></script>
  <script src="https://res.wx.qq.com/open/libs/weuijs/1.1.1/weui.min.js"></script>
  <script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript"></script>
  <script src="http://map.qq.com/api/js?v=2" charset="utf-8"></script>
</head>
<body>
<div id="app">

</div>



<script>

    var web={};
    web.BASE_PATH2="<?=env("BASE_PATH")?>";
    web.BASE_PATH="<?=env("BASE_PATH")?>";
    web.API_PATH=web.BASE_PATH2+"api/";
    web.IMG_PATH="/dist/";
    var now_week= "<?=date("W")?>";
    <?php
    if(!empty($guest)){echo "web.guest=true;";}
    ?>

    var vueRoutesBaseUrl="";
    var cookieVersion="";
</script>
<script src="<?=OSS_BUILD_PATH?>/rem.min.js?v=<?=STATIC_XQZS_VERSION?>"></script>
<script src="<?=OSS_BUILD_PATH?>/catch.error.min.js?v=<?=STATIC_XQZS_VERSION?>"></script>
<script src="<?=OSS_BUILD_PATH?>/cookie.min.js?v=<?=STATIC_XQZS_VERSION?>"></script>
<script src="<?=OSS_BUILD_PATH?>/xqzs.min.js?v=<?=STATIC_XQZS_VERSION?>"></script>
<script src="<?=OSS_BUILD_PATH?>/xqzs.face.min.js?v=<?=STATIC_XQZS_VERSION?>"></script>
<script src="<?=OSS_BUILD_PATH?>/weshare.min.js?v=<?=STATIC_XQZS_VERSION?>"></script>
<script src="<?=OSS_BUILD_PATH?>/build.js?v=<?=STATIC_XQZS_VERSION?>"></script>
<script src="<?=OSS_BUILD_PATH?>/calendar.min.js?v=<?=STATIC_XQZS_VERSION?>"></script>
<script src="https://cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>




<script src="<?=OSS_BUILD_PATH?>/clip_iscroll-zoom.min.js"></script>
<script src="https://cdn.staticfile.org/hammer.js/2.0.4/hammer.min.js"></script>
<script src="<?=OSS_BUILD_PATH?>/clip_jquery.photoClip.min.js"></script>
<script src="<?=OSS_BUILD_PATH?>/clip_exif.min.js"></script>


<script src="<?=OSS_BUILD_PATH?>/track.min.js?v=<?=STATIC_XQZS_VERSION?>"></script>
</body>
</html>

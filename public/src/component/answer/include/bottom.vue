<template >
    <div  class="answer_bottom_box" :style="'height:'+height+'px'">
        <router-link to='/asker/listen' >
            <span class="icon home"></span>咨询入口
        </router-link>
        <router-link to='/answer/race/list' replace :class="{on:tabOnIndex==0}">
            <span class="icon race"></span>抢答
        </router-link>
        <router-link to='/answer/ask/list'  replace :class="{on:tabOnIndex==1}" >
            <span class="icon ask"></span>待答
        </router-link>
        <router-link to='/answer/my/index' replace :class="{on:tabOnIndex==2}">
            <span class="icon my"></span>我的
        </router-link>
    </div>
</template>
<style>
    .answer_bottom_box {
        height:0.98rem;
        border-top: 0.02rem solid #eee;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        position: absolute; bottom: 0; left:0; line-height: 1; font-size: 0.31rem ; background: #fff;   color:rgba(36,37,61,1);
        width: 100%;
    }
    .answer_bottom_box a:active,.answer_bottom_box a.on{ color:RGBA(86, 196, 245,1)}
    .answer_bottom_box a{color:#999;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;
        text-align: center;
        font-size: 0.20rem;
        vertical-align: text-bottom;
    }
    .answer_bottom_box a .icon{ display: block;  width: 0.54rem;   margin: 0.03rem auto;background: url(http://oss.xqzs.cn/resources/psy/answer/tab_icon_blue.png) no-repeat; background-size: 2.16rem; height: 0.54rem  }
    .answer_bottom_box a .icon.race{  background-position: 0 0;}
    .answer_bottom_box a .icon.ask{background-position: -0.54rem 0;}
    .answer_bottom_box a .icon.my{  background-position: -1.08rem  0;}
    .answer_bottom_box a .icon.home{  background-position: -1.62rem  0;}

    .answer_bottom_box a:active .icon.race,.answer_bottom_box a.on .icon.race{ background-position: 0 -0.54rem ;}
    .answer_bottom_box a:active .icon.ask,.answer_bottom_box a.on  .icon.ask{background-position: -0.54rem -0.54rem ;}
    .answer_bottom_box a:active .icon.my,.answer_bottom_box a.on  .icon.my{   background-position: -1.08rem  -0.54rem ;}
    .answer_bottom_box a:active .icon.home,.answer_bottom_box a.on  .icon.home{   background-position: -1.62rem  -0.54rem ;}

</style>
<script>

    export default {
        props: {
            tabOnIndex: 0
        },
        data() {
            return {
                height: xqzs.equipment.tabHeight()
            }
        }
    }




</script>
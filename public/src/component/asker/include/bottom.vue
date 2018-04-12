<template >
    <div class="asker_bottom_box" :style="'height:'+height+'px'">

        <router-link to='/asker/listen' replace :class="{on:tabOnIndex==0}" >
            <span class="icon listen"></span>首页
        </router-link>
        <router-link to='/asker/expert' replace :class="{on:tabOnIndex==1}" >
            <span class="icon answer"></span>找专家
        </router-link>
        <router-link to='/asker/ask/' replace class="middle"  :class="{on:tabOnIndex==2}">
            <!--<div class="big_btn" :class="{on:tabOnIndex==2}">问</div>-->
            <span class="icon go_ask"></span>问
        </router-link>
        <router-link to='/asker/my/ask/list' replace   :class="{on:tabOnIndex==3}" >
            <span class="icon my_ask"></span>我问
        </router-link>
        <router-link to='/asker/my/index' replace  :class="{on:tabOnIndex==4}" >
            <span class="icon my"></span>我的
        </router-link>

    </div>
</template>
<style>
    .asker_bottom_box {
        height:0.95rem;
        border-top: 0.02rem solid #eee;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        position: absolute; bottom: 0; left:0; line-height: 1; font-size: 0.31rem ; background: #fff;   color:#454B54;
        width: 100%;
        padding-top: 0.03rem;
        z-index: 100;
    }
    .asker_bottom_box a:active,.asker_bottom_box a.on{ color:RGBA(86, 196, 245,1);}
    .asker_bottom_box a{color:#999;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;
        text-align: center;
        font-size: 0.20rem;
        vertical-align: text-bottom;
    }

    .asker_bottom_box .middle{ position: relative}

    .asker_bottom_box .big_btn{width:0.89rem;
        height:0.89rem;
        border-radius: 50%;
        background: #cdcdcd;
        font-size: 0.4rem;
        color: #fff;
        text-align: center;
        line-height:0.88rem;
        position: absolute;
        left:50%;
        margin-left:-0.44rem;
        top:-0.20rem;}
    .asker_bottom_box  .big_btn.on{
        background: #ff7900;
    }


    /*.asker_bottom_box a .icon{ display: block; width: 0.54rem; margin: 0.03rem auto;background: url(http://oss.xqzs.cn/resources/psy/asker/newBottom_icon.png) no-repeat; background-size: 2.16rem; height:0.54rem;  }*/
    .asker_bottom_box a .icon{ display: block; width: 0.5rem; margin: 0.06rem auto 0.03rem auto;background: url(http://oss.xqzs.cn/resources/psy/bootom_tab_bg.png) no-repeat; background-size: 2.86rem; height:0.5rem;  }

    .asker_bottom_box a .icon.listen{ background-position: 0.02rem -0.55rem;}
    .asker_bottom_box a .icon.answer{ background-position: -0.6rem -0.55rem;}
    .asker_bottom_box a .icon.go_ask{ background-position: -1.2rem -0.55rem;}
    .asker_bottom_box a .icon.my_ask{ background-position: -1.8rem  -0.55rem;}
    .asker_bottom_box a .icon.my{ background-position: -2.38rem -0.56rem;}

    .asker_bottom_box a:active .icon.listen,.asker_bottom_box a.on .icon.listen{  background-position: 0.02rem  0;}
    .asker_bottom_box a:active .icon.answer,.asker_bottom_box a.on  .icon.answer{ background-position: -0.6rem  0;}
    .asker_bottom_box a:active .icon.go_ask,.asker_bottom_box a.on  .icon.go_ask{ background-position: -1.2rem  0;}
    .asker_bottom_box a:active .icon.my_ask,.asker_bottom_box a.on  .icon.my_ask{ background-position: -1.8rem   0;}
    .asker_bottom_box a:active .icon.my,.asker_bottom_box a.on  .icon.my{ background-position: -2.38rem  0;}



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
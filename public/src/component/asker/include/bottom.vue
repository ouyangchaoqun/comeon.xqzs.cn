<template >
    <div class="asker_bottom_box" :style="'height:'+height+'px'">

        <router-link to='/asker/listen' replace :class="{on:tabOnIndex==0}" >
            <span class="icon listen"></span>首页
        </router-link>
        <router-link to='/asker/expert' replace :class="{on:tabOnIndex==1}" >
            <span class="icon answer"></span>找专家
        </router-link>
        <router-link to='/asker/ask/' replace class="middle"  >
            <div class="big_btn" :class="{on:tabOnIndex==2}">问</div>
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
        border-top: 1px solid rgba(0,0,0,0.2);
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        position: absolute; bottom: 0; left:0; line-height: 1; font-size: 0.31rem ; background: #fff;   color:rgba(36,37,61,1);
        width: 100%;
        padding-top: 0.03rem;
        z-index: 100;
    }
    .asker_bottom_box a:active,.asker_bottom_box a.on{ color:#FE7301;}
    .asker_bottom_box a{color:rgba(36,37,61,0.5);
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
        font-size: 20px;
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


    .asker_bottom_box a .icon{ display: block; width: 0.54rem; margin: 0.03rem auto;background: url(http://oss.xqzs.cn/resources/psy/asker/newBottom_icon.png) no-repeat; background-size: 2.16rem; height:0.54rem;  }
    .asker_bottom_box a .icon.listen{ background-position: 0 0;}
    .asker_bottom_box a .icon.answer{ background-position: -0.54rem 0;}
    .asker_bottom_box a .icon.my_ask{ background-position: -1.08rem  0;}
    .asker_bottom_box a .icon.my{ background-position: -1.62rem 0;}

    .asker_bottom_box a:active .icon.listen,.asker_bottom_box a.on .icon.listen{  background-position: 0  -0.54rem;}
    .asker_bottom_box a:active .icon.answer,.asker_bottom_box a.on  .icon.answer{ background-position: -0.54rem  -0.54rem;}
    .asker_bottom_box a:active .icon.my_ask,.asker_bottom_box a.on  .icon.my_ask{ background-position: -1.08rem   -0.54rem;}
    .asker_bottom_box a:active .icon.my,.asker_bottom_box a.on  .icon.my{ background-position: -1.62rem  -0.54rem;}



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
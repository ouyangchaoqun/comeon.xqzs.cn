<template>
    <div style="height: 100%;width: 100%"  class="asker_my_index_box">
        <div v-title class='hide_title'>我的</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="weui-tab__panel main">
            <div class="main">
                <div class="top" @click="revamp()">
                    <img class="img" :src="user.faceUrl" >
                    <div class="name">{{user.nickName}}</div>
                    <div  class="perfect">完善资料</div>
                    <div class="revamp"></div>

                    <span class="top_jt"></span>
                    <div class="clear"></div>

                </div>
                <div class="income_margin">
                    <router-link to="../my/ask/list" class="my_nav">
                        <div class="my_ask"></div>
                        <p>我的提问</p>
                    </router-link>
                    <router-link to="../my/cash" class="my_nav">
                        <div class="my_money"></div>
                        <p>我的余额</p>
                    </router-link>
                    <router-link to="../my/dotbean" class="my_nav" >
                        <div class="my_diandou"></div>
                        <p>我的点豆</p>
                    </router-link>
                </div>
                <router-link to="../my/listen/list" class="listen"><i></i>我的偷听</router-link>
                <router-link to="../my/answer/list" class="answer"><i></i>我的关注</router-link>
                <router-link to="../my/comment/list" class="comment"><i></i>我的评价</router-link>
            </div>
            <div class="join" @click="join()">入驻咨询师</div>
            <div class="join mt" @click="busniess()"  >商务合作</div>
        </div>
        <v-asker-bottom tabOnIndex="4"></v-asker-bottom>
        <div id="busniess" style="display: none;">
            <div class="busniess">
                <div class="title">商务合作</div>
                <div class="content">
                    “好一点”小程序有强大的粉丝变现能力，如果你有公众号或者小程序需要流量变现，请加客服微信，备注商务合作：<br><span>17816117310（同电话）</span> <span class="green">复制</span>

                </div>
                <div class="img">
                    <img src="http://oss.xqzs.cn/resources/psy/asker/buessniess.png">
                </div>
                <div class="tip">长按加客服微信</div>
            </div>
        </div>
        <div class="hide" style="position: fixed; z-index: -1">  <input type="text"   value="17816117310" id="mobile"></div>

    </div>
</template>

<script type="">

    import askerBottom from "../include/bottom.vue";
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                showLoad:false,
                couponNum:0,

            }
        },
        components: {
            "v-asker-bottom": askerBottom,
            'v-showLoad':showLoad
        },
        props:[
            'user'
        ],
        methods: {
            busniess:function () {
                xqzs.weui.dialogCustom($("#busniess").html());
                $(".busniess .green").click(function () {
                     document.getElementById("mobile").select();
                    document.execCommand("Copy");
                     xqzs.weui.tip('复制成功')
                });

            },
            getUserInfo:function(){
                let _this=this;
                xqzs.user.getUserInfo(function (user) {
                    _this.user =user;
                })

            },
            formatPrice:function (v) {
              return xqzs.string.formatPrice(v)
            },
            revamp:function () {
              window.location.href="http://wx.xqzs.cn/#/me/personal"
            },
            join: function () {

                let _this= this;
                xqzs.api.get(_this,'come/expert/query/detail/by/userId/_userId_',function (data) {
                    if (data.body.status == 1) {
                        if(data.data.data!=null){
                            let status = data.data.data.status;
                            console.log(status)
//                            const NOT_AUTHENTICATED=0;//未认证
//                            const AUTHENTICATED = 1 ;//已认证
//                            const AUTHENTICATING = 2;//认证中
//                            const AUTHENTICATING = -1;//提交中
                            if(status==2||status==-1){
                                //修改
                                xqzs.weui.tip("资料审核中，请稍后")
                            }
                            if(status==1){
                                //已认证
                                xqzs.weui.tip("您已成功入驻咨询师，请从公众号移步到咨询师。")
                            }
                            if(status==0){
                                //未认证
                                _this.goJoin()
                            }
                        }else{
                            //入驻
                            _this.goJoin()
                        }
                    }
                })
            },
            goJoin:function () {
                this.showLoad = true;
                this.$router.push("/join");
            },
            getCoupon:function () {
                let _this=this;
                xqzs.api.get(_this, 'come/user/query/coupon/_userId_',function (data) {
                    if (data.body.status == 1) {
                        _this.couponNum=data.body.data.couponCount;
                    }
                })
            }

        },
        activated: function () {
            if(!xqzs.user.isUserLogin()){
                return ;
            }
            $(".weui-tab__panel").height($(window).height() - 50);
            var obj = $(".asker_my_index_box .main a ,.join")
            xqzs.weui.active(obj);
            this.getUserInfo();
            this.getCoupon();
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
        }


    }

</script>
<style>
    .busniess { position: absolute; z-index: 1000; top:50%; left:50%; margin-left: -3rem; margin-top: -4rem; height: 8rem; width: 6rem; background: #fff; border-radius: 0.1rem; padding-top: 0.3rem; padding-bottom: 0.3rem}
    .busniess .title{ font-size: 0.36rem; font-weight: bold; text-align: center; line-height: 1.6; }
    .busniess .content{ font-size: 0.3rem;color:#333; line-height: 1.6; margin: 0.3rem;}
    .busniess .content span{ color:red; }
    .busniess .content span.green{ color:#09bb07; }
    .busniess .img{ text-align: center}
    .busniess .img img{ width: 3.8rem; height: 3.8rem; margin: 0 auto}
    .busniess .tip{ color:#999; font-size: 0.3rem; text-align: center; margin-top: 0.3rem;}


    .asker_my_index_box .top .name{
             line-height: 1;
         padding-top: 0.2rem; margin-bottom:0.13rem;font-weight: bold; font-size: 0.3rem; height: 0.3rem; overflow: hidden
    }

    .asker_my_index_box .top .perfect{color:rgba(255,255,255,1);font-size: 0.24rem;}

    .asker_my_index_box .top  .revamp{
        background: url(http://oss.xqzs.cn/resources/psy/asker/ask_fix.png) no-repeat;
        background-size: 100%;
        width: 0.3rem;
        height: 0.3rem;
        position: absolute;
        top: 1.12rem;
        left: 3.1rem;
    }
    .asker_my_index_box .top {
        height: 1.08rem;
         color: #fff;
        font-size: 0.36rem;
        background:rgba(121,207,253,1);
        background:linear-gradient(to right,rgba(121,207,253,1),rgba(157,167,251,1));
        padding: 0.5rem;
    }
    .asker_my_index_box .top .top_jt{
        background: url(http://oss.xqzs.cn/resources/psy/arrow.png);
        width: 0.32rem;
        height: 0.32rem;
        background-size: 0.32rem;
        position: absolute;
        right: 0.3rem;
        top: 0.96rem;
    }

    .asker_my_index_box .top img {
        display: block;
        float: left;
        width: 1.08rem;
        height: 1.08rem;
        border-radius: 50%;
        margin-right: 0.25rem;
        border: 0.08rem solid rgba(255,255,255,0.5);
    }
    .asker_my_index_box .main{
        margin-bottom: 0.2rem;
    }
    .asker_my_index_box .join{
        line-height: 1.02rem;
        background: RGBA(255, 255, 255, 1);
        text-align: center;
        color:RGBA(43, 53, 68, 1);
        font-size: 0.3rem;
    }
    .asker_my_index_box .join:active{
        background: #eee;
    }
    .join.mt{  margin-top: 0.2rem;}
    .asker_my_index_box .main a {
        position: relative;
        height: 1.18rem;
        line-height: 1.18rem;
        background: #fff;
        color: rgba(36, 37, 61, 1);
        font-size: 0.30rem;
        padding: 0 0.30rem;
        display: block;
        overflow: hidden;
        padding-left: 0.90rem;
    }



    .asker_my_index_box .main a:after {
        background: url(http://oss.xqzs.cn/resources/psy/arrow.png);
        width: 0.32rem;
        height: 0.32rem;
        background-size: 0.32rem;
        position: absolute;
        right: 0.30rem;
        top: 0.42rem;
        content: " "
    }

    .asker_my_index_box .main a:before {
        content: " ";
        height: 0.02rem;
        background: #eee;
        display: block;
        position: absolute;
        bottom: 0;
        left: 0.96rem;
        width: 100%;
    }
    .asker_my_index_box .main a .price {
        position: absolute;
        right: 0.75rem;
        top: 0.00rem;
        color: #56C4FE
    }

    .asker_my_index_box .main a:last-child:before {
        display: none;
    }
    .asker_my_index_box .main a.my_nav{
        width: 33.33%;
        height: 1.8rem;
        background: #fff;
        position: static;
        line-height: 1.6;
        color: rgba(36, 37, 61, 1);
         font-size: 0.28rem;
        padding: 0;
        display: block;
    }
    .asker_my_index_box .main a.my_nav:after{
        content: normal;
    }
    .asker_my_index_box .main a.my_nav:before{
        content: normal;
    }
    .asker_my_index_box .main a.my_nav div{
        width: 0.64rem;
        margin: 0 auto;
        margin-top: 0.4rem;
        height: 0.64rem;
    }
    .asker_my_index_box .main a.my_nav .my_money{
        width: 0.58rem;
        height: 0.66rem;
        background: url("http://oss.xqzs.cn/resources/psy/answer/ex_money.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
    }
    .asker_my_index_box .main a.my_nav .my_diandou{
        background: url("http://oss.xqzs.cn/resources/psy/answer/ex_dianBot.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
    }
    .asker_my_index_box .main a.my_nav .my_ask{
        background: url("http://oss.xqzs.cn/resources/psy/answer/ex_answer.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
    }
    .asker_my_index_box .main a.my_nav p{
        text-align: center;
    }

    .asker_my_index_box .main a i {
        display: block;
        position: absolute;

        top: 50%;
    }

    /*.asker_my_index_box .main a.income i {*/
        /*background-position: 0 0*/
    /*}*/
    /*.asker_my_index_box .main a.income.yhcard i{*/
        /*background: url(http://oss.xqzs.cn/resources/psy/asker/asker_left_card.png) no-repeat;*/
        /*background-size:65% 50% ;*/
        /*background-position: 40% 40%;*/
    /*}*/
    /*.asker_my_index_box .main a.income.dotCoin i{*/
        /*background: url(http://oss.xqzs.cn/resources/psy/asker/asker_left_dotCoin.png) no-repeat;*/
        /*background-size:65% 65% ;*/
        /*background-position: 40% 40%;*/
    /*}*/
    .asker_my_index_box .main a.listen i {
        width: 0.28rem;
        height: 0.4rem;
        background: url("http://oss.xqzs.cn/resources/psy/answer/ask_listen.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
        margin-top: -0.2rem;
        left: 0.44rem;

    }

    .asker_my_index_box .main a.answer i {
        width: 0.36rem;
        height: 0.34rem;
        background: url("http://oss.xqzs.cn/resources/psy/answer/ask_care.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
        margin-top: -0.17rem;
        left: 0.40rem;
    }

    .asker_my_index_box .main a.comment i {
        width: 0.36rem;
        height: 0.36rem;
        background: url("http://oss.xqzs.cn/resources/psy/answer/ask_comment.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
        margin-top: -0.18rem;
        left: 0.40rem;
    }

    .income_margin{
       margin-bottom: 0.2rem;
        display: flex;
        overflow: hidden;
    }
</style>
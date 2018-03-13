<template>
    <div style="height: 100%;width: 100%"  class="asker_my_index_box">
        <div v-title>我的</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="weui-tab__panel main">
            <div class="main">
                <div class="top" @click="revamp()">
                    <img class="img" :src="user.faceUrl" >
                    <div class="name">
                        <p style="font-weight: bold;font-size: 0.3rem">{{user.nickName}}</p>
                        <p >完善资料</p>
                        <div class="revamp"></div>
                    </div>

                    <span class="top_jt"></span>
                    <div class="clear"></div>

                </div>
                <div class="income_margin">
                    <router-link to="../my/cash" class="my_nav">
                        <div class="my_money"></div>
                        <p>我的余额</p>
                    </router-link>

                    <router-link to="../my/dotbean" class="my_nav" >
                    <div class="my_diandou"></div>
                        <p>我的点豆</p>
                    </router-link>
                    <router-link to="../my/ask/list" class="my_nav">
                    <div class="my_ask"></div>
                        <p>我的提问</p>
                    </router-link>
                </div>
                <router-link to="../my/listen/list" class="listen"><i></i>我的偷听</router-link>
                <router-link to="../my/answer/list" class="answer"><i></i>我的关注</router-link>
                <router-link to="../my/comment/list" class="comment"><i></i>我的评价</router-link>
                <a class="join" @click="join()"><i></i>入驻咨询师</a>
            </div>

        </div>
        <v-asker-bottom tabOnIndex="4"></v-asker-bottom>
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
            getUserInfo:function(){
                let _this=this;

                _this.$http({
                    method: 'GET',
                    type: "json",
                    url: web.API_PATH + 'user/find/by/user/Id/_userId_',
                }).then(function (data) {//es5写法

                    if (data.data.data !== null) {
                        _this.user = eval(data.data.data);
                    }
                }, function (error) {
                    //error
                });
            },
            formatPrice:function (v) {
              return xqzs.string.formatPrice(v)
            },
            revamp:function () {
              window.location.href="http://wx.xqzs.cn/#/me/personal"
            },
            join: function () {

                let _this= this;
                this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {//es5写法
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
                }, function (error) {

                });


            },
            goJoin:function () {
                this.showLoad = true;
                this.$router.push("/join");
            },
            getCoupon:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/user/query/coupon/_userId_' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                       _this.couponNum=data.body.data.couponCount;
                    }
                }, function (error) {

                });
            }

        },
        mounted: function () {
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
    .asker_my_index_box .top .name{
        height: 1.08rem;
        line-height: 1;
        font-size: 0.24rem;
    }
    .asker_my_index_box .top .name p{
        width: 50%;
        display: block;margin-top: 0.2rem;
    }
    .asker_my_index_box .top .name .revamp{
        background: url(http://oss.xqzs.cn/resources/psy/asker/ask_fix.png) no-repeat;
        background-size: 100%;
        width: 0.3rem;
        height: 0.3rem;
        position: absolute;
        top: 1.12rem;
        left: 3rem;
    }
    .asker_my_index_box .top {
        height: 1.08rem;
        line-height: 1.08rem;
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
        color: #FE7301
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

    .asker_my_index_box .main a.join i {
        width: 0.40rem;
        height: 0.40rem;
        background: url("http://oss.xqzs.cn/resources/psy/answer/ex_join.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
        margin-top: -0.20rem;
        left: 0.40rem;
    }
    .income_margin{
       margin-bottom: 0.2rem;
        display: flex;
        overflow: hidden;
    }
</style>
<template>
    <div style="height: 100%" class="asker_my_index_box">
        <div v-title>我的</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="weui-tab__panel main">
            <div class="main">
                <div class="top">
                    <img class="img" :src="user.faceUrl">
                    <div class="name">
                        {{user.nickName}}
                    </div>
                    <div class="clear"></div>

                </div>
                <div class="income_margin">
                <router-link to="../my/cash" class="income"><i></i>我的现金
                    <div class="price">{{formatPrice(user.balance)}}<span style="font-size: 0.24rem;color: black "> 元</span></div>
                </router-link>

                <router-link to="../my/dotbean" class="income dotCoin"><i></i>我的点豆
                    <div class="price">{{user.dianCoin}}<span style="font-size: 0.24rem;color: black "> 点</span></div>
                </router-link>
                <!--<router-link to="../my/coupon" class="income yhcard "><i></i>优惠卡券-->
                    <!--<div class="price">{{couponNum}}<span style="font-size: 0.24rem;color: black ">张</span></div>-->
                <!--</router-link>-->
                </div>
                <router-link to="../my/listen/list" class="listen"><i></i>我的偷听</router-link>
                <router-link to="../my/answer/list" class="answer"><i></i>我的关注</router-link>
                <router-link to="../my/comment/list" class="comment"><i></i>我的评价</router-link>
            </div>
            <div class="join" @click="join()">入驻咨询师</div>
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
                user:{}
            }
        },
        components: {
            "v-asker-bottom": askerBottom,
            'v-showLoad':showLoad
        },
        methods: {
            getUserInfo:function(){
                let _this=this;
                _this.showLoad = true;
                _this.$http({
                    method: 'GET',
                    type: "json",
                    url: web.API_PATH + 'user/find/by/user/Id/_userId_',
                }).then(function (data) {//es5写法
                    _this.showLoad = false;
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
    .asker_my_index_box .top {
        height: 1.08rem;
        line-height: 1.08rem;
        color: #fff;
        font-size: 0.36rem;
        background: url("http://oss.xqzs.cn/resources/psy/asker/topBackground.png") no-repeat;
        padding: 0.30rem;
        background-size: 100% 100%;
    }

    .asker_my_index_box .top img {
        display: block;
        float: left;
        width: 1.08rem;
        height: 1.08rem;
        border-radius: 50%;
        margin-right: 0.25rem;
        border: 0.02rem solid #fff
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
        background: rgba(238, 238, 238, 1);
        display: block;
        position: absolute;
        bottom: 0;
        left: 0.96rem;
        width: 100%
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

    .asker_my_index_box .main a i {
        display: block;
        background: url(http://oss.xqzs.cn/resources/psy/asker/asker_my_left_icon1.png) no-repeat;
        background-size: 0.58rem;
        width: 0.58rem;
        height: 0.58rem;
        position: absolute;
        left: 0.26rem;
        top: 50%;
        margin-top: -0.29rem;
    }

    .asker_my_index_box .main a.income i {
        background-position: 0 0
    }
    .asker_my_index_box .main a.income.yhcard i{
        background: url(http://oss.xqzs.cn/resources/psy/asker/asker_left_card.png) no-repeat;
        background-size:65% 50% ;
        background-position: 40% 40%;
    }
    .asker_my_index_box .main a.income.dotCoin i{
        background: url(http://oss.xqzs.cn/resources/psy/asker/asker_left_dotCoin.png) no-repeat;
        background-size:65% 65% ;
        background-position: 40% 40%;
    }
    .asker_my_index_box .main a.listen i {
        background-position: 0 -0.58rem;
    }

    .asker_my_index_box .main a.answer i {
        background-position: 0 -1.16rem
    }

    .asker_my_index_box .main a.comment i {
        background-position: 0 -1.74rem;
    }

    .asker_my_index_box .join {
        background: #fff;
        line-height: 1.18rem;
        margin: 0.14rem 0;
        text-align: center;
        font-size: 0.30rem;
        color: rgba(36, 37, 61, 1);
    }
    .income_margin{
        margin: 0.20rem 0;
    }
</style>
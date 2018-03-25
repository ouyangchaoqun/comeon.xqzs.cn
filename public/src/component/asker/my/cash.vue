<template>
    <div class="dotcoin_box">
        <div v-title>我的现金</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="banner banner_cash_bg">
            <div class="title">我的余额（元）</div>
            <div class="value">{{formatPrice(user.balance)||0.00}}</div>
        </div>
        <router-link to="../my/income">
            <div class="btn">
                <div class="left">提现</div>
                <div class="rightIcon"></div>
            </div>
        </router-link>
        <router-link to="./income/list">
            <div class="btn">
                <div class="left">明细</div>
                <div class="rightIcon"></div>
            </div>
        </router-link>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';

    export default {
        data() {
            return {
                user:{},
                showLoad:false
            }
        },
        mounted: function () {
            if(!xqzs.user.isUserLogin()){
                return ;
            }
             this.getUserInfo();
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
        },
        components: {
            'v-showLoad': showLoad,
        },
        methods: {
            formatPrice:function (v) {
                return xqzs.string.formatPrice(v)
            },
            getUserInfo:function(){
                let _this=this;
                xqzs.user.getUserInfo(function (user) {
                    _this.user=user;
                })
            }

        },
      


    }

</script>
<style>
    .dotcoin_box {background: #F4F4F8 ;}
    .dotcoin_box .banner{ background: #FE7A03 ;height: 3.38rem; overflow:hidden}
    .dotcoin_box .title{ margin-left: 0.32rem ;margin-top: 0.48rem; line-height: 0.42rem;color: #fff;}
    .dotcoin_box .banner.banner_cash_bg{ background: url(http://oss.xqzs.cn/resources/psy/asker/user_bg_money.png) bottom right #FE7A03 no-repeat; background-size:2.12rem;background-position-y: 1.02rem;}
    .dotcoin_box .value{font-size: 1.36rem; margin-top: 0.22rem; margin-left: 0.32rem;line-height: 2.00rem; color: #fff; }
    .dotcoin_box .btn{ height: 1.00rem; background: #fff; border-bottom: 0.02rem solid #eee;position: relative}
    .dotcoin_box .btn .left{ margin-left: 0.30rem;margin-top: 0.32rem; float: left; font-size: 0.30rem; line-height: 0.42rem;color: #4A4A4A;}
    .dotcoin_box .btn .rightIcon{background: url(http://oss.xqzs.cn/resources/psy/arrow.png) no-repeat;width: 0.32rem; height: 0.32rem;background-size: 0.32rem;position: absolute;right:0.30rem;top:50%;margin-top:-0.16rem;}

</style>

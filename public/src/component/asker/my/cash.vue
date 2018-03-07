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
            }

        },
      


    }

</script>
<style>
    .dotcoin_box {background: #F4F4F8 ;}
    .dotcoin_box .banner{ background: #FE7A03 ;height: 9.94rem; overflow:hidden}
    .dotcoin_box .title{ margin-left: 0.94rem ;margin-top: 1.41rem; line-height: 1.24rem;color: #fff;}
    .dotcoin_box .banner.banner_cash_bg{ background: url(http://oss.xqzs.cn/resources/psy/asker/user_bg_money.png) bottom right #FE7A03 no-repeat; background-size:6.235294117647059rem;background-position-y: 3rem;}
    .dotcoin_box .value{font-size: 4rem; margin-top: 0.65rem; margin-left: 0.94rem;line-height: 5.88rem; color: #fff; }
    .dotcoin_box .btn{ height: 2.94rem; background: #fff; border-bottom: 1px solid #eee;position: relative}
    .dotcoin_box .btn .left{ margin-left: 0.88rem;margin-top: 0.94rem; float: left; font-size: 0.88rem; line-height: 1.23rem;color: #4A4A4A;}
    .dotcoin_box .btn .rightIcon{background: url(http://oss.xqzs.cn/resources/psy/arrow.png) no-repeat;width: 0.94rem; height: 0.94rem;background-size: 0.94rem;position: absolute;right:0.88235rem;top:50%;margin-top:-0.47rem;}

</style>
<template>
    <div class="dotcoin_box">
        <div v-title>我的点币</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-recharge :rechargeMoney="rechargeMoney" v-show="rechargeFlag" v-on:childMessage="getFlagVal"></v-recharge>
        <div class="banner banner_bg">
            <div class="title">我的点豆</div>
            <div class="value">{{user.dianCoin}}<span style="font-size: 1.41rem">点豆</span></div>
        </div>
        <div class="btn" @click="recharge()">
                <div class="left">充值</div>
                <div class="rightIcon"></div>
        </div>
        <router-link to="./dotbean/list">
            <div class="btn">
                <div class="left">明细</div>
                <div class="rightIcon"></div>
            </div>
        </router-link>
    </div>

</template>

<script type="">
    import showLoad from '../../include/showLoad.vue';
    import Recharge from '../../asker/my/recharge.vue';
    export default {
        data() {
            return {
                user:{},
                showLoad:false,
                rechargeMoney:0,
                rechargeFlag :false,
                addMoneyVal:0
            }
        },
        mounted: function () {
            this.getUserInfo();
        },
        components: {
            'v-showLoad': showLoad,
            'v-recharge':Recharge,
        },
        methods: {
            recharge:function () {
                console.log(this.rechargeMoney)
                this.rechargeFlag = true
            },
            getFlagVal:function (val) {
                console.log(val)
                this.addMoneyVal = val.addMoneyVal;
                this.user.dianCoin=this.user.dianCoin+this.addMoneyVal;
                this.rechargeFlag  = val.rechargeFlag;
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
    .dotcoin_box {
        background: #F4F4F8 ;
    }
    .dotcoin_box .banner{ background: #FE7A03 ;height: 9.94rem; overflow:hidden}
    .dotcoin_box .banner.banner_bg{ background: url(../../../images/asker/dianCoin.png) bottom right #FE7A03 no-repeat; background-size:6.235294117647059rem; background-position-y: 3rem;}
    .dotcoin_box .title{ margin-left: 0.94rem ;margin-top: 1.41rem; line-height: 1.24rem;color: #fff;}
    .dotcoin_box .value{font-size: 4.23rem; margin-top: 0.65rem; margin-left: 0.94rem;line-height: 5.88rem; color: #fff; }
    .dotcoin_box .btn{ height: 2.94rem; background: #fff; border-bottom: 1px solid #eee;position: relative}
    .dotcoin_box .btn .left{ margin-left: 0.88rem;margin-top: 0.94rem; float: left; font-size: 0.88rem; line-height: 1.23rem;color: #4A4A4A;}
    .dotcoin_box .btn .rightIcon{background: url(../../../images/arrow.png) no-repeat;width: 0.94rem; height: 0.94rem;background-size: 0.94rem;position: absolute;right:0.88235rem;top:50%;margin-top:-0.47rem;}

</style>
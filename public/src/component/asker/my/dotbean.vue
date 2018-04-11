<template>
    <div class="dotcoin_box">
        <div v-title class='hide_title'>我的点豆</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-recharge :rechargeMoney="rechargeMoney" v-show="rechargeFlag" :user="user" v-on:childMessage="getFlagVal"></v-recharge>
        <div class="banner banner_bg">
            <div class="title">我的点豆</div>
            <div class="value">{{user.dianCoin||0}}<span style="font-size: 0.48rem"> 点豆</span></div>
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

                showLoad:false,
                rechargeMoney:0,
                rechargeFlag :false,
                addMoneyVal:0
            }
        },
        props:[
         "user"
        ],
        beforeRouteLeave (to, from, next) {
            if(this.rechargeFlag){
                this.rechargeFlag=false;
                next(false)
            }else{
                next()
            }
        },
        activated: function () {
            this.addMoneyVal=0;
            this.rechargeMoney=0;
             if(!xqzs.user.isUserLogin()){
                return ;
            }
            this.getUserInfo();
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
        },
        components: {
            'v-showLoad': showLoad,
            'v-recharge':Recharge,
        },
        methods: {
            recharge:function () {
                this.rechargeFlag = true;
            },
            getFlagVal:function (val) {
                this.addMoneyVal = val.addMoneyVal;
                this.user.dianCoin=this.user.dianCoin+this.addMoneyVal;
                this.rechargeFlag  = val.rechargeFlag;
                this.getUserInfo();
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
    .dotcoin_box {
        background: #F4F4F8 ;
    }
    .dotcoin_box .banner{ background: #FE7A03 ;height: 3.38rem; overflow:hidden}
    .dotcoin_box .banner.banner_bg{ background: url(http://oss.xqzs.cn/resources/psy/asker/dianCoin.png) bottom right #FE7A03 no-repeat; background-size:2.12rem; background-position-y: 1.02rem;}
    .dotcoin_box .title{ margin-left: 0.32rem ;margin-top: 0.48rem; line-height: 0.42rem;color: #fff;}
    .dotcoin_box .value{font-size: 1.44rem; margin-top: 0.22rem; margin-left: 0.32rem;line-height: 2.00rem; color: #fff; }
    .dotcoin_box .btn{ height: 1.00rem; background: #fff; border-bottom: 0.02rem solid #eee;position: relative}
    .dotcoin_box .btn .left{ margin-left: 0.30rem;margin-top: 0.32rem; float: left; font-size: 0.30rem; line-height: 0.42rem;color: #454B54;}
    .dotcoin_box .btn .rightIcon{background: url(http://oss.xqzs.cn/resources/psy/arrow.png) no-repeat;width: 0.32rem; height: 0.32rem;background-size: 0.32rem;position: absolute;right:0.30rem;top:50%;margin-top:-0.16rem;}

</style>


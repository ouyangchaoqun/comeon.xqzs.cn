<template>
    <div class="dotcoin_box">
        <div v-title>我的现金</div>
        <div class="banner">
            <div class="title">我的余额（元）</div>
            <div class="value">{{user.balance}}</div>
        </div>
        <router-link to="../my/income"><div class="btn"><div class="left">提现</div><div class="right"></div></div></router-link>
        <div class="btn"><div class="left">明细</div><div class="right"></div></div>

    </div>
</template>

<script type="es6">


    export default {
        data() {
            return {

            }
        },
        props:{
            user:{
                type:Object
            }
        },
        mounted: function () {

        },
        methods: {

        },
      


    }

</script>
<style>
    .dotcoin_box {
        background: #F4F4F8 ;
    }
    .dotcoin_box .banner{ background: #FE7A03 ;height: 9.94rem; overflow:hidden}
    .dotcoin_box .title{ margin-left: 0.94rem ;margin-top: 1.41rem; line-height: 1.24rem;color: #fff;}
    .dotcoin_box .value{font-size: 4rem; margin-top: 0.65rem; margin-left: 0.94rem;line-height: 5.88rem; color: #fff; }
    .dotcoin_box .btn{ height: 2.94rem; background: #fff; border-bottom: 1px solid #eee;}
    .dotcoin_box .btn .left{ margin-left: 0.88rem;margin-top: 0.94rem; float: left; font-size: 0.88rem; line-height: 1.23rem;color: #4A4A4A;}
    .dotcoin_box .btn .right{float: right;margin-right:0.9rem; margin-top: 0.94rem; width: 0.8rem; height: 1.13rem; background: url(../../../images/arrow.png) no-repeat;background-size: 100% 80%;  }

</style>
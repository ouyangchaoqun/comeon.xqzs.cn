<template >
    <div class="remind_box">
        <div v-title>入驻心理咨询师</div>
        <header>
            欢迎申请入驻“好一点”心理咨询服务平台
        </header>
        <div class="remind_com">
            <img src="http://oss.xqzs.cn/resources/psy/remind_computerImg.png" alt="">
            <div>入驻流程</div>
        </div>
        <p style="margin-bottom: 0.20rem">打开电脑端网站</p>
        <p>http://psy.xqzs.cn</p>
        <img src="http://oss.xqzs.cn/resources/psy/remind_jt.png" alt="">
        <p>点击“立即申请入驻”按钮</p>
        <img src="http://oss.xqzs.cn/resources/psy/remind_jt.png" alt="">
        <p>打开微信扫一扫二维码登录</p>
        <img src="http://oss.xqzs.cn/resources/psy/remind_jt.png" alt="">
        <p>填写咨询师入驻资料</p>
        <img src="http://oss.xqzs.cn/resources/psy/remind_jt.png" alt="">
        <p>提交审核</p>

    </div>
</template>

<script type="es6">



    export default {
        data() {
            return {

            }
        },


        mounted: function () {

        },
        methods: {


        },
        components: {

        }


    }
</script>
<style>
    .remind_box{
        background: #fff;
        text-align: center;
    }
    .remind_box header{
        height:1.80rem;
        line-height: 1.80rem;
        color:rgba(255,255,255,1);
        font-size: 0.36rem;
        background: url("http://oss.xqzs.cn/resources/psy/asker/topBackground.png") no-repeat;
        background-size: 100% 100%;
        margin-bottom: 0.62rem;
    }
    .remind_box p{
        color:rgba(36,37,61,1);
        font-size: 0.28rem;
        line-height: 1;
    }
    .remind_box .remind_com{
        position: relative;
        margin-bottom: 0.60rem;
    }
    .remind_box .remind_com img{
        width:3.30rem;
        margin:0;
    }
    .remind_box .remind_com div{
        font-size: 0.36rem;
        color:rgba(254,115,1,1);
        width:100%;
        position: absolute;
        top:25%;
    }
    .remind_box img{
        width:0.16rem;
        margin-top: 0.30rem;
        margin-bottom: 0.20rem;
    }
</style>
<template >
    <div style="height: 100%" class="wbg answer_join_reviewing_box">

        <div v-title class='hide_title'>入驻心理咨询师</div>

        <div class="reviewing">

            入驻资质审核中，请耐心等待！
        </div>

    </div>
</template>

<script type="es6">



    export default {
        data() {
            return {}
        },


        mounted: function () {

            xqzs.wx.setConfig(this);
        }


    }
</script>

<style>
    .reviewing{ text-align: center; width: 100%; background: url(http://oss.xqzs.cn/resources/psy/answer/join_reviewing.png) no-repeat  top center ;  background-size: 2.38rem; font-size:0.26rem; color: #666; padding-top: 2.89rem ; margin-top: 40%  }
</style>

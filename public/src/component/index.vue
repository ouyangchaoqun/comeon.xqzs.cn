<template >
    <div style="height: 100%">

        <div v-title class='hide_title'>心理咨询</div>

        <div style="height: 500px;" class="diididdid">
            <!--<router-link to="/asker/listen">用户</router-link>-->
            <!--<router-link to="/answer/race/list">咨询师</router-link>-->




        </div>
    </div>
</template>

<script type="es6">


    export default {
        data() {
            return {}
        },
        methods: {
            getExpert:function () {
                let _this=this;
                xqzs.api.get(_this,'come/expert/query/detail/by/userId/_userId_' ,function (data) {
                    if (data.body.status == 1) {
                        if(data.data.data&&data.data.data.id&&data.data.data.status==1){
                            let  expertId = data.data.data.id;
                            _this.expert= data.data.data;
                            cookie.set('expertId',expertId,300);
                            _this.$router.replace("/answer/race/list")
                        }else{
                            _this.$router.replace("/asker/listen")
                        }
                    }else{
                        _this.$router.replace("/asker/listen")
                    }
                })
            },
        },
        mounted: function () {

            xqzs.wx.setConfig(this);
            this.getExpert();

        }


    }
</script>
<style>
.diididdid a{ width: 100%; display: block; color:#454B54; line-height: 44px; border-bottom: 1px  solid #eee; text-align: center; background: #fff}
</style>
<template >
    <div style="height: 100%">

        <div v-title>心理咨询</div>

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
                this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {//es5写法
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
                }, function (error) {
                });
            },
        },
        mounted: function () {

            xqzs.wx.setConfig(this);
            this.getExpert();

        }


    }
</script>
<style>
.diididdid a{ width: 100%; display: block; color:rgba(36,37,61,1); line-height: 44px; border-bottom: 1px  solid #eee; text-align: center; background: #fff}
</style>
<template >
    <div class="setNickName_Box">
        <div class="joinstep_background">
            <v-showLoad v-if="showLoad"></v-showLoad>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure sure_nor" v-if="nickName==''">确定</div>
                <div class="joinSet_sure" @click="setNickName()" v-if="nickName!=''">确定</div>
            </div>
            <div class="nickName_text">
                <textarea placeholder="请输入咨询师昵称，展示给用户，不超过6个字
    例如：紫嫣姐姐" v-model="nickName" maxlength="6">{{nickName}}</textarea>
            </div>
        </div>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                nickName:cookie.get('reg_nickName')?unescape(cookie.get('reg_nickName')):'',
                edit:'',
            }
        },
        props: {
            user:{
                type:Object
            }
        },
        mounted: function () {
            this.edit= this.$route.query.edit;
            console.log()
            if(this.edit==1){
                this.getExpertInfo();
            }
        },
        methods: {
            getExpertInfo:function () {
                let expertId = cookie.get('expertId');
                this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+expertId+'/_userId_').then(function (data) {
                    if (data.body.status == 1) {
                        let showInfo = data.data.data;
                        this.nickName = showInfo.nickName
                    }
                }, function (error) {
                });
            },

            backStep:function () {
                this.$router.go(-1)
            },
            setNickName:function () {
                this.showLoad = true;
                if(this.edit==1){
                    console.log('修改')
                    let url = "come/expert/modify";
                    let msg = {
                        nickName: this.nickName,
                        userId:this.user.id,
                        id:this.user.id,
                        expertId:cookie.get('expertId')
                    }
                    this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                                console.log(response)
                            }
                        );
                }
                cookie.set('reg_nickName',escape(this.nickName),1);
                let _this = this;
                setTimeout(function () {
                    _this.$router.go(-1);
                },300)
            }
        },
        components:{
            'v-showLoad': showLoad,
        },


    }
</script>
<style>
    .setNickName_Box{
        background: #fff;
    }

    .nickName_text{
        padding:0.88235rem;
        background: #fff;
        height:2.94rem;
    }
    .nickName_text textarea{
        width:100%;
        height:100%;
        font-size: 0.8235rem;
        color:RGBA(69, 75, 84, 1);
        line-height: 1.176rem;
        display: block;
        border:0;
    }




</style>
<template >
    <div class="sign_box">
        <v-showLoad v-show="showLoad"></v-showLoad>
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure" @click="setSign()">确定</div>
            </div>
            <div class="text_area">
            <textarea v-model="sign" maxlength="25" placeholder="请输入一句话签名，展示给用户，不超过30个字
例如：
有阴影的地方就会有阳光
或：星洲易渡，心河难逾，与你共觅心河止舟
或：恋爱技巧，挽回感情，遭遇婚外情，告别前任
" id="" cols="30" rows="6">{{sign}}</textarea>
            </div>
        </div>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                sign:'',
                edit:'',
                showLoad:false

            }
        },
        props: {
            user:{
                type:Object
            }
        },
        mounted: function () {
            this.edit= this.$route.query.edit;
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
                        console.log(showInfo)
                        this.sign = showInfo.sign
                    }
                }, function (error) {
                });
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setSign:function () {
                if(this.sign==''){
                    xqzs.weui.tip('请填写签名',function () {

                    })
                }else{

                    if(this.edit==1){
                        //修改
                        console.log('修改')
                        let msg = {
                            sign: this.sign,
                            userId:this.user.id,
                            id:this.user.id,
                            expertId:cookie.get('expertId')
                        };
                        let url = "come/expert/modify";
                        this.$http.post(web.API_PATH + url, msg)
                            .then(
                                (response) => {

                                }
                            );
                    }else{
                        cookie.set('reg_sign',escape(this.sign),1)
                    }
                    this.showLoad = true;
                    this.$router.go(-1);
                }
            }
        },
        components: {
            'v-showLoad': showLoad,
        }

    }
</script>
<style>
    .sign_box{background: #fff;}
    .sign_box .text_area{background: #fff;padding:0.47rem 0.88235rem;}
    .sign_box .text_area textarea{color:rgba(69, 75, 84, 1);border:0;font-size: 0.8235rem;line-height: 1.176rem;width:100%;height:100%;}
</style>
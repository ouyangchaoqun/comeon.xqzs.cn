<template >
    <div class="sign_box">
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
    export default {
        data() {
            return {
                sign:'',
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
        },
        methods: {
            backStep:function () {
                this.$router.go(-1)
            },
            setSign:function () {
                if(this.sign==''){
                    xqzs.weui.tip('请填写签名',function () {

                    })
                }else{
                    let url = "come/expert/register";
                    let msg = {
                        sign: this.sign,
                        userId:this.user.id
                    };
                    //判断是否入驻
                    if(this.edit){
                        //修改
                        console.log('修改')
                        url = "come/expert/modify";
                        msg.expertId=cookie.get('expertId');
                        msg.userId= this.user.id;
                    }
                    //入驻
                    this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                                console.log(response)
                                this.$router.go(-1);
                            }
                        );
                }


            },
        }

    }
</script>
<style>
    .sign_box{background: #fff;}
    .sign_box .text_area{background: #fff;padding:0.47rem 0.88235rem;}
    .sign_box .text_area textarea{border:0;font-size: 0.8235rem;line-height: 1.176rem;width:100%;height:100%;}
</style>
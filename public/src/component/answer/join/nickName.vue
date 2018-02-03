<template >
    <div class="setNickName_Box">
        <div class="joinstep_background">
            <v-showLoad v-if="showLoad"></v-showLoad>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure" @click="setNickName()">确定</div>
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
                nickName:'',
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
            console.log(this.edit)
        },
        methods: {

            backStep:function () {
                this.$router.go(-1)
            },
            //判断是否入驻
            isJoin:function () {

            },
            setNickName:function () {
                if(this.nickName==''){
                    xqzs.weui.tip('请填写昵称',function () {

                    })
                }else{
                    let url = "come/expert/register";
                    let msg = {
                        nickName: this.nickName,
                        userId:this.user.id,
                        id:this.user.id
                    };
                    //判断是否入驻
                    console.log(this.edit)
                    if(this.edit==1){
                        console.log(this.edit+'ssssssssssss')
                        //修改
                        console.log('修改')
                        url = "come/expert/modify";
                        msg.expertId=cookie.get('expertId');
                    }
                    //入驻
                    this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                                console.log(response)
                                this.showLoad= false;
                                this.$router.go(-1);
                            }
                        );
                }
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
        color:RGBA(69, 75, 84, 0.6);
        line-height: 1.176rem;
        display: block;
        border:0;
    }




</style>
<template >
    <div class="setNickName_Box">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure" @click="setNickName()">确定</div>
        </div>
        <div class="nickName_text">
            <textarea placeholder="请输入咨询师昵称，展示给用户，不超过6个字
例如：紫嫣姐姐" @input="changeNickName()" v-model="nickName" maxlength="6"></textarea>
        </div>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                nickName:''
            }
        },
        mounted: function () {

        },
        methods: {
            changeNickName:function () {
                console.log(this.nickName)
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setNickName:function () {
                let _this = this;
                if(_this.nickName==''){
                    xqzs.weui.tip('请填写昵称');
                    return
                }else{
                    cookie.set('register_nickname',_this.nickName,1)
                    _this.$router.go(-1)
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
        background: RGBA(69, 75, 84, 0.03);
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
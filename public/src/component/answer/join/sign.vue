<template >
    <div class="sign_box">
        <div v-title>入驻心理咨询师</div>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure" @click="setSign()">确定</div>
        </div>
        <div class="text_area">
            <textarea v-model="sign" maxlength="25" @input="changeSign()"  placeholder="请输入一句话签名，展示给用户，不超过30个字
例如：
有阴影的地方就会有阳光
或：星洲易渡，心河难逾，与你共觅心河止舟
或：恋爱技巧，挽回感情，遭遇婚外情，告别前任
" id="" cols="30" rows="6">{{sign}}</textarea>
        </div>
    </div>
</template>

<script type="es6">
    export default {
        data() {
            return {
                sign:'',
            }
        },
        mounted: function () {

            xqzs.wx.setConfig(this);
        },
        methods: {
            changeSign: function () {
                console.log(this.sign)
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setSign:function () {
                let _this = this;
                if(_this.sign==''){
                    xqzs.weui.tip("请填写个人签名");
                    return
                }
                let msg={
                    sign:_this.sign
                };
                _this.$http.post(web.API_PATH + 'come/expert/register', msg)
                    .then(
                        (response) => {
                            _this.$router.go(-1)
                        }
                    );

            },
        }

    }
</script>
<style>
    .sign_box{background: RGBA(69, 75, 84, 0.03);}
    .sign_box .text_area{background: #fff;padding:0.47rem 0.88235rem;}
    .sign_box .text_area textarea{border:0;font-size: 0.8235rem;line-height: 1.176rem;width:100%;height:100%;}
</style>
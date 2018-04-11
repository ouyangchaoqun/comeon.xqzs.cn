<template>
    <div style="background: #fff;">
        <div class="joinmobile_box">
            <div v-title class='hide_title'>入驻心理咨询师</div>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure sure_nor" v-if="!isAllInput">确定</div>
                <div class="joinSet_sure" @click="submit()" v-if="isAllInput">确定</div>
            </div>
            <div style="height:0.22rem;background: RGBA(69, 75, 84, 0.05)"></div>
            <div class="validate_box">
                <div class="mobile__box">
                    <div class="validate_div">
                        <div class="joinmobile_input">
                            <input class="input_phone" type="tel" oninput="if(value.length>11)value=value.slice(0,11)"
                                   placeholder="请输入您的手机号" v-model="mobile"/>
                            <p id="errorMobile" v-if="!isMobileRight&&isShowErrorMobileMsg">手机格式错误</p>
                        </div>
                    </div>
                    <div class="joinmobile_code">
                        <div class="code_left">
                            <input class="input_code" type="tel" placeholder="请输入您收到的验证码" maxlength="4" v-model="code"/>
                        </div>
                        <div class="code_right" v-if="!isMobileRight||isGetingCodeIn">
                            {{getCodeBtnText}}
                            <!--<button href="javascript:;" id="_phonebtn" -->
                            <!--class="weui-btn weui-btn_plain-primary "-->
                            <!--:class="{'weui-btn_plain-disabled':!isMobileRight||isGetingCodeIn}">-->
                            <!--</button>-->
                        </div>
                        <div class="code_right code_right_can" @click="getCode()" v-if="isMobileRight&&!isGetingCodeIn">
                            {{getCodeBtnText}}
                        </div>


                        <p id="errorCode" v-if="isShowErrorCodeMsg">验证码错误</p>
                        <p id="message" v-if="isShowMessage">{{message}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isGetingCodeIn: false,
                interValObj: null,
                time: 60,
                getCodeBtnText: "获取验证码",
                isShowMessage: false,
                isMobileRight: false,
                isErrorCode: false,
                mobile: "",
                code:"",
                message: "验证码已发送，请注意查收短信",
                isShowErrorMobileMsg: false,
                isShowErrorCodeMsg: false,
                isAllInput:false,
                codeError:false,
                canNext:false,

            }
        },
        activated: function () {
            let _this = this;
            $('.mobile__box').click(function (e) {
                e.stopPropagation();
            });
            $('.input_phone').keyup(function () {
                var length = _this.mobile.length;
                if (length == 11) {
                    if (xqzs.string.checkUserPhoneReg(_this.mobile)) {
                        _this.isShowErrorMobileMsg =false;
                        _this.isMobileRight = true;
                    } else {
                        _this.isMobileRight = false;
                        _this.isShowErrorMobileMsg = true;
                        _this.isAllInput =false;
                    }
                } else {
                    _this.isMobileRight = false;
                    _this.isShowErrorMobileMsg = false;
                    _this.isAllInput =false;
                }
                console.log(_this.isMobileRight)
            })
            $('.input_code').keyup(function () {
                event.stopPropagation();
                var len = $(".input_code").val().length;
                if (len == 4) {
                    _this.isErrorCode = false;
                    if(_this.isMobileRight===true){
                        _this.isAllInput =true;
                    }

                } else {
                    _this.isAllInput =false;
                    _this.isErrorCode = true
                }
            });

            //获得焦点
            $('.input_phone').focus(function () {
                event.stopPropagation();
                _this.isShowErrorMobileMsg = false;
            });
            xqzs.wx.setConfig(_this);
        },
        methods: {
            backStep:function () {
                this.$router.go(-1)
            },
            submit:function () {
                let _this = this;
                if(_this.isAllInput==true){
                    _this.$http.post(web.API_PATH + 'user/update/mobile/by/code/mobile/_userId_', {mobile: _this.mobile,code:_this.code}).then(response => {
                        if (response.data.status === 1) {
                            xqzs.weui.toast("success","验证成功",function () {
                                if(_this.edit==1){
                                    let url = "come/expert/modify";
                                    let msg = {
                                        userId:this.user.id,
                                        id:this.user.id,
                                        mobile:this.mobile,
                                        expertId:cookie.get('expertId')
                                    };
                                    _this.$http.post(web.API_PATH + url, msg)
                                        .then(
                                            (response) => {

                                            }
                                        );
                                }
                                cookie.set('reg_mobile',_this.mobile,1)
                                _this.$router.go(-1)
                            })
                        } else   {
                            if(response.data.status === -2 || response.data.status === -3){
//                                clearInterval(_this.interValObj);
                                _this.isShowErrorCodeMsg =true;
                                _this.isShowMessage = false;
                                _this.codeError=true;

                            }
                            //-1 手机号码为空，-4 验证码为空 ，-5 手机格式错误，-2 验证码过期，-3 验证码不存在

                        }

                    }, response => {
                        // error
                    });
                }

            },

            getCode: function () {
                let _this = this;
                if (_this.isMobileRight&&!_this.isGetingCodeIn) {
                    _this.$http.post(web.API_PATH + 'base/verification/code/expert/get/code', {mobile: _this.mobile}).then(response => {
                        if (response.data.status === 1) {
                            _this.codeError=false;
                            _this.interValObj = setInterval(function () {
                                _this.setRemainTime();
                            }, 1000);
                            _this.isShowMessage = true;
                            _this.isShowErrorCodeMsg=false;
                            _this.message = "验证码已发送，请注意查收短信";
                        } else if (response.data.status === -1) {
                            _this.isShowMessage = true;
                            _this.isShowErrorCodeMsg=false;
                            _this.message = "请勿频繁获取验证码";
                        } else if (response.data.status === -2) {
                            _this.isShowMessage = true;
                            _this.isShowErrorCodeMsg=false;
                            _this.message = "手机号码不能为空";
                        } else if (response.data.status === -3) {
                            _this.isShowMessage = true;
                            _this.isShowErrorCodeMsg=false;
                            _this.message = "手机号码格式不正确";
                        }else if (response.data.status === -5) {
                            _this.isShowMessage = true;
                            _this.isShowErrorCodeMsg=false;
                            _this.message = "手机号码已经被注册";
                        }

                    }, response => {
                        // error
                    });
                }
            },
            setRemainTime: function () {
                let _this = this;
                if (_this.time == 0) {
                    clearInterval(_this.interValObj);
                    _this.getCodeBtnText = "获取验证码";
                    _this.isGetingCodeIn = false;
                    _this.isShowMessage = false;
                    _this.isShowErrorCodeMsg=false;
                    _this.time = 60;
                } else {

                    _this.time--;
                    _this.getCodeBtnText = '重获验证码' + '(' + _this.time + ')';
                    _this.isGetingCodeIn = true;
                    if(_this.codeError){
                        _this.isShowMessage = false;
                        _this.isShowErrorCodeMsg=true;
                    }else{
                        _this.isShowMessage = true;
                        _this.isShowErrorCodeMsg=false;
                    }


                }

            }
        }
    }
</script>
<style type="text/css">
    .code_left{
        width:50%;
        height:100%;
        float: left;
        background: #fff;
        border-radius: 0.10rem;
        padding-left: 0.22rem;
    }
    .code_left input{
        font-size: 0.30rem;
    }
    .code_right{
        width: 35%;
        float: right;
        text-align: center;
        font-size: 0.30rem;
        color:RGBA(255, 255, 255, 1);
        background: RGBA(86, 196, 245, 0.4);
        border-radius: 0.10rem;
    }
    .code_right_can{
        background: RGBA(86, 196, 245,1);
    }
    .code_right button{
        border:0;
    }
    .joinmobile_box{
        background: RGBA(69, 75, 84, 0.03);
        height:100%;
    }
    .joinmobile_box .validate_box {
        padding: 0.40rem 0.30rem 0 0.30rem;
    }

    .joinmobile_box .validate_top {
        margin-bottom: 0.64rem;
    }

    .joinmobile_box .validate_top h1 {
        font-size: 0.36rem;
        color: rgba(36,37,61,1); text-align: center;
        margin-bottom: 0.36rem;
    }

    .joinmobile_box .validate_top p {
        font-size: 0.30rem;
        color: #878686;
        text-align: center;
    }

    .joinmobile_box input {
        outline: none;
        font-size: 0.30rem;
        color:RGBA(69, 75, 84, 1);
        height:100%;
        width:100%;
    }

    .joinmobile_box .input_code {
        font-size: 0.30rem;
        width: 100%;
        color: rgba(36,37,61,1);
        height:100%;
    }

    .joinmobile_box .validate_div {
        margin-bottom: 0.41rem;
    }

    .joinmobile_box .joinmobile_input {
        border-radius: 0.10rem;
        background: #fff;
        height:0.96rem;
        line-height: 0.96rem;
        padding-left: 0.22rem;
        position: relative;
    }
    .joinmobile_box .joinmobile_code {
        height:0.96rem;
        line-height: 0.96rem;
        position: relative;
    }

    #errorMobile, #errorCode {
        font-size: 0.22rem;
        color: #fc0303;
        position: absolute;
        left: 0.30rem;
        bottom:-0.68rem;
    }

    #message {
        font-size: 0.22rem;
        color: #999;
        position: absolute;
        bottom:-1.02rem;
        left: 0.30rem;

    }
    .joinmobile_box  input:-ms-input-placeholder {
        color: rgba(0, 0, 0, 0.2);
    }

    .joinmobile_box input::-webkit-input-placeholder {
        color: rgba(0, 0, 0, 0.2);
    }

    .joinmobile_box  input::-moz-placeholder {
        color: rgba(0, 0, 0, 0.2)
    }

    .joinmobile_box input:-moz-placeholder {
        color: rgba(0, 0, 0, 0.2);
    }
</style>

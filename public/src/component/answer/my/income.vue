<template >
    <div style="height: 100%" class="answer_my_income_box wbg">

        <div v-title class='hide_title'>我的余额</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="my_income">
            <div class="img"></div>
            <div class="my_income_txt">我的余额</div>
            <div class="money">￥{{formatPrice(user.balance)}}</div>
            <div class="get_money" @click="showOut()">提现</div>
            <div class="income_list" @click="incomeList()"><span>余额明细</span></div>
        </div>
        <div class="moneyOut_box" v-if="isOut" @click="hideOut()">
            <div class="money_dialog" @click.stop>
                <div class="money_dialog_title">
                    1-3个工作日内将转账至您的微信钱包
                </div>
                <div class="money_dialog_detail">
                    <div class="detail_top">提现金额</div>
                    <div class="detail_input">
                        <span>￥</span>
                        <input type="number" @input="getMoney()" v-model="moneyVal">
                        <img @click="clearMoneyVal()" src="http://oss.xqzs.cn/resources/psy/clear_moneyVal_img.png" alt="">
                    </div>
                    <div class="detail_warn">
                        <span v-if="!isWarn&&!isSmall">
                            可用余额¥{{formatPrice(user.balance)}}
                        </span>
                        <span v-if="isWarn" class="warn_red">
                            金额已超过可提现余额
                        </span>
                        <span v-if="isSmall" class="warn_red">
                            提现金额至少1元
                        </span>
                    </div>
                    <div v-if="moneyVal<1" class="disabled_btn weui-btn weui-btn_disabled">
                        确认提现
                    </div>
                    <div v-if="moneyVal>=1" class="dialog_btn weui-btn weui-btn_primary" @click="withdraw()">
                        确认提现
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                showLoad:false,
                income:0,
                isOut:false,
                isWarn:false,
                isSmall:false,
                moneyVal:'',
                user:''

            }
        },
        components: {
            'v-showLoad': showLoad
        },

        activated: function () {
            if (!xqzs.user.isUserLogin()) {
                return;
            }
            this.getUserInfo()
            xqzs.wx.setConfig(this);

        },
        methods:{
            goQuestion:function () {
                this.$router.push("/answer/ask/list")
            },
//            getIncome:function () {
//                let expertId = cookie.get('expertId')
//                let _this= this;
//                _this.showLoad=true;
//                _this.$http.get(web.API_PATH + 'come/expert/query/income/'+expertId+'/_userId_' ).then(function (data) {//es5写法
//                    _this.showLoad=false;
//                    if (data.body.status == 1) {
//                        _this.income= data.body.data.inCome
//                    }
//                }, function (error) {
//                    _this.showLoad=false;
//                });
//
//            },
            getUserInfo:function () {
                let _this = this;
                _this.$http({
                    method: 'GET',
                    type: "json",
                    url: web.API_PATH + 'user/find/by/user/Id/_userId_',
                }).then(function (data) {//es5写法
                    if (data.data.data !== null) {
                        _this.user = eval(data.data.data);
                        console.log( _this.user )
                    }
                }, function (error) {
                    //error
                });
            },
            formatPrice:function(price){
                return  xqzs.string.formatPrice(price)
            },
            incomeList:function () {
                this.$router.push("./income/list")
            },
            hideOut:function () {
                this.isOut = false
            },
            showOut:function () {
                this.isOut = true
            },
            getMoney:function () {
                if(Number(this.moneyVal)>Number(this.user.balance)){
                    this.isWarn = true;
                    this.isSmall = false;
                }else if(Number(this.moneyVal)<1){
                    this.isSmall = true
                    this.isWarn = false;
                }else{
                    this.isWarn = false;
                    this.isSmall = false;
                }
            },
            clearMoneyVal:function () {
                this.moneyVal = '';
            },
            withdraw:function () {
                let _this = this;
                let msg = {
                    userId :_this.user.id,
                    money  : _this.moneyVal*100
                }
                if(this.isWarn){
                    xqzs.weui.tip("提现金额超限");
                    return;
                }
                _this.showLoad = true;
                _this.$http.post(web.API_PATH+'user/withdraw',msg).then(function (data) {

                    if(data.data.status==1){
                        _this.getUserInfo()
                        setTimeout(function () {
                            _this.showLoad = false;
                            _this.isOut = false;
                            _this.moneyVal = '';
                            xqzs.weui.tip("提交成功，等待审核");
                        },500)


                    }
                })
            },
        }


    }
</script>

<style>
.answer_my_income_box .my_income .img{background:url(http://oss.xqzs.cn/resources/psy/asker/income.png) no-repeat; width: 2.11rem; height: 2.02rem; background-size: 100% 100%; margin: 0 auto ; margin-top:1.26rem; }
.answer_my_income_box .my_income{ line-height: 1}
.answer_my_income_box .my_income .my_income_txt{color:#333; width: 100%; text-align: center; margin-top: 0.26rem;font-size: 0.30rem; }
.answer_my_income_box .my_income .money{color:#56C4FE ; margin-top: 0.24rem; text-align: center; width: 100%; font-size: 0.72rem; line-height: 1}

.answer_my_income_box  .my_income .get_money{color:rgba(255,255,255,1); margin:0 auto;line-height:0.72rem;text-align: center; font-size: 0.36rem; margin-top: 1.06rem;background: rgba(255,157,24,1);border:0.02rem solid rgba(229,135,6,1);border-radius: 0.36rem;width:42%;}
.answer_my_income_box  .my_income .get_money:active{background:rgba(229,135,6,1) ;}
.answer_my_income_box .my_income .income_list{ position:absolute;bottom:2.04rem;text-align: center;  width: 100%}
.answer_my_income_box .my_income .income_list span{padding-bottom:0.12rem; border-bottom: 0.02rem solid #999; font-size: 0.26rem; color:#999;border-radius: 0.02rem}

.answer_my_income_box .moneyOut_box{width: 100%;height:100%;background: rgba(239,238,244,1);position: absolute;top:0; bottom:0;}
.answer_my_income_box .money_dialog{  margin: 0.42rem 0.30rem;margin-bottom: 0;background: rgba(255,255,255,1)}
.answer_my_income_box .money_dialog_title{line-height: 1.04rem;font-size: 0.26rem;color:rgba(255,157,24,1);background: rgba(250,250,250,1);padding-left: 0.60rem;}
.answer_my_income_box .money_dialog_detail{padding:0.46rem 0.60rem;color:rgba(51,51,51,1);}
.answer_my_income_box .money_dialog_detail .detail_top{margin-bottom: 0.56rem;font-size: 0.28rem;}
.answer_my_income_box .money_dialog_detail .detail_input{margin-bottom: 0.40rem;padding-bottom: 0.12rem;height:1.04rem;border-bottom: 0.02rem solid #eee;}
.answer_my_income_box .money_dialog_detail .detail_input span{font-size: 0.60rem;color:rgba(51,51,51,1);line-height: 1;float: left;margin-right: 0.20rem;}
.answer_my_income_box .money_dialog_detail .detail_input input{outline: none;height:100%;font-size: 0.82rem;color: rgba(255,157,24,1);text-shadow: 0 0 0 rgba(51,51,51,1);-webkit-text-fill-color: transparent;width:70%;float: left}
.answer_my_income_box .money_dialog_detail .detail_warn{color:rgba(118,118,118,1);font-size: 0.26rem;line-height: 1; margin-bottom: 0.40rem;}
.answer_my_income_box .money_dialog_detail .detail_warn .warn_red{color:rgba(255,51,0,1)}
.answer_my_income_box .dialog_btn{background: rgba(255,157,24,1);line-height: 0.90rem;font-size: 0.36rem;color:rgba(255,255,255,1);border-radius: 0.10rem;text-align: center;}
.answer_my_income_box .disabled_btn{line-height: 0.90rem;font-size: 0.36rem;color:rgba(255,255,255,1);border-radius: 0.10rem;text-align: center;
}
.answer_my_income_box .dialog_btn:active{background: rgba(229,135,6,1);}
.answer_my_income_box .detail_input img{width:0.30rem;float: right;margin-top: 0.34rem;}
</style>
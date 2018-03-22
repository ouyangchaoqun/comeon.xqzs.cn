<template>
    <div class="recharge_box" >
        <div class="recharge_title">
            请选择充值点豆
        </div>
        <div class="items_box">
            <div class="items" v-for="(item,index) in items" @click="select(index)" :class="{selected:item.c==1}">
                <div class="price" :class="{nogift:item.couponCount==0}">{{item.dianCoin}} 点豆</div>
                <div class="dou" :class="{nogift:item.couponCount==0}">{{parseInt(item.money)}} 元</div>
                <div class="gift" v-if="item.couponCount!=0">赠送{{item.couponCount}}张偷听卡</div>
            </div>
        </div>
        <div class="cash">现金余额可使用<span style="color: #FB640A"> {{balance||0.00}} </span>元
            <div class="cash_right" :class={no:!isUseIncome} @click="useIncome()"></div>
        </div>
        <div class="rechar_btn" @click="doPay()">立即支付（{{pay}} 元）</div>
        <div class="question" >
            <img src="http://oss.xqzs.cn/resources/psy/asker/question_icon.png" alt="">
            <span @click="showTips">充值须知</span>
        </div>
        <div class="mask" v-if="isTips"></div>
        <div class="tips" v-if="isTips">
            <div class="close" @click="closeTips"></div>
            <div class="tip_title">充值须知</div>
            <div class="content">
                <p>1、免费偷听问答不扣点豆，已支付问题可重复免费听；</p>
                <p>2、点豆仅限平台内偷听、向专家提问，充值后不可提现；</p>
                <!--<p>3.请在有效期内使用偷听卡；</p>-->
                <p>3、如有疑问，请在微信对话窗口反馈。</p>
            </div>
            <div class="bottom"></div>

        </div>
    </div>
</template>
<script type="">
    export default {
        data() {
            return {
                checkIndex: 0,
                items: [],
                isTips: false,
                pay: 0,
                income: 0,
                isUseIncome: false,
                backUrl:'',
                havedianCoin:0,
                balance:0,
            }

        },
        props:[
            'rechargeMoney',
            'rechargeFlag',
            'user'
        ],
        mounted: function () {
            let _this=this;
            _this.getUserInfo();
            _this.$nextTick(function () {
                _this.getRechargeConfig();
            })

        },
        methods: {
            getUserInfo:function(){
                let _this=this;
                if(_this.user){
                    if( _this.user!=''|| _this.user!=undefined){
                        _this.balance = _this.user.balance
                        _this.havedianCoin = _this.user.dianCoin;
                    }
                }else{
                    xqzs.user.getUserInfo(function (user) {
                        _this.user =user;
                        if( _this.user!=''|| _this.user!=undefined){
                            _this.balance = _this.user.balance
                            _this.havedianCoin = _this.user.dianCoin;
                        }

                    })
                }


            },
            select: function (index) {
                this.checkIndex = index;
                let item = this.items[index];
                for (var i = 0; i < this.items.length; i++) {
                    this.items[i].c = 0;
                    this.$set(this.items, i, this.items[i])
                }
                item.c = 1;
                this.$set(this.items, index, item)
                if (this.isUseIncome) {
                    if (Number(this.balance) >= Number(item.money)) {
                        this.pay = 0;
                    } else {
                        this.pay = (Number(item.money) - Number(this.balance)).toFixed(2)
                    }
                }
                else {
                    this.pay = item.money;
                }
            },
            initSelect:function () {
                let _this=this;
                var needMoney=Number(_this.rechargeMoney);
                var x=needMoney-(_this.havedianCoin);//差值
                for(var i=0;i<_this.items.length;i++){
                    if(Number(_this.items[i].dianCoin)>=x){
                        _this.select(i)
                        break;
                    }
                }
                if(_this.rechargeMoney==0){
                    _this.select(0)
                }
            },
            showTips: function () {
                this.isTips = true;
            },
            closeTips: function () {
                this.isTips = false;
            },
            useIncome: function () {
                this.isUseIncome = !this.isUseIncome;
                this.select(this.checkIndex)
            },
            getRechargeConfig: function () {
                let _this = this;
//                console.log('获取充值信息')
                _this.$http.get(web.API_PATH + 'come/user/query/recharge/config').then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.items = data.body.data;
                        if( _this.items!=''|| _this.items!=[]){
                            _this.initSelect()
                        }
                    }
                }, function (error) {
                });
            },
            _doPay:function (isUseBalance) {
                let _this = this;
                let msg = {
                    userId: _this.user.id,
                    configId: _this.items[_this.checkIndex].id,
                    isUseBalance: isUseBalance,
                    amount: _this.pay,
                    channelOpenId:xqzs.localdb.get("channelopenid")
                };

                console.log(msg)

                _this.$http.put(web.API_PATH + "come/user/create/recharge", msg)
                        .then(function (bt) {
                            if (bt.data && bt.data.status == 1) {
                                let result = bt.data.data;
                                if (result.resultCode == 1) {
                                    xqzs.eventLog.visit('comeon_pay_balance_success');
                                    xqzs.weui.toast("success","支付成功", function () {
                                        _this.$emit(
                                                'childMessage',{
                                                    rechargeFlag:false
                                                }
                                        )
                                    });

                                }else{
                                    xqzs.wx.pay.pay(result, function () {

                                    }, function () {//success
                                        xqzs.eventLog.visit('comeon_pay_wxpay_success');
                                        xqzs.weui.toast("success","支付成功", function () {
                                            _this.$emit(
                                                'childMessage',{
                                                    rechargeFlag:false,
                                                }
                                            )
                                        });
                                    }, function () {//error

                                    })
                                }

                            }
                        });

            },
            doPay: function () {

                xqzs.eventLog.visit('comeon_pay')
                let _this = this;
                if (this.isUseIncome) {
                        _this._doPay(1)
                }else{
                    _this._doPay(0)
                }


            }
        }
    }

</script>
<style>
    .recharge_box {
        background: #fff;
        height: 100%;
        position: absolute;
        top:0;
        width: 100%;
        z-index: 55;
    }

    .recharge_box .recharge_title {
        margin-top: 0.42rem;
        margin-left: 0.32rem;
        font-size: 0.30rem;
        color: #29293E;
        line-height: 0.30rem;
        font-weight: bold;
    }

    .recharge_box .items_box {
        margin-top: 0.10rem;
        overflow: hidden;
        padding: 0 4.2%
    }

    .recharge_box .items_box .items {
        margin-top: 0.24rem;
        width: 46.5%;
        height: 1.64rem;
        background: #F7F7F7;
        border-radius: 0.2rem;
        float: left;
        text-align: center;
        border: solid 0.02rem #fff;
    }

    .recharge_box .items_box .items:nth-of-type(2n) {
        margin-left: 3.8%;
    }

    .recharge_box .items_box .items .price {
        margin-top: 0.10rem;
        font-size: 0.40rem;
        line-height: 0.56rem;
    }

    .recharge_box .items_box .items .price.nogift {
        margin-top: 0.34rem
    }

    .recharge_box .items_box .items .dou {
        line-height: 0.40rem;
        font-size: 0.28rem;
        margin-top: 0.04rem;
        color:#9B9B9B
    }

    .recharge_box .items_box .items .gift {
        line-height: 0.34rem;
        color: #56C4FE;
        font-size: 0.24rem;
        margin-top: 0.07rem
    }

    .recharge_box .cash {
        width: 6.56rem;
        height: 1.00rem;
        background: rgba(228, 227, 227, 0.5);
        margin: 0 auto;
        margin-top: 0.40rem;
        border-radius: 0.2rem;
        line-height: 1.00rem;
        font-size: 0.30rem;
        padding-left: 0.30rem;
    }

    .recharge_box .rechar_btn {
        width: 5.20rem;
        height: 0.88rem;
        background: #56C4FE;
        color: #fff;
        font-size: 0.36rem;
        line-height: 0.88rem;
        border-radius: 2.12rem;
        margin: 0 auto;
        margin-top: 0.70rem;
        text-align: center;
    }

    .recharge_box .question {
        margin-top: 1.19rem;
        font-size: 0.30rem;
        color: #9B9B9B;
        text-align: center
    }
    .recharge_box .question img{
        width:0.30rem;
        vertical-align: middle;
    }
    .recharge_box .question span{
        border-bottom: 0.02rem solid rgba(228, 227, 227, 0.5);
        padding: 0 0 0.03rem 0.07rem;
    }
    .mask {
        position: absolute;
        height: 100%;
        top: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 5;
        width: 100%;
    }

    .recharge_box .tips {
        width: 5.44rem;
        height: 6.46rem;
        position: absolute;
        z-index: 10;
        background: #fff;
        top: 24%;
        left: 50%;
        margin-left: -2.72rem;
        border-radius: 0.20rem
    }

    .recharge_box .items_box .items.selected {
        border: #56C4FE solid 0.02rem;
    }

    .recharge_box .tips .close {
        background:url("http://oss.xqzs.cn/resources/psy/asker/user_close.png") no-repeat;
        position: absolute;
        right: 0.30rem;
        top: 0.30rem;
        width: 0.68rem;
        height: 0.68rem;
        text-align: center;
        background-size: 50% 50%;
        background-position-x: 50%;
        background-position-y: 50%;
    }

    .recharge_box .tips .tip_title {
        position: absolute;
        top: 0.72rem;
        text-align: center;
        width: 100%;
        font-size: 0.40rem;
        line-height: 0.56rem;
    }

    .recharge_box .tips .content {
        position: absolute;
        padding: 0 0.56rem;
        top: 1.76rem;
        font-size: 0.30rem;
    }

    .recharge_box .tips .content p {
        line-height: 0.48rem;
        margin-bottom: 0.34rem;
    }

    .recharge_box .cash_right {
        margin-top: 0.30rem;
        float: right;
        margin-right: 0.28rem;
        width: 0.44rem;
        height: 0.44rem;
        background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_on.png");
        background-size: 100%;
    }

    .recharge_box .cash_right.no {
        background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_no.png");
        background-size: 100%;
    }
</style>

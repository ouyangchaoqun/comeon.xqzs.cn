<template>
    <div class="recharge_box" >
        <div class="recharge_title">
            请选择充值点豆
        </div>
        <div class="items_box">
            <div class="items" v-for="(item,index) in items" @click="select(index)" :class="{selected:item.c==1}">
                <div class="price" :class="{nogift:item.couponCount==0}">{{item.dianCoin}}点豆</div>
                <div class="dou" :class="{nogift:item.couponCount==0}">{{parseInt(item.money)}}元</div>
                <div class="gift" v-if="item.couponCount!=0">赠送{{item.couponCount}}张偷听卡</div>
            </div>
        </div>
        <div class="cash">现金余额可使用<span style="color: #FB640A">{{user.balance||0.00}}</span>元
            <div class="cash_right" :class={no:!isUseIncome} @click="useIncome()"></div>
        </div>
        <div class="rechar_btn" @click="doPay()">立即支付（{{pay}}元）</div>
        <div class="question" >
            <img src="../../../images/asker/question_icon.png" alt="">
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
                user:'',
                havedianCoin:0,
                balance:0,
            }

        },
        props:[
            'rechargeMoney',
            'rechargeFlag'
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
                xqzs.user.getUserInfo(function (user) {
                    _this.user =user;
                    if( _this.user!=''|| _this.user!=undefined){
                        _this.balance = _this.user.balance
                        _this.havedianCoin = _this.user.dianCoin;
                    }

                })
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
                console.log('获取充值信息')
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
                }
                _this.$http.put(web.API_PATH + "come/user/create/recharge", msg)
                        .then(function (bt) {
                            if (bt.data && bt.data.status == 1) {
                                let result = bt.data.data;
                                if (result.resultCode == 1) {
                                    xqzs.weui.tip("支付成功", function () {
                                        _this.$emit(
                                                'childMessage',{
                                                    rechargeFlag:false
                                                }
                                        )
                                    });

                                }else{
                                    xqzs.wx.pay.pay(result, function () {

                                    }, function () {//success
                                        xqzs.weui.tip("支付成功", function () {
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
        margin-top: 1.24rem;
        margin-left: 0.94rem;
        font-size: 0.88rem;
        color: #29293E;
        line-height: 0.88rem;
        font-weight: bold;
    }

    .recharge_box .items_box {
        margin-top: 0.29rem;
        overflow: hidden;
        padding: 0 4.2%
    }

    .recharge_box .items_box .items {
        margin-top: 0.70rem;
        width: 46.5%;
        height: 4.82rem;
        background: #F7F7F7;
        border-radius: 10px;
        float: left;
        text-align: center;
        border: solid 1px #fff;
    }

    .recharge_box .items_box .items:nth-of-type(2n) {
        margin-left: 3.8%;
    }

    .recharge_box .items_box .items .price {
        margin-top: 0.294rem;
        font-size: 1.176rem;
        line-height: 1.647rem;
    }

    .recharge_box .items_box .items .price.nogift {
        margin-top: 1rem
    }

    .recharge_box .items_box .items .dou {
        line-height: 1.176rem;
        font-size: 0.82rem;
        margin-top: 0.11rem;
    }

    .recharge_box .items_box .items .gift {
        line-height: 1rem;
        color: #FE7A03;
        font-size: 0.70rem;
        margin-top: 0.22rem
    }

    .recharge_box .cash {
        width: 19.294rem;
        height: 2.941rem;
        background: rgba(228, 227, 227, 0.5);
        margin: 0 auto;
        margin-top: 1.176rem;
        border-radius: 10px;
        line-height: 2.941rem;
        font-size: 0.88rem;
        padding-left: 0.88rem;
    }

    .recharge_box .rechar_btn {
        width: 15.294rem;
        height: 2.588rem;
        background: #FE7A03;
        color: #fff;
        font-size: 1.0588rem;
        line-height: 2.588rem;
        border-radius: 6.235rem;
        margin: 0 auto;
        margin-top: 2.058rem;
        text-align: center;
    }

    .recharge_box .question {
        margin-top: 3.5rem;
        font-size: 0.88rem;
        color: #9B9B9B;
        text-align: center
    }
    .recharge_box .question img{
        width:0.88235rem;
        vertical-align: middle;
    }
    .recharge_box .question span{
        border-bottom: 1px solid rgba(228, 227, 227, 0.5);
        padding: 0 0 0.1rem 0.2rem;
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
        width: 16rem;
        height: 19rem;
        position: absolute;
        z-index: 10;
        background: #fff;
        top: 24%;
        left: 50%;
        margin-left: -8rem;
        border-radius: 0.588rem
    }

    .recharge_box .items_box .items.selected {
        border: #FE7A03 solid 1px;
    }

    .recharge_box .tips .close {
        background:url("../../../images/asker/user_close.png") no-repeat;
        position: absolute;
        right: 0.88rem;
        top: 0.88rem;
        width: 2rem;
        height: 2rem;
        text-align: center;
        background-size: 50% 50%;
        background-position-x: 50%;
        background-position-y: 50%;
    }

    .recharge_box .tips .tip_title {
        position: absolute;
        top: 2.117rem;
        text-align: center;
        width: 100%;
        font-size: 1.176rem;
        line-height: 1.647rem;
    }

    .recharge_box .tips .content {
        position: absolute;
        padding: 0 1.647rem;
        top: 5.176rem;
        font-size: 0.88rem;
    }

    .recharge_box .tips .content p {
        line-height: 1.4rem;
        margin-bottom: 1rem;
    }

    .recharge_box .cash_right {
        margin-top: 0.88rem;
        float: right;
        margin-right: 0.82rem;
        width: 1.294rem;
        height: 1.294rem;
        background: url("../../../images/asker/user_income_on.png");
        background-size: 100%;
    }

    .recharge_box .cash_right.no {
        background: url("../../../images/asker/user_income_no.png");
        background-size: 100%;
    }
</style>
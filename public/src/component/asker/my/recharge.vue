<template>
    <div class="recharge_box">
       <div class="title">
           请选择充值金额
       </div>
        <div class="items_box">
            <div class="items" v-for="(item,index) in items" @click="select(index,item)"  :class="{selected:item.id==checkIndex}">
                <div class="price" :class="{nogift:item.couponCount==0}">{{item.money}}元</div>
                <div class="dou" :class="{nogift:item.couponCount==0}">{{item.dianCoin}}点豆</div>
                <div class="gift"v-if="item.couponCount!=0">赠送{{item.couponCount}}张偷听卡</div>
            </div>
        </div>

        <div class="cash" >现金余额可使用<span style="color: #FB640A">{{user.balance}}</span>元</div>
        <div class="btn" @click="pay()">立即支付（{{pay}}元）</div>
        <div class="question" @click="showTips">常见问题</div>
        <div class="mask" v-if="isTips"></div>
         <div class="tips" v-if="isTips">
             <div class="close" @click="closeTips">X</div>
            <div class="tip_title">常见问题</div>
            <div class="content">
                <p>1.免费偷听问答不扣点豆，已支付问题可重复免费听；</p>
                <p>2.点豆仅限平台内偷听、向专家提问，充值后不可提现；</p>
                <p>3.请在有效期内使用偷听卡；</p>
                <p>4.如有疑问，请在微信对话窗口反馈。</p>
            </div>
            <div class="bottom"></div>
        </div>
    </div>
</template>
<script type="">
    export default {
        data() {
            return {
                checkIndex:0,
                items:[],
                isTips:false,
                pay:0,
                income:0,
            }
        },
        props:{
            user:{
                type:Object
            }
        },
        mounted: function () {
            this.getRechargeConfig();

        },
        methods: {
            select:function (index,item) {
                this.checkIndex=item.id;
                console.log(0.6500004.toFixed(2));
                if(Number(this.user.balance)>=Number(item.money)){
                    this.pay=0;
                }else{
                        this.pay=(Number(item.money)-Number(this.user.balance)).toFixed(2)
                }
            },
            showTips:function () {
                this.isTips=true;
            },
            closeTips:function () {
                console.log("sssss")
                this.isTips=false;
            },
            getRechargeConfig:function () {
                console.log("sss")
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/user/query/recharge/config' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                      _this.items=data.body.data;
                    }
                }, function (error) {
                });
            },
            pay:function () {
                let _this=this;
                let msg = {
                    userId:_this.user.id,
                    configId:_this.checkIndex,
                    isUseBalance:1,
                    amount:_this.pay,
                }
                _this.$http.put(web.API_PATH + "come/user/create/recharge",msg)
                        .then(function (bt) {
                            if (bt.data && bt.data.status == 1) {
                                let result = bt.data.data;
                                xqzs.wx.pay.pay(result.order, function () {

                                }, function () {//success
                                    xqzs.weui.tip("充值成功", function () {
                                    });
                                }, function () {//error

                                })
                            }
                        });
            }
        },



    }

</script>
<style>
    .recharge_box{background: #fff; height: 100%}
    .recharge_box .title{margin-top: 1.24rem;margin-left:0.94rem;font-size:0.88rem;color: #29293E; line-height: 0.88rem;font-weight: bold; }
    .recharge_box .items_box{ margin-top: 0.29rem; overflow: hidden;padding: 0 4.2%}
    .recharge_box .items_box .items { margin-top:0.70rem;width: 47.5%; height: 4.82rem;background: #F7F7F7; border-radius: 10px; float: left;text-align: center ;border: solid 1px #fff;}
    .recharge_box .items_box .items:nth-of-type(2n){ margin-left: 3.8%;}
    .recharge_box .items_box .items .price{ margin-top: 0.294rem; font-size: 1.176rem; line-height: 1.647rem;}
    .recharge_box .items_box .items .price.nogift{margin-top: 1rem}
    .recharge_box .items_box .items .dou{ line-height: 1.176rem; font-size: 0.82rem; margin-top:0.11rem; }
    .recharge_box .items_box .items .gift{ line-height: 1rem ;color: #FE7A03; font-size: 0.70rem;margin-top: 0.22rem}
    .recharge_box .cash{width: 19.294rem; height: 2.941rem; background: rgba(228,227,227,0.5); margin: 0 auto; margin-top: 1.176rem; border-radius: 10px;
    line-height:2.941rem; font-size: 0.88rem;padding-left: 0.88rem;}
    .recharge_box .btn{width: 15.294rem; height: 2.588rem ; background: #FE7A03 ;color: #fff;font-size: 1.0588rem;line-height: 2.588rem;border-radius: 6.235rem; margin: 0 auto;margin-top: 2.058rem;text-align: center;}
    .recharge_box  .question{margin-top: 4.823rem;font-size: 0.88rem;color: #9B9B9B; text-align: center}
    .mask{position: absolute;height: 100%;top:0;background: rgba(0,0,0,0.5); z-index: 5;width: 100%;}
    .recharge_box .tips{ width: 16.47rem; height: 21.176rem;position: absolute;z-index: 10;background: #fff;top: 8.117rem;left: 2.823rem;border-radius: 0.588rem }
    .recharge_box .items_box .items.selected{
        border: #FE7A03 solid 1px;
    }
    .recharge_box .tips .close{ position: absolute;right: 1.823rem; top:1.223rem; width: 2rem;height: 2rem; text-align: center;}
    .recharge_box .tips .tip_title{ position: absolute;top:2.117rem;text-align: center;width: 100%; font-size:1.176rem;line-height: 1.647rem;}
    .recharge_box .tips .content{ position: absolute; padding: 0 1.647rem;top:5.176rem;font-size: 0.88rem; line-height: 1.235rem;}
    .recharge_box .tips .content p{margin-top: 0.88rem;}

</style>
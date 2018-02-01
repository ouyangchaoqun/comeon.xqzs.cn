<template >
    <div class="price_box">
        <v-showLoad v-show="showLoad"></v-showLoad>
        <div v-title>入驻心理咨询师</div>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure" @click="setPrice()">确定</div>
        </div>
        <div class="set_price">
            <input class="priceInput"  @input="changePrice()" :value="'￥'+price" placeholder="设置提问价格（元）例如：¥10" >
        </div>
        <div class="price_bottom">
            <div>至少10元</div>
            <div>解答须知</div>
        </div>
    </div>
</template>

<script type="es6">

    import answerTopStep from "./include/top_step.vue";

    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                prices:["1.00","2.00","3.00","5.00","10.00","15.00","20.00","30.00","50.00"],
                price:"10.00",
                showLoad:false,
                freeTime:null,
                freeTimeText:'',
                isSubmitting:false,
                times:[{
                    label: '不免费',
                    value: 0
                }, {
                    label: '30分钟',
                    value: 1
                }, {
                    label: '1小时',
                    value: 2
                },{
                    label: '2小时',
                    disabled: true,
                    value: 3
                }, {
                    label: '3小时',
                    value: 4
                },{
                    label: '4小时',
                    value: 5
                }]
            }
        },

        mounted: function () {
            let price = cookie.get("price");
            if(price)this.price= price;
            xqzs.wx.setConfig(this);

        } ,
        methods:  {
            changePrice:function () {
                let price= $(".priceInput").val()
                price=  price.replace('￥','');
                this.price=price;

            },
            backStep:function () {
                this.$router.go(-1)
            },
            setPrice:function () {
                this.$router.go(-1)
            },
            submit:function () {
                let _this=this;
                console.log(_this.isSubmitting)
                if(_this.isSubmitting){
                    return;
                }

                console.log('sub');

                if(!xqzs.string.checkPrice(_this.price)){
                    xqzs.weui.tip("请输入正确的金额！");
                    return ;
                }
                if(parseFloat(_this.price)>xqzs.price.MAX_ANSWER_SET_PRICE||parseFloat(_this.price)<xqzs.price.MIN_ANSWER_SET_PRICE){
                    xqzs.weui.tip("金额需在 "+xqzs.price.MIN_ANSWER_SET_PRICE+"-"+xqzs.price.MAX_ANSWER_SET_PRICE+" 之间！");
                    return;
                }

                _this.isSubmitting=true;
                _this.showLoad=true;
                let questionClassId= cookie.get("questionClassId");
                let  questionClassIds=[];
                if(questionClassId&&questionClassId!=''){
                    questionClassIds=questionClassId.split(',')
                }
                console.log("questionClassIds:"+questionClassIds)
                let data={
                    userId:"_userId_",
                    price:_this.price,
//                    freeTime:cookie.get("freeTime"),
                    sign:unescape(cookie.get("sign")),
                    mediaId:cookie.get("mediaId"),
                    voiceLength:cookie.get("voiceLength"),
                    questionClassId:questionClassIds,
                    jobTitle:unescape(cookie.get("jobTitle")),
                    certificateNo:unescape(cookie.get("certificateNo")),
                    certificateFile:[unescape(cookie.get("certificateFile1")),unescape(cookie.get("certificateFile2"))],
                    introduction:unescape(cookie.get("introduction")),
                    experience:unescape(cookie.get("experience")),
                    goodat:unescape(cookie.get("goodAt")),
                    identityNo:unescape(cookie.get("identityNo")),
                    cardImage:[unescape(cookie.get("identityFile1")),unescape(cookie.get("identityFile2"))]

                };
                $.ajax({
                    url: web.API_PATH + "come/expert/register",
                    data:data,
                    type: 'PUT',
                    success: function( bt ) {
                        _this.showLoad=false;
                        _this.isSubmitting=false;
                        let result = JSON.parse(bt)
                        if(result.status==9000006){
                            xqzs.weui.tip("您已经提交过审核",function () {
                                window.history.go(-1);
                            })
                        }else if(result.status==1){
                            _this.$router.replace("./reviewing")
                        }


                    }
                });

            }
        },
        components: {
            'v-showLoad': showLoad,
        }


    }
</script>
<style>
    .price_box{
        background: RGBA(69, 75, 84, 0.03);
    }
    .set_price{
        padding:0.88235rem;
        background: #fff;
        font-size: 0.8235rem;
        margin-bottom: 0.35rem;
    }
    .set_price input{
        width:100%;
        height:1.176rem;
    }
    .price_bottom{
        padding: 0 0.88235rem;
        line-height: 1.176rem;
    }
    .price_bottom div:nth-of-type(1){
        float: left;
        font-size: 0.8235rem;
        color:RGBA(69, 75, 84, 0.6);
    }
    .price_bottom div:nth-of-type(2){
        float: right;
        font-size: 0.70588rem;
        color:RGBA(69, 75, 84,0.55)
    }
</style>
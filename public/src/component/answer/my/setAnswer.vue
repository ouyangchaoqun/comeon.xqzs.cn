<template id="setAnswer">
    <div class="setAnswer_box">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title class='hide_title'>设置解答</div>
        <div class="setItem">
            <div>
                <div class="set_price">
                    <div class="til">设置提问酬金：</div>
                    <div class="select" ><input type="" class="priceInput" @input="getPrice()" :value="'￥'+expert.price"></div>
                    <div class="clear"></div>
                </div>
                <div class="set_price mt_mb" @click="showTimePicker()" >
                    <div class="til">设置限时免费：</div>
                    <div class="select" ><input type="" readonly   :value="label"></div>
                    <div class="clear"></div>
                </div>

            </div>





            <div class="setItem_bottom" @click="showMask()">
                <span>
                    <img src="http://oss.xqzs.cn/resources/psy/asker/need_know.png" alt="">
                    解答须知
                </span>
            </div>

            <div class="btn_box">
                <a id="subBtn" @click="submit()" >提交设置</a>
            </div>
        </div>
        <div class="weui-mask weui-animate-fade-in" v-if="myask_mask_flag" @click="hide_myask_mask()">
            <div class="myask_class myask_know_box" @click.stop>
                <h3>解答须知</h3>
                <ul style="overflow: auto;height: 4.20rem;">
                <li><b>1.</b>提交问题，设置赏金支付后，心情指数将为您匹配专业咨询师开始抢答。<br/>（1）设置的赏金越高，匹配的咨询师等级越高，抢答的咨询师越多。<br/>（2）问题描述越清楚，咨询师的回答将精准。</li>
                    <li><b>2.</b>你可以选择做佳答案：<br/>（1）该回答将产生偷偷听收入；<br/>（2）其咨询师将获得全部赏金；</li>
                    <li><b>3.</b>48小时内无人抢答，则全额退款。</li>


                </ul>
                <div class="myask_class_true" @click="hide_myask_mask()">知道了</div>
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
                label:'',
                myask_mask_flag:false,
                expert:{ price:"0.00"},
                keyValue:[{
                    label: '不免费',
                    value: 0
                }, {
                    label: '30分钟',
                    value: 30
                }, {
                    label: '1小时',
                    value: 60
                },{
                    label: '2小时',
                    value: 120
                }, {
                    label: '3小时',
                    value: 180
                },{
                    label: '4小时',
                    value: 240
                }]
            }
        },
        activated: function () {
            this.getExpertByUserId();
            $("#timePic").focus(function(){
                document.activeElement.blur();
            });
            xqzs.wx.setConfig(this);
        },
        methods:{
            formatPrice:function (v) {
              return xqzs.string.formatPrice(v)
            },
            showTimePicker:function () {
                let _this = this;
                let defaultValue= _this.expert.freeTime;
                weui.picker(_this.keyValue, {
                    defaultValue: defaultValue,
                    onChange: function (result) {

                    },
                    onConfirm: function (result) {
                        console.log(result);
                        _this.label = result[0].label;
                        _this.expert.freeTime = result[0].value;


                    }
                });
            },
            submit:function () {
                let _this=this;
                let expertId = cookie.get('expertId');

                if(!xqzs.string.checkPrice(_this.expert.price)){
                    xqzs.weui.tip("请输入正确的金额！");
                    return ;
                }

                if(parseFloat(_this.expert.price)>xqzs.price.MAX_ANSWER_SET_PRICE||parseFloat(_this.expert.price)<xqzs.price.MIN_ANSWER_SET_PRICE){
                    xqzs.weui.tip("金额需在 "+xqzs.price.MIN_ANSWER_SET_PRICE+"-"+xqzs.price.MAX_ANSWER_SET_PRICE+" 之间！");
                    return;
                }

                let msg = {
                    expertId:expertId,
                    userId:'',
                    price: _this.expert.price,
                    freeTime: _this.expert.freeTime
                };
                _this.$http.post(web.API_PATH + 'come/expert/answerset', msg,{emulateJSON: true})
                    .then(
                        (response) => {
                            if(response.body.status==1){
                                console.log('success')
                                xqzs.weui.toast("success", "设置成功", function () {
                                    _this.$router.go(-1)
                                })
                            }else{
                                xqzs.weui.tip("设置失败")
                            }

                        });
            },
            showMask:function () {
                this.myask_mask_flag = true
            },
            hide_myask_mask:function () {
                this.myask_mask_flag = false
            },
            getPrice:function () {
                this.expert.price=$(".priceInput").val().replace("￥","");
            },
            getExpertByUserId:function () {
                let _this=this;
                _this.showLoad=true;
                this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {//es5写法
                    _this.showLoad=false;
                    if (data.body.status == 1&&data.body.data!=null) {

                        _this.expert = data.data.data;
                        for(let i =0  ;i< _this.keyValue.length;i++){
                            if(_this.keyValue[i].value== _this.expert.freeTime){
                                _this.label=_this.keyValue[i].label;
                                break;

                            }
                        }

                    }
                }, function (error) {
                    _this.showLoad=false;
                });
            },
        },
        components: {
            'v-showLoad': showLoad,

        },

    }
</script>
<style>

    .setAnswer_box{
        background: #fff;
        position: relative;
    }

    .setItem .setItem_bottom{
        text-align: center;
        font-size: 0.24rem;
        color: rgba(36,37,61,0.7);
    }
    .setItem_bottom img{
        height:0.28rem;
        width:0.28rem;
        display: inline-block;
        position: absolute;
        left:0;
        top:0.02rem;
    }
    .setItem_bottom span{
        margin:0 auto;
        position: relative;
        padding-left: 0.40rem;
    }
    .set_price{ width: 66%; margin: 0.00rem auto; margin-top: 48% }
    .set_price .til,  .set_price .select{ float:left; line-height: 0.68rem; text-align: center; color:#454B54; font-size: 0.30rem;}
    .set_price .select input{ width: 2.04rem;font-size: 0.41rem; line-height: 0.68rem;;color: #56C4FE; text-align: center}
    .set_price .select{ margin-left: 0.20rem; width:50%;color: #ffaa00; font-size: 0.41rem; line-height: 0.68rem;; border-radius: 0.10rem; border:0.02rem solid #B3B3B3
    }
    .set_price.mt_mb{ margin-top: 0.6rem; margin-bottom: 0.6rem;}

    .setAnswer_box  .btn_box{width:100%;position: relative;margin-top: 1.70rem;display: flex;display: -webkit-flex;text-align: center;line-height: 0.80rem}
    .btn_box a{flex: 1;border:0.02rem solid rgba(253,115,1,1);margin:0 0.30rem;border-radius: 0.40rem;color:rgba(253,115,1,1);background: #fff;}
    .btn_box a:last-of-type{color:#fff;background:linear-gradient(to right, rgba(255,158,25,1), rgba(253,114,6,1))}
    .btn_box a:active{
        color:#fff;background:linear-gradient(to right, rgb(217, 128, 25), rgb(220, 107, 6))
    }
</style>
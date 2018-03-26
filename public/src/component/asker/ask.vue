<template xmlns="http://www.w3.org/1999/html">
    <div style="height: 100%" class="asker_ask_box">
        <div v-title>提问</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-recharge :rechargeMoney="rechargeMoney" v-show="rechargeFlag" v-on:childMessage="getFlagVal"></v-recharge>
        <div class="change_height">
            <div class="ask_type_new" v-if="isSelectAnswer">
                <div class="tab">问题类型 <span>点击选择</span></div>
                <div class="select_box">
                    <div v-for="item in types">{{item.title}}</div>
                </div>
            </div>
            <div class="ask_type" v-if="!isSelectAnswer" @click="selectType()">
                <div class="tab">选择问题类型：</div>
                <div class="select_box">{{type}}</div>
                <div class="clear"></div>
            </div>
            <!--<div class="ask_type_new" v-if="!isSelectAnswer">-->
                <!--<div class="tab">问题类型 <span>点击选择</span></div>-->
                <!--<div class="select_box">-->
                    <!--<div v-for="item in types">{{item.title}}</div>-->
                <!--</div>-->
            <!--</div>-->
            <div class="text_area">
                <textarea v-model="expertextContent" v-if="isSelectAnswer" placeholder="请详细描述您的问题，专家将第一时间帮您解答。" class="content answer_select" maxlength="200" @input="valChange()"></textarea>
                <textarea @input="valChange()" v-model="fastAsktextContent" v-if="!isSelectAnswer" placeholder="请详细描述你的问题，专家将尽快为你解答！" class="content" maxlength="200"></textarea>
                <div v-if="!isSelectAnswer" class="last_word_count">{{contentLength}}/200</div>
                <div v-if="isSelectAnswer" class="last_word_count">{{contentLength}}/{{MAX_LENGTH}}</div>
                <div class="price" v-if="isSelectAnswer">￥{{parseInt(expertDetail.price)}}</div>
                <div class="price" v-if="!isSelectAnswer">￥10</div>
            </div>
            <div class="addAnonymous">
                <span>匿名 </span>
                <input type="checkbox" class="weui-switch" :checked="checked" @click="getChecked()">
            </div>
            <div class="tip" @click="tip()">提问须知</div>
            <div class="clear"></div>
            <!--<div class="set_price" v-if="!isSelectAnswer">-->
            <!--<div class="txt">设置赏金：</div>-->
            <!--<input type="number" class="price" placeholder="10元起">-->
            <!--</div>-->
            <div class="submit weui-btn weui-btn_primary" @click="typeDialog()">提交</div>
        </div>

        <v-asker-bottom v-if="!isSelectAnswer" tabOnIndex="2"></v-asker-bottom>

        <div id="select_type" style="display: none">
            <div class="dialog_select_type">
                <div class="select_title">选择问题类型</div>
                <div class="select_types">
                    <div class="item"   v-for="(item,index) in types"   :index="index"><span>{{item.title}}</span></div>
                    <div class="clear"></div>
                </div>
                <div class="select_yes">
                    <div class="hide_btn">取消</div>
                    <div class="sure_click">确定</div>
                </div>


            </div>
        </div>
        <!--<div class="weui-mask weui-animate-fade-in" v-if="is_checked">-->
            <!--<div class="addAnonymous_dialog">-->
                <!--<div class="addAnonymous_title">匿名开启</div>-->
                <!--<div class="addAnonymous_content">匿名后您的个人信息将保密</div>-->
                <!--<div class="addAnonymous_btn" @click="hideAddAnonymous()">我知道了</div>-->
            <!--</div>-->
        <!--</div>-->

        <div id="tip" style="display: none">
            <div class="dialog_select_type dialog_select_Height">
                <div class="select_title">提问须知</div>
                <div class="tip_content" v-if="!isSelectAnswer">
                    <p>
                        1、提交问题，付款成功后，将为你匹配专家进行抢答；
                    </p>
                        2、你可以从解答中选出最佳答案：<br>
                        1）你将免费获得2颗点豆奖励</br>
                        2）被选中者获得全部赏金</br>
                    </p>
                    <p>
                        3、若48小时内无专家抢答，赏金将按原路径返还；<br>
                    </p>
                    <p>
                        4、若你在48小时内没有选出最佳答案<br>
                        1）你不会获得点豆奖励<br>
                        2）参与抢答的专家将平分赏金<br>
                    </p>
                </div>
                <div class="tip_content" v-if="isSelectAnswer">
                    <p>
                        1、提交问题，付款成功后，将安排专家在第一时间为你解答；
                    </p>
                    <p>
                        2、若48小时内专家未解答，支付酬金将按原路径返还。
                    </p>
                </div>

                <div class="yes know" >知道了</div>


            </div>
        </div>
    </div>
</template>

<script type="">
    import showLoad from '../include/showLoad.vue';
    import askerBottom from "./include/bottom.vue";
    import Recharge from '../asker/my/recharge.vue' ;
    export default {
        data() {
            return {
                showLoad:false,
                types:[],
                showTypes:false,
                showTip:false,
                isSelectAnswer: false, //是否针对专家提问
                type:'',
                typeSelectIndex:null,
                questionClass:0,//0(有类型)1（取消类型）
                expertId:0,
                expertDetail:{},
                contentLength:0,
                MAX_LENGTH:200,
                checked:false,
                isAnonymous:0,
                is_checked:false,
                couponList:[],
                couponNum:0,
                expertextContent:'',
                fastAsktextContent:'',
                rechargeMoney:0,
                user:{},
                rechargeFlag :false,

            }
        },

        components: {
            'v-showLoad': showLoad,
            "v-asker-bottom": askerBottom,
            'v-recharge':Recharge,
        },
        mounted: function () {
            this.expertId=this.$route.query.expertId;
            this.getUserInfo();
            if( this.expertId&& this.expertId!=''){
                this.isSelectAnswer=true;
                this.getExpert();
                this.expertextContent =xqzs.localdb.get('expertextContent')||'';

            }else{
                this.getClassList()
                this.fastAsktextContent =xqzs.localdb.get('fastAsktextContent')||'';
            }
            //数字变化
            let _this=this;
            this.getCoupon();
            _this.$nextTick(function () {
                $(document).resize(function() {
                    $(".asker_ask_box .change_height").height($(document).height()-48)
                });
                $(".content").keyup(function () {
                    let content  =  $(this).val();

                    if(content.length>_this.MAX_LENGTH){
                        $(this).val(content.substr(0,_this.MAX_LENGTH));
                        _this.contentLength=_this.MAX_LENGTH;
                    }else{
                        _this.contentLength= content.length;
                    }
                })

            });
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
        },
        methods: {
            getFlagVal:function (val) {
                this.rechargeFlag  = val.rechargeFlag;
                this.getUserInfo();
            },
            valChange:function () {
                let content= $(".content").val();
                if(this.isSelectAnswer){
                    xqzs.localdb.set('expertextContent',content)
                }else{
                    xqzs.localdb.set('fastAsktextContent',content)
                }
            },
            getUserInfo:function(){
                let _this=this;
                xqzs.user.getUserInfo(function (user) {
                    _this.user =user;
                })
            },
            getCoupon:function () {
                let _this = this;
                xqzs.api.get(_this, 'come/user/get/coupon/_userId_/1/10/0',function (data) {
                    _this.couponList = data.data.data;
                    _this.couponNum = data.data.data.length;
                })
            },
            typeDialog:function () {
                if(!xqzs.user.isUserLogin()){
                    return ;
                }
                if(this.questionClass==0){
                    xqzs.weui.tip("请选择问题类型")
                    return;
                }
                let content= $(".content").val();
                if(content==''){
                    xqzs.weui.tip("请填写问题内容");
                    return;
                }
                let _this = this;
                if( _this.expertId&& _this.expertId!=''){
                    console.log('一对一')
                    xqzs.api.post(_this,"come/expert/post/expert/question",{userId:"_userId_",content:content, questionClass: _this.questionClass,expertId:_this.expertId,isAnonymous:this.isAnonymous,  channelOpenId:xqzs.localdb.get("channelopenid")},function (bt) {
                        if (bt.data && bt.data.status == 1) {
                            let result = bt.data.data;
                            xqzs.localdb.remove('expertextContent');
                            let config =result.config;
                            xqzs.wx.pay.pay(config, function () {

                            }, function () {
                                xqzs.weui.toast("success","支付成功", function () {
                                    _this.$router.push("/asker/my/ask/list");
                                });
                            }, function () {

                            },web.BASE_PATH+"asker/my/ask/list")
                        }
                    })
                }else{
                    console.log('快问');
                    xqzs.api.post(_this,"come/user/post/grab/question", {userId:"_userId_",content:content, questionClass: _this.questionClass,price:10,isAnonymous:this.isAnonymous,  channelOpenId:xqzs.localdb.get("channelopenid")},function (bt) {
                        if (bt.data && bt.data.status == 1) {
                            let result = bt.data.data;
                            let config =result.config;
                            xqzs.localdb.remove('fastAsktextContent');
                            xqzs.wx.pay.pay(config, function () {

                            }, function () {
                                xqzs.weui.toast("success","支付成功", function () {
                                    _this.$router.push("/asker/my/ask/list");
                                });
                            }, function () {

                            },web.BASE_PATH+"asker/my/ask/list")


                        }
                    })
                }
//                let payTitle;
//                let subHtml;
//                let isEnough = false;
//                if(_this.isSelectAnswer){
//                    //向专家提问
//                    payTitle = '确认向专家提问';
//                }else{
//                    //快问
//                    payTitle = '确认发布问题';
//                }
//                if(Number(_this.user.dianCoin)>=Number(_this.rechargeMoney)){
//                    isEnough = true;
//                    subHtml=""
//                }else{
//                    subHtml="去充值"
//                }
//                let msg = '使用：<span class="colorStyle">'+_this.rechargeMoney+'</span> 点豆&nbsp&nbsp&nbsp剩余：<span class="colorStyle">'+_this.user.dianCoin+'</span> 点豆'
//                xqzs.weui.dialog(payTitle,msg,subHtml,function(){},function () {
//                    if(isEnough){
//                        _this.submit()
//                    }else{
//
//                       _this.rechargeFlag = true
//                    }
//
//                })
            },
            getChecked:function () {
                var checkedVal = $('.weui-switch').prop('checked');
                this.checked = checkedVal;
                if(checkedVal){
                    this.isAnonymous = 1;
                }else{
                    this.isAnonymous = 0
                }
            },
            getExpert:function () {
                //专家擅长领域
                let _this= this;
                let id=  this.expertId;
                xqzs.api.get(_this, 'come/expert/show/to/user/'+id+'/_userId_',function (data) {
                    if (data.body.status == 1) {
                        _this.expertDetail=data.body.data;
                        _this.rechargeMoney = _this.expertDetail.price;
                        console.log( _this.rechargeMoney)
                        _this.types= data.body.data.domain;
                    }
                })
            },
            getClassList:function () {
                console.log('快问类型选择')
                let _this=this;
                xqzs.api.get(_this, 'come/listen/question/class/list?fastask=1',function (data) {
                    if (data.body.status == 1) {
                        _this.types= data.body.data;
                        _this.rechargeMoney = 10.00;
                    }
                })
            },
//            submit:function () {
//                let _this = this;
//                let content= $(".content").val();
//                if( this.expertId&& this.expertId!=''){
//                        this.$http.post(web.API_PATH + "come/expert/post/expert/question", {userId:"_userId_",content:content, questionClass: _this.questionClass,expertId:this.expertId,isAnonymous:this.isAnonymous})
//                        .then(function (bt) {
//                            if (bt.data && bt.data.status == 1) {
//                                xqzs.localdb.remove('expertextContent');
//                                xqzs.weui.toast("success","支付成功", function () {
//                                    _this.$router.push("/asker/listen");
//                                });
//                            }
//                        });
//                }else{
//                    this.$http.post(web.API_PATH + "come/user/post/grab/question", {userId:"_userId_",content:content, questionClass: this.questionClass,price:10,isAnonymous:this.isAnonymous})
//                        .then(function (bt) {
//                            if (bt.data && bt.data.status == 1) {
//                                xqzs.localdb.remove('fastAsktextContent');
//                                xqzs.weui.toast("success","支付成功", function () {
//                                    _this.$router.push("/asker/listen");
//                                });
//                            }
//                        });
//                }
//            },
            tip: function () {
                this.showTip=true;
                let _this= this;
                xqzs.weui.dialogCustom($("#tip").html());
                $(".know").click(function () {
                    _this.closeDialog()
                })
            },
            //九种类型选择
            selectType: function () {
                let _this=this;
                this.showTypes=true;
                xqzs.weui.dialogCustom($("#select_type").html());
                if(_this.typeSelectIndex!=null){
                    $(".js_dialog .select_types .item").each(function (i) {
                        if(i==_this.typeSelectIndex){
                            $(this).addClass("on")
                        }
                    })
                }
                $(".select_types .item").click(function () {
                    $(".select_types .item").removeClass("on");
                    $(this).addClass("on");
                    let index=  parseInt($(this).attr("index"));
                    console.log(index)
                    _this.typeSelectIndex=index;
                });
                $(".dialog_select_type .select_yes .sure_click").click(function () {
                    if(_this.typeSelectIndex==null){
                        xqzs.weui.tip("请选择类型");
                    }else{
                        cookie.set("fastAsk_selIndex",_this.typeSelectIndex,1)
                        _this.type= _this.types[_this.typeSelectIndex].title;
                        _this.questionClass=_this.types[_this.typeSelectIndex].id;
                        if( _this.questionClass==undefined){
                            _this.questionClass=_this.types[_this.typeSelectIndex].classId;
                        }
                        console.log( _this.questionClass)
                        xqzs.weui.dialogClose();

                    }
                })
            },
            closeDialog:function () {
                xqzs.weui.dialogClose()
            }

        },
        updated:function () {
            let _this = this;
            if(_this.typeSelectIndex!=null){
                $('.select_box div').each(function (i) {
                    if(i==_this.typeSelectIndex){
                        $(this).addClass("on_new")
                    }
                })
            }
            $('.select_box div').on('click',function () {
                $('.select_box div').removeClass('on_new')
                $(this).addClass('on_new')
                let index=  $(this).index();
                _this.typeSelectIndex=index;
                _this.questionClass=_this.types[_this.typeSelectIndex].id;
                if( _this.questionClass==undefined){
                    _this.questionClass=_this.types[_this.typeSelectIndex].classId;
                }
            })

        }


    }
</script>
<style>
    .addAnonymous_dialog{
        width:74%;
        background: #fff;
        border-radius: 0.10rem;
        position: absolute;
        top:40%;
        text-align: center;
        padding-top: 0.58rem;
        font-size: 0.36rem;
        left:50%;
        margin-left: -37%;
        overflow: hidden;
    }
    .addAnonymous_title{
        color:rgba(36,37,61,1);
    }
    .addAnonymous_dialog div{
        line-height: 1;
    }
    .addAnonymous_content{
        color:rgba(36,37,61,0.5);
        font-size: 0.30rem;
        padding:0.32rem 0;
        border-bottom: 0.02rem solid rgba(224,224,225,1);
    }
    .addAnonymous_btn{
        color:rgba(253,87,57,1);
        padding:0.32rem 0;
    }
    .addAnonymous_btn:active{
        background: #f5f5f5;
    }
    .addAnonymous{
        color:rgba(36,37,61,0.5);
        float: left;
        margin-top: 0.27rem;
        margin-left: 0.30rem;
        font-size: 0.24rem;
        line-height: 0.40rem;
    }
    .addAnonymous span{
        float: left;
        margin-right: 0.10rem;
    }
    .addAnonymous .weui-switch, .weui-switch-cp__box{
        width:0.84rem;height:0.44rem;
    }
    .addAnonymous .weui-switch-cp__box:before, .weui-switch:before{
        width:0.80rem;height:0.40rem;
    }
    .addAnonymous .weui-switch-cp__box:after, .weui-switch:after{
        width:0.40rem;height:0.40rem;
    }
    .change_height{ height: 100%; overflow-y: scroll; width: 100%;}
    .asker_ask_box {
        background: #fff
    }
    .asker_ask_box .ask_type {
        margin: 0.50rem 0.30rem 0.40rem 0.30rem;
        background: #f4f4f7;
        border-radius: 0.12rem;
    }
    .asker_ask_box .ask_type .tab {
        line-height: 0.88rem;
        font-size: 0.30rem;
        color: rgba(36,37,61,0.5);
        margin-right: 0.10rem;
        float: left;
        margin-left: 0.24rem;
    }
    .asker_ask_box  .ask_type .select_box {
        width: 2.56rem;
        float: left;
        text-align: center;
        line-height: 0.88rem;
        height: 0.88rem;
        color: rgba(36,37,61,1);
        font-size: 0.30rem;
    }
    .asker_ask_box .text_area {
        position: relative;
        margin: 0 0.30rem;
        background: #F4F4F7;
        border-radius: 0.12rem;
        font-size: 0.26rem;
        padding: 0.34rem;
        height: 3.06rem;
        margin-top: 0.30rem;
        padding-top: 0.20rem;
    }
    /*.asker_ask_box .text_area p{*/
    /*font-size: 0.28rem;*/
    /*line-height: 0.48rem;*/
    /*color:rgba(36,37,61,0.5);*/
    /*margin-bottom: 0.10rem;*/
    /*}*/
    .asker_ask_box .text_area .price{
        width: 0.68rem;
        color: rgba(253,114,6,1);
        position: absolute;
        left: 0.20rem;
        bottom: 0.20rem;
    }
    .asker_ask_box  .text_area textarea {
        border: none;
        background: none;
        width: 100%;
        height: 100%;
        resize: none;
    }
    .asker_ask_box .text_area .last_word_count {
        color: rgba(36,37,61,0.5);
        position: absolute;
        right: 0.20rem;
        bottom: 0.20rem;
    }
    .asker_ask_box .tip {
        display: inline-block;
        float: right;
        background: url(http://oss.xqzs.cn/resources/psy/asker/tip.png) no-repeat;
        background-size: 0.28rem;
        height: 0.28rem;
        line-height: 0.28rem;
        padding-left: 0.34rem;
        font-size: 0.24rem;
        margin-right: 0.30rem;
        margin-top: 0.27rem;
        color:rgba(36,37,61,0.5);
    }
    .asker_ask_box .set_price {
        margin-top: 0.68rem;
        text-align: center;
        line-height: 0.68rem;
    }
    .asker_ask_box .set_price .txt {
        color:rgba(36,37,61,1);
    }
    .asker_ask_box .set_price div {
        display: inline-block
    }
    .asker_ask_box .set_price .price {
        font-size: 0.36rem;
        color: #FE7301;
        color: rgba(253,114,6,1);
        padding: 0 0.17rem;
        width: 1.36rem;
        height:0.52rem;
        border: none;
        border-bottom: 0.02rem solid #ccc;

    }
    ::-webkit-input-placeholder {
        color: #ff9900;}
    .asker_ask_box .submit {
        border-radius: 1.00rem;
        margin: 1.02rem 0.30rem 1.70rem;
    }

    .asker_ask_box .submit:active {
        background: linear-gradient(to right, rgb(239, 143, 25), rgb(211, 105, 6));
    }
    .dialog_select_Height{ height:6.46rem;}
    .dialog_select_type  .yes{ border-top: 0.02rem solid #eee; color:#FE7301; text-align: center; line-height: 0.88rem; position: absolute; bottom:0; left:0; width: 100% }
    .dialog_select_type  .yes:active{background: #eee}
    .dialog_select_type .tip_content{ padding:0  0.34rem; line-height: 1.8; font-size: 0.28rem; color:#666; height: 4.42rem; overflow: auto}
    .dialog_select_type .tip_content p{ margin-bottom: 0.20rem;}
    .asker_ask_box .ask_type_new{padding:0.40rem 0.30rem;padding-bottom: 0.10rem}
    .asker_ask_box .ask_type_new .tab{color:rgba(36,37,61,1);font-size: 0.34rem;line-height: 1;margin-bottom: 0.40rem;}
    .asker_ask_box .ask_type_new .tab span{color:rgba(36,37,61,0.5);font-size: 0.24rem;}
    .asker_ask_box .ask_type_new .select_box{display: flex;text-align: center;height:0.72rem;line-height: 0.72rem;}
    .asker_ask_box .ask_type_new .select_box div{flex:1;color:rgba(36,37,61,0.7);font-size: 0.30rem;background: rgba(245,245,245,1);border-radius: 0.10rem;}
    .asker_ask_box .ask_type_new .select_box div:nth-of-type(2){margin:0 0.30rem;}
    .asker_ask_box .ask_type_new .select_box .on_new{background: rgba(253,114,6,1);color:#fff;}
    .asker_ask_box  .text_area .content{ font-size: 0.28rem; color:rgba(36,37,61,1); height: 90%; line-height: 0.48rem}
    /*.asker_ask_box  .text_area  .answer_select{ height: 90%}*/
</style>

<template >
    <div class="listenDetail_box">
        <!--详情头部-->
        <div v-title>问题详情</div>
        <v-recharge  v-if="rechargeFlag"  :rechargeMoney="rechargeMoney" v-on:childMessage="getFlagVal"></v-recharge>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="steal_detail_header" v-if="detail.title">
            <div class="steal_detail_top">
                <img v-if="detail.isAnonymous==0" :src="detail.faceUrl" alt="">
                <img v-if="detail.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                <!--<div>在<span>{{detail.title}}</span>方面</div>-->
                <div v-if="detail.isAnonymous==0">{{detail.nickName}}</div>
                <div v-if="detail.isAnonymous==1">匿名用户</div>
                <div class="steal_detail_top_price">{{detail.title}}</div>
            </div>
            <div class="steal_detail_content">{{detail.content}}</div>
        </div>
        <!--专家回答列表-->
        <ul>
            <li class="steal_detail_answer" v-for="(item,index) in detail.answerList">

                <div class="steal_expert_info">
                    <img :src="item.expertUrl" alt="" @click="goDetail(item.expertId)">
                    <div>
                        <span class="steal_expert_name" @click="goDetail(item.expertId)">{{item.expertName}}</span><span class="steal_expert_fans">{{item.followCount}} 人关注</span>
                    </div>
                    <div class="steal_expert_des">{{item.sign}}</div>
                    <div class="followed_box" v-if="item.isFollowed==0" @click="follow(index)"> 关注</div>
                    <div class="followed_box isfollow_style"  v-if="item.isFollowed==1" @click="follow(index)" >已关注</div>
                </div>
                <div class="steal_answer_top">
                    <!--<img class="steal_answer_topimg" :src="item.expertUrl" alt="" >-->
                    <div class="steal_answer_yy">

                        <!--* const GRAB_NOT_BEST    = 1;抢答一般的答案-->
                        <!--* const GRAB_BEST_ANSWER = 2;抢答最好的答案-->
                        <!--* const EXPERT_FREE_TIME = 3;专家免费偷听期内答案-->
                        <!--* const EXPERT_NOT_FREE  = 4;专家收费期内的答案",-->
                        <!--免费听-->
                        <template v-if="detail.needPay==0&&(item.answerType==1||item.answerType!=3)">
                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                <div class="audio_btn" @click.stop="play(index)" >
                                    <template v-if="!item.playing&&!item.paused">点击播放</template>
                                    <template v-if="item.playing">正在播放..</template>
                                    <template v-if="item.paused">播放暂停</template>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </template>

                        <!--付费听-->
                        <template  v-if="detail.needPay==1">
                            <div @click="typeDialog(item.questionId,item.answerId,index)">
                                <div class="audio"><div class="audio_btn pay" >偷听</div></div>
                            </div>
                        </template>
                        <!--限时免费听-->
                        <template v-if="item.answerType==3&&detail.needPay==0">
                            <div class="audio"  :class="{playing:item.playing,paused:item.paused}"><div class="audio_btn" @click.stop="play(index)" >
                                <template v-if="!item.playing&&!item.paused">限时免费听</template>
                                <template v-if="item.playing">正在播放..</template>
                                <template v-if="item.paused">播放暂停</template>
                            </div><div class="clear"></div></div>
                        </template>



                    </div>
                    <div :class="{position_change2:(item.answerType==2||item.answerType==4)&&detail.needPay==1}">{{(item.ct && item.ct!='00')?item.ct:item.length}}”</div>
                </div>
                <div class="steal_answer_zan">
                    <div class="problem_answer_time">{{formatDateText(item.addTime)}}</div>
                    <div class="problem_answer_zan">
                        <div>
                            <span>听过</span>
                            <span> {{item.listenTimes}}</span>
                        </div>
                        <div @click="like(index)" class="good_care" :class="{good_cared:item.isCared}"><span>{{item.likeTimes}}</span></div>
                    </div>

                </div>
            </li>

        </ul>
        <!--新增评价-->
        <div class="evaluate_box" v-if="evaluates_flag&&!showLoad">
            <h3>用户评价</h3>
            <div class="title_border"></div>
            <ul>
                <li v-for="item in evaluates">
                    <img v-if="item.isAnonymous==0" :src="item.faceUrl" alt="">
                    <img v-if="item.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                    <div class="eva_main">
                        <div class="eva_name" v-if="item.isAnonymous==0">{{item.nickName}}</div>
                        <div class="eva_name" v-if="item.isAnonymous==1">匿名用户</div>
                        <div class="eva_content">{{item.content}}</div>
                        <div class="eva_time">{{getFormatDate(item.addTime)}}</div>
                    </div>
                </li>
            </ul>
            <div class="eva_btn" @click="getCommentList()" v-show="!isPageEnd">查看更多</div>
        </div>
        <div class="evaluate_block" v-if="isListened">
            <div @click="showCommentBox()">
                <img src="http://oss.xqzs.cn/resources/psy/asker/evaulate_icon.png" alt="">
                我要评价
            </div>
        </div>

        <div id="comment_box" style="display: none">
            <div class="comment_box2">
                <ul class="stars">
                    <li  v-for="(item,index) in comments"  :v="item.v" :class="{on:item.v<=commentValue}" >
                        <div class="star"></div>
                        <div class="text">{{item.t}}</div>
                    </li>
                </ul>
                <div class="textarea">
                    <textarea placeholder="分享您的咨询感受" id="textarea_comment" ></textarea>
                    <div class="anFlag">
                        匿名
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>
<script>

    import showLoad from '../include/showLoad.vue';
    import Recharge from '../asker/my/recharge.vue' ;
    export default {
        data() {
            return {
                comments:[{v:1,t:'极差'},{v:2,t:'不满意'},{v:3,t:'一般'},{v:4,t:'满意'},{v:5,t:'极满意'}],
                commentValue:0,
                questionId:0,
                detail:{},
                showLoad:false,
                timeOut:null,
                playing:false,
                list:[],
                couponList:[],
                couponNum:0,
                rechargeMoney:0,
                user:{},
                rechargeFlag:false,
                evaluates:[],
                isListened:false,
                evaluates_flag:false,
                page:2,
                row:3,
                isPageEnd:false,
                isLoading:false,
                anonyVal:0,
            }
        },
        mounted: function () {
            this.questionId=this.$route.query.questionId;
            this.getDetail();
            this.getCoupon();
            this.getUserInfo();


        },
        props:{
            user:{
                type:Object
            }
        },
        components: {
            'v-recharge':Recharge,
            'v-showLoad': showLoad,
        },
        methods:{
            getUserInfo:function(){
                let _this=this;
                xqzs.user.getUserInfo(function (user) {
                    _this.user =user;
                })
            },
            getFormatDate:function (t) {
                return xqzs.dateTime.formatDate(t)
            },
            showCommentBox:function () {
                let _this=this;
                let isAnonymous = false;

                xqzs.weui.dialog("评价",$("#comment_box").html(),"",function () {

                },function () {

                    _this.submitComment();
                });

                $(".anFlag").click(function () {
                    isAnonymous = !isAnonymous;
                    if(isAnonymous==true){
                        $(this).addClass('anFlag_on')
                        _this.anonyVal = 1
                    }else {
                        $(this).removeClass('anFlag_on')
                        _this.anonyVal = 0
                    }
                    console.log(_this.anonyVal)
                })


                $(".comment_box2 .stars li ").click(function () {
                    let v= parseInt($(this).attr("v"))
                    _this.setCommentValue(v)
                })


            },
            submitComment:function () {
                let that=this;
                let content = $(".weui-dialog #textarea_comment").val();
                console.log(content)
                console.log(that.detail.bestAnswerId)
                if(that.commentValue==0){
                    xqzs.weui.toast('fail',"请选择评分",function () {

                    })
                    return;
                }
                if(content.length==0){
                    xqzs.weui.toast('fail',"请输入评论内容",function () {

                    })
                    return;
                }
                xqzs.api.put(that, "come/user/evaluate/answer",{userId:"_userId_",answerId:that.detail.bestAnswerId, point:that.commentValue,content:content,isAnonymous :that.anonyVal},function (bt) {
                    that.showLoad=false;
                    if (bt.data && bt.data.status == 1) {
                        xqzs.weui.toast("success","评论成功",function () {
                            let  stuckMessage = {
                                faceUrl:that.detail.faceUrl,
                                content:content,
                                nickName: that.detail.nickName,
                                isAnonymous:that.anonyVal,
                                addTime:parseInt(new Date().getTime()/1000)
                            };
                            that.evaluates.splice(0,0,stuckMessage)
                            that.showLoad = false;
                        })
                    }
                })
            },
            setCommentValue:function (v) {
                let _this=this;
                _this.commentValue=v;
                console.log(_this.commentValue)
                $(".comment_box2 .stars li ").each(function () {
                    let v= parseInt($(this).attr("v"))
                    if( _this.commentValue>=v){
                        $(this).addClass("on")
                    }else{
                        $(this).removeClass("on")
                    }
                })
            },
            getCommentList:function () {
                let _this = this;
                if(_this.isLoading){
                    return
                }
                let questionId = _this.detail.questionId;
                _this.isLoading = true
                xqzs.api.get(_this,'come/comment/query/page/question/'+questionId+'/'+this.page+'/'+this.row,function(data){
                    if (data.body.status == 1) {
                        _this.isLoading=false;
                        _this.page++;
                        let arr =  data.data.data;
                        if(arr.length<_this.row){
                            _this.isPageEnd = true
                        }
                        _this.evaluates = _this.evaluates.concat(arr);
                    }
                },function (error) {
                    _this.isLoading=false;
                })
            },
            getFlagVal:function (val) {
                this.rechargeFlag  = val.rechargeFlag;
                this.getUserInfo()
            },
            like:function (index) {
                let  item = this.detail.answerList[index];
                if(item.isCared){
                    xqzs.weui.tip("已经点赞");
                    return ;
                }
                let _this=this;
                xqzs.api.put(_this,"come/user/like/answer/_userId_/"+item.answerId,{},function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        item.isCared=1;
                        item.likeTimes=item.likeTimes+1;
                        _this.$set(_this.detail.answerList,index,item);
                    }
                })

            },
            formatDateText:function (time) {
                return xqzs.dateTime.getTimeFormatText(time)
            },
            //获取是否有偷听卡
            getCoupon:function () {
                let _this = this;
                xqzs.api.get(_this,'come/user/get/coupon/_userId_/1/10/0',function (data) {
                    _this.couponList = data.data.data;
                    _this.couponNum = data.data.data.length;
                })
            },
            typeDialog:function (questionId ,answerId ,index) {
                if(!xqzs.user.isUserLogin()){
                    return ;
                }
                let _this = this;
                let useCoupon = false;
                let useCoin = false;
                let recharge = false;
                let payTitle,msg,subHtml;
                console.log(questionId,answerId)
                if(_this.couponNum!=0){
                    payTitle = '使用偷听卡免费听';
                    msg='';
                    subHtml='';
                    useCoupon = true;
                }else{
                    payTitle = '确认偷听此问题';
                    subHtml='';
                    msg = '使用：<span class="colorStyle">1</span> 点豆&nbsp&nbsp&nbsp剩余：<span class="colorStyle">'+_this.user.dianCoin+'</span> 点豆';
                    if(_this.user.dianCoin>=1){
                        useCoin = true;
                        console.log(_this.user.dianCoin)
                    }else{
                        recharge = true;
                        console.log(_this.user.dianCoin)
                        subHtml='去充值';
                    }
                }
                xqzs.weui.dialog(payTitle,msg,subHtml,function(){},function () {
                    switch(true)
                    {
                        case useCoupon:
                            console.log('使用偷听券支付');
                            let data = {
                                code:_this.couponList[0].code,
                                questionId:questionId,
                                answerId:answerId
                            };
                            _this.showLoad=true;


                            xqzs.api.put(_this,"come/listen/put/coupon/_userId_",data,function (bt) {
                                if(bt.data.status==1){
                                    xqzs.weui.toast("success","支付成功", function () {
                                        _this.setPayed(index);
                                    });
                                }else{
                                    xqzs.weui.tip("支付失败", function () {

                                    });
                                }
                                _this.getCoupon();
                                _this.showLoad=false;
                            });
                            break;
                        case useCoin:
                            console.log('使用点豆支付');
                            _this.showLoad=true;
                            xqzs.api.put(_this,"come/listen/put/coin/_userId_/"+questionId+'/'+answerId+'/1',data,function (bt) {
                                if(bt.data.status==1){
                                    xqzs.weui.toast("success","支付成功", function () {
                                        _this.setPayed(index);
                                    });
                                }else{
                                    xqzs.weui.tip("支付失败", function () {

                                    });
                                }
                                _this.getUserInfo();
                                _this.showLoad=false;
                            })

                            break;
                        case recharge:
                            _this.rechargeFlag = true;
                            console.log("_this.rechargeFlag" +_this.rechargeFlag )
                            break;
                    }

//                    _this.pay(index)
                })
            },
            pay:function (index) {
                let  item = this.detail.answerList[index];
                let _this=this;
                xqzs.api.get(_this, "come/listen/create/order/_userId_/" + item.answerId, function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        let result = bt.data.data;
                        xqzs.wx.pay.pay(result.order, function () {

                        }, function () {//success
                            xqzs.weui.toast("success", "支付成功", function () {
                                _this.setPayed(index);
                            });
                        }, function () {//error

                        })
                    }
                });
            },
            //设置dom 已经支付
            setPayed:function (index) {
                let item =  this.detail.answerList[index];
                item.answerType=1;
                item.needPay=0;
                this.detail.needPay=0;
                this.detail.isListened = 1;
                this.$set( this.detail.answerList,index,item);
            },
            timeout:function (play,time,index) {
                let _this=this;
                _this.timeOut = setTimeout(function () {
                    if(play==true){  //试听
                        if(time>0){
                            time = time -1 ;
                            if(time<10)time="0"+time
                            _this.timeout(play,time,index);
                        }else{
                            _this.playing=false;
                        }
                    }

                },1000);
                _this.list[index].ct =time;
                console.log(time)
                _this.$set(_this.list,index,_this.list[index])
            },

            clearTimeOut:function () {
                let _this=this;
                if(_this.timeOut!==null){
                    clearTimeout(_this.timeOut);
                }
            },
            play:function (index) {
                let _this=this;
                console.log('bofangle ')
                let list = _this.detail.answerList;
                let CT= list[index].ct? list[index].ct: list[index].length;
                let T = list[index].length;
                console.log(CT)
                xqzs.voice.onEnded=function () {
                    list[index].paused=false;
                    list[index].playing=false;
                    _this.$set(_this.detail.answerList,index,list[index]);
                    if(_this.playing)_this.clearTimeOut();
                    _this.playing = false;
                };
                //重置其他列表内容
                for(let i = 0;i<list.length;i++){
                    if(index!=i&&(list[i].playing||list[i].paused)){
                        list[i].paused=false;
                        list[i].playing=false;
                        _this.$set(_this.detail.answerList,i,list[i]);
                    }
                }
                let item= list[index];
                if(item.paused){  //暂停中也就是已经获取到且为当前音频
                    list[index].paused=false;
                    list[index].playing=true;
                    _this.$set(_this.detail.answerList,index,list[index])
                    xqzs.voice.play();
                    _this.timeout(true,CT,index)
                }else{
                    if(item.playing){    //播放中去做暂停操作
                        list[index].paused=true;
                        list[index].playing=false;
                        _this.$set(_this.detail.answerList,index,list[index])
                        xqzs.voice.pause();
                        _this.clearTimeOut();
                        _this.playing = false;
                    }else{     //重新打开播放
                        let answerId= item.answerId;
                        xqzs.voice.getAnswerVoice(answerId,function (url) {
                            xqzs.voice.play(url);
                            list[index].playing=true;
                            list[index].paused=false;
                            _this.$set(_this.detail.answerList,index,list[index]);
                            _this.playing=true;
                            _this.clearTimeOut();
                            _this.timeout(true,T,index)
                        })
                    }

                }

            },


            getDetail:function () {
                let _this= this;
                _this.showLoad=true;
                xqzs.api.get(_this,'come/listen/question/detail/'+_this.questionId +"/_userId_"+'?comments=3',function (data) {
                    _this.showLoad=false;
                    if (data.body.status == 1) {
                        _this.detail= data.body.data
                        _this.list = _this.detail.answerList;
                        _this.evaluates = _this.detail.evaluates;
                        if(_this.evaluates.length){
                            _this.evaluates_flag = true
                        }
                        _this.isListened = _this.detail.isListened;
                        xqzs.wx.setConfig(_this, function () {
                            var config = {
                                imgUrl:"http://oss.xqzs.cn/resources/psy/logo.jpg",
                                title:  "听解答：" +  _this.detail.content,
                                desc: '价值10元的解忧语音，1点豆即可偷听！‘好一点’你的实用人生导师',
                                link: weshare.getShareUrl("asker/listen/detail?questionId="+ _this.questionId ,true)
                            };
                            weshare.init(wx, config)
                        });
                    }
                },function (error) {
                    _this.showLoad=false;
                })


            },
            goDetail:function (extId) {
                this.$router.push('/asker/expert/detail/?id='+extId)
            },
            follow:function (index) {

                let item = this.list[index];
                let id = item.expertId;
                console.log(item)
                let that=this;
                xqzs.api.put(this,"come/expert/follow/expert/"+id+"/_userId_",{},function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        if(item.isFollowed==0){
                            item.isFollowed==1
                            item.followCount = item.followCount + 1
                            xqzs.weui.toast("success","关注成功")
                        }else{
                            item.isFollowed==0
                            item.followCount = item.followCount - 1
                            xqzs.weui.toast("success","取消成功")
                        }
                        item.isFollowed=!item.isFollowed;
                        that.$set(that.list,index,item);

                    }else if(bt.data.status ==900004){
                        xqzs.weui.toast("success","已经关注",function () {

                        })
                    }else if(bt.data.status ==9000003){
                        xqzs.weui.toast("fail","不能关注自己",function () {

                        })
                    }else {
                        xqzs.weui.toast("fail","关注失败",function () {

                        })
                    }
                });

            },
        },
        beforeDestroy:function () {
            xqzs.voice.pause();
        },

    }


</script>

<style>
    /**新增评价样式**/
    .evaluate_box{
        padding-top: 0.3rem;
        padding-bottom: 1.68rem;
    }
    .evaluate_box h3{
        color:#2EB1FF;
        font-size: 0.36rem;
        line-height: 0.5rem;
        text-align: center;
        font-weight: normal;
        margin-bottom: 0.14rem;
    }
    .evaluate_box .title_border{
        width:0.62rem;
        height:0.06rem;
        background: #D8D8D8;
        border-radius: 0.03rem;
        margin: 0 auto;
    }
    .evaluate_box li{
        margin-left: 0.28rem;
        border-bottom: 0.02rem solid #eee;
        position: relative;
        padding-top: 0.3rem;
    }
    .evaluate_box li img{
        width:0.6rem;
        height:0.6rem;
        position: absolute;
        border-radius: 50%;
    }
    .evaluate_box li .eva_main{
        padding-left: 0.9rem;
        padding-right: 0.2rem;
        color:rgba(3, 3, 3, 1);
        font-size: 0.3rem;
        line-height: 0.42rem;
    }
    .evaluate_box li .eva_main .eva_name{
        color:rgba(3, 3, 3, 0.5);
        font-size: 0.28rem;
        line-height: 0.4rem;
        margin-bottom: 0.1rem;
    }
    .evaluate_box li .eva_main .eva_content{
        margin-bottom: 0.14rem;
    }
    .evaluate_box .eva_main .eva_time{
        color:rgba(3, 3, 3, 0.5);
        font-size: 0.24rem;
        line-height: 0.34rem;
        margin-bottom: 0.3rem;
    }
    .evaluate_box .eva_btn{
        width:1.8rem;
        line-height: 0.6rem;
        text-align: center;
        border-radius: 0.36rem;
        color:rgba(202, 201, 203, 1);
        border:0.02rem solid rgba(202, 201, 203, 1);
        font-size: 0.28rem;
        margin: 0.3rem auto;
    }
    .evaluate_block{
        bottom:0;
        width: 100%;
        background: RGBA(255, 255, 255, 1);
        position: fixed;
        left: 0;
        z-index: 10000;
        border-top: 0.02rem solid RGBA(238, 238, 238, 1);
        height:1.42rem;
        padding-top: 0.24rem;
    }
    .evaluate_block>div{
        color:RGBA(69, 75, 84, 1);
        font-size: 0.3rem;
        border-radius: 0.1rem;
        background: RGBA(244, 244, 247, 1);
        line-height: 0.68rem;
        margin:0 0.3rem;
    }
    .evaluate_block>div img{
        width:0.36rem;
        display: inline-block;
        margin-left:0.2rem;
        margin-right: 0.12rem;
    }
    .comment_box2 .stars{ display: flex;margin-bottom: 0.37rem}
    .comment_box2 .stars li{ flex:1;}
    .comment_box2 .stars li .star{ background: url(http://oss.xqzs.cn/resources/psy/asker/ask_rack_comment_star.png) center no-repeat ; background-size:  0.48rem;;height:0.48rem; width: 0.48rem; color:#999; width: 100%; margin-bottom: 0.10rem; }
    .comment_box2 .stars li .text{color:#999 ; font-size: 0.24rem; text-align: center}
    .comment_box2 .stars li.on .star{background: url(http://oss.xqzs.cn/resources/psy/asker/ask_rack_comment_star_on.png) center no-repeat ; background-size:  0.48rem;}
    .comment_box2 .stars li.on .text{ color:#ffaa00}

    .comment_box2 .textarea{ width: 100%; height:2.38rem;position: relative;background: #f1f1f1; border-radius: 0.07rem;overflow: hidden;margin-bottom: 0.10rem; }
    .comment_box2 .textarea .anFlag{position: absolute;right:0.10rem;bottom:0.10rem;color:RGBA(69, 75, 84, 0.49);font-size: 0.24rem;background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_no.png")no-repeat left center;background-size: 0.28rem;padding-left: 0.34rem;line-height: 0.34rem;z-index:1000;height:0.32rem;}
    .comment_box2 .textarea .anFlag_on{background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_on.png") no-repeat left center;background-size: 0.28rem;}
    .comment_box2 .textarea  textarea{ border: none; width: 92% ;font-size: 0.30rem; line-height: 0.41rem; height: 1.70rem;background: #f1f1f1;padding-top: 0.20rem;}
    .listenDetail_box{
        background: #fff;
    }
    .steal_expert_info{
        padding-left: 1.42rem;
        padding-bottom: 0.51rem;
    }
    .position_change_detail{
        color:#fff;
        position: absolute;
        top: 0.27rem;left:0.51rem; font-size: 0.30rem;
    }
    .position_change2{ margin-top: 0.34rem;}
    .pay_listen{ width: 3.52rem; height: auto}
    .steal_detail_header{
        padding:0.24rem 0.30rem 0.40rem 0.30rem;
        background: #fff;
        border-bottom: 0.20rem solid #f4f4f7;
    }
    .steal_detail_top{
        display: flex;
        display: -webkit-flex;
        display: -webkit-box;
        color: rgba(36,37,61,0.5);
        font-size: 0.24rem;
        line-height: 0.68rem;
        position: relative;
    }
    .steal_detail_top .steal_detail_top_price{
        position: absolute;
        right:0.30rem;
    }
    .steal_detail_top img{
        display: block;
        height:0.68rem;
        width:0.68rem;
        border-radius: 50%;
        margin-right: 0.20rem;
        margin-bottom: 0.24rem;
    }
    .steal_detail_top span{
        color: rgba(36,37,61,1);
    }
    .steal_detail_content{
        color: rgba(36,37,61,1);
        font-size:0.30rem;
        line-height: 0.46rem;
        overflow: hidden;
        word-wrap:break-word;
    }
    .steal_detail_answer{
        background: #fff;
        /*margin-bottom: 0.20rem;*/
    }
    .steal_answer_top{
        display: flex;
        display: -webkit-flex;
        display: -webkit-box;
        height:0.96rem;
        line-height: 0.96rem;
        color: rgba(36,37,61,0.5);
        font-size: 0.28rem;
        padding-bottom: 0.40rem;
        margin-left: 0.88rem;
        margin-right: 0.60rem;
        margin-bottom: 0.20rem;
        border-bottom: 0.02rem solid #eee;
    }
    .steal_answer_topimg{
        display: block;
        height:1.02rem;
        width: 1.02rem;
        border-radius: 50%;
        margin-right: 0.20rem;
    }
    .steal_answer_yy{
        position: relative;
        margin-top:0.06rem;
        margin-right:0.14rem;
    }

    .steal_answer_ly{
        display: block;
        height:0.90rem;
        width:auto;
        border-radius: 0;
    }
    .steal_answer_zan{
        color:rgba(36,37,61,0.5);
        font-size: 0.24rem;
        height:0.32rem;
        line-height: 0.32rem;
        padding-bottom:0.20rem;
        padding-right: 0.60rem;
        padding-left: 0.88rem;
        border-bottom: 0.20rem solid #f4f4f7;
    }
    /*.steal_answer_zan div{*/
    /*float: right;*/
    /*margin-right: 0;*/
    /*}*/

    /*.steal_answer_zan div:nth-of-type(2){*/
    /*margin-right:0.42rem;*/
    /*}*/
    .steal_answer_zan div:nth-of-type(1) img{
        float: left;
        margin-right:0.10rem;
    }
    .steal_answer_zan div:nth-of-type(1) span{
        float: left;
        margin-top:0.00rem;
    }
    .steal_answer_sond{
        display: block;
        margin-top:-0.22rem;
        height:0.32rem;
        position: absolute;
        top:50%;
        left:0.30rem;
    }


</style>
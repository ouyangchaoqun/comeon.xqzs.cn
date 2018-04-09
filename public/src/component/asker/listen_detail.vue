<template >
    <div class="listenDetail_box" :class="{listenDetail_box_index:rechargeFlag}">
        <div v-title class='hide_title'>问题详情</div>
        <v-recharge  v-if="rechargeFlag"  :rechargeMoney="rechargeMoney" :user="user" v-on:childMessage="getFlagVal"></v-recharge>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-scroll   :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :isShowMoreText="isShowMoreText" :bottomHeight="height">
            <!--详情头部-->
            <div class="listenDetail_inner_box">


            <div class="steal_detail_header" v-if="detail.title">
                <div class="steal_detail_top">
                    <img v-if="!detail.isAnonymous" :src="detail.faceUrl" alt="">
                    <img v-if="detail.isAnonymous" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                    <div class="base_info">
                        <div class="name"><template v-if="!detail.isAnonymous">{{detail.nickName}}</template><template v-if="detail.isAnonymous">匿名用户</template> 咨询</div>
                        <div class="base_title">{{detail.title}}</div>
                    </div>



                </div>
                <div class="steal_detail_content">{{detail.content}}</div>
            </div>
            <!--专家回答列表-->
            <ul>
                <li class="steal_detail_answer" v-for="(item,index) in detail.answerList">

                    <div class="steal_expert_info">
                        <img :src="item.expertUrl" alt="" @click="goDetail(item.expertId)">
                        <block v-if="item.userId!=1658&&item.userId!=424">
                            <img style="left: 0.82rem;" src="http://oss.xqzs.cn/resources/psy/asker/header_img_v.png" class="expert_v">
                        </block>

                        <div>
                            <span class="steal_expert_name" @click="goDetail(item.expertId)">{{item.expertName}}</span><span class="steal_expert_fans">{{item.followCount}} 人关注</span>
                        </div>
                        <!--<div class="steal_expert_des">{{item.sign}}</div>-->
                        <div class="steal_expert_jobTitle">{{item.jobTitle}}</div>
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
                                <span>{{item.listenTimes}} 人听过</span>
                            </div>
                            <div @click="like(index)" >
                                <span>{{item.likeTimes}}</span>
                                <img v-if="!item.isCared" src="http://oss.xqzs.cn/resources/psy/asker/zan_nor.png" class="care_icon"/>
                                <img v-if="item.isCared" src="http://oss.xqzs.cn/resources/psy/asker/zan_por1.png" class="care_icon"/>
                            </div>
                        </div>

                    </div>
                </li>

            </ul>
            <!--新增评价列表-->
            <div class="evaluate_box" v-if="evaluates.length>0">
                <h3>用户留言</h3>
                <div class="title_border"></div>
                <ul>
                    <li v-for="(item,index) in evaluates" @click.stop="commentOrDel(item.userId,item.id,item.questionId,item.nickName,index,item.replies,evaluates,item.userExpertNickName)">

                       <block v-if="item.userExpertId&&item.userId!=1658&&item.userId!=424">
                           <img :src="item.userExpertFaceUrl" alt="" @click.stop="goExpert(item.userExpertId)">
                           <img src="http://oss.xqzs.cn/resources/psy/asker/header_img_v.png" class="expert_v">
                       </block>
                        <block v-else>
                            <img v-if="item.isAnonymous==0" :src="item.faceUrl" alt="">
                            <img v-if="item.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                        </block>

                        <div class="eva_main">
                            <block v-if="item.userExpertId&&item.userId!=1658&&item.userId!=424">
                                <div class="eva_name" @click.stop="goExpert(item.userExpertId)">{{nameSub(item.userExpertNickName)}} <span class="expert_line"></span> <span> {{item.userExpertJobTitle}}</span></div>
                            </block>
                            <block v-else>
                            <div class="eva_name" v-if="item.isAnonymous==0">{{item.nickName}}</div>
                            <div class="eva_name" v-if="item.isAnonymous==1">匿名用户</div>
                            </block>
                            <div class="eva_content">{{item.content}}</div>
                            <div class="eva_time">
                                {{getFormatDate(item.addTime)}}
                            </div>
                            <div class="eva_commont_box" v-if="item.replies&&item.replies.length>0">
                                <div class="friend_commont"  v-for="(reply,replyIndex) in item.replies" :key="replyIndex" @click.stop="commentOrDel(reply.userId,reply.id,item.questionId,reply.nickName,replyIndex,item.replies,null,null)">
                                <span class="name" v-if="reply.toEvaluateId==0||reply.toEvaluateId==null">
                                    <template >{{reply.nickName | shortName(7)}}</template>：</span>
                                    <template v-if="reply.toEvaluateId!=0&&reply.toEvaluateId!=null"><span class="name">
                                        <template >{{reply.nickName | shortName(7)}}</template>
                                    </span> 回复
                                        <span class="name">
                                        <template>{{reply.toNickName | shortName(7)}}</template>：
                                        </span>
                                    </template><span class="commont">{{reply.content}}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>
         </div>
        </v-scroll>

    </div>

</template>
<script>
    import Bus from '../bus.js';
    import scroll from '../include/scroll.vue';
    import showLoad from '../include/showLoad.vue';
    import Recharge from '../asker/my/recharge.vue' ;
    export default {
        data() {
            return {
                comments:[{v:1,t:'极差'},{v:2,t:'不满意'},{v:3,t:'一般'},{v:4,t:'满意'},{v:5,t:'极满意'}],
                commentValue:0,
                questionId:0,
                detail:{},
                isShowMoreText:false,
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
                evaluates_flag:false,
                page:2,
                row:5,
                isPageEnd:false,
                isLoading:false,
                isAnonymous:false,
                anonyVal:0,
                height:xqzs.equipment.tabHeight(),
                lengthLock:false
            }
        },
        beforeRouteLeave (to, from, next) {
            if(this.rechargeFlag){
                this.rechargeFlag=false;
                next(false)
            }else{
                next()
            }
        },

        activated: function () {
            this.initAll();
        },
        props:
           ['user','isKeepAlive']
        ,
        components: {
            'v-scroll': scroll,
            'v-recharge':Recharge,
            'v-showLoad': showLoad,
        },
        filters:{
            shortName:function(value,len){
                return xqzs.shortname(value,len);
            }
        },
        methods:{
            nameSub:function (str) {
                str = str||'';
                if(str.length>6){
                    return str.substring(0,6) + '...'
                }else{
                    return str
                }

            },
            goExpert: function (id) {
                this.$router.push("/asker/expert/detail?id=" + id);
            },
            initTextareaVal:function () {
                $('.comment_text').val('');
                $('#textarea').height('');
                this.height = xqzs.equipment.tabHeight()
            },
            initAll:function () {
                this.questionId=this.$route.query.questionId;
                this.getDetail();
                this.getCoupon();
                this.getUserInfo();
                 this.$nextTick(function () {
                    this.levMsg(true);
                })
            },
            //默认留言框
            levMsg:function (isAuto) {
                let _this= this;
                _this.actionSheetEdit( "发布", function (value) {
                    if(value.length<8){
                        xqzs.weui.tip('不能少于8个字')
                        return
                    }
                    //回复操作
                    xqzs.api.put(_this, "come/user/evaluate/question",{userId:"_userId_",questionId :_this.detail.questionId,content:value,isAnonymous :_this.anonyVal},function (bt) {

                        if (bt.data && bt.data.status == 1) {
                            let msg = bt.data.data;
                            xqzs.weui.toast('success','留言成功',function () {
                                _this.initTextareaVal()
                            });
                            let  stuckMessage = {
                                faceUrl:_this.user.faceUrl,
                                content:value,
                                nickName: _this.user.nickName,
                                isAnonymous:_this.anonyVal,
                                addTime:parseInt(new Date().getTime()/1000),
                                userId :msg.userId,
                                id:msg.id
                            };
                            _this.evaluates.splice(0,0,stuckMessage); //插入第一条
                            console.log(_this.evaluates)
                        }
                    })
                }, function () {

                }, '我也来说两句',false,false,isAuto);

                $('.comment_box').attr({'minHeight':_this.height})
            },
            //留言或删除
            commentOrDel:function (userId,msgId,questionId,name,index,item,bigItem,expertName) {
                let vm = this;
                console.log(userId,msgId,questionId,name,index,item);
                if(!xqzs.user.isUserLogin())return
                if(userId==vm.user.id){
                    console.log('删除自己的留言评论')
                    vm.delItem(userId,msgId,index,item,bigItem)

                }else{
                    console.log('对别人的评论进行评论')
                    vm.replyItem(msgId,questionId,name,item,expertName)
                }
            },
            delItem:function (userId,messageId,index,item,bigItem) {

                let _this = this;
                xqzs.weui.actionSheet("删除我的评论?","删除",function () {
                    ///删除操作
                    console.log('删除')
                    xqzs.api.post(_this, "come/user/evaluate/remove",{userId:userId,id:messageId},function (bt) {
                        if (bt.data && bt.data.status == 1) {
                            console.log('删除成功')
                            if(bigItem){
                                bigItem.splice(index,1);
                                if(bigItem.length==0){
                                    _this.page = 1;
                                    _this.getCommentList()
                                }
                            }else if(item){
                                item.splice(index,1)
                            }
                        }
                    })
                },function () {
                    //取消
                    console.log('取消')
                })
            },

            //对留言进行回复、对别人的回复进行回复
            replyItem:function (id,questionId,nickName,item,expertName) {
                let vm = this;
                let showName;
                if(expertName){
                    showName=expertName
                }else{
                    showName = nickName
                }
                vm.actionSheetEdit("发送", function (v) {
                    if(v.length<8){
                        xqzs.weui.tip('不能少于8个字')
                        return
                    }
                    xqzs.api.put(vm,'come/user/evaluate/question', {
                        "id": id,
                        "userId":"_userId_",
                        "content": v,
                        "questionId":questionId,
                    },function (response) {
                        if (response.data.status === 1) {
                            let msg = response.data.data;
                            console.log(msg)
                            xqzs.weui.toast("success", "回复成功", function () {
                                vm.initTextareaVal()
                                vm.levMsg()
                            });
                            let  stuckMessage = {
                                content:v,
                                nickName: vm.user.nickName,
                                userExpertNickName:expertName,
                                toNickName:nickName,
                                id:msg.id,
                                questionId:questionId,
                                toEvaluateId:msg.toEvaluateId,
                                toUserId:msg.toUserId,
                                userId:vm.user.id,
                            };
                            item.push(stuckMessage);
                        }
                    })
                    console.log(v)

                }, function (v) {
                    console.log(v)
                    //取消
                }, "回复" + xqzs.shortname(showName,7))
            },
            actionSheetEdit: function ( sendText, doFun, cancelFun, placeholder,maxLength,noHide,isAuto) {
                let _this = this;
                if(!maxLength){
                    maxLength=1000;
                }
                //判断是否已经存在输入框
                if ($("#action_sheet_edit") && $("#action_sheet_edit").hasClass("action-sheet-edit")) {
                    $("#action_sheet_edit").remove()
                }
                xqzs.weui.textareaAutoOldHeight = xqzs.weui.textareaAutoBaseH;
                xqzs.weui.textareaHeight = [];
                var html = '<div class="action-sheet-edit" id="action_sheet_edit">';
                html += ' <div class="comment_box bottom_comment" style="' ;

                if(xqzs.equipment.isIphoneX()){
                    html +=' bottom:40px';
                }

                html += '">';
                html += '  <span class="release release_btn">' + sendText + '</span>';
                html += '<div class="box"><textarea contenteditable="true" maxlength="'+maxLength+'" class="comment_text" id="textarea" placeholder="' + placeholder + '" oninput="xqzs.weui.textareaAutoHeight();"></textarea></div>';
                if(xqzs.isIos()){
                    html +='<div style=" height: 44px;    background: #f5f5f5;width: 100%;position: absolute;bottom: -44px;text-align: center;font-size: 12px;color: #ddd; line-height: 30px">一切都好一点</div>';
                }

                html += '  </div>';
                html += '  </div>';

                $("body").append(html);


                var timeout ;
                // //解决第三方软键盘唤起时底部input输入框被遮挡问题
                // var bfscrolltop = document.body.scrollTop;//获取软键盘唤起前浏览器滚动部分的高度
                //
                $(".comment_text").focus(function () {
                    xqzs.weui.textareaHover=true;
                    xqzs.weui.actionSheetEditTimeout();
                }).blur(function () {
                    xqzs.weui.textareaHover=false;
                    let h= xqzs.equipment.isIphoneX()?'40px':0
                    $(".comment_box").animate({bottom: h}, 150)
                });
                //.blur(function () {//设定输入框失去焦点时的事件
                //     clearTimeout(interval);//清除计时器
                //      document.body.scrollTop = bfscrolltop;//将软键盘唤起前的浏览器滚动部分高度重新赋给改变后的高度
                // });
                if(!isAuto){
                    $(".comment_text").focus()
                }

                $(".comment_text").keyup(function () {
                    _this.height = $('.comment_box').outerHeight();
                    var val = $(this).val();
                    if (val.length >= 8) {

                        $(".action-sheet-edit .release_btn").css({'borderColor': "rgba(86, 196, 254, 1)", "background": "rgba(86, 196, 254, 1)"})
                        $(".comment_p").css('display', 'none')
                    } else {
                        $(".action-sheet-edit .release_btn").css({'borderColor': "RGBA(86, 196, 254, 0.1)", "background": "RGBA(86, 196, 254, 0.5)"})
                        $(".comment_p").css('display', 'block');
                    }
                });


                setTimeout(function () {
                    $(".comment_box").removeClass('subactive').addClass("addactive");
                }, 10);

                $(".action-sheet-edit .cancel").click(function () {
                    xqzs.weui.weuiMaskClose();
                    $(".comment_box").removeClass('addactive').addClass("subactive");
                    $(".action-sheet-edit").delay(100).animate({opacity: 0}, 200, function () {
                        $(".action-sheet-edit").remove();
                        cancelFun();
                    });
                });
                $(".comment_box .release_btn").click(function () {
                    var v = $(".comment_text").val();
                    if (v !== "") {
                        doFun(v);
                    }
                    if(noHide){

                    }else{
                        $(".comment_box").removeClass('addactive').addClass("subactive");
//                        $(".action-sheet-edit").delay(100).animate({opacity: 0}, 200, function () {
//                            $(".action-sheet-edit").remove();
//                        });
                    }


                })
            },
            getUserInfo:function(){
                let _this=this;
                _this.showLoad = true
                xqzs.user.getUserInfo(function (user) {
                    _this.user =user;
                    _this.showLoad = false
                })
            },

            setAnonymous:function () {
                this.isAnonymous = !this.isAnonymous;
                if(this.isAnonymous){
                    this.anonyVal = 1
                }else {
                    this.anonyVal = 0
                }
            },
            getFormatDate:function (t) {
                return xqzs.dateTime.formatLevMsgDate(t)
            },
            onInfinite(done) {
                 this.getCommentList(done);

            },
            getCommentList:function (done) {
                let _this = this;
                if (_this.isLoading || _this.isPageEnd) {
                    return;
                }
                let questionId = _this.detail.questionId;
                 _this.isLoading = true;
                xqzs.api.get(_this,'come/comment/query/page/question/'+questionId+'/'+this.page+'/'+this.row,function(data){
                      _this.isLoading = false;
                    if(done&&typeof(done)==='function'){
                        done()
                    }
                    if (data.body.status == 1) {

                        _this.page++;
                        let arr =  data.data.data;
                        if(arr.length<_this.row){
                            _this.isPageEnd = true;
                            _this.isShowMoreText = false;
                        }
                        _this.evaluates = _this.evaluates.concat(arr);

                    }
                },function (error) {
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
                        item.likeTimes=parseInt(item.likeTimes)+1;
                        _this.$set(_this.detail.answerList,index,item);
                    }
                    if(bt.data.status==800003){
                        xqzs.weui.tip("偷听后才能点赞哦");
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

            pause:function (index) {
                let  _this=this;
                _this.clearTimeOut();
                let list = _this.list;
                list[index].paused = true;
                list[index].playing = false;
                _this.currPlayIndex = null;
                _this.$set(_this.list, index, list[index])
                xqzs.voice.pause();
            },


            play:function (index) {
                let _this=this;
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
                    _this.currPlayIndex=index;
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
                            _this.currPlayIndex=index;
                            _this.clearTimeOut();
                            _this.timeout(true,T,index)
                        })
                    }

                }

            },
            getDetail:function () {
                let _this= this;
                xqzs.api.get(_this,'come/listen/question/detail/'+_this.questionId +"/_userId_"+'?comments=5',function (data) {

                    if (data.body.status == 1) {
                        _this.detail= data.body.data
                        _this.list = _this.detail.answerList;
                        _this.evaluates = _this.detail.evaluates; //获取评论列表
                        if(_this.evaluates.length>=_this.row){
                            _this.isShowMoreText = true;

                        }
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
        deactivated:function () {
            if(this.currPlayIndex!=null)this.pause(this.currPlayIndex);
        },


    }


</script>

<style>
    /**jobTitle**/
    .steal_expert_jobTitle{
        color:#2EB1FF;
        font-size: 0.28rem;
        padding-top: 0.16rem;
        line-height: 1;
    }
    /**留言回复**/
    .eva_commont_box{
        background: #f9f9f9;
        padding:0.1rem 0.2rem;
        margin-bottom: 0.3rem;
        border-radius: 0.1rem;
        font-size: 0.26rem;
        color:RGBA(69, 75, 84, 1);
        position: relative;
    }
    .eva_commont_box:before{ content: '';  width: 0; height: 0;border-left:0.2rem solid transparent;
        border-right: 0.2rem solid transparent;
        border-bottom:  0.2rem solid #f9f9f9; position: absolute; top:-0.15rem;
        display: block;}
    .eva_commont_box .name{
        color:#5e61a2;
        font-weight: 600;
    }


        /**底部input样式**/
    .bottom_comment{
        z-index: 900;
    }
    .comment_box .release_btn{
        background: rgba(86, 196, 254, 0.5);
        border:1px solid  rgba(86, 196, 254, 0.1);
    }
    .comment_box .anonymous_active{
        background: rgba(86, 196, 254, 1);
        color:#fff;
    }
    .listenDetail_inner_box{
        overflow-y: scroll !important;
    }
    /**评价框**/
    .evaluation_frame{
        width:84%;
        background: #fff;
        border-radius: 0.2rem;
        position: fixed;
        webkit-transform: translate(-50%,-50%);
        transform: translate(-50%,-50%);
        top:50%;
        left:50%;
        padding:0.36rem 0.2rem 0.5rem 0.2rem;
        z-index: 10001;
    }
    .evaluation_frame .frame_title{
        font-size: 0.4rem;
        color:RGBA(69, 75, 84, 1);
        line-height: 0.56rem;
        text-align: center;
        margin-bottom: 0.28rem;
    }
    .evaluation_frame .frame_close{
        width:0.32rem;height:0.32rem;
        background: url('http://oss.xqzs.cn/resources/psy/asker/user_close.png') no-repeat;
        background-size: 100% 100%;
        position: absolute;
        right:0.3rem;
        top:0.3rem;
    }
    .evaluation_frame .stars{ display: flex;margin-bottom: 0.44rem}
    .evaluation_frame .stars li{ flex:1;}
    .evaluation_frame .stars li .star{ background: url(http://oss.xqzs.cn/resources/psy/asker/ask_rack_comment_star.png) center no-repeat ; background-size:  0.66rem;;height:0.66rem; width: 0.66rem; color:#999; width: 100%; margin-bottom: 0.26rem; }
    .evaluation_frame .stars li .text{color:#999 ; font-size: 0.28rem; text-align: center;line-height: 0.4rem;}
    .evaluation_frame .stars li.on .star{background: url(http://oss.xqzs.cn/resources/psy/asker/ask_rack_comment_star_on.png) center no-repeat ; background-size:  0.66rem;}
    .evaluation_frame .stars li.on .text{ color:RGBA(245, 166, 35, 1)}
    .frame_textarea{height:2.2rem;border:0.02rem solid RGBA(238, 238, 238, 1);border-radius: 0.12rem;margin-bottom: 0.5rem;padding:0.2rem;position: relative}
    .frame_textarea textarea{color:rgba(36,37,61,1);outline: none;border:0;width:100%;font-size: 0.28rem;line-height: 0.4rem;height:1.6rem;}
    .frame_btn{color:RGBA(255, 255, 255, 1);font-size: 0.36rem;width:80%;line-height: 0.88rem;border-radius: 0.1rem;text-align: center;margin: 0 auto;background: RGBA(86, 196, 254, 0.5);}
    .frame_btn_active{background: RGBA(86, 196, 254, 1);}
    .evaluation_frame .frame_textarea .anFlag{position: absolute;right:0.2rem;bottom:0.2rem;color:RGBA(69, 75, 84, 0.49);font-size: 0.24rem;background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_no.png")no-repeat left center;background-size: 0.28rem;padding-left: 0.34rem;line-height: 0.34rem;z-index:1000;height:0.32rem;}
    .evaluation_frame .frame_textarea .anFlag_on{background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_on.png") no-repeat left center;background-size: 0.28rem;}
    .pageEndStyle{text-align: center;font-size: 0.28rem;color:#999;line-height: 0.4rem;padding: 0.2rem;}
    /**新增评价样式**/
    .evaluate_box{
        padding: 0.3rem 0;
    }
    .evaluate_box h3{
        color:#2EB1FF;
        font-size: 0.30rem;
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
        margin-left: 0.3rem;
        border-bottom: 0.02rem solid #eee;
        position: relative;
        padding-top: 0.3rem;
        margin-right: 0.3rem;
    }
    .evaluate_box li img{
        width:0.8rem;
        height:0.8rem;
        position: absolute;
        border-radius: 50%;
    }

    .evaluate_box li .expert_line{ height: 0.28rem; width: 0.02rem; background: #00b9e8;display: inline-block; position: relative; vertical-align: middle; margin-left: 0.12rem; margin-right: 0.06rem;}
    /*.evaluate_box li .expert_line:before{ content: ''; display: inline-block; position: absolute; height: 0.04rem; width: 0.04rem; border-radius: 50%;left:-0.02rem; top:0.12rem; background:#00b9e8;}*/
    .listenDetail_box .expert_v{
        width:0.28rem;
        height:0.28rem;
        border-radius: inherit;
        top:0.84rem; left:0.52rem;
    }
    .evaluate_box li .eva_main{
        padding-left: 1rem;
        color:rgba(69, 75, 84, 1);
        font-size: 0.28rem;
     }
    .evaluate_box li .eva_main .eva_name{
        color:#999;
        font-size: 0.28rem;
        line-height: 1;
        padding-top: 0.07rem;
        margin-bottom: .12rem;
    }
    .evaluate_box li .eva_main .eva_name span{ color:#2EB1FF}
    .evaluate_box li .eva_main .eva_content{
        margin-bottom: 0.14rem;
        word-wrap:break-word
    }
    .evaluate_box .eva_main .eva_time{
        color:#999;
        font-size: 0.24rem;
        line-height: 0.34rem;
        margin-bottom: 0.3rem;
    }
    .evaluate_box .eva_main .eva_time span{
        color:#5e61a2;
        float: right;
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
        background: #f4f4f7;
        position: absolute;
        left: 0;
        z-index: 999;
        border-top: 0.02rem solid RGBA(238, 238, 238, 1);
        height:1.42rem;
        padding-top: 0.24rem;
    }
    .canEvaluate{
        display: block;
    }
    .evaluate_block>div{
        color:RGBA(69, 75, 84, 1);
        font-size: 0.3rem;
        border-radius: 0.1rem;
        line-height: 0.68rem;
        margin:0 0.3rem;
        text-align: center;
    }
    .evaluate_block>div img{
        width:0.36rem;
        display: inline-block;
        margin-left:-0.2rem;
        margin-right: 0.12rem;
    }
    .listenDetail_box{
        background: #fff;
    }
    .listenDetail_box_index{
        z-index: 990 !important;
    }
    .steal_expert_info{
        padding-left: 1.30rem;
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
        color: #999;
        font-size: 0.28rem;
        line-height: 1.6;
        position: relative;
    }



    .steal_detail_top .steal_detail_top_price{
        position: absolute;
        right:0.30rem;
    }
    .steal_detail_top img{
        display: block;
        height:0.8rem;
        width:0.8rem;
        border-radius: 50%;
        margin-right: 0.20rem;
        margin-bottom: 0.24rem;
    }
    .steal_detail_top span{
        color: rgba(36,37,61,1);
    }
    .steal_detail_content{
        color: rgba(69, 75, 84, 1);
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
        color: #999;
        font-size: 0.28rem;
        padding-bottom: 0.40rem;
        margin-left: 1.3rem;
        margin-right: 0.30rem;
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
        color:#999;
        font-size: 0.24rem;
        height:0.32rem;
        line-height: 0.32rem;
        padding-bottom:0.20rem;
        padding-right: 0.30rem;
        padding-left: 1.3rem;
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
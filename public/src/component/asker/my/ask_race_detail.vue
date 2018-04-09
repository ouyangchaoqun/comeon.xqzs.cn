<template id="rob_problem">

    <div class="rob_problem">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title class='hide_title'>问题详情</div>
        <div class="my_problem_detail">
            <div class="problem_detail_header">
                <!--问题类型:  <div class="race_titleColor">{{detail.title}}</div>-->
                <img :src="user.faceUrl" alt="">
                <div>{{user.nickName}}</div>
                <div>{{detail.title}}</div> <!--赏金￥{{detail.price}}-->
            </div>
            <div class="problem_detail_content">
                {{detail.question}}
            </div>

            <!--const QUESTION_CONTINUE = 0;//问题还在进行-->
            <!--const QUESTION_FINISH = 1;//问题结束有最佳答案-->
            <!--const QUESTION_FINISH_NOT_BEST = 2;//问题结束但是没有最佳答案-->
            <!--const QUESTION_NOT_PAY = 3;//问题还未支付-->
            <!--超时未解答-->
            <div class="rob_status_box" v-if="detail.questionStatus==2&&detail.answerCount==0">
                <div>未解答 </div>
                <div>超过<span>48</span>小时，无咨询师抢答你的问题，赏金已全额退还至你的微信钱包！</div>
            </div>
            <!--正在进行中-->
            <div class="rob_status_box"  v-if="detail.questionStatus==0&&detail.answerCount==0">
                <div class="last_red_color">还{{formatTimeLastText(detail.endTime)}} </div>
                <div>还没有咨询师抢答</div>
            </div>
            <div class="rob_status_box" v-if="detail.questionStatus==0&&detail.answerCount!=0">
                <div class="last_red_color">还{{formatTimeLastText(detail.endTime)}} </div>
                <div class="last_red_color">已有<span> {{detail.answerCount}} </span>位咨询师抢答，在48小时内选出最佳答案，你将免费获得<span>1</span>颗点豆！
                </div>
            </div>
            <div class="rob_status_box" v-if="detail.questionStatus==1">
                <div>已解答 </div>
                <div v-if="!detail.evaluate_ed">
                    共有<span> {{detail.answerCount}}&nbsp;</span>位咨询师抢答，{{bestAnswer.expertNickName}}的回答被选为最佳答案。<br/><span class="last_red_color">"去评价"老师的回答，将免费获得<span>1</span>颗点豆！</span>

                </div>
                <div v-if="detail.evaluate_ed">
                    共有<span> {{detail.answerCount}}&nbsp;</span>位咨询师抢答，{{bestAnswer.expertNickName}}的回答被选为最佳答案，你已免费获得<span>2</span>颗点豆！

                </div>
            </div>
            <div class="rob_status_box" v-if="detail.questionStatus==2&&detail.answerCount>0">
                <div>已解答 </div>
                <div>共有<span>{{detail.answerCount}}</span>位咨询师抢答，咨询师们已平分赏金！</div>
            </div>


            <ul class="rob_lists">
                <li v-for="(item,index) in detail.answers">
                    <div class="comment_btn"  @click="showCommentBox()"  v-if="detail.bestAnswerId!=0&&detail.questionStatus!=0&&detail.bestAnswerId==item.answerId&&!detail.evaluate_ed">
                            去评价
                    </div>
                    <div class="rob_box_top">
                        <img :src="item.expertFaceUrl" alt="" @click="goExpert(item.expertId)">
                        <img class="expert_v" src="http://oss.xqzs.cn/resources/psy/asker/header_img_v.png" alt="">
                        <div class="expertInfo">
                            <span>{{item.expertNickName}}</span>
                            <div><template v-for="(d,i) in item.domain">{{d.title}}<template v-if="i<item.domain.length-1">、</template></template></div>
                        </div>
                    </div>
                    <div class="rob_answer">
                        <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                            <div class="audio_btn" @click.stop="play(index)">
                                <template v-if="!item.playing&&!item.paused">点击播放</template>
                                <template v-if="item.playing">正在播放..</template>
                                <template v-if="item.paused">播放暂停</template>
                                <div class="second">{{(item.ct && item.ct!='00')?item.ct:item.voiceLength}}”</div>
                            </div>

                            <div class="clear"></div>
                            <div class="addBest_text" v-if="detail.bestAnswerId!=0&&detail.questionStatus!=0&&detail.bestAnswerId==item.answerId" >此回答为<a>最佳答案</a>，答主获得全部赏金</div>
                        </div>


                        <!--选择最佳答案选框-->
                        <div v-if="detail.bestAnswerId==0&&detail.questionStatus==0" @click="selectBestAnswerId(item)" class="best_answer" :class='[{best_answer_true:selBestAnswerId==item.answerId}]'></div>
                        <!--最佳确定-->
                        <div class="best_getMoney" v-if="detail.bestAnswerId!=0&&detail.questionStatus!=0&&detail.bestAnswerId==item.answerId" >
                            <div>￥{{detail.price}}</div>
                            <img src="http://oss.xqzs.cn/resources/psy/asker/getmoney.png" alt="">
                        </div>
                        <!--未选择最佳平分赏金-->
                        <div class="deuce_money_box" v-if="detail.bestAnswerId==0&&detail.questionStatus==2">
                            ￥{{formatPrice(detail.price/detail.answerCount)}}
                        </div>
                    </div>
                    <div class="problem_answer_bottom rob_answer_bottom">
                        <div class="problem_answer_time">{{formatDateText(item.addTime)}}</div>
                        <div class="problem_answer_zan">
                            <div><span>{{item.ListenTimes}} 人听过</span></div>
                            <div @click="like(index)">
                                <span> {{item.likeTimes}}</span>
                                <img v-if="!item.isLiked" src="http://oss.xqzs.cn/resources/psy/asker/zan_nor.png" class="care_icon"/>
                                <img v-if="item.isLiked" src="http://oss.xqzs.cn/resources/psy/asker/zan_por1.png" class="care_icon"/>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--评价弹窗-->
        <div v-if="evaluation_frame_flag">
            <div class="weui-mask" @click="frameClose()"></div>
            <div class="evaluation_frame">
                <div class="frame_title">评价</div>
                <div class="frame_close" @click="frameClose()"></div>
                <ul class="stars">
                    <li  v-for="(item,index) in comments" @click="getStar(item.v)"  :class="{on:item.v<=commentValue}" >
                        <div class="star"></div>
                        <div class="text">{{item.t}}</div>
                    </li>
                </ul>
                <div class="frame_textarea">
                    <textarea placeholder="分享您的咨询感受" id="frame_textarea"></textarea>
                    <div class="anFlag" @click="setAnonymous()" :class="{anFlag_on:isAnonymous}">
                        匿名
                    </div>
                </div>
                <div class="frame_btn" :class="{frame_btn_active:star_pass}" @click="subEvaluationFrame()">提交评价</div>
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
        <!--<div class="showOk" v-if="showOk">-->
            <!--<span >+2</span><img src="http://oss.xqzs.cn/resources/psy/asker/asker_left_dotCoin.png"></div>-->
    </div>
</template>

<script type="">

    import showLoad from '../../include/showLoad.vue';
    var rob_problem = {
        template: '#rob_problem'
    };

    export default {
        data() {
            return {
                comments:[{v:1,t:'极差'},{v:2,t:'不满意'},{v:3,t:'一般'},{v:4,t:'满意'},{v:5,t:'极满意'}],
                commentValue:0,
                detail:{},
                selBestAnswerId:0,
                selBestAnswer:null,
                bestAnswer:{},
                isOver:true,
                showLoad:false,
                set_award_dian_coin:0,
//                showOk:false,
                evaluation_frame_flag:false,
                isAnonymous:false,
                anonyVal:0,
            }
        },
        props:{
            user:{
                type:Object
            }
        },
        components: {
            'v-showLoad': showLoad,
         },
        activated: function () {
            if(!xqzs.user.isUserLogin()){
                return ;
            }
            this.id= parseInt(this.$route.query.id);
            this.getDetail();
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
        },
        deactivated: function () {
            if(this.timeInterval!=null){
                clearInterval(this.timeInterval);
            }
            this.clearTimeOut();
        },
        methods: {
            timeIntervalFun:function () {
                let _this=this;
                if(_this.timeInterval!=null){
                    clearInterval(_this.timeInterval);
                }
                _this.timeInterval=   setInterval(function () {
                    _this.detail.endTime= _this.detail.endTime + 1;
                    _this.detail.endTime= _this.detail.endTime - 1
                },1000)
            },
            showCommentBox:function () {
                let _this=this;
                _this.evaluation_frame_flag = true;
            },
            frameClose:function () {
                this.evaluation_frame_flag = false;
            },
            getStar:function (v) {
                this.commentValue = v;
                if(this.commentValue!=0){
                    this.star_pass= true;
                }
            },
            setAnonymous:function () {
                this.isAnonymous = !this.isAnonymous;
                if(this.isAnonymous){
                    this.anonyVal = 1
                }else {
                    this.anonyVal = 0
                }
            },
            subEvaluationFrame:function () {
                let that=this;
                that.showLoad=true
                let textareaVal = $('#frame_textarea').val();
                if(that.commentValue==0){
                    xqzs.weui.tip("请选择评分",function () {

                    })
                    return;
                }
                xqzs.api.put(that, "come/user/evaluate/answer",{userId:"_userId_",answerId:that.detail.bestAnswerId, point:that.commentValue,content:textareaVal,isAnonymous :that.anonyVal},function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        xqzs.weui.toast("success","评论成功",function () {
                            that.showLoad = false;
                            that.evaluation_frame_flag = false;
                            that.getDetail();
                        })
                    }
                })

            },
            formatPrice:function (v) {
                return xqzs.string.formatPrice(v)
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

                _this.detail.answers[index].ct =time;
                console.log(time)
                _this.$set(_this.detail.answers,index,_this.detail.answers[index])
            },

            clearTimeOut:function () {
                let _this=this;
                if(_this.timeOut!==null){
                    clearTimeout(_this.timeOut);
                }
            },
            play:function (index) {
                let _this=this;
                  let list = _this.detail.answers;
                let CT= list[index].ct? list[index].ct: list[index].voiceLength;
                let T = list[index].voiceLength;
                xqzs.voice.onEnded=function () {
                    list[index].paused=false;
                    list[index].playing=false;
                    if(_this.playing)_this.clearTimeOut();
                    _this.$set(_this.detail.answers,index,list[index])
                };
                //重置其他列表内容
                for(let i = 0;i<list.length;i++){
                    if(index!=i&&(list[i].playing||list[i].paused)){
                        list[i].paused=false;
                        list[i].playing=false;
                        _this.$set(_this.detail.answers,i,list[i]);
                    }
                }
                let item= this.detail.answers[index];
                if(item.paused){  //暂停中也就是已经获取到且为当前音频
                    console.log(list[index])
                    list[index].paused=false;
                    list[index].playing=true;
                    _this.$set(_this.detail.answers,index,list[index])
                    xqzs.voice.play();
                    _this.timeout(true,CT,index)
                }else{
                    if(item.playing){    //播放中去做暂停操作
                        list[index].paused=true;
                        list[index].playing=false;
                        _this.$set(_this.detail.answers,index,list[index])
                        xqzs.voice.pause();
                        _this.clearTimeOut();
                    }else{     //重新打开播放
                        let answerId= item.answerId;
                        xqzs.voice.getAnswerVoice(answerId,function (url) {
                            xqzs.voice.play(url);
                            list[index].playing=true;
                            list[index].paused=false;
                            _this.$set(_this.detail.answers,index,list[index]);
                            _this.clearTimeOut();
                            _this.timeout(true,T,index)
                        })
                    }

                }

            },


            like:function (index) {
                let  item = this.detail.answers[index];
                if(item.isLiked){
                    xqzs.weui.tip("已经点赞");
                    return ;
                }
                let _this=this;
                xqzs.api.put(_this,"come/user/like/answer/_userId_/"+item.answerId,{},function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        item.isLiked=1;
                        item.likeTimes=parseInt(item.likeTimes)+1;
                        _this.$set(_this.detail.answers,index,item);
                    }
                    if(bt.data.status==800003){
                        xqzs.weui.tip("偷听后才能点赞哦");
                    }
                })
            },
            setBestAnswerId:function () {
                let _this=this;
                let data= {userId:"_userId_",answerId:_this.selBestAnswerId, questionId: _this.id};
                xqzs.api.post(_this, "come/user/set/question/best/answer",data,function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        _this.detail.bestAnswerId=_this.selBestAnswerId;
                        _this.detail.questionStatus=1
                    }
                })

            },
            selectBestAnswerId:function (answer) {
                let _this=this;
                _this.selBestAnswerId=answer.answerId;
                xqzs.weui.dialog("最佳答案","选择<span style='color: black'>" + answer.expertNickName+"</span>的回答为最佳答案<br>领取<span style='color: black'>"+_this.set_award_dian_coin+"</span>点豆奖励","",function () {
                    _this.selBestAnswerId=0;

                },function () {
                    _this.selBestAnswer=answer;
                    _this.selBestAnswerId=answer.answerId;
//                    _this.showOk=true;
//                    setTimeout(function () {
//                        _this.showOk=false;
//                    },1000)

                    _this.setBestAnswerId();

                })



            },
            goExpert:function (extId) {
                this.$router.push('../../../../asker/expert/detail/?id='+extId)
            },
            formatDateText:function (time) {
                return xqzs.dateTime.getTimeFormatText(time)
            },
            formatTimeLastText:function (time) {
                return xqzs.dateTime.getTimeFormatLastText(time)
            },
            getDetail:function () {
                let _this= this;
                _this.showLoad=true;
                xqzs.api.get(_this,'come/user/query/question/_userId_/'+_this.id ,function (data) {
                    _this.showLoad=false;
                    if (data.body.status == 1) {
                        console.log(data.body.data.data)
                        _this.detail= data.body.data.data;
                        _this.set_award_dian_coin=data.body.data.set_award_dian_coin;
                        _this.detail.evaluate_ed=data.body.data.evaluate_ed
                        for(let i=0;i<_this.detail.answers.length;i++){
                            if(_this.detail.answers[i].isBestAnswer){
                                _this.bestAnswer=_this.detail.answers[i];
                            }
                        }
                        _this.timeIntervalFun()
                    }
                })
            },
            best_qx:function () {
                this.changeFlg = false;
                this.isactive=true;
            },
            best_confirm:function () {
                this.changeFlg = false;
                this.isactive=false
            },
            hide_dialog:function () {
                this.changeFlg = false;
            }
        }


    }
</script>
<style>
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
    .evaluation_frame .stars li .text{color:RGBA(69, 75, 84, 0.5) ; font-size: 0.28rem; text-align: center;line-height: 0.4rem;}
    .evaluation_frame .stars li.on .star{background: url(http://oss.xqzs.cn/resources/psy/asker/ask_rack_comment_star_on.png) center no-repeat ; background-size:  0.66rem;}
    .evaluation_frame .stars li.on .text{ color:RGBA(245, 166, 35, 1)}
    .frame_textarea{height:2.2rem;border:0.02rem solid RGBA(238, 238, 238, 1);border-radius: 0.12rem;margin-bottom: 0.5rem;padding:0.2rem;position: relative}
    .frame_textarea textarea{color:rgba(36,37,61,1);outline: none;border:0;width:100%;font-size: 0.28rem;line-height: 0.4rem;height:1.6rem;}
    .frame_btn{color:RGBA(255, 255, 255, 1);font-size: 0.36rem;width:80%;line-height: 0.88rem;border-radius: 0.1rem;text-align: center;margin: 0 auto;background: RGBA(86, 196, 254, 0.5);}
    .frame_btn_active{background: RGBA(86, 196, 254, 1);}
    .evaluation_frame .frame_textarea .anFlag{position: absolute;right:0.2rem;bottom:0.2rem;color:RGBA(69, 75, 84, 0.49);font-size: 0.24rem;background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_no.png")no-repeat left center;background-size: 0.28rem;padding-left: 0.34rem;line-height: 0.34rem;z-index:1000;height:0.32rem;}
    .evaluation_frame .frame_textarea .anFlag_on{background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_on.png") no-repeat left center;background-size: 0.28rem;}

    .rob_status_box .race_detail_inCome{float: right;margin-right: 0.30rem;color:rgba(36,37,61,0.5);}
    .rob_status_box span {
        padding:  0 0.04rem;
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


    .rob_problem .rob_lists li{ position: relative}
    .rob_problem  .rob_lists li .comment_btn{ position: absolute; right:0.30rem; top:0.30rem; color:#FE7A03; border: 0.02rem solid #FE7A03; line-height:0.48rem; height: 0.48rem; padding: 0 0.27rem; border-radius: 0.07rem; font-size: 0.24rem;width:auto  }
    .rob_problem  .rob_lists li .comment_btn:active{ color:#e36b02; border: 0.02rem solid #e36b02; }
    .rob_problem .addBest_text{
        color:#999;
        font-size: 0.24rem;
        line-height: 1;
        padding-top: 0.34rem;
    }
    .rob_problem .addBest_text a{
        color:rgba(253,87,57,1);
    }
    .problem_detail_header .race_titleColor{
        color: rgba(51,51,51,1);
    }
    .problem_detail_header img {
        height: 0.8rem;
        width: 0.8rem;
        display: block;
        border-radius: 50%;
        margin-right: 0.20rem;
    }
    .submit_best_answer{ background: #fff; height:0.88rem; line-height: 0.88rem;  color:rgba(36,37,61,1); font-size: 0.26rem; position: fixed; bottom:0;left:0; width: 100%;box-shadow: 0.04rem 0 0.2rem rgba(0,0,0,0.3);padding-left:0.17rem;}
    .submit_btn { position: absolute;right:0.40rem; top:50%; height: 0.54rem;line-height: 0.54rem; text-align: center; width: 1.60rem;border:0.01rem solid rgba(252,66,42,1);color:rgba(252,66,42,1);border-radius: 0.30rem;margin-top:-0.27rem;}
    .submit_btn:active{ background: #FE7301;color:#fff;}
    .rob_problem{
        background: #fff;
    }
    .rob_status_box{
        color: #999;
        font-size: 0.24rem;
    }
    .rob_status_box>div:nth-of-type(1){
        margin-bottom: 0.28rem;
        line-height: 1;
        padding-top: 0.17rem;
        padding-left: 0.30rem;
    }
    .rob_status_box>div:nth-of-type(2){
        background: rgba(245,245,245,1);
        padding: 0.10rem 0.3rem;
        line-height: 0.40rem;
    }
    .rob_status_box span{
        color:rgba(255,153,0,1);
    }
    .rob_lists li{
        background: #fff;
        padding:0.40rem 0.30rem 0 0.30rem;
        border-bottom: 0.20rem solid #f4f4f7;
    }
    .rob_problem .audio{
        margin:0;
        padding-bottom: 0.40rem;
        border-bottom: 0.02rem solid rgba(238,238,238,1);
    }
    .rob_box_top{
        display: flex;
        display: -webkit-box;
        display: -webkit-flex;
        font-size: 0.26rem;
        color: rgba(69, 75, 84, 1);
        line-height: 0.68rem;
        margin-bottom: 0.36rem;
        position: relative;
    }
    .rob_box_top .expertInfo{
        line-height: 1;
    }
    .rob_box_top .expertInfo span{
        margin-bottom: 0.17rem;
        display: inline-block;
    }
    .rob_box_top .expertInfo div{
        color:#999;
        font-size: 0.24rem;
    }
    .rob_box_top img{
        width: 0.8rem;
        height:0.8rem;
        display: block;
        border-radius: 50%;
        margin-right:0.20rem;
    }
    .rob_box_top .expert_v{
        width:0.28rem;
        height:0.28rem;
        position: absolute;
        border-radius: inherit;
        top:0.54rem;
        left:0.52rem;
    }
    .rob_answer{
        position: relative;
        margin-left: 0.88rem;
        margin-bottom: 0.28rem;
    }
    .answer_line{
        width:3.48rem;
        display: block;
    }
    .answer_play{
        height:0.32rem;
        display: block;
        position: absolute;
        top:50%;
        left:0.30rem;
        margin-top:-0.22rem;
    }
    .paly_html{
        color: #fff;
        width:2.00rem;
        position: absolute;
        font-size: 0.30rem;
        text-align: center;
        line-height: 1;
        top:0.24rem;
        left:0.72rem;
    }
    .rob_answer span{
        position: absolute;
        left:3.70rem;
        top:0.16rem;
        color: rgba(36,37,61,0.5);
        font-size: 0.28rem;
    }

    .rob_answer_bottom{
        padding: 0;
        padding-left: 0.88rem;
        margin-bottom: 0.24rem;
    }
    .best_answer{
        height: 0.36rem;
        width:0.36rem;
        display: block;
        position: absolute;
        right:0;
        top: 50%;
        margin-top: -0.22rem;
        background: url("http://oss.xqzs.cn/resources/psy/asker/imgfalse.png") no-repeat;
        background-size: 100% 100%;
    }
    .best_answer_true{
        background: url("http://oss.xqzs.cn/resources/psy/asker/imgtrue.png") no-repeat;
        background-size: 100% 100%;
    }
    .problem_dialog{
        width:5.60rem;
        background: #fff;
        border-radius: 0.1rem;
        position: absolute;
        top:40%;
        left:50%;
        margin-left:-2.80rem;
    }
    .problem_dialog_bd{
        height:1.90rem;
        line-height: 1.90rem;
        color: #7F7E7E;
        font-size: 0.32rem;
        text-align: center;
        border-bottom: 0.02rem solid #D1D1D3;
    }
    .problem_dialog_bd span{
        color: rgba(36,37,61,1);
    }
    .problem_dialog_fd{
        display: flex;
        display: -webkit-box;
        display: -webkit-flex;
        height:0.96rem;
        line-height: 0.96rem;
    }
    .problem_dialog_fd div{
        flex: 1;
        text-align: center;
        font-size: 0.36rem;
    }
    .problem_dialog_fd div:active{
        background: #eee;
    }
    .problem_dialog_fd div:nth-of-type(1){
        color: rgba(36,37,61,0.7);
        position: relative;
    }
    .problem_dialog_fd div:nth-of-type(1)::after{
        content: '';
        height:100%;
        width:0.02rem;
        background: #D1D1D3;
        position: absolute;
        right:0;
    }
    .problem_dialog_fd div:nth-of-type(2){
        color: #09bb07;
    }
    .best_dialog{
        width:4.92rem;
        background: #fff;
        position: absolute;
        top:30%;
        left:50%;
        margin-left:-2.46rem;
        padding-top:0.56rem;
        color: rgba(36,37,61,0.7);
        font-size: 0.26rem;
        border-radius: 0.1rem;
        z-index: 10001;
    }
    .best_dialog_header{
        position: relative;
        color: rgba(36,37,61,1);
        font-size: 0.42rem;
        text-align: center;
        margin-bottom: 0.40rem;
    }
    .best_dialog_header div{
        margin-top:-0.40rem;
    }
    .best_dialog_header img{
        height:2.22rem;
        display: block;
        margin:0 auto;
    }
    .best_dialog_html{
        padding:0 0.32rem 0.40rem 0.36rem;
        line-height: 0.40rem;
        border-bottom: 0.02rem solid #D1D1D3;
    }
    .best_dialog_fb{
        color: #FE7301;
        font-size: 0.36rem;
        text-align: center;
        height:1.00rem;
        line-height: 1.00rem;
    }
    .best_dialog_fb:active{
        background: #eee;
    }
    .deuce_money_box{
        color: #FF9900;
        font-size: 0.28rem;
        position: absolute;
        top: 0.16rem;;
        right:0;
    }
    .best_getMoney{
        position: absolute;
        right:0;
        top:0.26rem;
    }
    .best_getMoney div{
        float: left;
        line-height: 1;
        margin-right:0.20rem;
        color: #FF9900;
        font-size: 0.28rem;
    }
    .best_getMoney img{
        width:0.36rem;
        display: block;
        float: left;
        margin-top:-0.14rem;
    }
    .showOk{
        font-size: 1.02rem;
        position: absolute;
        top:50%;
        left: 30%;
        animation: showOk linear 1.5s;
    }
    @keyframes showOk {
        0%{
            font-size: 1.02rem;
            position: absolute;
            top:50%;
            color: #56C4FE;
            opacity: 1;
            left: 30%;
        }
        100%{
            font-size: 1.02rem;
            position: absolute;
            top:40%;
            color: #56C4FE;
            opacity:0;
            left: 30%;
        }
    }
</style>
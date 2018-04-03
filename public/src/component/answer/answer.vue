<template >
    <div style="height: 100%" class="answer_answer_box wbg">

        <div v-title class='hide_title'>回答</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <!--问题详情-->
        <div class="answer" >
            <div class="img">
                <img v-if="detail.isAnonymous==0" :src="detail.faceUrl">
                <img v-if="detail.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
            </div>
            <div class="info">
                <div class="names">
                    <span v-if="detail.isAnonymous==0">{{detail.nickname}}</span>
                    <span v-if="detail.isAnonymous==1">匿名用户</span>
                    <div class="price">￥{{detail.price}}</div>
                </div>
                <div class="content">{{detail.content}}</div>
                <div class="last_time">在哪方面：{{detail.title}}</div>
                <div class="last_time last_red_color">还{{formatDateText( detail.addTime+172800)}}</div>
                <div class="clear"></div>

                <div class="audio" :class="{playing:playing,paused:paused}" v-if="isAnswered&&answerId">
                    <div class="audio_btn" @click.stop="play()" >
                        <template v-if="!playing&&!paused">点击播放</template>
                        <template v-if="playing">正在播放..</template>
                        <template v-if="paused">播放暂停</template>
                        <div class="second">{{voiceLength}}”</div>
                    </div>
                    <div class="clear"></div>
                </div>


            </div>


            <div class="clear"></div>
        </div>

        <div class="tipxuzhi" @click="tip()">  <div   v-if="detail.questionType==1">抢答须知</div>
            <div  v-if="detail.questionType==2">回答须知</div></div>
        <div style="clear: both"></div>

        <!--播放状态-->
        <div>
            <div class="record_voice_box" v-if="!isAnswered&&!outTime">
                <div class="time_in" style="position: static;margin-bottom:0.6rem;padding-top: 0.3rem;">
                    <div>{{answerTime}}"</div>
                </div>
                <div class="circle">
                    <div class="pie_left_play">
                        <div class="left_play"></div>
                    </div>
                    <div class="pie_right_play">
                        <div class="right_play"></div>
                    </div>
                    <div class="pie_left">
                        <div class="left"></div>
                    </div>
                    <div class="pie_right">
                        <div class="right"></div>
                    </div>
                    <div class="gg"></div>
                    <div class="move">
                        <div class="qq"></div>
                    </div>
                    <div class="mask"><i class="start"></i></div>
                </div>
                <div class="tip" :class="{tryListenColor:isTry}">点击录音(至少录制45秒)</div>
                <div class="addPlayBox" >
                    <!--操作按钮-->
                    <div class="action_btn" v-if="!isAnswered"> <!---->

                        <div class="item" v-if="isOver">  <!---->
                            <div class="re_start" @click="reStart()">重录</div>
                        </div>
                        <div class="item" style="flex: 2">
                        </div>


                        <div class="item" v-if="isOver"> <!---->
                            <div class="send" :class="{cant_send:voiceLength<MIN_VOICE_LENGTH}" @click="send()">发送</div>
                        </div>


                        <div class="item" v-if="outTime">
                            <div class="audio_btn_in audio_cant_begin outTimeStyle"></div>
                            <div class="txt">已超时</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <div id="tip" style="display: none">
            <div class="dialog_select_type dialog_select_Height">
                <div class="select_title"  v-if="detail.questionType==1">抢答须知</div>
                <div class="select_title"  v-if="detail.questionType==2">回答须知</div>
                <div class="tip_content">
                    <h3>一、专家解答注意事项</h3>
                    <p>
                        1、回答问题时请保持清晰的思路，并找一个安静的地方进行录制。
                    </p>
                    <p>
                        2、60秒语音录制完成后，请试听一遍，确认表达无误后再点击发送。
                    </p>
                    <p>
                        3、请专家针对用户的提问给予专业、有针对性的回答。
                    </p>
                    <p>
                        4、不允许为用户推荐任何心理咨询机构及联系方式。
                    </p>
                    <p>
                        5、不可开处方（包括指导用户具体用药剂量）、推荐处方及各种符合处方范畴内的药物。
                    </p>
                    <div v-if="detail.questionType==1">
                     <h3>
                         二、抢答问题回复须知
                     </h3>
                    <p>
                        1、抢答模式的问题提出后，将保留48小时，每个问题最多由5位咨询师进行抢答
                    </p>

                    <p>
                        2、回答成功后，用户未选择最佳答案，酬金将被所有回答者平分
                    </p>
                    <p>
                        3、回答成功后，用户可在48小时内选出最佳答案，最佳答案回答者将获得100%酬金；
                    </p>
                    <p>
                        4、最佳回答将会被推荐到首页，享受偷听曝光量；
                    </p>
                    </div>
                    <div v-if="detail.questionType==2">
                        <h3>
                            二、一对一问题回复须知
                        </h3>
                        <p>
                            1、一对一问题回答后，将获得100%提问酬金，且问题将被推荐到首页
                        </p>
                        <p>
                            2、您的回答被其他用户偷听时，将获得30%偷听费
                        </p>
                        <p>
                            3、用户向您提问后，你可在48小时内进行回答，若未回答，提问酬金将返还给用户
                        </p>
                    </div>
                </div>
                <div class="yes know " >知道了</div>
            </div>
        </div>
    </div>
</template>

<script type="">






    import showLoad from '../include/showLoad.vue';
    export default {
        data() {
            return {
                showLoad:false,
                isAnswered:false,
                answering:false,
                outTime:false,
//                preAnswer:false,
                playing:false,
                answerTime:"00",
                timeOut:null,
                questionId:null,
                detail:{},
                str:null,
                localId:null,
                serviceId:null,
                voiceLength:0,
                isOver:false,
                MIN_VOICE_LENGTH:45,
                isTry:false
            }
        },
        activated: function () {
            let _this = this;
            this.showLoad = false;
            this.isAnswered = false;
            this.answering = false;
            this.outTime = false;
            this.playing = false;
            this.answerTime = "00";
            this.timeOut = null;
            this.questionId = null;
            this.detail = {};
            this.str = null;
            this.localId = null;
            this.serviceId = null;
            this.voiceLength = 0;
            this.isOver = false;
            myVideo.initStart();
            this.questionId = this.$route.query.askId;
            let expertId = cookie.get("expertId");
            this.$http.get(web.API_PATH + 'come/expert/question/detail/'+this.questionId,{params:{expertId:expertId}}).then(function (data) {//es5写法
                if (data.body.status == 1) {

                    _this.timeIntervalFun();
                    console.log(data)
                    _this.detail = data.data.data;

                    _this.$nextTick(function () {
                        var isLookReply=xqzs.localdb.get("isLookReply");
                        console.log(isLookReply)
                         if(isLookReply){
                             }else{
                             this.tip()
                             xqzs.localdb.set("isLookReply","true")
                        }

                    })




                    if( _this.detail.questionStatus==1){
                        xqzs.weui.tip('问题已经完成',function () {
                            if( _this.detail.questionTypp=2){
                                _this.$router.replace("/answer/my/answer/list")
                            }else{
                                _this.$router.replace("/answer/my/answer/list")
                            }

                        })

                    }
                }
            }, function (error) {

            });
            xqzs.wx.setConfig(this, function () {
                var config = {
                    imgUrl: 'http://oss.xqzs.cn/resources/psy/join_share_img3.jpg',
                    title:  '‘好一点’心理咨询平台诚邀您入驻' ,
                    desc: '‘好一点’心理咨询师的兼职平台，诚邀您入驻，一次回答，收益不断！',
                    link: "http://wx.xqzs.cn/comeon/guest#/join",
                };
                weshare.init(wx, config)
            });

            myVideo.config({obj:$('.circle')}).init(_this.start,_this.stop,_this.play,_this.play);
                console.log(this.detail)


        },
        deactivated:function () {
            if(this.timeInterval!=null){
                clearInterval(this.timeInterval);
            }
            this.clearTimeOut();
            this.stop();
        },

        methods: {
            timeIntervalFun:function () {
                let _this=this;
                if(_this.timeInterval!=null){
                    clearInterval(_this.timeInterval);
                }
                _this.timeInterval=   setInterval(function () {
                    _this.detail.addTime= _this.detail.addTime + 1;
                    _this.detail.addTime= _this.detail.addTime - 1
                },1000)
            },
            formatDateText:function (time) {
                return xqzs.dateTime.getTimeFormatLastText(time)
            },
            closeDialog:function () {
                xqzs.weui.dialogClose()
            },
            tip: function () {
                this.showTip=true;
                let _this= this;
                xqzs.weui.dialogCustom($("#tip").html());
                $(".know").click(function () {
                    _this.closeDialog()
                })
            },
            timeout:function (play) {
                let _this=this;
                _this.timeOut =    setTimeout(function () {
                    let time = parseInt(_this.answerTime);
                    if(play==true){  //试听
                        if(time>0){
                            time = time -1 ;
                            if(time<10)time="0"+time
                            _this.answerTime = time ;
                            _this.timeout(play);
                        }else{
                            _this.playing=false;
                        }
                    }else{
                        if(time<60){
                            time = time +1 ;
                            _this.voiceLength=time;
                            if(time<10)time="0"+time
                            _this.answerTime = time ;
                            _this.timeout();
                        }else{
                            _this.voiceLength=time;
                            _this.stop();
                        }

                    }

                },1000);
                console.log( _this.answerTime);

            },

            clearTimeOut:function () {
                let _this=this;
                if(_this.timeOut!==null){
                    clearTimeout(_this.timeOut);
                }
            },
            reStart:function () {
                let _this=this;
                _this.tryListenColor = false;
                xqzs.weui.dialog("重新录制将会删除此条录音","确定要重新录制嘛","",function () {
                    
                },function () {
                    //重新开始录制
                    console.log('重新开始录制')
                    _this.answerTime="00";
                    _this.voiceLength=0;
//                this.preAnswer=false;
                    if(_this.playing)xqzs.wx.voice.pausePlay( _this.localId);
                    _this.playing=false;
                    _this.isOver=false;
                    _this.localId=null;
                    _this.clearTimeOut();
                    myVideo.config({obj:$('.circle')}).init(_this.start,_this.stop,_this.play,_this.play);
                    myVideo.initStart();
                    $('.record_voice_box .tip').html('点击录音(至少录制45秒)')

                   // myVideo.obj.click();
                })

            },
            send:function () {
                let _this=this;
                if(_this.voiceLength<_this.MIN_VOICE_LENGTH){
                    return ;
                }

                xqzs.weui.dialog("心情加油站温馨提醒","为防止误点，请确认录音满意后再发送","",function () {

                },function () {
                    if(_this.playing)xqzs.wx.voice.pausePlay( _this.localId);
                    //发送到微信服务器并获取serverId
                    console.log("onRecordEnd" +_this.localId)
                    xqzs.wx.voice.upload(_this.localId,function (serverId) {
                        _this.serverId=serverId;

                        let data ={
                            mediaId:serverId,
                            voiceLength:_this.voiceLength,
                            expertId:cookie.get("expertId"),
                            userId:"_userId_"
                        };
                        _this.showLoad=true;
                        _this.$http.put(web.API_PATH + "come/expert/answer/"+_this.questionId, data)
                            .then(function (bt) {
                                _this.showLoad=false
                                if (bt.data && bt.data.status == 1) {
                                    _this.isAnswered=true;
                                    _this.answerId= bt.data.data.answerId;
                                    $(".record_voice_box .tip").hide();
                                    xqzs.weui.toast("success",'回答成功',function () {
                                        _this.$router.push("/answer/my/answer/list");
                                    })
                                }else if(bt.data.status==910003){
                                    window.history.go(-1);

                                }
                            });
                    });

                    _this.clearTimeOut();
                });




            },
            start:function () {
//                开始录制
                let _this=this;
                xqzs.wx.voice.stopRecord();
                //暂停
                if(this.localId)xqzs.wx.voice.pausePlay( this.localId);
                this.clearTimeOut();
                xqzs.wx.voice.startRecord();
                console.log("开始录制")
                this.answering=true;
                this.timeout()
                xqzs.wx.voice.onRecordEnd(function (localId) {
                    console.log("onRecordEnd" +localId);
                    if(localId){
                        _this.localId=localId;
                        xqzs.localdb.set("voice_localId",localId);
                        _this._recordStop();
                    }

                });

            },
            play:function () {//试听
                let _this = this;
                _this.tryListenColor = false;
                if(this.playing){  //在播放中则暂停
                    if(_this.localId!=null) {
                        _this.clearTimeOut();
                        _this.playing = false;
                        this.paused = true;
                        xqzs.wx.voice.pausePlay(_this.localId);
                        xqzs.wx.voice.playing=_this.playing;
                        console.log("pausePlaypausePlaypausePlaypausePlay")

                    }
                }else{
                    console.log("PlayPlayPlayPlay")
                    console.log("_this.localId:"+_this.localId)
                    if(_this.localId!=null){
                        this.clearTimeOut();
                        _this.playing=true;
                        this.timeout(true);
                        this.paused = false;
                        xqzs.wx.voice.startPlay(_this.localId);
                        xqzs.wx.voice.playing=_this.playing;
                        xqzs.wx.voice.onPlayEnd(function () {
                            console.log("onPlayEnd")
                            if(_this.playing)_this.clearTimeOut();
                            _this.playing = false;
                            _this.paused = false;
                            xqzs.wx.voice.playing=_this.playing;
                            if(_this.voiceLength<10){
                                _this.answerTime = "0"+_this.voiceLength
                            }else{
                                _this.answerTime = ""+_this.voiceLength
                            }
                        })
                    }
                }
            },
            stop:function () { //停止录制
                console.log('stopstopstopstopstopstop')
                let _this = this;
                _this.tryListenColor = true;
                xqzs.wx.voice.stopRecord(function (localId) {
                    console.log("stopstopstopstopstop"+localId)

                    if(localId){
                        _this.localId = localId;
                        xqzs.localdb.set("voice_localId", localId);
                        _this._recordStop();
                    }

                });




            },
            _recordStop:function () {
                console.log('_recordStop_recordStop_recordStop')
                let _this = this;
                _this.isOver = true;
                _this.clearTimeOut();
                _this.answering=false;
//                _this.preAnswer=true;
            },
            getTime:function (time) {
                return xqzs.dateTime.getTimeFormatText(time)
            }
        },
        beforeDestroy:function () {
            this.clearTimeOut()
        },
        components: {
            'v-showLoad': showLoad,

        }
    }
</script>
<style>

    .record_voice_box .tryListenColor{
        color:rgba(254,115,1,1)
    }
    .tipxuzhi{ margin-top:0.30rem; margin-right:0.30rem; text-align: right; font-size: 0.24rem; color:#999;
        background: url(http://oss.xqzs.cn/resources/psy/asker/tip.png) no-repeat;
        background-size: 0.28rem;
        height: 0.28rem;
        line-height: 0.28rem;
        padding-left: 0.34rem; display: inline-block; float:right;}
    .answer_answer_box{ width: 100%; overflow: hidden}
    .answer_answer_box .answer{ background: #fff; padding:0.40rem 0.30rem 0.16rem 0.30rem;  position: relative; border-bottom: 0.20rem solid #F4F4F7 }
    .answer_answer_box .answer .img{ width: 0.58rem; height: 0.58rem; float:left; }
    .answer_answer_box .answer .img img{ width: 100%; height: 100%; border-radius: 50%}
    .answer_answer_box .answer .info .names{ font-size: 0.24rem;  color:rgba(36,37,61,0.5); margin-bottom: 0.16rem; line-height: 0.58rem}
    .answer_answer_box .answer .info .names span{
        margin-left: 0.20rem;
    }
    .answer_answer_box .answer .info .content{  font-size: 0.30rem; color:rgba(36,37,61,1); line-height:0.40rem; margin-bottom:  0.28rem;width:98%; min-height: 3rem;}
    .answer_answer_box .answer .info .last_time{ line-height:1;font-size: 0.26rem;color:rgba(36,37,61,0.5);margin-bottom: 0.18rem;
    }
    .answer_answer_box .answer .info .type span{ color:rgba(36,37,61,0.5);}
    .answer_answer_box .answer .price{ font-size: 0.24rem;color:rgba(254,115,1,1); float: right}
    .answer_answer_box .answer .info  .audio{ margin-top: 0.20rem;}


    /*播放*/
    .answer_answer_box   .time_go{ position: relative;  height: 2.11rem; width: 2.11rem; margin: 0 auto;}
    .answer_answer_box  .time_go .playing { height: 2.11rem; width: 2.11rem; border-radius: 50%; background: #00b9e8; position: absolute; top:0;left:0; text-align: center; color:#fff; font-size: 0.60rem; line-height: 2.11rem;}
    .answer_answer_box   .play{
        opacity: 0.1;
        transform:scale(1.6,1.6);
    }
    .answer_answer_box   .play2{
        opacity: 0.3;
        transform:scale(1.4,1.4);
    }
    .answer_answer_box   .play3{
        opacity: 0.4;
        transform:scale(1.2,1.2);
    }

    .answer_answer_box  .play_go  .play{
        animation: playing 1s infinite;
        -webkit-animation: playing  1s infinite;
        animation-delay: -0.8s;
        -webkit-animation-delay: -0.8s;
    }
    .answer_answer_box  .play_go .play2{
        animation-delay: -0.6s;
        -webkit-animation-delay: -0.6s;
        animation: playing 1s infinite;
        -webkit-animation: playing 1s infinite;

    }
    .answer_answer_box  .play_go .play3{
        animation-delay: -0.4s;
        -webkit-animation-delay: -0.4s;
        animation: playing 1s infinite;
        -webkit-animation: playing 1s infinite;

    }
    @keyframes  playing {
        0%{
            transform:scale(0.9,0.9);
            opacity: 0.8;
        }
        100%{
            transform:scale(1.7,1.7);
            opacity: 0;
        }
    }






    .answer_answer_box  .times{ padding: 0.07rem 0.85rem; background: #fff}
    .answer_answer_box   .times .bg{ background: #163201; height: 1.00rem;  border-radius: 0.50rem ; }
    .answer_answer_box    .times .bg .in{ padding:  0.05rem; }
    .answer_answer_box    .times .bg .go{ background: #09bb07; height: 0.90rem; min-width:0.90rem;  width: 0; border-radius:0.45rem;      }
    .answer_answer_box  .times .last_time{ text-align: center; color:#666; margin-top: 0.17rem; font-size: 0.52rem;}
    .answer_answer_box  .action_btn{ top:3rem;width:100%;position: absolute;  display: -webkit-box; display: -webkit-flex;  display: flex;}
    .answer_answer_box  .action_btn .item{  -webkit-box-flex: 1;  -webkit-flex: 1;  flex: 1;  text-align: center; color:#666; font-size: 0.26rem;  }
    .answer_answer_box  .action_btn .item .txt{ margin-top: 0.20rem}
    .answer_answer_box  .audio_btn_in{ background: linear-gradient(to right, rgba(255,158,25,1), rgba(253,114,6,1)); border-radius: 50%; height: 1.40rem; width: 1.40rem;
        -webkit-box-shadow:0 0 0.16rem rgba(255,158,25,1);box-shadow:0 0 8px rgba(255,158,25,1);  position: relative; margin: 0 auto}
    .answer_answer_box .audio_btn_in:active{ background: rgba(253,114,6,0.6)}
    .answer_answer_box  .audio_btn_in:before{content: " " ; display: block ; position: absolute; top:50%; left:50% ;}
    .answer_answer_box   .audio_begin:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_begin1.png)  no-repeat; background-size:  0.46rem; width:0.46rem;; height: 0.64rem;  margin-left: -0.23rem; margin-top: -0.32rem;  }
    .answer_answer_box   .audio_end:before{ background:#fff; background-size:  0.42rem; width:0.42rem;; height: 0.42rem;  margin-left: -0.21rem; margin-top: -0.21rem; border-radius: 0.1rem ;   }
    .answer_answer_box   .audio_play,.audio_send{width:0.88rem;height:0.88rem;background:#ccc;border-radius: 50%;margin:0.26rem auto;color:#fff;font-size: 0.26rem;line-height: 0.88rem}
    .answer_answer_box .audio_send  img{position: absolute;left:50%;top:0.40rem;margin-left:-0.30rem;width:0.60rem}
    .answer_answer_box   .audio_cant_begin:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_begin1.png)  no-repeat; background-size:  0.46rem; width:0.46rem;; height: 0.64rem;  margin-left: -0.23rem; margin-top: -0.32rem;  }
    .answer_answer_box .overStyle{background: #00B9E8;}
    .answer_answer_box .outTimeStyle{background: linear-gradient(to right, rgba(255,158,25,0.4), rgba(253,114,6,0.4))}
    /*.answer_answer_box .addPlayBox{position: absolute;bottom:1.53rem;width:100%;}*/

    .dialog_select_Height{ height:10.04rem; margin-top: -5rem;width: 86% ;margin-left:-43%}
    .dialog_select_type  .yes{ width:60%;height:0.70rem;line-height: 0.71rem;background: RGBA(86, 196, 245,1);border-radius: 0.45rem;color:RGBA(255, 255, 255, 1);text-align: center;font-size: 0.30rem;margin:0.25rem auto;}
    .dialog_select_type  .yes:active{background: #eee}
    .dialog_select_type .tip_content{ padding: 0 0.2rem;
        margin: 0 0.2rem;
        line-height: 1.8;
        font-size: 0.28rem;
        color: #666;
        height: 7.78rem;
        overflow: auto;
        background: RGBA(69, 75, 84, 0.05);}
    .dialog_select_type .tip_content p{margin-top: 0.1rem ;margin-bottom: 0.10rem;}
    .asker_ask_box .ask_type_new{padding:0.40rem 0.30rem;padding-bottom: 0.10rem}
    .asker_ask_box .ask_type_new .tab{color:rgba(36,37,61,1);font-size: 0.34rem;line-height: 1;margin-bottom: 0.40rem;}
    .asker_ask_box .ask_type_new .tab span{color:rgba(36,37,61,0.5);font-size: 0.24rem;}
    .asker_ask_box .ask_type_new .select_box{display: flex;text-align: center;height:0.72rem;line-height: 0.72rem;}
    .asker_ask_box .ask_type_new .select_box div{flex:1;color:rgba(36,37,61,0.7);font-size: 0.30rem;background: rgba(245,245,245,1);border-radius: 0.1rem;}
    .asker_ask_box .ask_type_new .select_box div:nth-of-type(2){margin:0 0.30rem;}
    .asker_ask_box .ask_type_new .select_box .on_new{background: rgba(253,114,6,1);color:#fff;}
    .asker_ask_box  .text_area .content{ font-size: 0.26rem; color:rgba(36,37,61,1); height: 90%; line-height: 0.48rem}

</style>

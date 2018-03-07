<template >
    <div style="height: 100%" class="answer_answer_box wbg">

        <div v-title>回答</div>
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
                <div class="last_time">{{getTime(detail.addTime)}}</div>
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



        <!--播放状态-->

        <div class="addPlayBox" >

            <!--操作按钮-->
            <div class="action_btn" v-if="!isAnswered">

                <div class="item" v-if="isOver">
                    <div class="re_start" @click="reStart()">重录</div>
                </div>
                <div class="item" style="flex: 2" >
                </div>


                <div class="item" v-if="isOver">
                    <div class="send" :class="{cant_send:voiceLength<MIN_VOICE_LENGTH}" @click="send()">发送</div>
                </div>


                <div class="item" v-if="outTime">
                    <div class="audio_btn_in audio_cant_begin outTimeStyle"></div>
                    <div class="txt">已超时</div>
                </div>
            </div>
        </div>
        <div class="record_voice_box" v-if="!isAnswered&&!outTime">
            <div class="time_in">
                <div>{{answerTime}}"</div>

            </div>
            <!--<div class="time_in_tip">至少录制45秒</div>-->
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
            <div class="tip">点击录音(至少录制45秒)</div>
        </div>


    </div>
</template>

<script type="es6">






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
                MIN_VOICE_LENGTH:45
            }
        },
        mounted: function () {
            let _this=this;
            myVideo.initStart();
            this.questionId = this.$route.query.askId
            this.$http.get(web.API_PATH + 'come/expert/question/detail/'+this.questionId).then(function (data) {//es5写法
                if (data.body.status == 1) {
                    console.log(data)
                    _this.detail = data.data.data;
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

        },
        methods: {
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
                if(this.playing){  //在播放中则暂停
                    if(_this.localId!=null) {
                        _this.clearTimeOut();
                        _this.playing = false;
                        this.paused = true;
                        xqzs.wx.voice.pausePlay(_this.localId);
                        xqzs.wx.voice.playing=_this.playing;
                        console.log("pausePlay")

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

                let _this = this;

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
    .answer_answer_box{ width: 100%; overflow: hidden}
    .answer_answer_box .answer{ background: #fff; padding:0.40rem 0.30rem 0.16rem 0.30rem;  position: relative; border-bottom: 0.16rem solid #F4F4F7 }
    .answer_answer_box .answer .img{ width: 0.58rem; height: 0.58rem; float:left; }
    .answer_answer_box .answer .img img{ width: 100%; height: 100%; border-radius: 50%}
    .answer_answer_box .answer .info .names{ font-size: 0.24rem;  color:rgba(36,37,61,0.5); margin-bottom: 0.16rem; line-height: 0.58rem}
    .answer_answer_box .answer .info .names span{
        margin-left: 0.20rem;
    }
    .answer_answer_box .answer .info .content{  font-size: 0.30rem; color:rgba(36,37,61,1); line-height:0.40rem; margin-bottom:  0.28rem;width:98%;}
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
    .answer_answer_box  .action_btn{ background: #fff; padding: 2.18rem 0.34rem 0 0.34rem; display: -webkit-box; display: -webkit-flex;  display: flex;}
    .answer_answer_box  .action_btn .item{  -webkit-box-flex: 1;  -webkit-flex: 1;  flex: 1;  text-align: center; color:#666; font-size: 0.26rem;  }
    .answer_answer_box  .action_btn .item .txt{ margin-top: 0.20rem}
    .answer_answer_box  .audio_btn_in{ background: linear-gradient(to right, rgba(255,158,25,1), rgba(253,114,6,1)); border-radius: 50%; height: 1.40rem; width: 1.40rem;
        -webkit-box-shadow:0 0 8px rgba(255,158,25,1);box-shadow:0 0 8px rgba(255,158,25,1);  position: relative; margin: 0 auto}
    .answer_answer_box .audio_btn_in:active{ background: rgba(253,114,6,0.6)}
    .answer_answer_box  .audio_btn_in:before{content: " " ; display: block ; position: absolute; top:50%; left:50% ;}
    .answer_answer_box   .audio_begin:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_begin1.png)  no-repeat; background-size:  0.46rem; width:0.46rem;; height: 0.64rem;  margin-left: -0.23rem; margin-top: -0.32rem;  }
    .answer_answer_box   .audio_end:before{ background:#fff; background-size:  0.42rem; width:0.42rem;; height: 0.42rem;  margin-left: -0.21rem; margin-top: -0.21rem; border-radius: 5px ;   }
    .answer_answer_box   .audio_play,.audio_send{width:0.88rem;height:0.88rem;background:#ccc;border-radius: 50%;margin:0.26rem auto;color:#fff;font-size: 0.26rem;line-height: 0.88rem}
    .answer_answer_box .audio_send  img{position: absolute;left:50%;top:0.40rem;margin-left:-0.30rem;width:0.60rem}
    .answer_answer_box   .audio_cant_begin:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_begin1.png)  no-repeat; background-size:  0.46rem; width:0.46rem;; height: 0.64rem;  margin-left: -0.23rem; margin-top: -0.32rem;  }
    .answer_answer_box .overStyle{background: #00B9E8;}
    .answer_answer_box .outTimeStyle{background: linear-gradient(to right, rgba(255,158,25,0.4), rgba(253,114,6,0.4))}
    .answer_answer_box .addPlayBox{position: absolute;bottom:1.53rem;width:100%;}



</style>

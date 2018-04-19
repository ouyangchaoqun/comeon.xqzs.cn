<template >
    <div style="height: 100%" class="answer_join_voice wbg">

        <div v-title class='hide_title'>入驻心理咨询师</div>
        <div class="voice_placeholder" v-if="!showBgm">
            <header>咨询师一分钟语音介绍</header>
            <div>例子1： 我是好一点心理咨询师：xxx,是国家二级咨询师，从事 心理咨询行业十五年，帮助过上千人走出困境。我个人 比较擅长：个人成长、情感困惑、婚姻家庭这三方面的 辅导，（额外可加自身的成就或者自己的一段座右铭… …）</div>
            <div>例子2： 人生，也许就是一场繁芜，一世轮回，一怀禅意。有的 人只能擦肩而过，有的人却可以蓦然回首。 其实，生命就是一次探寻的旅程，只有先读懂自己，才 会明了自己想要的人生。让我和你一起去探索吧！我是 好一点咨询咨询师：XX。 </div>
        </div>



        <!--<div class="audio" :class="{playing:vPlaying,paused:vPaused}" v-if="finish" >-->
            <!--<div class="audio_btn" @click.stop="playV()" >-->
                <!--<template v-if="!vPlaying&&!vPaused">点击播放</template>-->
                <!--<template v-if="vPlaying">正在播放..</template>-->
                <!--<template v-if="vPaused">播放暂停</template>-->
                <!--<div class="second">{{voiceLength}}”</div>-->
            <!--</div>-->

            <!--<div class="clear"></div>-->
        <!--</div>-->




        <div class="addPlayBox" v-if="preAnswer&&!finish&&!showBgm">  <!-- -->

            <!--操作按钮-->

            <div class="action_btn" >

                <div class="item" >
                    <div class="re_start" @click="reStart()">重录</div>
                </div>
                <div class="item" style="flex: 2" >
                </div>


                <div class="item">
                    <div class="send" :class="{cant_send:voiceLength<MIN_VOICE_LENGTH}" @click="send()">完成</div>
                </div>


            </div>
        </div>
        <div class="record_voice_box" :class="{voice_needMusic:showBgm}"> <!--v-if="!finish"-->
            <div class="time_in">
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
            <div class="tip">点击开始录制</div>

            <div class="voice_bg_box" v-if="showBgm">
                <h3>选择背景音乐</h3>
                <ul>
                    <li v-for="(item,index) in bgmList" @click="composeBgm(item.path,index,item.id)">
                        <img class="bgmImg" :src="item.pic" alt="">{{item.title}}
                        <img v-show="item.is_checked" class="checkedImg" src="http://oss.xqzs.cn/resources/psy/asker/filter_sure_icon.png" alt="">
                    </li>

                </ul>
                <div class="compose_btn" @click="subComposeBgm()">确定</div>
            </div>
        </div>




    </div>
</template>

<script type="es6">

    import answerTopStep from "./include/top_step.vue";

    export default {
        data() {
            return {
                answering2:false,
                preAnswer:false,
                playing:false,
                answerTime:"00",
                timeOut:null,
                finish:false,
                showBgm:false,
                vPlaying:false,
                vPaused:false,
                localId:null,
                serviceId:null,
                voiceLength:0,
                MIN_VOICE_LENGTH:10,
                edit:'',
                bgmList:[],
                voiceBgmId:''
            }
        },

        methods: {
            //获取背景音乐
            getBgmList:function () {
                console.log('获取背景音乐')
                let _this = this;
              xqzs.api.get(_this,'come/expert/voice/bgm',function (res) {
                  console.log(res)
                  if(res.data.status==1){
                      _this.bgmList = res.data.data;

                  }
              },function () {
               //error
              })
            },
            composeBgm:function (bgmUrl,index,bgmId) {
                let _this = this;
                console.log(bgmId)
                _this.voiceBgmId = bgmId;
                cookie.get('voiceBgmId',bgmId,1)
                for(let i=0;i<_this.bgmList.length;i++){
                    _this.bgmList[i].is_checked = false
                }
                _this.bgmList[index].is_checked = true;
                _this.$set(_this.bgmList,index,_this.bgmList[index])
               xqzs.voice.play(bgmUrl)
            },
            //上传合成音频
            subComposeBgm:function () {
                let _this = this;
                console.log(_this.localId)
                //发送合成音音频到微信服务器并获取serverId
                xqzs.wx.voice.upload(_this.localId,function (serverId) {
                    _this.serviceId = serverId;
                });
                cookie.set('voiceLength',_this.voiceLength,1)
                cookie.set('serverId',_this.serverId,1)
                if(_this.edit==1){
                    //修改
                    let url =  'come/expert/modify/voice';
                    let data={
                        userId:"_userId_",
                        expertId:cookie.get("expertId"),
                        mediaId:_this.serviceId,
                        voiceLength:_this.voiceLength,
                        voiceBgmId:_this.voiceBgmId
                    };
                    xqzs.api.post(_this, url,data,function (bt) {
                        //成功
                        console.log(bt)
                        console.log(data)


                    },function () {
                        //失败
                        console.log('上传失败')
                    })
                }else{
                    //注册
                   console.log('首次注册')
                    //_this.$router.go(-1)
                }


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
                //重新开始录制
                let _this= this;
                this.answerTime="00";
                this.voiceLength=0;
                this.preAnswer=false;
                if(this.playing)xqzs.wx.voice.stopPlay( this.localId);
                this.playing=false;

                this.localId=null;
                myVideo.config({obj:$('.circle')}).init(_this.start,_this.stop,_this.play,_this.play);
                myVideo.initStart();
                myVideo.obj.click();
            },
            send:function () {
                console.log('send')
                console.log(this.edit)
                let _this=this;
                _this.finish=true;
                _this.showBgm = true;
                if(_this.voiceLength<_this.MIN_VOICE_LENGTH){
                    return ;
                }
                this.clearTimeOut();

            },
            start:function () {

//                开始录制
                let _this=this;
                _this.vPaused=true;
                _this.vPlaying=false;
                xqzs.voice.pause();
                this.clearTimeOut();
                this.answering2=true;
                this.timeout()
                console.log("startRecordtimeout")
                xqzs.wx.voice.startRecord();
                xqzs.wx.voice.onRecordEnd(function (localId) {
                    _this.localId=localId
                    xqzs.localdb.set("voice_localId",localId);
                    _this._recordStop();
                });

            },
            play:function () {//试听
                let _this = this;
                if(this.playing){  //在播放中则暂停
                    if(_this.localId!=null) {
                        _this.clearTimeOut();
                        xqzs.wx.voice.pausePlay(_this.localId);
                        console.log("pausePlay")
                        this.playing = false;
                    }
                }else{
                    if(_this.localId!=null){
                        this.clearTimeOut();
                        xqzs.wx.voice.startPlay(_this.localId);
                        this.playing=true;
                        this.timeout(true);
                        xqzs.wx.voice.onPlayEnd(function () {
                            console.log("onPlayEnd")
                            if(_this.playing)_this.clearTimeOut();
                            _this.playing = false;

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
                    if (localId){
                        _this.localId = localId;
                        xqzs.localdb.set("voice_localId", localId);
                        _this._recordStop();
                    }
                });

            },
            _recordStop:function () {
                let _this = this;
                _this.clearTimeOut();
                _this.answering2=false;
                _this.preAnswer=true;
            },
            getTime:function (time) {
                return xqzs.dateTime.getTimeFormatText(time)
            },
            playV:function () {
                let _this=this;
                if(_this.vPaused){  //暂停中也就是已经获取到且为当前音频
                    _this.vPaused=false;
                    _this.vPlaying=true;
                    xqzs.wx.voice.startPlay(_this.localId)
                    console.log("1")
                }else{
                    if(_this.vPlaying){    //播放中去做暂停操作
                        _this.vPaused=true;
                        _this.vPlaying=false;
                        xqzs.wx.voice.pausePlay(_this.localId)
                        console.log("2")
                    }else{     //重新打开播放
                        _this.vPaused=false;
                        _this.vPlaying=true;
                        xqzs.wx.voice.startPlay(_this.localId)
                        console.log("3")
                    }

                }

            }
        },
        mounted: function () {

            xqzs.wx.setConfig(this);
            let _this= this;
            _this.getBgmList()
            myVideo.config({obj:$('.circle')}).init(_this.start,_this.stop,_this.play,_this.play);
        },
        activated:function () {
            this.answering2 = false;
            this.preAnswer = false;
            this.playing = false;
            this.answerTime = "00";
            this.timeOut = null;
            this.finish = false;
            this.edit= this.$route.query.edit;
            this.vPlaying = false;
            this.vPaused = false;
            this.localId = null;
            this.serviceId = null;
            this.voiceLength = 0;
            this.MIN_VOICE_LENGTH = 10;
            this.clearTimeOut();

        },
        components: {
            "v-answer-top-step": answerTopStep
        },
        beforeDestroy:function () {
            this.clearTimeOut()
        },


    }
</script>
<style>
    .answer_join_voice .voice_bg_box{border-top: 0.2rem solid #F4F4F7;padding-top: 0.3rem;padding-left: 0.3rem;color:#454B54;}
    .voice_bg_box h3{font-size: 0.3rem;line-height: 0.42rem;margin-bottom: 0.06rem;}
    .voice_bg_box ul{padding-left: 0.1rem;}
    .voice_bg_box li{line-height: 1.08rem;border-bottom: 0.02rem solid #eee;font-size: 0.28rem;position: relative}
    .voice_bg_box .bgmImg{width:0.6rem;height:0.6rem;margin-right: 0.24rem;display: inline-block;vertical-align: middle}
    .voice_bg_box .checkedImg{width:0.46rem;height:0.3rem;position: absolute;right:0.6rem;top:50%;margin-top: -0.23rem;}
    .voice_bg_box .compose_btn{line-height: 0.88rem;text-align: center;color:#fff;font-size: 0.36rem;background: #56C4FE;border-radius: 0.1rem;margin:0.6rem 0.3rem 0.3rem 0}
    .answer_join_voice{ width: 100%; overflow-x: hidden}
    .answer_join_voice .voice_placeholder{
        padding:0.4rem 0.3rem 0rem 0.3rem;
        padding-top: 0.4rem;
        font-size: 0.28rem;
        color:#999;
        line-height: 0.4rem;
    }
    .voice_placeholder div{padding-top: 0.4rem;}
    .voice_placeholder header{color:#454B54}
    .answer_join_voice .record_voice_box .time_in{position: static;margin-bottom: 0.5rem;padding-top: 0.4rem;}
    .answer_join_voice .tipc{  margin:0.60rem    0.40rem; font-size: 0.26rem; line-height: 1.5; color:#999}
    .answer_join_voice .audio{ margin-left:  15%  !important ; margin-top: 2.72rem;}
    .pt3{ padding-top: 3.06rem !important}



    .voice_box { position: fixed;  width: 100%; height: 100%; top:0; padding: 3.00rem 0  }
    .voice_box .bg{background: rgba(0,0,0,0.7); width: 100%; height: 100%; position: fixed; top:0;    z-index: -1;}

    /*播放*/
    .answer_join_voice   .time_go{ position: relative;  height: 1.63rem; width: 1.63rem; margin: 0 auto; margin-top: 0.68rem;}
    .answer_join_voice  .time_go .playing { height: 1.63rem; width: 1.63rem; border-radius: 50%; background: #00b9e8; position: absolute; top:0;left:0; text-align: center; color:#fff; font-size: 0.60rem; line-height: 1.63rem;}
    .answer_join_voice   .play{
        opacity: 0.1;
        transform:scale(1.6,1.6);
    }
    .answer_join_voice   .play2{
        opacity: 0.3;
        transform:scale(1.4,1.4);
    }
    .answer_join_voice   .play3{
        opacity: 0.4;
        transform:scale(1.2,1.2);
    }

    .answer_join_voice  .play_go  .play{
        animation: playing 1s infinite;
        -webkit-animation: playing  1s infinite;
        animation-delay: -0.8s;
        -webkit-animation-delay: -0.8s;
    }
    .answer_join_voice  .play_go .play2{
        animation-delay: -0.6s;
        -webkit-animation-delay: -0.6s;
        animation: playing 1s infinite;
        -webkit-animation: playing 1s infinite;

    }
    .answer_join_voice  .play_go .play3{
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






    .answer_join_voice  .times{ padding: 0.07rem 0.85rem; background: #fff}
    .answer_join_voice   .times .bg{ background: #163201; height: 1.00rem;  border-radius: 0.50rem ; }
    .answer_join_voice    .times .bg .in{ padding:  0.05rem; }
    .answer_join_voice    .times .bg .go{ background: #09bb07; height: 0.90rem; min-width:0.90rem;  width: 0; border-radius:0.45rem;      }
    .answer_join_voice  .times .last_time{ text-align: center; color:#666; margin-top: 0.17rem; font-size: 0.52rem;}
    .answer_join_voice  .action_btn{   padding: 1.20rem 0.34rem;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
    }
    .answer_join_voice  .action_btn .item{
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;
        text-align: center; color:#fff; font-size: 0.26rem;
    }
    .answer_join_voice  .action_btn .item .txt{ margin-top: 0.20rem}
    .answer_join_voice  .audio_btn_in{ background: #fff; border-radius: 50%; height: 1.40rem; width: 1.40rem;
        -webkit-box-shadow:0 0 0.16rem #09bb07;box-shadow:0 0 0.16rem #09bb07;  position: relative; margin: 0 auto}
    .answer_join_voice .audio_btn_in:active{ background: rgba(105,186,45,0.1)
    }

    .answer_join_voice .audio_send.not { background: #b1b1b1;}
    .answer_join_voice .audio_send.not:active{ background: #b1b1b1;}


    .answer_join_voice  .audio_btn_in:before{content: " " ; display: block ; position: absolute; top:50%; left:50% ;}
    .answer_join_voice   .audio_begin:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_begin.png)  no-repeat; background-size:  0.46rem; width:0.46rem;; height: 0.64rem;  margin-left: -0.23rem; margin-top: -0.32rem;  }
    .answer_join_voice   .audio_end:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_end.png)  no-repeat; background-size:  0.42rem; width:0.42rem;; height: 0.42rem;  margin-left: -0.21rem; margin-top: -0.21rem;  ;   }
    .answer_join_voice   .audio_play:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_play.png)  no-repeat; background-size: 0.45rem; width:0.45rem;; height: 0.52rem;  margin-left: -0.18rem; margin-top: -0.26rem;  }
    .answer_join_voice   .audio_send:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_send.png)  no-repeat; background-size:  0.46rem; width:0.50rem;; height:0.48rem;  margin-left: -0.25rem; margin-top: -0.24rem;   }
    .answer_join_voice   .audio_cant_begin:before{ background:url(http://oss.xqzs.cn/resources/psy/audio_btn_cant_begin.png)  no-repeat; background-size:  0.46rem; width:0.46rem;; height: 0.64rem;  margin-left: -0.23rem; margin-top: -0.32rem;  }
    .answer_join_voice .addPlayBox{position: absolute;bottom:0.5rem;width:100%;z-index: 10}
    .answer_join_voice  .record_voice_box{top:55%;position: absolute;width:100%;margin:0;background: #fff;padding-bottom: 0.4rem;height: auto}
    .answer_join_voice .voice_needMusic{padding-bottom:0;animation: heightChange 1s ease forwards}
    .answer_join_voice .record_voice_box .circle{margin-bottom: 0.4rem;}
    .answer_join_voice .record_voice_box .tip{margin-bottom: 0.4rem;}
    .answer_join_voice .record_voice_box .circle{z-index: 1000}

    @keyframes heightChange{
        from{top:55%;}
        to{top:0;}
    }
</style>
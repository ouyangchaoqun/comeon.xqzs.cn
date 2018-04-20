<template>
    <div style="height: 100%" class="transitionBox">
        <transition :name="transitionName">

            <keep-alive   >
                <router-view    class="child-view" :user=user :isGoIndex="isGoIndex"
                             :isKeepAlive="isKeepAlive" :expert="expert"></router-view>
                <!-- 这里是会被缓存的视图组件 -->
                </router-view>
            </keep-alive>


        </transition>

    </div>
</template>
<script>
    import Bus from './bus.js';
    xqzs.localdb.set("isReUrl","false");
    export default {
        data() {
            return {
                transitionName: 'page-xqzs-left',
                pagesIn: [],
                isFunny: false,
                user: {},
                expert:{},
                friendMoodsSpe: [],
                friendMoods: [],
                myLastMood: null,
                isGoIndex: false,
                isKeepAlive:false
            }
        },
        created: function () {
            let channelopenid = this.$route.query.channelopenid;
            if(channelopenid){
                xqzs.localdb.set("channelopenid",channelopenid);
            }
            let _this=this;
            xqzs.user.getUserInfo(function (user) {
                console.log('app---------created')
                _this.user =user;
                _this.subscribe();//没有关注的逻辑
            });

            Bus.$on("onUpdateUserInfo", function () {
                _this.updateUserInfo();
            });
//
        },
        mounted: function () {
            let _this = this
            Bus.$on("goIndex", function (v) {
                _this.isGoIndex = v;
            });
            this.getExpert();



        },
        methods: {
            updateUserInfo:function () {
                let _this= this;
                console.log('app---updateUserInfo')
                xqzs.user.getUserInfo(function (user) {
                    _this.user =user;
                });
            },
            subscribe:function () {
                let  isShare = this.$route.query.share;


                if(!this.user||(this.user.issubscribe!=1&&isShare==1)){
                    //戳我
                    let clickMeHtml  = '<div class="click_me" ><span>心理咨询</span></div>';
                    $('body').append(clickMeHtml);
                    $(".click_me").click(function () {
                        xqzs.user.getShareTip();
                    })

                }
            },
            getExpert:function () {
                let _this=this;
                this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                      if(data.data.data&&data.data.data.id){
                          let  expertId = data.data.data.id;
                          _this.expert= data.data.data;
                          cookie.set('expertId',expertId,300);
                      }
                    }
                }, function (error) {
                });
            },

            //清除声音
            clearAllVoice: function () {
//                xqzs.voice.pause();
//                wx.stopRecord();
//                let localId = xqzs.localdb.get("voice_localId");
//                console.log("voice_localId："+localId);
//                if (localId && localId != "") {
//                    wx.stopVoice(localId);
//                    console.log(localId)
//                }
            },

            //清除未关闭的控件
            clearPlugs: function () {
                $(".child-view").removeClass("page-xqzs-left-enter-active").removeClass("page-xqzs-left-leave-active").removeClass("page-xqzs-right-enter-active").removeClass("page-xqzs-right-leave-active");
                xqzs.weui.removeWhenPageChange();
                xqzs.image.hideClip();
                $(".weui-toast").remove();
                $(".weui-mask_transparent").remove();

            },
            isTabChange: function (from, to) {
                let userTabs = [
                    '/asker/expert',
                    '/asker/listen',
                    '/asker/ask/',
                    '/asker/my/ask/list',
                    '/asker/my/index'
                ];
                let answerabs = [
                    '/answer/race/list',
                    '/answer/ask/list',
                    '/answer/my/index'
                ];

                let flag = false;
                if ($.inArray(from.path, userTabs) != -1 && $.inArray(to.path, userTabs) != -1) {
                    flag = true;
                } else if ($.inArray(from.path, answerabs) != -1 && $.inArray(to.path, answerabs) != -1) {
                    flag = true;
                }
                return flag;
            }
        },

        beforeRouteUpdate(to, from, next) {


           if($('.yo-scroll')){
               //console.log($('.yo-scroll').scrollTop());
               xqzs.localdb.set("st_"+from.path,$('.yo-scroll').scrollTop())

           }

//            this.$store.commit('SAVE_POSITION', position) //离开路由时把位置存起来



            this.clearPlugs();
            this.clearAllVoice();
             //是否底部tab切换
            if (this.isTabChange(from, to)) {
                this.transitionName = '';
                this.isKeepAlive=true;
            } else {
                let isBack = false;
                //左右滑动判断存储
                for (let i = 0; i < this.pagesIn.length; i++) {
//                console.log(this.pagesIn[i]);
                    if (this.pagesIn[i].to == from.fullPath && this.pagesIn[i].from == to.fullPath) {
                        isBack = true;
                        this.pagesIn.splice(i, 1);
                        break;
                    }
                }
                if (!isBack) {
                    this.pagesIn.push({to: to.fullPath, from: from.fullPath})
                }

                if (isBack) {
                    this.transitionName = 'page-xqzs-right'
                } else {
                    this.transitionName = 'page-xqzs-left'
                }
                this.isKeepAlive=isBack;
                console.log( this.isKeepAlive)
            }
            if(this.isKeepAlive){
                from.meta.isKeepAlive =  true;
                to.meta.isKeepAlive =  false;
            }else{
                from.meta.isKeepAlive =  false;
                to.meta.isKeepAlive =  true;

            }

            this.$nextTick(function () {
                setTimeout(function () {
                    next()
                }, 1)
            })
        },

    }
</script>

<style>
    .subscribe_box{ background: #fff; position: absolute; top:50%;left:50%; margin-top: -3.2rem; height: 6.4rem; width: 6rem ; margin-left: -3rem; border-radius: 0.1rem; font-size: 0.3rem; color:#666;  z-index: 1000;
        text-align: center; line-height: 2 }
    .subscribe_box img{ width: 4rem;
        height:4rem; }
    .subscribe_box .top{ line-height: 1rem;}
     .click_me {   color:#fff;   z-index: 1000;      position: fixed; top:4rem; right:0rem;

         height: 1.10rem;
         width: 1.44rem;
         background: url(http://oss.xqzs.cn/resources/psy/logo.png) no-repeat #fff;
         background-size: 0.50rem;
         background-position: 0.54rem 0.07rem;
         border-radius: 0.55rem;
         box-shadow: -0.08rem 0.04rem 0.12rem #ddd;

         border-bottom-right-radius: 0;
         border-top-right-radius: 0;
     }
    .click_me span{

        margin-top: 0.61rem;
        margin-left: 0.07rem;
        display: inline-block;
        width: 100%;
        text-align: center;
        color: rgba(69,75,84,1);
        font-size: 0.24rem;
    }



    .audio .audio_btn:before {
        width: 0.23rem;
        position: absolute;
        content: " ";
        display: block;
        height: 0.33rem;
        background: url(http://oss.xqzs.cn/resources/psy/sond_blue.png) no-repeat;
        background-size: 0.23rem;
        top: 30%;
        left: 0.20rem;
        z-index: 2;
    }

    .audio .audio_btn.pay:before {
        width: 0.23rem;
        position: absolute;
        content: " ";
        display: block;
        height: 0.33rem;
        background: url(http://oss.xqzs.cn/resources/psy/sond_yello.png) no-repeat;
        background-size: 0.23rem;
        top: 30%;
        left: 0.20rem;
        z-index: 2;
    }

    .audio .audio_btn:after{ position: absolute;     top: -0.02rem;
        left: -0.17rem;height: 0.91rem; background: url(http://oss.xqzs.cn/resources/psy/audio_blue_left.png) no-repeat; width:0.97rem; background-size: 0.97rem;  display: block; content:" "; z-index: 1}
    .audio .audio_btn.pay:after{ position: absolute;     top: -0.02rem;
        left: -0.17rem; height: 0.91rem; background: url(http://oss.xqzs.cn/resources/psy/audio_yellow_left.png) no-repeat; width:0.97rem; background-size: 0.95rem;  display: block; content:" "; z-index: 1}


    .arrow:after {
        background: url(http://oss.xqzs.cn/resources/psy/arrow.png);
        width: 0.32rem;
        height: 0.32rem;
        background-size: 0.32rem;
        position: absolute;
        right: 0.30rem;
        top: 50%;
        margin-top: -0.16rem;
        content: " "
    }

    .good_care.good_cared {
        background: url("http://oss.xqzs.cn/resources/psy/asker/zan_por1.png") no-repeat;
        background-size: 0.32rem;
        background-position: 0,0.10rem;
    }
    .good_care {
        float: left;
        display: inline-block;
        margin-right: 0.07rem;
        background: url("http://oss.xqzs.cn/resources/psy/asker/zan_nor.png") no-repeat;
        background-position: 0,0.10rem;
        background-size: 0.32rem;padding-left: 0.37rem; margin-left: 0.10rem;
    }

    .child-view {
        position: absolute !important;
        width: 100% !important;
        height: 100% !important;
        background: #f4f4f7;
        top: 0 !important;
        left: 0 !important;
        overflow-y: scroll !important;
        -webkit-overflow-scrolling: touch
    }

    .transitionBox {
        position: relative
    }

    .transitionBox .child-view:nth-child(1) {
        z-index: 2
    }

    .transitionBox .child-view:nth-child(2) {
        z-index: 1
    }

    .page-xqzs-left-enter-active {
        animation-name: fold-in;
        animation-duration: .38s;
    }

    .page-xqzs-left-leave-active {
        animation-name: fold-out;
        animation-duration: .38s;
    }

    .page-xqzs-right-enter-active {
        animation-name: fold-right-in;
        animation-duration: .25s;
    }

    .page-xqzs-right-leave-active {
        animation-name: fold-right-out;
        animation-duration: .25s;
    }

    @media (device-height: 480px) and (-webkit-min-device-pixel-ratio: 2) {
        /* 兼容iphone4/4s */
        .page-xqzs-left-enter-active {
            animation-name: noA;
            animation-duration: 0s;
        }

        .page-xqzs-left-leave-active {
            animation-name: noA;
            animation-duration: 0s;
        }

        .page-xqzs-right-enter-active {
            animation-name: noA;
            animation-duration: 0s;
        }

        .page-xqzs-right-leave-active {
            animation-name: noA;
            animation-duration: 0s;
        }
    }

    @media (device-height: 568px) and (-webkit-min-device-pixel-ratio: 2) {
        /* 兼容iphone5 */
        .page-xqzs-left-enter-active {
            animation-name: noA;
            animation-duration: 0s;
        }

        .page-xqzs-left-leave-active {
            animation-name: noA;
            animation-duration: 0s;
        }

        .page-xqzs-right-enter-active {
            animation-name: noA;
            animation-duration: 0s;
        }

        .page-xqzs-right-leave-active {
            animation-name: noA;
            animation-duration: 0s;
        }
    }

    @keyframes fold-right-in {
        0% {
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
        }
        100% {
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
        }
    }

    @keyframes fold-right-out {
        0% {
            transform: translate3d(0%, 0, 0);
            -webkit-transform: translate3d(0%, 0, 0);
        }

        100% {
            transform: translate3d(100%, 0, 0);
            -webkit-transform: translate3d(100%, 0, 0);
        }
    }

    @keyframes fold-in {
        0% {
            transform: translate3d(100%, 0, 0);
            -webkit-transform: translate3d(100%, 0, 0);
        }
        15% {
            transform: translate3d(100%, 0, 0);
            -webkit-transform: translate3d(100%, 0, 0);
            z-index: 2;
        }
        100% {
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
            z-index: 2;
        }
    }

    @keyframes fold-out {
        0% {
            transform: translate3d(0%, 0, 0);
            -webkit-transform: translate3d(0%, 0, 0);
        }
        15% {
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
            z-index: 1;
        }
        100% {
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
            z-index: 1;
        }
    }


    .record_voice_box .mask i.start{ background: url(http://oss.xqzs.cn/resources/psy/answer/record_voice_start.png) no-repeat;
        background-size: 0.55rem;
        width: 0.55rem;
        height: 0.77rem;
        margin-left: -0.28rem;
        margin-top: -0.38rem;}
    .record_voice_box .mask i.end{     background: url(http://oss.xqzs.cn/resources/psy/answer/record_voice_stop.png) no-repeat;
        background-size: 0.54rem;
        width: 0.54rem;
        height: 0.54rem;
        margin-left: -0.27rem;
        margin-top: -0.27rem; }
    .record_voice_box .mask i.playing{    background: url(http://oss.xqzs.cn/resources/psy/answer/record_voice_play.png) no-repeat;
        background-size: 0.54rem;
        width: 0.54rem;
        height: 0.65rem;
        margin-left: -0.20rem;
        margin-top: -0.32rem; }
    .record_voice_box .mask i.pause{    background: url(http://oss.xqzs.cn/resources/psy/answer/record_voice_pause.png) no-repeat;
        background-size: 0.54rem;
        width: 0.54rem;
        height: 0.61rem;
        margin-left: -0.27rem;
        margin-top: -0.31rem; }

    .record_voice_box .time_in{ color:#56C4FE ; font-size:0.54rem; text-align: center ; position: absolute  ; top:-2.55rem; width: 100%; padding-left: 0.17rem;}
    .record_voice_box .time_in_tip{ color:#999;font-size: 0.28rem;text-align: center;position: absolute  ; top:-2.38rem;width: 100%;}
    .addPlayBox  .re_start:before{content: ' '; display: block; width: 0.6rem; height: 0.6rem; background: url('http://oss.xqzs.cn/resources/psy/answer/voice_restart_nor.png') no-repeat; background-size: 0.6rem; margin: 0 auto; margin-top: 0.27rem; margin-bottom: 0.17rem;color:#999}
    .addPlayBox .re_start{ text-align: center;color:#56C4FE}
    .addPlayBox .re_start.re_start_per{color:#999;}
    .addPlayBox .re_start.re_start_per:before{ content: ' '; display: block; width: 0.6rem; height: 0.6rem; background: url('http://oss.xqzs.cn/resources/psy/answer/voice_restart_per.png') no-repeat; background-size: 0.6rem; margin: 0 auto; margin-top: 0.27rem; margin-bottom: 0.17rem;}

    .addPlayBox .send.cant_send:before{ content: ' '; display: block; width: 0.6rem; height: 0.6rem; background: url('http://oss.xqzs.cn/resources/psy/answer/voice_sub_per.png') no-repeat; background-size: 0.6rem; margin: 0 auto; margin-top: 0.27rem; margin-bottom: 0.17rem;}
    .addPlayBox .send:before{ content: ' '; display: block; width: 0.6rem; height: 0.6rem; background: url('http://oss.xqzs.cn/resources/psy/answer/voice_sub_nor.png') no-repeat; background-size: 0.6rem; margin: 0 auto; margin-top: 0.27rem; margin-bottom: 0.17rem;}
    .addPlayBox .send.cant_send{ color:#999}
    .addPlayBox  .send{  color:#56C4FE }


</style>
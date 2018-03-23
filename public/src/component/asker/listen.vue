<template id="stealListen_index">
    <div class="asker_listen_box" :class="{wbg:list.length==0 }">
        <!--头部导航栏-->
        <div v-title>心理咨询</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <!--<div @click.stop="go_expert()" class="expert_entry" v-if="isRegExpert"></div>-->
        <div class="weui-tab__panel main">
             <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                      :isShowMoreText="isShowMoreText" :bottomHeight="50">
                <v-typeHeader :urlType="1"></v-typeHeader>
                <div style="height:0.20rem;background: #f4f4f7"></div>
                 <!--精选-->
                 <div class="index_box selected_box">
                     <div class="new_question">
                         <div class="kuan"></div>
                         <div class="top_left">精选 • 问题</div>
                         <div class="top_right" @click="updateList()">
                            更多
                         </div>
                     </div>
                     <div v-show="list.length>0" class="index_content_active">
                         <ul>
                             <li v-for="(item,index) in RecommendList">
                                 <img :src=item.expertFaceUrl alt="" class="expert_headerImg" @click="goAnswer(item.expertId)">
                                 <div @click="goDetail(item.questionId)">
                                     <div class="index_li_header">
                                         <div>{{item.expertName}} 回答了</div>
                                         <div>{{item.title}}</div>
                                     </div>
                                     <div class="index_li_content">{{item.content}}</div>
                                     <div class="index_li_bottom">
                                         <!--免费听-->
                                         <span class="problem_answer_yy" v-if="item.answerType==1">
                                            <div class="audio_mask" @click.stop="hideMask(index,RecommendList)" :class="{maskState:item.isAdd}"></div>
                                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                                <div class="audio_btn " @click.stop="play(index,RecommendList)" :class="{widthAnimation_class:item.isAdd}">
                                                    <div class="radio"><span></span><i></i></div>
                                                    <template v-if="!item.playing&&!item.paused">点击播放</template>
                                                    <template v-if="item.playing">正在播放..</template>
                                                    <template v-if="item.paused">播放暂停</template>
                                                    <div class="second">{{(item.ct && item.ct!='00')?item.ct:item.length}}”</div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </span>

                                         <!--付费听-->
                                         <div class="problem_answer_yy" v-if="item.answerType==2||item.answerType==4">
                                             <div  class="audio_mask" @click.stop="hideMask(index,RecommendList)" :class="{maskState:item.isAdd}"></div>
                                             <div class="audio">
                                                 <div @click.stop="typeDialog(item.questionId,item.answerId,index ,RecommendList)"
                                                      v-if="item.answerType==2||item.answerType==4" class="audio_btn pay" :class="{widthAnimation_class:item.isAdd}">偷听
                                                     <div class="second">{{(item.ct &&
                                                         item.ct!='00')?item.ct:item.length}}”
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                         <!--限时免费听-->
                                         <span class="problem_answer_yy" v-if="item.answerType==3">
                                            <div class="audio_mask" @click.stop="hideMask(index,RecommendList)" :class="{maskState:item.isAdd}"></div>
                                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                                <div class="audio_btn " @click.stop="play(index,RecommendList)" :class="{widthAnimation_class:item.isAdd}">
                                                    <template v-if="!item.playing&&!item.paused">限时免费听</template>
                                                    <template v-if="item.playing">正在播放..</template>
                                                    <template v-if="item.paused">播放暂停</template>
                                                    <div class="second">{{(item.ct && item.ct!='00')?item.ct:item.length}}”</div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </span>

                                         <div class="index_li_count">听过 {{item.listenTimes}}</div>
                                     </div>
                                 </div>
                             </li>

                         </ul>
                     </div>


                 </div>


                 <!--最新-->
                <div class="index_box">
                    <div class="new_question">
                        <div class="kuan"></div>
                        <div class="top_left">最新 • 问题</div>
                        <div class="top_right" @click="updateList()">
                            更多
                        </div>
                    </div>
                    <div v-show="list.length>0" class="index_content_active">
                        <ul>
                            <li v-for="(item,index) in list" >
                                <img :src=item.expertFaceUrl alt="" class="expert_headerImg" @click="goAnswer(item.expertId)">
                                <div @click="goDetail(item.questionId)">
                                    <div class="index_li_header">
                                        <div>{{item.expertName}} 回答了</div>
                                        <div>{{item.title}}</div>
                                    </div>
                                    <div class="index_li_content">{{item.content}}</div>
                                    <div class="index_li_bottom">
                                        <!--免费听-->
                                        <span class="problem_answer_yy" v-if="item.answerType==1">
                                            <div class="audio_mask" @click.stop="hideMask(index,list)" :class="{maskState:item.isAdd}"></div>
                                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                                <div class="audio_btn " @click.stop="play(index,list)" :class="{widthAnimation_class:item.isAdd}">
                                                    <div class="radio"><span></span><i></i></div>
                                                    <template v-if="!item.playing&&!item.paused">点击播放</template>
                                                    <template v-if="item.playing">正在播放..</template>
                                                    <template v-if="item.paused">播放暂停</template>
                                                    <div class="second">{{(item.ct && item.ct!='00')?item.ct:item.length}}”</div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </span>

                                        <!--付费听-->
                                        <div class="problem_answer_yy" v-if="item.answerType==2||item.answerType==4">
                                            <div  class="audio_mask" @click.stop="hideMask(index,list)" :class="{maskState:item.isAdd}"></div>
                                            <div class="audio">
                                                <div @click.stop="typeDialog(item.questionId,item.answerId,index ,list)"
                                                     v-if="item.answerType==2||item.answerType==4" class="audio_btn pay" :class="{widthAnimation_class:item.isAdd}">偷听
                                                    <div class="second">{{(item.ct &&
                                                        item.ct!='00')?item.ct:item.length}}”
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--限时免费听-->
                                        <span class="problem_answer_yy" v-if="item.answerType==3">
                                            <div class="audio_mask" @click.stop="hideMask(index,list)" :class="{maskState:item.isAdd}"></div>
                                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                                <div class="audio_btn " @click.stop="play(index,list)" :class="{widthAnimation_class:item.isAdd}">
                                                    <template v-if="!item.playing&&!item.paused">限时免费听</template>
                                                    <template v-if="item.playing">正在播放..</template>
                                                    <template v-if="item.paused">播放暂停</template>
                                                    <div class="second">{{(item.ct && item.ct!='00')?item.ct:item.length}}”</div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </span>

                                        <div class="index_li_count">听过 {{item.listenTimes}}</div>
                                    </div>
                                    </div>
                            </li>

                        </ul>
                    </div>


                </div>
            </v-scroll>
         </div>

        <v-asker-bottom tabOnIndex="0"></v-asker-bottom>
        <v-recharge :rechargeMoney="rechargeMoney" v-show="rechargeFlag" :user="user" v-on:childMessage="getFlagVal"></v-recharge>


    </div>

</template>
<script>
    import showLoad from '../include/showLoad.vue';
    import typeHeader from '../include/typeHeader.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../bus.js';
    import askerBottom from "./include/bottom.vue";
    import Recharge from '../asker/my/recharge.vue' ;
    var stealListen_index = {
        template: '#stealListen_index'
    };
    export default {
        data() {
            return {
                navLists: [],
                typeIndex: 0,
                page: 1,
                row: 5,
                isPageEnd: false,
                isShowMoreText: false,
                showLoad: true,
                type: 0,
                timeOut: null,
                playing: false,
                list: [],
                couponNum: 0,
                couponList: [],
                rechargeMoney: 0,
                rechargeFlag: false,

                urlType:1,
                isAnimate:false,
                currPlayIndex:null,
                isRegExpert:false,
                RecommendList:[]
            }
        },
        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
            "v-asker-bottom": askerBottom,
            'v-recharge': Recharge,
            'v-typeHeader': typeHeader,
        },
        mounted: function () {
            let expertId = cookie.get("expertId");
            if(expertId){
                this.isRegExpert = true
            }else{
                this.isRegExpert = false
            }
            this.getClassList();
            this.getUserInfo(true)
            this.getCoupon();
            xqzs.voice.audio = null;


            xqzs.wx.setConfig(this, function () {
                var config = {
                    imgUrl:"http://oss.xqzs.cn/resources/psy/logo.jpg",
                    title:  "专家详解人生小困惑" ,
                    desc: '你的人生小困惑都在这里，专家60秒解忧语音，偷听只需1点豆',
                    link:  xqzs.wx.getPubUrl("asker/listen") ,
                };
                weshare.init(wx, config)
            });

        },
        props:[
            'expert','user'
        ],
        methods: {
            go_expert:function () {
                let _this = this;
                _this.$router.push("/answer/race/list")
            },
            getFlagVal: function (val) {
                this.rechargeFlag = val.rechargeFlag;
                this.getUserInfo()
            },
            updateList:function () {
                this.isAnimate=true
                this.isPageEnd = false;
                this.isShowMoreText = false;
                this.getList(false,true)
            },
            goAnswer:function (extId) {
                this.$router.push('/asker/expert/detail/?id=' + extId)
            },
            getUserInfo: function (isAuto) {

                let _this = this;
                if(isAuto&&!_this.user){
                    console.log("getUserInfogetUserInfogetUserInfogetUserInfo")
                    xqzs.user.getUserInfo(function (user) {
                        _this.user = user;
                    })
                }

            },
            initView: function () {
                 var minHeight = $(window).height() - $('nav').height() - 100;
                 $('.index_nocontent').css('minHeight', minHeight)
            },

            initActive: function () {
//                var obj = $(".index_box li")
//                xqzs.weui.active(obj);
//
//                $(".audio ,.index_li_voice ").on("touchstart", function () {
//                    event.stopPropagation();
//                })
            },
            timeout: function (play, time, index,list) {
                let _this = this;
                _this.timeOut = setTimeout(function () {
                    if (play == true) {  //试听
                        if (time > 0) {
                            time = time - 1;
                            if (time < 10)time = "0" + time
                            _this.timeout(play, time, index,list);
                        } else {
                            _this.playing = false;
                        }
                    }

                }, 1000);
                list[index].ct = time;
                _this.$set(list, index,list[index])
            },

            clearTimeOut: function () {
                let _this = this;
                if (_this.timeOut !== null) {
                    clearTimeout(_this.timeOut);
                }
            },
            //获取是否有偷听卡
            getCoupon: function () {
                let _this = this;
                _this.$http.get(web.API_PATH + 'come/user/get/coupon/_userId_/1/10/0').then(function (data) {
                    _this.couponList = data.data.data;
                    _this.couponNum = data.data.data.length;
                })
            },
            typeDialog: function (questionId, answerId, index,list) {
                let _this = this;
                let useCoupon = false;
                let useCoin = false;
                let recharge = false;
                let payTitle, msg, subHtml;
                if (_this.couponNum != 0) {
                    payTitle = '使用偷听卡免费听';
                    msg = '';
                    subHtml = '';
                    useCoupon = true;
                } else {
                    payTitle = '确认偷听此问题';
                    subHtml = '';
                    msg = '使用：<span class="colorStyle">1</span> 点豆&nbsp&nbsp&nbsp剩余：<span class="colorStyle">' + _this.user.dianCoin + '</span> 点豆';
                    if (Number(_this.user.dianCoin) >= 1) {
                        useCoin = true;
                    } else {
                        recharge = true;
                        subHtml = '去充值';
                    }
                }
                xqzs.weui.dialog(payTitle, msg, subHtml, function () {
                }, function () {
                    switch (true) {
                        case useCoupon:
                            console.log('使用偷听券支付');
                            let data = {
                                code: _this.couponList[0].code,
                                questionId: questionId,
                                answerId: answerId
                            };
                            _this.showLoad = true;
                            $.ajax({
                                url: web.API_PATH + "come/listen/put/coupon/_userId_",
                                data: data,
                                type: 'PUT',
                                dataType: 'JSON',
                                success: function (bt) {
                                    if (bt.status == 1) {
                                        xqzs.weui.toast("success", "支付成功", function () {
                                            _this.setPayed(index,list);
                                        });
                                    } else {
                                        xqzs.weui.tip("支付失败", function () {

                                        });
                                    }
                                    _this.getCoupon();
                                    _this.showLoad = false;
                                }
                            });
                            break;
                        case useCoin:
                            console.log('使用点豆支付');
                            _this.showLoad = true;
                            $.ajax({
                                url: web.API_PATH + "come/listen/put/coin/_userId_/" + questionId + '/' + answerId + '/1',
                                data: data,
                                type: 'PUT',
                                dataType: 'JSON',
                                success: function (bt) {
                                    if (bt.status == 1) {
                                        xqzs.weui.toast("success", "支付成功", function () {
                                            _this.setPayed(index,list);
                                        });
                                    } else {
                                        xqzs.weui.tip("支付失败", function () {

                                        });
                                    }
                                    _this.getUserInfo();
                                    _this.showLoad = false;
                                }
                            });
                            break;
                        case recharge:
                            xqzs.eventLog.visit('comeon_listen_go_pay');
                            _this.rechargeFlag = true
                            break;
                    }

//                    _this.pay(index)
                })
            },
            pay: function (index,list) {
                let item = list[index];
                console.log(item)
                let _this = this;
                this.$http.get(web.API_PATH + "come/listen/create/order/_userId_/" + item.answerId)
                        .then(function (bt) {
                            if (bt.data && bt.data.status == 1) {

                                let result = bt.data.data;


                                xqzs.wx.pay.pay(result.order, function () {

                                }, function () {//success
                                    xqzs.weui.toast("success", "支付成功", function () {
                                        _this.setPayed(index,list);
                                    });
                                }, function () {//error

                                })
                            }
                        });
            },
            //设置dom 已经支付
            setPayed: function (index,list) {
                let item = list[index];
                item.answerType = 1;
                this.$set(list, index, item);
                this.$nextTick(function () {
                    this.initActive();
                })

            },
            hideMask:function (index,list) {
                let _this = this;
//                let list = _this.list;
                for (let i = 0; i < list.length; i++) {
                    list[i].isAdd = false;
                    if (index != i && (list[i].playing || list[i].paused)) {
                        list[i].paused = false;
                        list[i].playing = false;
                    }
                    _this.$set(list, i, list[i]);
                }

                if(_this.currPlayIndex!=null)
                {
                    _this.clearTimeOut();
                    _this.pause(_this.currPlayIndex,list);

                }

                list[index].isAdd = true;
            },

            pause:function (index,list) {
                let  _this=this;
//                let list = _this.list;
                list[index].paused = true;
                list[index].playing = false;
                _this.currPlayIndex = null;
                _this.$set(list, index, list[index])
                xqzs.voice.pause();
            },

            play: function (index,list) {
                let _this = this;
//                let list = _this.list;
                let CT = list[index].ct ? list[index].ct : list[index].length;
                let T = list[index].length;
                xqzs.voice.onEnded = function () {
                    list[index].paused = false;
                    list[index].playing = false;
                    _this.$set(list, index, list[index]);
                    if (_this.playing)_this.clearTimeOut();
                    _this.playing = false;
                };
                //重置其他列表内容
                for (let i = 0; i < list.length; i++) {
                    if (index != i && (list[i].playing || list[i].paused)) {
                        list[i].paused = false;
                        list[i].playing = false;
                        _this.$set(list, i, list[i]);
                    }
                }
                let item = list[index];

                if (item.paused) {  //暂停中也就是已经获取到且为当前音频
                    list[index].paused = false;
                    list[index].playing = true;
                    _this.$set(list, index, list[index])
                    xqzs.voice.play();
                    _this.currPlayIndex=index;
                    _this.timeout(true, CT, index,list)
                } else {
                    if (item.playing) {    //播放中去做暂停操作
                        list[index].paused = true;
                        list[index].playing = false;
                        _this.$set(list, index, list[index])
                        xqzs.voice.pause();
                        _this.clearTimeOut();
                        _this.playing = false;
                    } else {     //重新打开播放
                        let answerId = item.answerId;
                        xqzs.voice.getAnswerVoice(answerId, function (url) {
                            xqzs.voice.play(url);
                            list[index].playing = true;
                            list[index].paused = false;
                            _this.$set(list, index, list[index]);
                            _this.playing = true;
                            _this.currPlayIndex=index;
                            _this.clearTimeOut();
                            _this.timeout(true, T, index,list)
                        })
                    }

                }



            },

            goDetail: function (questionId) {
                this.$router.push("/asker/listen/detail/?questionId=" + questionId)
            },
            getClassList: function () {
                let _this = this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list').then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        let arr = [{"title": "全部", id: 0}]
                        _this.navLists = data.body.data
                        _this.navLists = arr.concat(_this.navLists);

                        _this.initView();
                        _this.getList();
                        _this.getRecommendList();
                    }
                }, function (error) {
                });
            },
            randContentNum:function (arr) {

                for(let i =0;i<arr.length;i++){
                    let count =30 + parseInt( Math.random()*40);
                    if(count<arr[i].content.length){
                        arr[i].content= arr[i].content.substring(0,count) +".."
                    }

                }
                return arr ;
            },
            getRecommendList:function () {
                let vm = this;
                vm.$http.get(web.API_PATH + 'come/listen/listen/list/_userId_/' + vm.type + '/1/3'+'?hottestOrNewest='+3).then((response) =>{
                    vm.RecommendList = response.data.data;
                })
            },
            getList: function (done,isRef) {
                let vm = this;
                this.rankUrl = web.API_PATH + 'come/listen/listen/list/_userId_/' + vm.type + '/' + vm.page + '/' + vm.row+'?hottestOrNewest='+2;

                if (web.guest) {
                    vm.rankUrl = vm.rankUrl + "guest=true"
                }
                if (vm.isLoading || vm.isPageEnd) {
                    return;
                }
                if (vm.page == 1) {
                    vm.showLoad = true;
                }
                vm.isLoading = true;
                if (vm.couponNum != 0) {
                    vm.getCoupon();
                }

                vm.$http.get(vm.rankUrl).then((response) => {
                    if (done && typeof(done) === 'function') {
                        done()
                    }
                    vm.showLoad = false;
                    vm.isLoading = false;


                    if (response.data.status != 1 && vm.page == 1) {
                        vm.list = [];
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;
                        Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                        return;
                    }

                    let arr = response.data.data;
                    arr= vm.randContentNum(arr);
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    } else {

                        vm.isShowMoreText = true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                    vm.isAnimate=false
                    if (vm.page == 1) {
//                        console.log("dogopage1")
                        vm.list = arr;
                    } else {
                        if(isRef==true){
                            vm.list = arr;
                            if(vm.list.length==0){
                                vm.page=1;
                                vm.updateList()
                            }
                        }else {
                            vm.list = vm.list.concat(arr);
                        }

                    }
                    if (arr.length == 0) return;

                    vm.page = vm.page + 1;

                     vm.$nextTick(function () {
                        vm.initActive()
                    });

                }, (response) => {
                    vm.isLoading = false;
                    vm.showLoad = false;
                });

            },
            onInfinite(done) {
                this.getList(done);

            },
        },
        beforeDestroy: function () {
            xqzs.voice.pause();
        },
    }


</script>

<style>
    .expert_entry{
        width:1rem;
        height:1rem;
        border-radius: 50%;
        background: url("http://oss.xqzs.cn/resources/psy/asker/expert_entry_bg.png") no-repeat center;
        background-size: 0.5rem 0.5rem;
        background-color: RGBA(86, 196, 245,1);
        position: absolute;
        right:0.25rem;
        bottom:15%;
        z-index: 10001;
    }
    .asker_listen_box .audio_mask{
        position: absolute;
        top: -0.02rem;
        left:0rem;
        height: 0.91rem;
        width: 0.97rem;
        z-index: 100;
    }
    .asker_listen_box .maskState{
        display: none;
    }
    .weui-dialog .weui-dialog__bd .colorStyle {
        color: rgba(251, 100, 10, 1);
    }

    .asker_listen_box {
        background: #fff;
    }

    .asker_listen_box .audio .audio_btn {
        width: 0 !important;
    }
    .widthAnimation_class{
        animation:widthAnimation .3s linear forwards;
        -webkit-animation:widthAnimation .3s linear forwards;

    }
    @keyframes widthAnimation {
        0%{width:0;opacity: 0}
        100%{width:3.52rem;opacity: 1}
    }
    @-webkit-keyframes widthAnimation {
        0%{width:0;opacity: 0}
        100%{width:3.52rem;opacity: 1}
    }

    .index_li_bottom .problem_answer_yy {
        width: 100%
    }

    .index_li_bottom .problem_answer_play {
        margin-left: 0;
        left: 12%;
    }





    .index_box {
        width: 100%;
        height: auto;
    }

    .index_box .index_content_active {
        display: block;
    }


    .index_box li {
        background: #fff;
        padding: 0.30rem 0.30rem 0.36rem 0;
        border-bottom: 0.02rem solid #eee;
        position: relative;
        padding-left: 1.3rem;
    }
    .index_box li .expert_headerImg{
        width:0.8rem;
        height:0.8rem;
        border-radius: 50%;
        position: absolute;
        left:0.3rem;
        border: solid 0.02rem #eee;
    }

    .index_li_header {
        font-size: 0.24rem;
        color: RGBA(69, 75, 84, 0.6);
        line-height: 0.34rem;
        margin-bottom: 0.18rem;
        position: relative;
    }

    .index_li_header .header_className {
        font-size: 0.28rem;
    }

    .index_li_header img {
        height: 0.58rem;
        width: 0.58rem;
        display: block;
        border-radius: 50%;
        margin-right: 0.16rem;
    }

    .index_li_content {
        font-size: 0.30rem;
        color: rgba(36, 37, 61, 1);
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        margin-bottom: 0.30rem;
    }

    .index_li_bottom {
        position: relative;
    }

    .index_li_bottom img {
        position: absolute;
        left: 0;
        width: 0.82rem;
        height: 0.82rem;
        border-radius: 50%
    }

    .index_li_voice {
        position: relative;
        font-size: 0.30rem;
        color: #fff;
        width: 3.48rem;
    }

    .index_li_voice img {
        width: 100%;
        height: auto;
        display: block;
    }

    .index_li_voice div {
        width: 2.00rem;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -1.00rem;
        text-align: center;

    }

    .position_change1 {
        margin-top: -0.06rem;
    }

    .position_change2 {
        margin-top: -0.30rem;
    }

    .index_li_count {
        position: absolute;
        right: 0;
        font-size: 0.26rem;
        color: rgba(36, 37, 61, 0.5);
        top: 0;
        line-height: 0.82rem;
    }

    .index_nocontent {
        background: #fff;
        margin-top: 1px;
        text-align: center;
        font-size: 0.26rem;
        color: rgba(36, 37, 61, 0.5);
        position: relative;
    }

    .index_nocontent img {
        display: block;
        margin: 0 auto;
        width: 100%;
    }

    .index_nocontent > div {
        position: absolute;
        top: 0.68rem;
        width: 100%;
    }

    .nocontent_html {
        margin-top: -1.29rem
    }

    .asker_listen_box .audio {
        margin-bottom: 0;
        overflow: hidden;
    }
    .new_question{
        height: 1.10rem;
        width: 100%;
        border-bottom: 0.02rem solid #eee;
        overflow: hidden;
    }
    .top_left{
        float: left;
        line-height: 1.10rem ;
        font-size: 0.3rem;
        margin-left: 0.27rem ;
        font-weight: bold;
        color:RGBA(86, 196, 254, 1)
    }
    .top_right{
        float: right;
        margin-right: 0.4rem;
        font-size: 0.24rem;
        color:RGBA(69, 75, 84,0.5);
        line-height: 1.1rem;
        background: url(http://oss.xqzs.cn/resources/psy/arrow.png) no-repeat center right;
        background-size: 0.24rem 0.20rem;
        padding-right: 0.30rem;
    }

    .index_box .new_question .top.text{
        float: left;
    }
    .index_box .new_question .refresh{
        width: 0.34rem;
        height:0.3rem;
        margin-right: 0.05rem;
        background:url("http://oss.xqzs.cn/resources/psy/asker/asker_refresh_blue.png") no-repeat;
        background-size: 90%;
        float: left;
        margin-top: 0.04rem;
    }
    .index_box .new_question .refresh.click{
        animation:rotate 0.5s linear infinite;
    }
    @keyframes rotate {
        0%{
            transfrom:rotate(0deg);

        }
        100%{
            transform: rotate(180deg);
        }
    }

    .asker_listen_box .kuan{
        width: 0.09rem;
        height: 0.4rem;
        position: absolute;
        margin: 0.34rem  0;
        left:0;
        border-right: 0.09rem solid transparent;
        border-top: 0.4rem solid #56C4FE;
    }
    .selected_box {
        border-bottom: 0.16rem solid RGBA(244, 244, 247, 1);
    }
    .selected_box .kuan{
        border-top: 0.4rem solid RGBA(254, 122, 3, 1);
    }
    .selected_box .top_left{
        color:RGBA(254, 122, 3, 1);
    }
</style>
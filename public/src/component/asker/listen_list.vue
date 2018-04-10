<template>
    <div class="asker_listen_box listenList_box">
        <div v-title class='hide_title'>{{titleVal}}</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="weui-tab__panel main">
            <div class="down_list">
                <div class="nav_select">
                    <div class="sort_rank" :class="{active_color:isShowSort||firstSel}" @click="showSelect(1)">{{nowSort}}<span class="sanjiao" :class="{xsanjiao:isShowSort,selActive:firstSel&&!isShowSort}"></span></div>
                    <div class="class_rank" :class="{active_color:isShowClass||twoSel}"  @click="showSelect(2)">{{nowClass}}<span class="sanjiao" :class="{xsanjiao:isShowClass,selActive:twoSel&&!isShowClass}"></span></div>
                </div>
                <div  v-if="isShowSort" class="sort_list"  >
                    <ul>
                        <li v-for="item in sortList" :val="item.value" class="sort_list_item " :class="{selected:nowSort==item.label}" @click="selectTab(item,1)">{{item.label}}
                            <img v-show="nowSort==item.label" src="http://oss.xqzs.cn/resources/psy/asker/filter_sure_icon.png" alt="">
                        </li>
                    </ul>

                </div>
                <div v-if="isShowClass" class="class_select" >
                    <li v-for="item in navLists" :val="item.value" class="class_list_item" :class="{selected:nowClass==item.title}" @click="selectTab(item,2)">
                        {{item.title}}
                    </li>
                </div>
            </div>
            <div class="downList_mask" v-if="isShowSort==true||isShowClass==true" @click="closeList()" @touchmove.prevent></div>
            <!--导航栏-->
            <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite"
                      :isPageEnd="isPageEnd" :isShowMoreText="isShowMoreText" :bottomHeight="0" >

                <div class="asker_listen_list_box">
                    <div v-show="list.length>0">
                        <ul>
                            <li v-for="(item,index) in list" @click="goDetail(item.questionId)">
                                <img :src=item.expertFaceUrl alt="" class="expert_headerImg">
                                <div class="index_li_header">
                                    <div>{{item.expertName}} 回答了</div>
                                    <div>{{item.title}}</div>
                                </div>
                                    <div class="index_li_content">{{item.content}}</div>
                                    <div class="index_li_bottom">
                                        <!--免费听-->
                                        <span class="problem_answer_yy" v-if="item.answerType==1">
                                            <div class="audio_mask" @click.stop="hideMask(index)" :class="{maskState:item.isAdd}"></div>
                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                <div class="audio_btn" @click.stop="play(index)" :class="{widthAnimation_class:item.isAdd}">
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
                                            <div class="audio_mask" @click.stop="hideMask(index)" :class="{maskState:item.isAdd}"></div>
                                            <div class="audio">
                                                <div class="audio_btn pay" @click.stop="typeDialog(item.questionId,item.answerId,index )" :class="{widthAnimation_class:item.isAdd}">偷听
                                                    <div class="second">{{(item.ct &&
                                                        item.ct!='00')?item.ct:item.length}}”
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--限时免费听-->
                                        <span class="problem_answer_yy" v-if="item.answerType==3">
                                            <div class="audio_mask" @click.stop="hideMask(index)" :class="{maskState:item.isAdd}"></div>
                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                <div class="audio_btn" @click.stop="play(index)" :class="{widthAnimation_class:item.isAdd}">
                                    <template v-if="!item.playing&&!item.paused">限时免费听</template>
                                    <template v-if="item.playing">正在播放..</template>
                                    <template v-if="item.paused">播放暂停</template>
                                    <div class="second">{{(item.ct && item.ct!='00')?item.ct:item.length}}”</div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </span>

                                        <div class="index_li_count">{{item.listenTimes}} 人听过</div>
                                    </div>
                            </li>

                        </ul>
                    </div>
                    <!--<div v-show="list.length==0&&!showLoad">-->
                        <!--<div class="index_nocontent">-->
                            <!--<div>-->
                                <!--<img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">-->
                                <!--<div class="nocontent_html">暂无该方面问题</div>-->
                            <!--</div>-->

                        <!--</div>-->
                    <!--</div>-->
            </div>
            </v-scroll>
        </div>
        <v-recharge :rechargeMoney="rechargeMoney" :user="user" v-show="rechargeFlag" v-on:childMessage="getFlagVal"></v-recharge>
    </div>

</template>
<script>
    import showLoad from '../include/showLoad.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../bus.js';
    import askerBottom from "./include/bottom.vue";
    import Recharge from '../asker/my/recharge.vue' ;
    export default {
        data() {
            return {
                //downList

                sortList: [ {label: "最新问题", value: 2, flag: false},{label: "精选问题", value: 3, flag: true},{label: "最热问题", value: 1, flag: false}],
                isShowSort:false,
                isShowClass:false,
                nowSort:"最新问题",
                nowClass:"主题",
                classId:1,
                screenHeight:document.body.clientHeight,
                //downList
                navLists: [
                ],
                typeIndex: 0,
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText: false,
                showLoad: true,
                type: 1,
                timeOut: null,
                playing: false,
                list: [],
                couponNum: 0,
                couponList: [],
                rechargeMoney: 0,
                rechargeFlag: false,

                qType:2,
                titleVal:'',
                currPlayIndex:null,
                firstSel:false,
                twoSel:false
            }
        },
        props:
            ['isKeepAlive','user']
        ,
        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
            "v-asker-bottom": askerBottom,
            'v-recharge': Recharge,
        },
        mounted: function () {
            this.initAll()
        },
        methods: {
            initAll:function () {
                this.type = this.$route.query.classId;
                let qType = this.$route.query.qType;
                if(qType){
                    this.qType = qType;
                }
                let title = this.$route.query.title;
                if(title){
                    this.titleVal = title;
                    this.nowSort = title;
                }
                let rightTitle = this.$route.query.rightTitle
                if(rightTitle){
                    this.titleVal = rightTitle;
                    this.nowClass = rightTitle;
                }
                this.getClassList();
                this.getUserInfo()
                this.getCoupon();
                xqzs.voice.audio = null;
                xqzs.wx.setConfig(this, function () {
                    var config = {
                        imgUrl:"http://oss.xqzs.cn/resources/psy/logo.jpg",
                        title:  "专家详解人生小困惑" ,
                        desc: '你的人生小困惑都在这里，专家60秒解忧语音，偷听只需1点豆',
                        link: weshare.getShareUrl("asker/listen"  ,false)
                    };
                    weshare.init(wx, config)
                });
            },
            //downList
            closeList:function () {
                let _this=this;
                _this.isShowSort=false;
                _this.isShowClass=false;

            },
            showSelect:function (n) {
                let _this=this;
                if(n==1){
                    _this.isShowSort=!_this.isShowSort;
                    _this.isShowClass=false;

                }
                else if(n==2){
                    _this.isShowSort=false;
                    _this.isShowClass=!_this.isShowClass;
                }

            },
            selectTab:function (item,n) {
                //偷听
                console.log(item)
                let _this=this;
                if(n==1){
                    _this.nowSort=item.label;
                    _this.isShowSort=false;
                    _this.qType =item.value;
                    _this.titleVal = item.label;
                    _this.firstSel = true;
                }

                if(n==2){
                    _this.nowClass=item.title;
                    _this.isShowClass=false;
                    _this.type = item.id;
                    _this.titleVal = item.title;
                    _this.twoSel = true;
                }
                this.initGetList();
            },
            //downList
            getFlagVal: function (val) {
                this.rechargeFlag = val.rechargeFlag;
                this.getUserInfo()
            },
            getUserInfo: function () {
                console.log('获取user')
                let _this = this;
                xqzs.user.getUserInfo(function (user) {
                    _this.user = user;
                })
            },

            initActive: function () {
//                var obj = $(".asker_listen_list_box li")
//                xqzs.weui.active(obj);
//
//                $(".audio ,.index_li_voice ").on("touchstart", function () {
//                    event.stopPropagation();
//                })
            },
            timeout: function (play, time, index) {
                let _this = this;
                _this.timeOut = setTimeout(function () {
                    if (play == true) {  //试听
                        if (time > 0) {
                            time = time - 1;
                            if (time < 10)time = "0" + time
                            _this.timeout(play, time, index);
                        } else {
                            _this.playing = false;
                        }
                    }

                }, 1000);
                _this.list[index].ct = time;
                _this.$set(_this.list, index, _this.list[index])
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
                xqzs.api.get(_this,"come/user/get/coupon/_userId_/1/10/0",function (data) {
                    _this.couponList = data.data.data;
                    _this.couponNum = data.data.data.length;
                })

            },
            typeDialog: function (questionId, answerId, index) {


                if(!xqzs.user.isUserLogin()){
                    return ;
                }


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
                            xqzs.api.put(_this, "come/listen/put/coupon/_userId_",data,function (bt) {
                                if (bt.data.status == 1) {
                                    xqzs.weui.toast("success", "支付成功", function () {
                                        _this.setPayed(index);
                                    });
                                } else {
                                    xqzs.weui.tip("支付失败", function () {

                                    });
                                }
                                _this.getCoupon();
                                _this.showLoad = false;
                            })

                            break;
                        case useCoin:
                            console.log('使用点豆支付');
                            _this.showLoad = true;
                            xqzs.api.put(_this,  "come/listen/put/coin/_userId_/" + questionId + '/' + answerId + '/1',data,function (bt) {

                                if (bt.data.status == 1) {
                                    xqzs.weui.toast("success", "支付成功", function () {
                                        _this.setPayed(index);
                                    });
                                } else {
                                    xqzs.weui.tip("支付失败", function () {

                                    });
                                }
                                _this.getUserInfo();
                                _this.showLoad = false;
                            })

                            break;
                        case recharge:
                            xqzs.eventLog.visit('comeon_listen_go_pay');
                            _this.rechargeFlag = true
                            break;
                    }

//                    _this.pay(index)
                })
            },
            pay: function (index) {
                let item = this.list[index];
                console.log(item)
                let _this = this;
                xqzs.api.get(_this,"come/listen/create/order/_userId_/" + item.answerId,function (bt) {
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
                })

            },
            //设置dom 已经支付
            setPayed: function (index) {
                let item = this.list[index];
                item.answerType = 1;
                this.$set(this.list, index, item);
                this.$nextTick(function () {
                    this.initActive();
                })

            },
            hideMask:function (index) {
                let _this = this;
                let list = _this.list;
                for (let i = 0; i < list.length; i++) {
                    list[i].isAdd = false;
                    if (index != i && (list[i].playing || list[i].paused)) {
                        list[i].paused = false;
                        list[i].playing = false;
                    }
                    _this.$set(_this.list, i, list[i]);
                }

                if(_this.currPlayIndex!=null)
                {

                    _this.pause(_this.currPlayIndex);

                }

                list[index].isAdd = true;
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

            play: function (index) {

                let _this = this;
                let list = _this.list;
                let CT = list[index].ct ? list[index].ct : list[index].length;
                let T = list[index].length;
                xqzs.voice.onEnded = function () {
                    list[index].paused = false;
                    list[index].playing = false;
                    _this.$set(_this.list, index, list[index]);
                    if (_this.playing)_this.clearTimeOut();
                    _this.playing = false;
                };
                //重置其他列表内容
                for (let i = 0; i < list.length; i++) {
                    if (index != i && (list[i].playing || list[i].paused)) {
                        list[i].paused = false;
                        list[i].playing = false;
                        _this.$set(_this.list, i, list[i]);
                    }
                }
                let item = _this.list[index];
                if (item.paused) {  //暂停中也就是已经获取到且为当前音频
                    list[index].paused = false;
                    list[index].playing = true;
                    _this.$set(_this.list, index, list[index])
                    xqzs.voice.play();
                    _this.currPlayIndex=index;
                    _this.timeout(true, CT, index)
                } else {
                    if (item.playing) {    //播放中去做暂停操作
                        list[index].paused = true;
                        list[index].playing = false;
                        _this.$set(_this.list, index, list[index])
                        xqzs.voice.pause();
                        _this.clearTimeOut();
                        _this.playing = false;
                    } else {     //重新打开播放
                        let answerId = item.answerId;
                        xqzs.voice.getAnswerVoice(answerId, function (url) {
                            xqzs.voice.play(url);
                            list[index].playing = true;
                            list[index].paused = false;
                            _this.$set(_this.list, index, list[index]);
                            _this.playing = true;
                            _this.currPlayIndex=index;
                            _this.clearTimeOut();
                            _this.timeout(true, T, index)
                        })
                    }

                }

            },

            goDetail: function (questionId) {
                this.$router.push("/asker/listen/detail/?questionId=" + questionId)
            },
            getClassList: function () {
                let _this = this;
                xqzs.api.get(_this, 'come/listen/question/class/list', function (data) {
                    if (data.body.status == 1) {
                        let arr = [{"title": "全部", id: 0, list: []}]
                        _this.navLists = data.body.data;
                        console.log(_this.navLists)
                        _this.navLists = arr.concat(_this.navLists);
                        _this.getList();
                    }
                })

            },
            initGetList:function () {

                this.isPageEnd = false;
                this.page = 1;
                this.isShowMoreText = false;
                this.getList();
            },

            randContentNum:function (arr) {

                for(let i =0;i<arr.length;i++){
                    let count =30 + parseInt( Math.random()*40)
                    if(count<arr[i].content.length){
                        arr[i].content= arr[i].content.substring(0,count) +".."
                    }
                }
                return arr ;
            },

            getList: function (done) {
                let vm = this;
                this.rankUrl = 'come/listen/listen/list/_userId_/' + vm.type + '/' + vm.page + '/' + vm.row+'?hottestOrNewest='+vm.qType;;
                if (vm.isLoading || vm.isPageEnd) {
                    if (done && typeof(done) === 'function') {
                        done()
                    }
                    return;
                }

                if (vm.page == 1) {
                    vm.showLoad = true;
                }
                vm.isLoading = true;
                if (vm.couponNum != 0) {
                    vm.getCoupon();
                }
                xqzs.api.get(vm,vm.rankUrl,function (response) {
                    if (done && typeof(done) === 'function') {
                        done()
                    }
                    vm.showLoad = false;
                    vm.isLoading = false;


                    if (response.data.status != 1 && vm.page == 1) {
                        vm.list = [];
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;

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


                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    if (arr.length == 0) return;

                    vm.page = vm.page + 1;
                    vm.$nextTick(function () {
                        vm.initActive()
                    });
                },function (error) {
                    vm.isLoading = false;
                    vm.showLoad = false;
                })

            },
            onInfinite(done) {
                this.getList(done);

            },
        },
        beforeDestroy: function () {
            xqzs.voice.pause();
        },
        beforeRouteLeave (to, from, next) {
            if(this.rechargeFlag){
                this.rechargeFlag=false;
                next(false)
            }else{
                next()
            }
        },
        deactivated:function () {
            if(this.currPlayIndex!=null)this.pause(this.currPlayIndex);

        },
        activated:function () {
             this.rechargeMoney=0;
            console.log("this.isKeepAlive"+this.isKeepAlive)
            if(!this.isKeepAlive){
                this.nowSort="最新问题";
                 for(let i= 0;i<this.sortList.length;i++){
                    this.sortList[i].flag=false;
                    this.$set(this.sortList,i,this.sortList[i])
                }
                this.firstSel=false;
                this.twoSel=false;
                this.isPageEnd = false;
                this.page = 1;
                this.isShowMoreText = false;
                this.initAll();
            }else{
                let st = xqzs.localdb.get("st_"+this.$route.path);
                console.log(st);
                if(st){
                    $('.yo-scroll').scrollTop(st);
                }
            }
        }

    }


</script>

<style>
    .listenList_box{
        background: #F4F4F7 !important;
    }
     .listenList_box .yo-scroll .inner{
        top:0.48rem;
    }
    .down_list{
        position: relative;
        z-index: 10001;
    }
    .sort_rank {
        width: 50%;
        height: 100%;
        float: left
    }

    .class_rank {
        width: 50%;
        height: 100%;
        float: right
    }
    .active_color{
        color:#56C4FE;
    }
    .sanjiao {
        width: 0;
        height: 0;
        display: inline-block;
        vertical-align: middle;
        border-top: 0.1rem solid #999;
        border-right: 0.1rem solid transparent;
        border-left: 0.1rem solid transparent;
        margin-left: 0.12rem;
    }
    .xsanjiao{
        border-top:0;
        border-bottom: 0.1rem solid #999;
    }
    .selActive{
        border-top: 0.1rem solid #56C4FE;
        border-bottom:0;
    }

    .sort_list_item {
        width: 100%;
        font-size: 0.28rem;
        height: 0.96rem;
        line-height: 0.96rem;
        border-bottom: 1px solid #f4f4f7;
        color:RGBA(69, 75, 84, 1);
        position: relative;
    }
    .sort_list_item img{
        width:0.32rem;
        height:0.24rem;
        position: absolute;
        right:0.4rem;
        top:50%;
        margin-top: -0.12rem;
    }
    .sort_list{
        position: absolute;
        z-index: 55;
        width: 100%;
        background: #fff;
    }
    .sort_list ul{
        padding-left: 0.4rem;
    }
    .class_select {
        width: 100%;
        height: 3.16rem;
        background: #fff;
        position: absolute;
        z-index: 55;
    }

    .class_list_item {
        float: left;
        width: 1.56rem;
        margin-left: 0.22rem;
        margin-top: 0.25rem;
        font-size: 0.26rem;
        height: 0.56rem;
        text-align: center;
        line-height: 0.60rem;
        color: rgba(69, 75, 84, 0.5);
        background: #fff;
        border-radius: 0.10rem;
        border: 0.02rem solid #eee;
    }

    .sort_list_item.selected {
        color: #56C4FE;
    }

    .class_list_item.selected {
        color: #fff !important;
        background: #56C4FE;
    }
    .downList_mask{
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        position: absolute;
        z-index: 54;
    }
    .nav_select{ background:#fff;height: 0.96rem;width: 100%; overflow: hidden;text-align: center;line-height: 0.96rem;font-size: 0.30rem;color: #999;border-bottom: 0.02rem solid #eee;}

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


    .con_swiper_c .swiper-slide {
        overflow-y: scroll
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

    nav {
        width: 100%;
        background: #FDFDFD;
        height: 0.84rem;
        line-height: 0.84rem;
        font-size: 0.28rem;
        color: rgba(36, 37, 61, 1);
        text-align: center;
        border-bottom: 0.02rem solid #E0E0E1;
        display: none;
    }

    .navSwiper .swiper-slide {
        width: 1.36rem;
    }

    .nav_active {
        color: #56C4FE;
        position: relative;
        transition: all .1s linear;
    }

    .nav_active:after {
        content: '';
        width: 0.30rem;
        height: 0.04rem;
        background: #56C4FE;
        position: absolute;
        bottom: 0.10rem;
        left: 50%;
        margin-left: -0.14rem;
    }

    .asker_listen_list_box {
        width: 100%;
        height: auto;
    }

    .asker_listen_list_box .index_content_active {
        display: block;
    }

    .asker_listen_list_box li {
        background: #fff;
        padding: 0.40rem 0.3rem 0.36rem 0rem;
        border-bottom: 0.02rem solid #eee;
        position: relative;
        padding-left: 1.3rem;
    }
    .expert_headerImg{
        width:0.8rem;
        height:0.8rem;
        border-radius: 50%;
        position: absolute;
        left:0.3rem;
    }

    .index_li_header {
        font-size: 0.24rem;
        color: #999;
        line-height: 0.34rem;

        position: relative;
    }

    .index_li_header .header_className {
        position: absolute;
        right: 0;
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
        color: RGBA(69, 75, 84, 1);
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
        width:3.48rem;
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
        color: #999;
        top: 0;
        line-height: 0.82rem;
    }

    .index_nocontent {
        background: #fff;
        margin-top: 0.02rem;
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


</style>
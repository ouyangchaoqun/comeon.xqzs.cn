<template>
    <div class="asker_listen_box">
        <div v-title>偷听</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="weui-tab__panel main">
            <!--导航栏-->
            <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite"
                      :isPageEnd="isPageEnd" :isShowMoreText="isShowMoreText" :bottomHeight="50">
                <div class="index_box">
                    <div>
                        <ul>
                            <li v-for="(item,index) in navList.list">
                                <a @click="goDetail(item.questionId)">
                                    <div class="index_li_header">
                                        <div>
                                            <template v-if="item.askerNickName!=''">
                                                {{item.askerNickName}}
                                            </template>
                                            <template v-if="item.askerNickName==''">匿名用户</template>
                                            <span>咨询了</span></div>
                                        <div class="header_className">{{item.title}}</div>
                                    </div>
                                    <div class="index_li_content">{{item.content}}</div>
                                    <div class="index_li_bottom">
                                        <img :src="item.expertFaceUrl" alt="">
                                        <!--免费听-->
                                        <span class="problem_answer_yy" v-if="item.answerType==1">
                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                <div class="audio_btn" @click.stop="play(index)">
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
                                        <div class="problem_answer_yy"
                                             @click.stop="typeDialog(item.questionId,item.answerId,index )"
                                             v-if="item.answerType==2||item.answerType==4">
                                            <div class="audio">
                                                <div class="audio_btn pay">偷听
                                                    <div class="second">{{(item.ct &&
                                                        item.ct!='00')?item.ct:item.length}}”
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--限时免费听-->
                                        <span class="problem_answer_yy" v-if="item.answerType==3">
                            <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                <div class="audio_btn" @click.stop="play(index)">
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
                                </a>
                            </li>

                        </ul>
                    </div>

            </div>
            </v-scroll>
        </div>
        <v-asker-bottom tabOnIndex="0"></v-asker-bottom>
        <v-recharge :rechargeMoney="rechargeMoney" v-show="rechargeFlag" v-on:childMessage="getFlagVal"></v-recharge>
    </div>

</template>
<script>
    import showLoad from '../include/showLoad.vue';
    import typeHeader from '../include/typeHeader.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../../js/bus.js';
    import askerBottom from "./include/bottom.vue";
    import Recharge from '../asker/my/recharge.vue' ;
    export default {
        data() {
            return {
                navLists: [
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false},
                    {title: '', list: [], page: 1, isPageEnd: false}
                ],
                typeIndex: 0,
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText: false,
                showLoad: false,
                type: 0,
                timeOut: null,
                playing: false,
                list: [],
                couponNum: 0,
                couponList: [],
                rechargeMoney: 0,
                rechargeFlag: false,
                user: {},
                navList:{}
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
            this.type = this.$route.query.classId;
            this.getClassList();
            this.getUserInfo()
            this.getCoupon();
            xqzs.voice.audio = null;
            xqzs.wx.setConfig(this);
        },
        methods: {
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
                var obj = $(".index_box li")
                xqzs.weui.active(obj);

                $(".audio ,.index_li_voice ").on("touchstart", function () {
                    event.stopPropagation();
                })
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
                _this.navLists[_this.typeIndex].list[index].ct = time;
                _this.$set(_this.navLists[_this.typeIndex].list, index, _this.navLists[_this.typeIndex].list[index])
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
            typeDialog: function (questionId, answerId, index) {
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
                                            _this.setPayed(index);
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
                                            _this.setPayed(index);
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
            pay: function (index) {
                let item = this.navLists[this.typeIndex].list[index];
                console.log(item)
                let _this = this;
                this.$http.get(web.API_PATH + "come/listen/create/order/_userId_/" + item.answerId)
                    .then(function (bt) {
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
            setPayed: function (index) {
                let item = this.navLists[this.typeIndex].list[index];
                item.answerType = 1;
                this.$set(this.navLists[this.typeIndex].list, index, item);
                this.$nextTick(function () {
                    this.initActive();
                })

            },

            play: function (index) {

                let _this = this;
                let list = _this.navLists[_this.typeIndex].list;
                let CT = list[index].ct ? list[index].ct : list[index].length;
                let T = list[index].length;
                console.log(CT, T)
                console.log(list)
                console.log(list[index].length)
                xqzs.voice.onEnded = function () {
                    list[index].paused = false;
                    list[index].playing = false;
                    _this.$set(_this.navLists[_this.typeIndex].list, index, list[index]);
                    if (_this.playing)_this.clearTimeOut();
                    _this.playing = false;
                };
                //重置其他列表内容
                for (let i = 0; i < list.length; i++) {
                    if (index != i && (list[i].playing || list[i].paused)) {
                        list[i].paused = false;
                        list[i].playing = false;
                        _this.$set(_this.navLists[_this.typeIndex].list, i, list[i]);
                    }
                }
                let item = _this.navLists[_this.typeIndex].list[index];
                if (item.paused) {  //暂停中也就是已经获取到且为当前音频
                    list[index].paused = false;
                    list[index].playing = true;
                    _this.$set(_this.navLists[_this.typeIndex].list, index, list[index])
                    xqzs.voice.play();
                    _this.timeout(true, CT, index)
                } else {
                    if (item.playing) {    //播放中去做暂停操作
                        list[index].paused = true;
                        list[index].playing = false;
                        _this.$set(_this.navLists[_this.typeIndex].list, index, list[index])
                        xqzs.voice.pause();
                        _this.clearTimeOut();
                        _this.playing = false;
                    } else {     //重新打开播放
                        let answerId = item.answerId;
                        xqzs.voice.getAnswerVoice(answerId, function (url) {
                            xqzs.voice.play(url);
                            list[index].playing = true;
                            list[index].paused = false;
                            _this.$set(_this.navLists[_this.typeIndex].list, index, list[index]);
                            _this.playing = true;
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
                console.log('getClassList')
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list').then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        let arr = [{"title": "全部", id: 0, list: []}]
                        _this.navLists = data.body.data;
                        console.log(_this.navLists)
                        _this.navLists = arr.concat(_this.navLists);
                        for (let i = 0; i < _this.navLists.length; i++) {
                            _this.navLists[i].list = [];
                            _this.navLists[i].page = 1;
                            _this.navLists[i].isPageEnd = false;
                            _this.navLists[i].isLoading = false;
                        }
                        _this.getList();
                    }
                }, function (error) {
                });
            },
            getList: function (done) {
                let vm = this;
                let item = vm.navLists[vm.typeIndex]
                console.log(vm.typeIndex)
                console.log(item)
                let url = web.API_PATH + 'come/listen/listen/list/_userId_/' + vm.type + '/' + item.page + '/' + vm.row;
                this.rankUrl = url + "?";
                if (web.guest) {
                    this.rankUrl = this.rankUrl + "guest=true"
                }
                if (item.isLoading || item.isPageEnd) {
                    return;
                }
                if (item.page == 1) {
                    vm.showLoad = true;
                }
                item.isLoading = true;
                if (vm.couponNum != 0) {
                    vm.getCoupon();
                }

                vm.$http.get(vm.rankUrl).then((response) => {
                    if (done && typeof(done) === 'function') {
                        done()
                    }
                    vm.showLoad = false;
                    item.isLoading = false;
                    if (response.data.status != 1 && item.page == 1) {
                        item.list = [];
                        item.isPageEnd = true;
                        vm.isShowMoreText = false;
                        Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                        return;
                    }

                    let arr = response.data.data;
//
                    if (arr.length < vm.row) {
                        item.isPageEnd = true;
                        vm.isShowMoreText = false
                    } else {

                        vm.isShowMoreText = true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);

                    if (item.page == 1) {
                        item.list = arr;
                    } else {
                        item.list = item.list.concat(arr);
                    }
                    if (arr.length == 0) return;

                    item.page = item.page + 1;

                    console.log(vm.navLists)

                    vm.navLists.forEach(function (item) {
                        console.log(item)
                        vm.navList = item
                    })
                    console.log(vm.navList)
                    vm.$set(vm.navLists, vm.typeIndex, item);
                    vm.$nextTick(function () {
                        vm.initActive()
                    });

                }, (response) => {
                    vm.navLists[vm.typeIndex].isLoading = false;
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
    .weui-dialog .weui-dialog__bd .colorStyle {
        color: rgba(251, 100, 10, 1);
    }

    .asker_listen_box {
        background: #fff;
    }

    .con_swiper_c .swiper-slide {
        overflow-y: scroll
    }

    .asker_listen_box .audio .audio_btn {
        width: 52%
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
        height: 2.471rem;
        line-height: 2.471rem;
        font-size: 0.8235rem;
        color: rgba(36, 37, 61, 1);
        text-align: center;
        border-bottom: 1px solid #E0E0E1;
        display: none;
    }

    .navSwiper .swiper-slide {
        width: 4rem;
    }

    .nav_active {
        color: #FE7301;
        position: relative;
        transition: all .1s linear;
    }

    .nav_active:after {
        content: '';
        width: 0.88235rem;
        height: 2px;
        background: #FE7301;
        position: absolute;
        bottom: 0.29rem;
        left: 50%;
        margin-left: -0.41176rem;
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
        padding: 0.88235rem 0.88235rem 1.176471rem 0.88235rem;
        border-bottom: 0.41176471rem solid #f5f5f5;
    }

    .index_li_header {
        font-size: 0.8235rem;
        color: rgba(36, 37, 61, 0.5);
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        line-height: 1.70588235rem;
        margin-bottom: 0.5294rem;
        position: relative;
    }

    .index_li_header .header_className {
        position: absolute;
        right: 0;
        font-size: 0.8235rem;
    }

    .index_li_header img {
        height: 1.70588235rem;
        width: 1.70588235rem;
        display: block;
        border-radius: 50%;
        margin-right: 0.470588rem;
    }

    .index_li_content {
        font-size: 0.88235rem;
        color: rgba(36, 37, 61, 1);
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        margin-bottom: 0.88235rem;
    }

    .index_li_bottom {
        position: relative;
        padding-left: 2.8rem;
    }

    .index_li_bottom img {
        position: absolute;
        left: 0;
        width: 2.4rem;
        height: 2.4rem;
        border-radius: 50%
    }

    .index_li_voice {
        position: relative;
        font-size: 0.88235rem;
        color: #fff;
        width: 10.235rem;
    }

    .index_li_voice img {
        width: 100%;
        height: auto;
        display: block;
    }

    .index_li_voice div {
        width: 5.88235rem;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -2.941175rem;
        text-align: center;

    }

    .position_change1 {
        margin-top: -0.176471rem;
    }

    .position_change2 {
        margin-top: -0.88235rem;
    }

    .index_li_count {
        position: absolute;
        right: 0;
        font-size: 0.76471rem;
        color: rgba(36, 37, 61, 0.5);
        top: 0;
        line-height: 2.4rem;
    }

    .index_nocontent {
        background: #fff;
        margin-top: 1px;
        text-align: center;
        font-size: 0.76471rem;
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
        top: 2rem;
        width: 100%;
    }

    .nocontent_html {
        margin-top: -3.8rem
    }

    .asker_listen_box .audio {
        margin-bottom: 0
    }
    .new_question{
        height: 3.235rem;
        width: 100%;
        border-bottom: 1px solid #eee;
        overflow: hidden;
    }
    .top_left{
        float: left;
        line-height: 3.235rem ;
        font-size: 1.47rem;
        margin-left: 0.78rem ;
        font-weight: bold;
    }
    .top_right{
        float: right;
        margin-right: 0.82rem;
    }

</style>
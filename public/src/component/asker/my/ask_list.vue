<template>
    <div :class="{wbg:list.length==0&&!showLoad}">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title>我问</div>
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :bottomHeight="50"
                  :isShowMoreText="isShowMoreText">

            <!--<div class="my_problem_tabs">-->
                <!--<div>-->
                    <!--<div :class="{my_problem_active:type==2}" @click="changeType(2)">一对一咨询</div>-->
                <!--</div>-->
                <!--<div>-->
                    <!--<div  :class="{my_problem_active:type==1}"  @click="changeType(1)">抢答模式</div>-->
                <!--</div>-->
            <!--</div>-->

            <div class="nothing answer" v-if="list.length==0&&!showLoad" >
                <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                <div class="nothing_bottom">
                    <p>您还没有任何提问</p>
                    到问页面可以进行提问
                    <div @click="goAsk()">去提问</div>
                </div>
            </div>
            <div class="my_problem_box">
                <!--一对一列表-->
                <div class="problem_box_active">
                    <ul>
                        <li class="my_problem_list1" v-for="item in list" >
                            <a  @click="goDetail(item.questionType,item.questionId)">
                                <div class="problem_header">
                                    <div>{{formatDateText(item.addTime)}}</div>
                                    <div class="wait_Answer last_red_color" v-if="item.questionStatus==0">还{{formatTimeLastText(item.endTime)}}</div>
                                    <!--一对一完成-->
                                    <div class="wait_Answer" v-if="item.questionType==2&&item.questionStatus==1">已完成</div>
                                    <!--抢答完成-->
                                    <div class="wait_Answer" v-if="(item.questionType==1&&item.questionStatus==1)||(item.questionType==1&&item.questionStatus==2&&item.answerCount>0)">已完成</div>
                                    <!--一对一超时-->
                                    <div class="wait_Answer" v-if="item.questionType==2&&item.questionStatus==2">未解答</div>
                                    <!--抢答超时-->
                                    <div class="wait_Answer " v-if="item.questionType==1&&item.questionStatus==2&&item.answerCount==0">未解答</div>
                                </div>
                                <div class="my_problem_content">
                                    {{item.question}}
                                </div>
                                <div class="my_problem_bottom">
                                    <div v-if="item.expertFaces.length>0">
                                        <span>已回答咨询师</span>
                                        <img v-for="imgSrc in item.expertFaces" :src="imgSrc" alt="">
                                    </div>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
                <!--抢答列表-->
            </div>

        </v-scroll>
        <v-asker-bottom tabOnIndex="3"></v-asker-bottom>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    import scroll from '../../include/scroll.vue';
    import Bus from '../../bus.js';
    import askerBottom from "../include/bottom.vue";

    export default {
        data() {
            return {
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText: false,
                showLoad: false,
                list: [],
                type: 0,
                timeInterval:null
            }
        },
        props:{
            user:{
                type:Object
            }
        },

        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
            "v-asker-bottom": askerBottom
        },
        mounted: function () {
            if(!xqzs.user.isUserLogin()){
                return ;
            }
            console.log(this.user)
            this.getList();
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
        },
        methods: {
            timeIntervalFun:function () {
                let _this=this;
                if(_this.timeInterval!=null){
                    clearInterval(_this.timeInterval);
                }
                _this.timeInterval=   setInterval(function () {
                    for(let i =0;i<_this.list.length;i++){
                        _this.list[i].endTime= _this.list[i].endTime + 1;
                        _this.list[i].endTime= _this.list[i].endTime - 1;
                        _this.$set(_this.list,i, _this.list[i]);
                    }
                },1000)
            },
            formatTimeLastText:function (time) {
                return xqzs.dateTime.getTimeFormatLastText(time)
            },
            goAsk:function () {
                this.$router.push("/asker/ask");
            },
            initActive:function () {
                var obj =  $(".my_problem_list1")
                xqzs.weui.active(obj);
            },
            formatPrice:function (price) {
                return xqzs.string.formatPrice(price)
            },
            formatDateText: function (time) {
                return xqzs.dateTime.getTimeFormatText(time)
            },
            goDetail: function (questionType,id) {
                if(questionType==1){
                    //抢答详情
                    console.log('抢答详情')
                    this.$router.push("/asker/my/ask/race/detail?id=" + id);
                }else{
                    //一对一详情
                    console.log('一对一详情')
                    this.$router.push("/asker/my/ask/detail?id=" + id);
                }

            },
//            changeType: function (v) {
//                this.type = v;
//                this.page = 1;
//                this.list = [];
//                this.isPageEnd = false;
//                this.isShowMoreText = false;
//                this.getList();
//            },
            getList: function (done) {

                let vm = this;
                let url =   'come/user/query/question/' + this.type + '/page/_userId_/' + vm.page + '/' + vm.row;
                this.rankUrl = url + "?";
                if (web.guest) {
                    this.rankUrl = this.rankUrl + "guest=true"
                }
                if (vm.isLoading || vm.isPageEnd) {
                    return;
                }
                if (vm.page == 1) {
                    vm.showLoad = true;
                }
                vm.isLoading = true;
                xqzs.api.get(vm,vm.rankUrl,function (response) {
                    console.log(response)
                    if(done&&typeof(done)==='function'){
                        done()
                    }
                    vm.showLoad = false;
                    vm.isLoading = false;
//                    console.log(response)

                    if (response.data.status != 1 && vm.page == 1) {
                        vm.list = [];
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;
                        Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                        return;
                    }
                    let arr = response.data.data;
//
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    }else{
                        vm.isShowMoreText = true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);


                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    console.log(vm.list)
                    if (arr.length == 0) return;
                    vm.page = vm.page + 1;
                    vm.timeIntervalFun();
                },function (error) {
                    vm.isLoading = false;
                    vm.showLoad = false;
                })


            },
            onInfinite(done) {
                this.getList(done);

            },
        },
        updated:function () {
            this.initActive();
        }


    }
</script>
<style>
    .my_problem_tabs {
        color: rgba(36,37,61,1);
        font-size: 0.30rem;
        text-align: center;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        background: #fff;
        border-bottom: 0.02rem solid #eee;
        height: 0.80rem;
        line-height: 0.80rem;
    }

    .my_problem_tabs > div {
        flex: 1;
        position: relative;
    }

    .my_problem_tabs > div:nth-of-type(1)::after {
        content: '';
        height: 0.38rem;
        width: 0.02rem;
        background: #E0E0E0;
        position: absolute;
        right: 0;
        top: 50%;
        margin-top: -0.19rem;
    }
    .my_problem_tabs > div div{
        width:50%;
        margin:0 auto;
    }
    .my_problem_active {
        border-bottom: 0.04rem solid rgba(253,114,6,1);
    }

    .my_problem_box {
        position: relative;
        width: 100%;
        height: auto;
    }


    .my_problem_list1 {
        padding: 0.24rem 0.30rem 0.31rem 0.30rem;
        background: #fff;
        margin-bottom: 0.20rem;
    }

    .problem_header {
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        position: relative;
        color: rgba(36,37,61,0.5);
        font-size: 0.24rem;
        margin-bottom: 0.20rem;
        line-height:  0.58rem ;
    }
    .problem_header > div:nth-of-type(2) {
        position: absolute;
        right: 0;
    }

    .problem_header img {
        height: 0.58rem;
        width: 0.58rem;
        display: block;
        border-radius: 50%;
        margin-right: 0.20rem;
    }

    .my_problem_content {
        color: rgba(36,37,61,1);
        font-size: 0.30rem;
        line-height: 0.48rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        margin-bottom: 0.30rem;
    }

    .my_problem_bottom {
        color: rgba(36,37,61,0.5);
        font-size: 0.24rem;
        line-height: 1;
        overflow: hidden;
    }
    .my_problem_bottom span{
        margin-right:0.37rem;
    }
    .my_problem_bottom img{
        width: 0.58rem;
        height:0.58rem;
        border-radius: 50%;
        display: inline-block;
        vertical-align: middle;
        border: 0.06rem solid #fff;
        margin-left: -0.23rem;
    }
</style>
<template >
    <div style="height: 100%" class="answer_ask_list_box"  :class="{wbg:list.length==0&&!showLoad}">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div id="tip" style="display: none">
            <div class="dialog_select_type dialog_select_Height">
                <div class="select_title">解答须知</div>
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
                    <div>
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
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :bottomHeight="48"
                  :isShowMoreText="isShowMoreText">
            <div v-title class='hide_title'>待答</div>
            <div class="nothing comment" v-if="list.length==0&&!showLoad">
                <div>
                    <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                    <div class="nothing_bottom">
                        <p>暂无待答问题</p>
                        <div @click="tip()">解答须知</div>
                    </div>
                </div>
            </div>

            <div class="list">
                <div class="item" v-for="item in list" @click="answer(item.id)">
                    <div class="img">
                        <img v-if="item.isAnonymous==0" :src="item.faceUrl">
                        <img v-if="item.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                    </div>
                    <div class="info">
                        <div class="names">
                            <span v-if="item.isAnonymous==0">{{item.nickName}}</span>
                            <span v-if="item.isAnonymous==1">匿名用户</span>
                            <div class="price">酬金￥{{item.price}}</div>
                        </div>
                        <div class="content">{{item.content}}

                        </div>
                        <div class="last_time"> 在哪方面：{{item.questionClassName}}</div>
                        <div class="last_time last_red_color">还{{formatDateText(item.endTime)}}</div>

                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </v-scroll>
        <v-answer-bottom tabOnIndex="1" ></v-answer-bottom>

    </div>
</template>

<script type="es6">

    import answerBottom from "./include/bottom.vue";
    import showLoad from '../include/showLoad.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../bus';
    export default {
        data() {
            return {
                showLoad:false,
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText:false,
                list:[],
                timeInterval:null
            }
        },
        props:
            ['isKeepAlive']
        ,
        mounted: function () {
            this.initAll()
        },
        activated: function () {
            if(!this.isKeepAlive){
                this.initAll();
            }else{
                let st = xqzs.localdb.get("st_"+this.$route.path);
                console.log(st);
                if(st){
                    $('.yo-scroll').scrollTop(st);
                }
                this.timeIntervalFun()
            }
        },


        deactivated: function () {
            if(this.timeInterval!=null){
                clearInterval(this.timeInterval);
            }
        },
        methods: {
            closeDialog:function () {
                xqzs.weui.dialogClose()
            },
            tip: function () {
                let _this= this;
                xqzs.weui.dialogCustom($("#tip").html());
                $(".know").click(function () {
                    _this.closeDialog()
                })
            },
            initAll:function () {
                if (!xqzs.user.isUserLogin()) {
                    return;
                }
                this.page=1;
                this.isPageEnd=false;
                this.isShowMoreText=false;
                this.list=[];
                this.getList();
                xqzs.wx.setConfig(this, function () {
                    var config = {
                        imgUrl: 'http://oss.xqzs.cn/resources/psy/join_share_img3.jpg',
                        title:  '‘好一点’心理咨询平台诚邀您入驻' ,
                        desc: '‘好一点’心理咨询师的兼职平台，诚邀您入驻，一次回答，收益不断！',
                        link: "http://wx.xqzs.cn/comeon/guest#/join",
                    };
                    weshare.init(wx, config)
                });
            },
            timeIntervalFun:function () {
                let _this=this;
                if(_this.timeInterval!=null){
                    clearInterval(_this.timeInterval);
                }
                _this.timeInterval=   setInterval(function () {
                    for(let i =0;i<_this.list.length;i++){
                        _this.list[i].endTime= parseInt(_this.list[i].endTime ) + 1;
                        _this.list[i].endTime= parseInt(_this.list[i].endTime ) - 1;
                        _this.$set(_this.list,i, _this.list[i]);
                    }
                },1000)
            },
            getList: function (done) {
                let vm= this;
                let expertId = cookie.get('expertId')
                let url =web.API_PATH + 'come/expert/query/to/be/answered/page/'+expertId+'/_userId_/'+vm.page+'/'+vm.row;
                this.rankUrl = url + "?";
                if (web.guest) {
                    this.rankUrl = this.rankUrl + "guest=true"
                }
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
                vm.$http.get(vm.rankUrl).then((response) => {
                    if(done&&typeof(done)==='function'){
                        done()
                    }
                    vm.showLoad = false;
                    vm.isLoading = false;
//                    console.log(response)

                    if(response.data.status!=1&&vm.page==1){
                        vm.list = [];
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;

                        return;
                    }
                    console.log(response.data.data.rows)
                    let arr = response.data.data.rows;
//
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    }else{
                        vm.isShowMoreText=true;
                    }




                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    if (arr.length == 0) return;
                    vm.page = vm.page + 1;




                    vm.timeIntervalFun();



                }, (response) => {
                    vm.isLoading = false;
                    vm.showLoad = false;
                });

            },
            onInfinite(done) {
                this.getList(done);

            },
            answer:function (askId) {
                this.$router.push("/answer/answer?askId="+askId)
            },
            formatDateText:function (time) {
                return xqzs.dateTime.getTimeFormatLastText(time)
            }
        },
        components: {
            "v-answer-bottom": answerBottom,
            'v-showLoad': showLoad,
            'v-scroll': scroll,
        }


    }
</script>
<style>
    .answer_ask_list_box .list .item{ background: #fff; padding:0.40rem 0.30rem 0.16rem 0.30rem; margin-bottom:0.14rem; }
    .answer_ask_list_box .list .item:active{background: #eee}
    .answer_ask_list_box  .list .item .img{ width: 0.58rem; height: 0.58rem; float:left; }
    .answer_ask_list_box  .list .item .img img{ width: 100%; height: 100%; border-radius: 50%}
    .answer_ask_list_box  .list .info .names{ font-size: 0.24rem;color:#999;line-height:0.58rem;margin-bottom: 0.08rem; }
    .answer_ask_list_box .list .info .names span{ color:#999; font-size: 0.26rem; margin-left: 0.20rem}
    .answer_ask_list_box  .list .info .content{ font-size: 0.30rem; color:#454B54;margin-bottom: 0.28rem ;width:98%;}
    .answer_ask_list_box  .list .info .last_time{line-height:1;font-size: 0.26rem;color:#999;margin-bottom: 0.18rem;}
    .answer_ask_list_box  .list .info .price{ float:right;font-size: 0.24rem;color:#56C4FE; text-align: right; width: 2.38rem;}

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
</style>

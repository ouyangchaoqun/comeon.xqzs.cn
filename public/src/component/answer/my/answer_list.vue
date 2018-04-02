<template id="my_problem_index">
    <div style="background: #fff">
        <div v-title>我的回答</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :bottomHeight="0"
                  :isShowMoreText="isShowMoreText">
            <div class="my_problem_tabs">
                <div class="my_problem_tab">
                    <div class=" my_problem_active">抢答</div>
                </div>
                <div class="my_problem_tab">
                   <div > 一对一解答</div>
                </div>
            </div>
            <div class="nothing comment" v-if="list.length==0">
                <div>
                    <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                    <div class="nothing_bottom">
                        <p v-if="type==1">还没有抢答</p>
                        <p v-if="type==2">还未人找你解答</p>
                        <div @click="goRace()" v-if="type==1">去抢答</div>
                    </div>
                </div>
            </div>
            <div class="my_problem_box">
            <!--抢答-->
            <div class="problem_box_active">
                <ul class="problem_item">
                    <li v-for="(item,index) in list" v-if="type==1" :class="{li_border:list.length>1}">
                        <div class="problem_item_top">
                            <img v-if="item.isAnonymous==0" :src="item.askUserFaceUrl" alt="">
                            <img v-if="item.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                            <span v-if="item.isAnonymous==0">{{item.askUserNickName}}</span>
                            <span v-if="item.isAnonymous==1">匿名用户</span>
                            <div class="problem_item_right" v-if="item.wageType!=0">
                                <template v-if="item.wageType==4"><i> 全部赏金 ￥{{formatPrice(item.wage)}}</i></template>
                                <template v-if="item.wageType==5">平分<i>￥{{formatPrice(item.wage)}}</i></template>
                            </div>
                        </div>
                        <div class="problem_item_del">{{item.questionContent}}</div>
                        <div class="problem_answer_info">
                            <img :src="item.expertUserFaceUrl" alt="">
                            <!--回答，专家语音-->
                            <div class="problem_answer_yy" v-if="true">
                                <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                    <div class="audio_btn" @click.stop="play(index)">
                                        <template v-if="!item.playing&&!item.paused">点击播放</template>
                                        <template v-if="item.playing">正在播放..</template>
                                        <template v-if="item.paused">播放暂停</template>
                                        <div class="second">{{item.voiceLength}}”</div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <span class="problem_bestAns" v-if="item.isBestAnswer==1&&item.bestAnswerId">最佳回答</span>
                                <span class="problem_bestAns" v-if="item.isBestAnswer!=1&&item.bestAnswerId">非最佳回答</span>
                            </div>
                        </div>
                        <!--回答后底部显示详情-->
                        <div class="problem_answer_bottom">
                            <div class="problem_answer_time"><span>{{formatDateText(item.answerTime)}}</span><span>听过 {{item.listenTimes}}</span></div>
                            <div class="problem_answer_zan">
                                <div  v-if="item.inCome!=0"><span>偷听分成 </span><span>￥{{formatPrice(item.inCome)}}</span></div>
                                <div class="good_care"><span>{{item.likeTimes}}</span></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <!--一对一解答-->
            <div>
                <ul class="problem_item">
                    <li v-if="type==2" v-for="(item,index) in list" :class="{li_border:list.length>1}">
                        <div class="problem_item_top">
                            <img v-if="item.isAnonymous==0" :src="item.askUserFaceUrl" alt="">
                            <img v-if="item.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                            <span v-if="item.isAnonymous==0">{{item.askUserNickName}}</span>
                            <span v-if="item.isAnonymous==1">匿名用户</span>
                            <div class="problem_item_right">酬金<i>￥{{formatPrice(item.wage)}} </i></div>
                        </div>
                        <div class="problem_item_del">{{item.questionContent}}</div>
                        <div class="problem_answer_info">
                            <img :src="item.expertUserFaceUrl" alt="">
                            <!--回答，专家语音-->
                            <div class="problem_answer_yy" v-if="true">
                                <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                    <div class="audio_btn" @click.stop="play(index)">
                                        <template v-if="!item.playing&&!item.paused">点击播放</template>
                                        <template v-if="item.playing">正在播放..</template>
                                        <template v-if="item.paused">播放暂停</template>
                                        <div class="second">{{item.voiceLength}}”</div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                        </div>
                        <!--回答后底部显示详情-->
                        <div class="problem_answer_bottom">
                            <div class="problem_answer_time"><span>{{formatDateText(item.answerTime)}}</span><span>听过 {{item.listenTimes}}</span></div>
                            <div class="problem_answer_zan">
                                <div><span>偷听分成 </span><span style="color:#FF9900">￥{{formatPrice(item.inCome)}}</span></div>
                                <div class="good_care"><span>{{item.likeTimes}}</span></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        </v-scroll>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    import scroll from '../../include/scroll.vue';
    import Bus from '../../bus';
    export default {
        data() {
            return {
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText: false,
                showLoad: false,
                list: [],
                type: 1
            }
        },
        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll
        },
        activated: function () {
            this.page=1;
            this.isPageEnd=false;
            this.isShowMoreText=false;
            this.list=[];
            let _this = this;
            $('.my_problem_tabs .my_problem_tab div').click(function () {
                $('.my_problem_tabs .my_problem_tab div').removeClass('my_problem_active')
                $('.my_problem_box>div').removeClass('problem_box_active')
                $(this).addClass('my_problem_active')
                $('.my_problem_box>div').eq($(this).parent().index()).addClass('problem_box_active')

                if ($(this).parent().index() == 0) {
                    _this.changeType(1);
                } else {
                    _this.changeType(2);
                }
            })
            this.getList();
            xqzs.wx.setConfig(this);
        },
        methods: {

            play:function (index) {

                let _this=this;
                let list = _this.list;
                xqzs.voice.onEnded=function () {
                    list[index].paused=false;
                    list[index].playing=false;
                    _this.$set(_this.list,index,list[index])
                };
                //重置其他列表内容
                for(let i = 0;i<list.length;i++){
                    if(index!=i&&(list[i].playing||list[i].paused)){
                        list[i].paused=false;
                        list[i].playing=false;
                        _this.$set(_this.list,i,list[i]);
                    }
                }
                let item= this.list[index];
                if(item.paused){  //暂停中也就是已经获取到且为当前音频
                    list[index].paused=false;
                    list[index].playing=true;
                    _this.$set(_this.list,index,list[index])
                    xqzs.voice.play();
                }else{
                    if(item.playing){    //播放中去做暂停操作
                        list[index].paused=true;
                        list[index].playing=false;
                        _this.$set(_this.list,index,list[index])
                        xqzs.voice.pause();
                    }else{     //重新打开播放
                        let answerId= item.answerId;
                        xqzs.voice.getAnswerVoice(answerId,function (url) {
                            xqzs.voice.play(url);
                            list[index].playing=true;
                            list[index].paused=false;
                            _this.$set(_this.list,index,list[index])
                        })
                    }

                }

            },

            getList: function (done) {
                let expertId = cookie.get('expertId');
                let vm = this;
                let url = web.API_PATH + 'come/expert/query/my/answer/page/' + this.type + '/'+expertId+'/_userId_/' + vm.page + '/' + vm.row;
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
                vm.$http.get(vm.rankUrl).then((response) => {
                    if(done&&typeof(done)==='function'){
                        done()
                    }
                    vm.showLoad = false;
                    vm.isLoading = false;
//                    console.log(response)

                    if (response.data.status != 1 && vm.page == 1) {
                        vm.list = [];
                        return;
                    }
                    let arr = response.data.data.rows;
//
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    }else{
                        vm.isShowMoreText =true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);


                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    if (arr.length == 0) return;
                    vm.page = vm.page + 1;

                }, (response) => {
                    vm.isLoading = false;
                    vm.showLoad = false;
                });
            },
            onInfinite(done) {
                this.getList(done);
            },
            changeType: function (v) {
                this.type = v;
                this.page = 1;
                this.list = [];
                this.isPageEnd = false;
                this.isShowMoreText = true;
                xqzs.voice.pause();
                this.getList();
            },
            formatDateText: function (time) {
                return xqzs.dateTime.getTimeFormatText(time)
            },
            formatPrice:function (price) {
                return xqzs.string.formatPrice(price)
            },
            goRace:function () {
                this.$router.push("/answer/race/list")
            }

        },
        beforeDestroy:function () {
            xqzs.voice.pause();
        }


    }
</script>
<style>
    .my_problem_tabs{
        color: rgba(36,37,61,1);
        font-size: 0.30rem;
        text-align: center;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        background: #fff;
        border-bottom: 0.02rem solid #eee;
        height:0.80rem;
        line-height: 0.80rem;
    }
    .my_problem_tabs>div{
        flex: 1;
        position: relative;
    }
    .my_problem_tab>div{
        width:50%;
        margin:0 auto;
    }
    .my_problem_tabs>div:nth-of-type(1)::after{
        content: '';
        height:0.38rem;
        width:0.02rem;
        background: #eee;
        position: absolute;
        right:0;
        top:50%;
        margin-top:-0.19rem;
    }
    .my_problem_active{
        border-bottom: 0.04rem solid rgba(86,196,245,1);
    }
    .my_problem_box{
        position: relative;
        width: 100%;
        height:auto;
    }
    .my_problem_box>div{
        display: none;
        width:100%;
    }
    .my_problem_box .problem_box_active{
        display: block;
    }
    .problem_item li{
        padding:0.30rem 0.30rem 0.20rem 0.30rem;
        background: #fff;

    }
    .problem_item .li_border{
        border-bottom: 0.20rem solid #f4f4f7;
    }
    .problem_item_top{display: -webkit-box;display: -webkit-flex;display: flex;font-size: 0.24rem;line-height: 0.68rem;color:#999;position: relative}
    .problem_item_top span{color:rgba(36,37,61,0.7);margin-right: 0.20rem;}
    .problem_item_top i{font-style: normal;color:rgba(254,115,1,1);margin-left: 0.10rem}
    .problem_item_top img{
        width:0.68rem;
        height:0.68rem;
        display: block;
        border-radius: 50%;
        margin-right: 0.20rem;
    }
    .problem_item_right{position: absolute;right:0}
    .problem_item_del{color:rgba(36,37,61,1);font-size: 0.30rem;line-height: 0.40rem;padding-left:0.88rem;margin-bottom: 0.40rem}
    .problem_answer_info{
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        line-height: 0.96rem;
        height:0.96rem;
        font-size: 0.28rem;
        color: rgba(36,37,61,0.5);
        margin-bottom: 0.30rem;
    }
    .problem_answer_info img{
        height:0.88rem;
        display: block;
        width:0.88rem;
        border-radius: 50%;
        margin-right: 0.20rem;
    }
    .problem_answer_yy{
        position: relative;
        margin-top: 0.06rem;
    }
    .problem_answer_yy .problem_answer_ly{
        display: block;
        width:3.48rem;
        border-radius: 0;
    }
    .problem_answer_play{
        position: absolute;
        color: #fff;
        font-size: 0.30rem;
        width: 2.00rem;
        left: 50%;
        margin-left: -1.00rem;
        text-align: center;
        top:50%;
        line-height: 1;
        margin-top:-0.22rem;
    }
    .answer_play_time{
        position: absolute;
        line-height: 1;
        height:0.40rem;
        top:50%;
        right:-0.68rem;
        margin-top:-0.20rem;
    }
    .problem_bestAns{
        color: rgba(36,37,61,1);
        font-size: 0.24rem;
        position: absolute;
        right:-1.90rem;
        top:0;
        line-height: 1;
        top:50%;
        margin-top:-0.20rem;

    }
    .problem_answer_info .problem_answer_sond{
        height:0.32rem;
        display: block;
        position: absolute;
        width:auto;
        top:50%;
        margin-top:-0.20rem;
        left:0.30rem;
    }
    .problem_answer_bottom{
        padding:0;
        margin:0;
    }
    .problem_answer_time span:nth-of-type(1){margin-right:0.40rem}
</style>
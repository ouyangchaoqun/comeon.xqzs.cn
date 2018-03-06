<template>
    <div style="height: 100%" class="answer_index">
        <div v-title>找专家</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :isShowMoreText="isShowMoreText" :bottomHeight="50">
            <v-typeHeader :urlType="2"></v-typeHeader>
            <div class="title_top">
                <div class="hot_head">
                    热门推荐
                       <div class="hot_hidden" @click="getMore()">
                          更多
                       </div>
                  </div>
                <div class="hot_content">

                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide" v-for="item in manList">
                                <ul>
                                    <li  class="hot_item" @click="goDetail(item.expertId)">
                                        <div class="hot_top">
                                            <div class="hot_face"><img :src="item.faceUrl" alt=""></div>
                                            <div class="hot_name">{{item.nickName}}</div>
                                        </div>
                                        <div class="hot_bottom">
                                            <div class="hot_text">
                                                <p style="color: #FE7A03">￥{{item.price}}</p>
                                                <p>{{item.answerCount}}个回答</p>
                                            </div>
                                            <div class="hot_intro">个人简介</div>
                                        </div>
                                        <div class="hot_evaluate">"{{item.lastEvaluate}}"</div>
                                        <div class="hot_num">{{item.evaluateCount}}人评价>></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="answer_list">

                <div class="title_top">
                    <div class="new_head">
                        最新入驻
                        <div class="hot_hidden" @click="getNewMore()">
                            更多
                        </div>
                    </div>
                </div>


                <div class="item" v-for="(item,index) in list">
                    <div @click="goDetail(item.expertId)">
                        <div class="itemDetail">
                            <div class="img"><img :src="item.faceUrl"></div>
                            <div class="itemDetail_right">
                                <div class="itemHeader">
                                    <div style="font-weight: bold">{{item.nickName}}<span>{{item.city}}</span><span style="float: right;color: #2EB1FF;font-size: 0.70rem">找TA咨询</span></div>
                                    <div class="header_addRightStyle" v-if="false ">
                                        <div class="headerImg" @click.stop="play(index)">
                                            <div :class="{addPlaying:item.playing,addPaused:item.paused}"></div>
                                        </div>
                                        {{item.length}}''
                                    </div>
                                </div>
                                <div class="title">{{item.sign}}</div>
                                <div class="line1"></div>
                                <div class="itemDetail_class_s">
                                    <span v-for="(good,goodIndex) in item.goodAt" class="tab_i_i">{{good.title}}</span>
                                </div>
                                <div class="class_s other">
                                    <span class="price"><b>问价</b>  <a>￥{{item.price}}</a></span>
                                    <div class="class_right">
                                        <span v-if="item.answerCount!=null">{{item.answerCount}} 个回答</span>
                                        <span v-if="item.listenCount!=null">{{item.listenCount}} 次被偷听</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" v-for="(item,index) in list" v-if="false">
                    <div @click="goDetail(item.expertId)">
                        <div class="info">
                            <div class="problem_answer_yy">
                                <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                    <div class="audio_btn" @click.stop="play(index)">
                                        <template v-if="!item.playing&&!item.paused">点击播放</template>
                                        <template v-if="item.playing">正在播放..</template>
                                        <template v-if="item.paused">播放暂停</template>
                                        <div class="second">{{item.length}}”</div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="noContent_icon" v-if="noContent">
                    <img src="../../images/asker/newNoContent.png" alt="">
                    <div>暂无该方面问题</div>
                </div>
            </div>
        </v-scroll>

        <v-asker-bottom tabOnIndex="1"></v-asker-bottom>
    </div>
</template>

<script type="">
    import showLoad from '../include/showLoad.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../../js/bus.js';
    import askerBottom from "./include/bottom.vue";
    import typeHeader from '../include/typeHeader.vue';

    export default {
        data() {
            return {
                classList: [],
                list: [],
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText: false,
                showLoad: false,
                classId: 0,
                noContent: false,
                urlType: 2,
                manList:[1,2,3,4,5],
                psychtestSwiper:null,

            }
        },

        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
            "v-asker-bottom": askerBottom,
            'v-typeHeader':typeHeader
        },
        methods: {
            initActive: function () {
                var obj = $(".answer_list .item")
                xqzs.weui.active(obj);

                $(".audio ").on("touchstart", function () {
                    event.stopPropagation();
                })
            },

            play: function (index) {

                let _this = this;
                let list = _this.list;
                xqzs.voice.onEnded = function () {
                    list[index].paused = false;
                    list[index].playing = false;
                    _this.$set(_this.list, index, list[index])
                };
                //重置其他列表内容
                for (let i = 0; i < list.length; i++) {
                    if (index != i && (list[i].playing || list[i].paused)) {
                        list[i].paused = false;
                        list[i].playing = false;
                        _this.$set(_this.list, i, list[i]);
                    }
                }
                let item = list[index];
                if (item.paused) {  //暂停中也就是已经获取到且为当前音频
                    console.log(1)
                    item.paused = false;
                    item.playing = true;
                    _this.$set(_this.list, index, item)
                    xqzs.voice.play();
                } else {
                    if (item.playing) {    //播放中去做暂停操作
                        console.log(2)
                        item.paused = true;
                        item.playing = false;
                        _this.$set(_this.list, index, item)
                        xqzs.voice.pause();
                    } else {     //重新打开播放
                        xqzs.voice.getExpertVoice(item.expertId, function (url) {
                            console.log(3)

                            if (url != null && url != undefined && url != '') {
                                xqzs.voice.play(url);
                                item.playing = true;
                                item.paused = false;
                                _this.$set(_this.list, index, item)
                            }

                        })
                    }

                }

            },


            goDetail: function (extId) {
                this.$router.push('/asker/expert/detail/?id=' + extId)
            },
            goClass: function (classId) {
//                this.$router.push('./list?id='+item.id+"&name="+item.title)
                let _this = this;
                _this.classId = classId;
                _this.page = 1;
                _this.isPageEnd = false;
                _this.getList()
            },
            getMore:function () {
                this.$router.push('expert/list?classId=0')
            },
            getNewMore:function () {
                this.$router.push('expert/list?classId=0&orderType=2')
            },
            getHotList:function () {
              let _this=this;
                _this.$http.get(web.API_PATH+ "come/expert/get/by/class/0/1/5?complexOrNew=1").then(function (data) {
                    if(data.body.status == 1){
                        var List=data.body.data;
                        console.log(List)
                        for(var i =0;i<List.length;i++){
                                console.log(i)
                            if( List[i].lastEvaluate.length>7){
                                List[i].lastEvaluate=List[i].lastEvaluate.substring(0,7)+"..."
                            }
                        }
                        _this.manList=List;
                        console.log(_this.manList)
                    }
                })
            },
            getClassList: function () {
                let _this = this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list').then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.classList = data.body.data
                        _this.classList.splice(0, 0, {id: 0, title: '全部', code: 'qb'})
                    }
                }, function (error) {
                });
            },
            getList: function (done) {

                let vm = this;
                let url = web.API_PATH + "come/expert/get/by/class/" + vm.classId + "/" + vm.page + "/" + vm.row + "";
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
                    if (done && typeof(done) === 'function') {
                        done()
                    }
                    vm.showLoad = false;
                    vm.isLoading = false;
//                    console.log(response)

                    if (response.data.status == 9000003) {
                        vm.list = [];
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;
                        Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                        return;
                    }
                    console.log(response)
                    let arr = response.data.data;

//
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    } else {
                        vm.isShowMoreText = true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);


                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    if (vm.list.length == 0 && vm.page == 1) {
                        vm.noContent = true
                    } else {
                        vm.noContent = false
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

        },
        mounted: function () {

            $(".weui-tab__panel").height($(window).height() - 50)
            this.getClassList();
            this.getList(0);
            this.getHotList();
            this.$nextTick(function () {
                var mySwiper = new Swiper('.swiper-container',{
                    slidesPerView :2.2,
                    slidesPerGroup : 1,
                    speed:500,
                })
            })

            xqzs.wx.setConfig(this, function () {
                var config = {
                    imgUrl:"http://oss.xqzs.cn/resources/psy/logo.jpg",
                    title:  "‘好一点’专业咨询师为你答疑解惑" ,
                    desc: '‘好一点’你的实用人生导师，专业咨询师60秒语音为你排忧解难',
                    link:  xqzs.wx.getPubUrl("asker/expert") ,
                };
                weshare.init(wx, config)
            });

        },
        beforeDestroy: function () {
            xqzs.voice.pause();
        },
        updated: function () {
            this.initActive()
            let h = $(window).height() - $('.class_list').outerHeight(true) - $('.asker_bottom_box').outerHeight(true)
            $('.noContent_icon').height(h);
            $('.class_item').click(function () {
                $('.class_item div').removeClass('clickImg');
                $(this).find('div').addClass('clickImg');
            })
        }

    }
</script>
<style>
    .class_right {
        margin-top: 0.82rem;
        right: 0;
        top: 0;
        width: 70%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: right
    }
    .class_right span:nth-of-type(1) {
        margin-right: 0.294rem;
    }
    .header_addRightStyle {
        position: absolute;
        right: 0;
        top: -1px;
        display: flex;
        color: rgba(36, 37, 61, 0.5);
        font-size: 0.70588rem;
        font-weight: normal
    }
   .answer_index .answer_list .headerImg {
        width: 1.471rem;
        height: 1.471rem;
        border-radius: 50%;
        border: 1px solid rgba(253, 87, 57, 1);
        position: relative;
        margin-right: 0.35rem;
    }
    .answer_index .answer_list .headerImg div {
        background: url("../../images/playing3.png") no-repeat;
        content: '';
        width: 0.88235rem;
        height: 0.76471rem;
        position: absolute;
        left: 5px;
        top: 6px;
        background-size: 100%;
    }
    .answer_index .answer_list .headerImg .addPlaying {
        animation: addPlaying 1.5s infinite;
        -webkit-animation: addPlaying 1.5s infinite;
    }
    .answer_index .answer_list .headerImg img {
        width: 0.88235rem;
        height: 0.76471rem;
        display: inline-block;
        margin-left: 0.3rem;
    }
    .answer_index .answer_list .class_s .price {
        color: #FE7301;
    }
    .answer_index .answer_list .class_s .price b {
        color: rgba(36, 37, 61, 0.5);
        font-style: normal;
        font-weight: normal;
        text-align: left
    }
    /*.answer_list .class_s .price a{*/
    /*right: 0;*/
    /*padding-left: 1rem;*/
    /*background: url(../../images/asker/asker_left_dotCoin.png)no-repeat;*/
    /*background-size: 0.85rem 0.8rem;}*/
    .itemDetail_class_s {
        font-size: 0.70588rem;
        color: rgba(36,37,61,0.5);
    }
    .answer_index .answer_list .info .other {
        margin-bottom: 0.88235rem
    }
    .answer_index .noContent_icon {
        background: #fff;
        color: rgba(36, 37, 61, 0.5);
        font-size: 0.76471rem;
        text-align: center;
    }
    .answer_index .noContent_icon div {
        margin-top: -3.8rem;
    }
    @keyframes addPlaying {
        0% {
            background: url("../../images/playing1.png") no-repeat;
            background-size: 100%;
        }
        50% {
            background: url("../../images/playing2.png") no-repeat;
            background-size: 100%;
        }
        100% {
            background: url("../../images/playing3.png") no-repeat;
            background-size: 100%;
        }
    }
    .answer_index .itemDetail_class_s .tab_i_i {
        color: #FF9966;
        background: rgba(204, 204, 204, 0.3);;
        border-radius: 0.1470588235294118rem;
        padding: 0.2rem 0.3rem;
        margin: 0 0.3rem;
    }
    .answer_index .itemDetail_class_s .tab_i_i:first-child {
        margin-left: 0;
    }
    .answer_index .nav_select {
        background: RGBA(69, 75, 84, 0.05);
        height: 2.82rem;
        width: 100%;
        overflow: hidden;
        text-align: center;
        line-height: 2.82rem;
        font-size: 0.88rem;
        color: RGBA(69, 75, 84, 0.5)
    }
    .answer_index .title_top {
        background: #fff;
        padding:1.176rem 0rem;
        padding-left: 0.88rem;
        margin-top: 0.70rem;
    }
    .answer_index .hot_head{
        height: 1.764rem;
        width: 100%;
        color: #454B54;
        font-size: 1.1rem;
        font-weight: bold;
        position: relative;
        margin-bottom: 0.6rem;

    }
    .answer_index .new_head{
        height: 1.764rem;
         color: #454B54;
        font-size: 1.1rem;
        font-weight: bold;
        position: relative;
        background: #fff;

     }
    .hot_hidden{
        position: absolute;
        right: 0.88rem;
        top:0;
        font-size: 0.70588rem;
       line-height: 1.8rem;
        color:RGBA(254, 122, 3, 1);
        background: url("../../images/arrow.png") no-repeat center right;
        background-size: 0.70588rem 0.588235rem;
        padding-right: 0.88235rem;
    }
    .answer_index  .hot_right{
        color: #FE7A03;
        font-size: 0.70rem;
        margin-right: 0.2rem;
    }
    .answer_index  .hot_head img{
        width: 0.6rem;
        height:0.6rem;
        margin-right:1rem;
    }
    .answer_index .hot_item{
        width: 8.529rem;
        border: 1px solid rgba(69, 75, 84, 0.15);
        float: left;
        margin-right: 1rem;
        border-radius: 0.32rem 0.32rem 0.32rem 0.32rem;
    }
    .answer_index .hot_top{
        background: RGBA(255, 123, 0, 0.15);
        height: 5.647rem;
    }
    .answer_index .hot_face{
        width: 2.941rem;
        height: 2.941rem;
        border-radius: 50%;
        margin: 0 auto;
        padding-top: 0.7rem;
    }
    .answer_index .hot_face img{
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
    .answer_index .hot_name{
        margin-top:0.32rem ;
        font-size: 0.82rem;
        font-weight: bold;
        text-align: center;
    }
    .answer_index ul{
        overflow: hidden;
    }
    .answer_index .hot_bottom{
        background: #fff;
        overflow: hidden;
    }
    .answer_index .hot_text{
        margin-left: 0.5rem;
        margin-top: 0.1rem;
        float: left;
        font-size: 0.70rem;
    }
    .answer_index .hot_intro{
        float: right;
        width: 3.882rem;
        height: 1.76rem;
        border-radius:0.94rem 0 0 0.94rem  ;
        font-size: 0.70rem;
        line-height: 1.76rem;
        background: RGBA(255, 123, 0, 0.15);
        color: #FE7A03;
        margin-top: 0.64rem;
        text-align: center;
    }
    .answer_index .hot_evaluate{
        margin-top:0.588rem ;
        font-size: 0.78rem;
        color: RGBA(69, 75, 84, 0.5);
        text-align: center;
    }
    .answer_index .hot_num{
        margin-top:0.588rem ;
        font-size: 0.78rem;
        color: RGBA(69, 75, 84, 0.5);
        text-align: right;
        margin-bottom: 0.4rem;
    }
    .swiper-slide{ overflow: auto}

</style>
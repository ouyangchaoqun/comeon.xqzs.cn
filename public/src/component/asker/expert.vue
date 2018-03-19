<template>
    <div style="height: 100%" class="answer_index">
        <div v-title>找专家</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :isShowMoreText="isShowMoreText" :bottomHeight="50">
            <v-typeHeader :urlType="2"></v-typeHeader>
            <div class="title_top">
                <div class="hot_head">
                    热门 • 推荐
                       <div class="hot_hidden" @click="getMore()">
                          更多
                       </div>
                        <div class="kuan"></div>
                  </div>
                <div class="hot_content">

                    <div class="swiper-container">
                        <div class="swiper-wrapper" style="margin-left: 0.15rem">
                            <div class="swiper-slide" v-for="item in manList" v-if="item.expertId!=expertId"  @click="goDetail(item.expertId)">
                                <div class="hot_item">
                                    <div class="hot_face ">
                                        <img :src="item.faceUrl" alt=""></div>
                                    <div class="hot_name">{{item.nickName}}</div>

                                    <div class="hot_evaluate">已帮助<span class="hot_text">{{item.answerCount}}</span>人
                                    </div>
                                    <div class="hot_num">已被<span class="hot_text">{{item.followCount}}</span>人关注</div>
                                    <div class="hot_bottom"> 咨询TA</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="answer_list">

                <div class="title_top">
                    <div class="new_head">
                        <div class="kuan" style="background: RGBA(86, 196, 245,1)"></div>
                        最新 • 入驻
                        <div class="hot_hidden" @click="getNewMore()">
                            更多
                        </div>
                    </div>
                </div>

                <div style="padding-left: 0.3rem;background: #fff">
                    <div class="item" v-for="(item,index) in list" v-if="item.expertId!=expertId">
                        <div @click="goDetail(item.expertId)">
                            <div class="itemDetail">
                                <div class="img"><img :src="item.faceUrl"></div>
                                <div class="itemDetail_right">
                                    <div class="itemHeader">
                                        <div >{{item.nickName}}<span>{{item.city}}</span></div>
                                        <div class="header_addRightStyle" v-if="false ">
                                            <div class="headerImg" @click.stop="play(index)">
                                                <div :class="{addPlaying:item.playing,addPaused:item.paused}"></div>
                                            </div>
                                            {{item.length}}''
                                        </div>
                                    </div>
                                    <div class="title">{{item.sign}}</div>
                                    <div class="itemDetail_class_s">
                                        <span v-for="(good,goodIndex) in item.goodAt" class="tab_i_i">{{good.title}}</span>
                                    </div>
                                    <div class="class_s other">
                                        <span class="price"><b>问价</b>  <a>￥{{item.price}}</a></span>
                                        <span class="class_right">
                                        <span v-if="item.answerCount!=null">{{item.answerCount}} 个回答</span>
                                        <span v-if="item.listenCount!=null">{{item.listenCount}} 次被偷听</span>
                                    </span>

                                    </div>
                                </div>
                                <div style="clear: both"></div>
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
                    <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                    <div>暂无该方面问题</div>
                </div>
            </div>
        </v-scroll>

        <v-asker-bottom tabOnIndex="1"></v-asker-bottom>
    </div>
</template>

<script>
    import showLoad from '../include/showLoad.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../bus.js';
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
        props :[
                'expert'
        ],
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
                this.$router.push('expert/list?classId=0&orderType=1&title=热门推荐')
            },
            getNewMore:function () {
                this.$router.push('expert/list?classId=0&orderType=2&title=最新入驻')
            },
            getHotList:function () {
              let _this=this;
                _this.$http.get(web.API_PATH+ "come/expert/get/by/class/0/1/5?complexOrNew=1").then(function (data) {
                    if(data.body.status == 1){
                        var List=data.body.data;
                        _this.manList=List;
                        this.$nextTick(function () {
                            var mySwiper = new Swiper('.swiper-container',{
                                slidesPerView :2.2,
                                slidesPerGroup : 1,
                                speed:500,
                            })
                        })
//                        console.log(_this.manList)
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
//                    console.log(response)
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
            this.expertId = cookie.get('expertId')
            $(".weui-tab__panel").height($(window).height() - 50)
            this.getClassList();
            this.getList(0);
            this.getHotList();
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

    .header_addRightStyle {
        position: absolute;
        right: 0;
        top: -0.02rem;
        display: flex;
        color: rgba(36, 37, 61, 0.5);
        font-size: 0.24rem;
        font-weight: normal
    }
    .answer_index .answer_list .headerImg {
        width: 0.50rem;
        height: 0.50rem;
        border-radius: 50%;
        border: 0.02rem solid rgba(253, 87, 57, 1);
        position: relative;
        margin-right: 0.12rem;
    }
    .answer_index .answer_list .headerImg div {
        background: url("http://oss.xqzs.cn/resources/psy/playing3.png") no-repeat;
        content: '';
        width: 0.30rem;
        height: 0.26rem;
        position: absolute;
        left: 0.10rem;
        top: 0.12rem;
        background-size: 100%;
    }
    .answer_index .answer_list .headerImg .addPlaying {
        animation: addPlaying 1.5s infinite;
        -webkit-animation: addPlaying 1.5s infinite;
    }
    .answer_index .answer_list .headerImg img {
        width: 0.30rem;
        height: 0.26rem;
        display: inline-block;
        margin-left: 0.10rem;
    }


    .answer_index .answer_list .info .other {
        margin-bottom: 0.30rem
    }
    .answer_index .noContent_icon {
        background: #fff;
        color: rgba(36, 37, 61, 0.5);
        font-size: 0.26rem;
        text-align: center;
    }
    .answer_index .noContent_icon div {
        margin-top: -1.29rem;
    }
    @keyframes addPlaying {
        0% {
            background: url("http://oss.xqzs.cn/resources/psy/playing1.png") no-repeat;
            background-size: 100%;
        }
        50% {
            background: url("http://oss.xqzs.cn/resources/psy/playing2.png") no-repeat;
            background-size: 100%;
        }
        100% {
            background: url("http://oss.xqzs.cn/resources/psy/playing3.png") no-repeat;
            background-size: 100%;
        }
    }


    .answer_index .nav_select {
        background: RGBA(69, 75, 84, 0.05);
        height: 0.96rem;
        width: 100%;
        overflow: hidden;
        text-align: center;
        line-height: 0.96rem;
        font-size: 0.30rem;
        color: RGBA(69, 75, 84, 0.5)
    }
    .answer_index .title_top {
        background: #fff;
        margin-top: 0.2rem;
        border-bottom: 0.02rem solid #eee;
    }
    .answer_index .hot_head{
        height: 1rem;
        color: #454B54;
        font-size: 0.30rem;
        position: relative;
        margin-bottom: 0.20rem;
        line-height: 1rem;
        padding: 0 0.3rem;
        border-bottom: 0.02rem solid #eee;
        font-weight: bold;
    }
    .answer_index .new_head{
        height: 1rem;
        color: #454B54;
        font-size: 0.30rem;
        font-weight: bold;
        position: relative;
        background: #fff;
        line-height: 1rem;
        padding:0 0.3rem;
    }
    .hot_hidden{
        position: absolute;
        right: 0.30rem;
        top:0;
        font-size: 0.24rem;
        color:RGBA(69, 75, 84, 0.5);
        background: url("http://oss.xqzs.cn/resources/psy/arrow.png") no-repeat center right;
        background-size: 0.24rem 0.20rem;
        padding-right: 0.30rem;
        font-weight: normal;
    }
    .answer_index  .hot_head img{
        width: 0.20rem;
        height:0.20rem;
        margin-right:0.34rem;
    }
    .answer_index .hot_item{
        width: 2.84rem;
        box-shadow: 0 0.04rem 0.3rem RGBA(0, 0, 0, 0.1);
        float: left;
        margin-right: 0.20rem;
        margin-left: 0.2rem;
        border-radius: 0.11rem 0.11rem 0.11rem 0.11rem;
        margin-top: 0.4rem;
        margin-bottom: 0.4rem;
    }

    .answer_index .hot_face{
        width: 1.12rem;
        height: 1.12rem;
        border-radius: 50%;
        margin: 0 auto;
        padding-top: 0.24rem;

    }
    .answer_index .hot_face img{
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 0.02rem solid #eee;
    }
    .answer_index .hot_name{
        margin-top:0.15rem ;
        font-size: 0.30rem;
        height: 0.30rem;
        font-weight: bold;
        text-align: center;
        line-height:1;
    }
    .answer_index ul{
        overflow: hidden;
    }
    .answer_index .hot_text{
        color: #FE7A03;font-weight:bold;
        margin: 0 0.06rem;
    }
    .answer_index .hot_bottom{
        margin: 0.2rem auto;
        border:0.02rem solid #56C4FE;
        background: #fff;
        width: 1.8rem;
        overflow: hidden;
        height: 0.5rem;
        line-height: 0.5rem;
        text-align: center;
        border-radius: 0.1rem;
        color: #56C4FE;
        font-size: 0.24rem;
    }

    .answer_index .hot_evaluate{
        margin-top:0.15rem ;
        font-size: 0.27rem;
        color: RGBA(69, 75, 84, 0.5);
        text-align: center;
        line-height: 1;
    }
    .answer_index .hot_num{
        margin-top:0.15rem ;
        font-size: 0.27rem;
        color: RGBA(69, 75, 84, 0.5);
        text-align: center;
        margin-bottom: 0.14rem;
        line-height: 1;
    }
    .swiper-slide{ overflow: auto}
    .answer_index .kuan{
        width: 0.09rem;
        height: 0.4rem;
        background: #FE7A03;
        position: absolute;
        top:50% ;
        margin-top: -0.2rem;
        left:0;

    }
</style>
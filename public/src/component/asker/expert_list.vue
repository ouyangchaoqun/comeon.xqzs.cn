<template >
    <div style="height: 100%" class="expert_list">
        <div v-title>{{titleVal}}</div>
        <v-showLoad v-if="showLoad"></v-showLoad>


        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :isShowMoreText="isShowMoreText" :bottomHeight="0">
            <v-downList :urlType="2" v-on:downMessage="getQType" v-on:classMessage="getQid"  :currtype="classId" :ordertype="exType" ></v-downList>
            <div class="answer_list">
                <div class="item" v-for="(item,index) in list"  v-if="item.expertId!=52">
                    <div @click="goDetail(item.expertId)">
                        <div class="itemDetail">
                            <div class="img"><img :src="item.faceUrl"></div>
                            <div class="itemDetail_right">
                                <div class="itemHeader">
                                    <div>{{item.nickName}}<span>{{item.city}}</span></div>
                                    <div class="header_addRightStyle" v-if="false">
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
                                    <span class="price" ><b>问价</b>  <a>￥{{item.price}}</a></span>
                                    <span  class="class_right">
                                        <span v-if="item.answerCount!=null">{{item.answerCount}} 个回答</span>
                                        <span v-if="item.listenCount!=null">{{item.listenCount}} 次被偷听</span>
                                    </span>

                                </div>
                            </div>
                            <div style="clear: both"></div>
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

     </div>
</template>

<script type="">
    import showLoad from '../include/showLoad.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../bus.js';
    import askerBottom from "./include/bottom.vue";
    import downList from "../include/downList.vue"

    export default {
        data() {
            return {
                classList:[],
                list:[],
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText:false,
                showLoad:false,
                classId:0,
                noContent:false,
                titleVal:''
            }
        },

        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
            "v-asker-bottom": askerBottom,
            'v-downList':downList,

        },
        methods: {
            getQType:function (n) {
                this.exType= n.exType;
                this.initGetList()
            },
            getQid:function (msg) {
                this.titleVal = msg.title;
                this.classId = msg.classId;
                this.exType= msg.exType;
                this.initGetList()
            },

            initGetList:function () {
                this.isPageEnd = false;
                this.page = 1;
                this.isShowMoreText = false;
                this.getList();
            },
            initActive:function () {
                var obj =  $(".answer_list .item")
                xqzs.weui.active(obj);

                $(".audio ").on("touchstart",function () {
                    event.stopPropagation();
                })
            },

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
                let item= list[index];
                if(item.paused){  //暂停中也就是已经获取到且为当前音频
                    console.log(1)
                    item.paused=false;
                    item.playing=true;
                    _this.$set(_this.list,index,item)
                    xqzs.voice.play();
                }else{
                    if(item.playing){    //播放中去做暂停操作
                        console.log(2)
                        item.paused=true;
                        item.playing=false;
                        _this.$set(_this.list,index,item)
                        xqzs.voice.pause();
                    }else{     //重新打开播放
                        xqzs.voice.getExpertVoice(item.expertId,function (url) {
                            console.log(3)

                            if(url!=null&&url!=undefined&&url!=''){
                                xqzs.voice.play(url);
                                item.playing=true;
                                item.paused=false;
                                _this.$set(_this.list,index,item)
                            }

                        })
                    }

                }

            },



            goDetail:function (extId) {
                this.$router.push('/asker/expert/detail/?id='+extId)
            },
            goClass:function (classId) {
//                this.$router.push('./list?id='+item.id+"&name="+item.title)
                let _this = this;
                _this.classId  = classId;
                _this.page = 1;
                _this.isPageEnd = false;
                _this.getList()
            },
            getClassList:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.classList= data.body.data
                        _this.classList.splice(0,0,{id:0,title:'全部',code:'qb'})
                    }
                }, function (error) {
                });
            },
            getList: function (done) {

                let vm= this;
                let url = web.API_PATH + "come/expert/get/by/class/"+vm.classId+"/"+vm.page+"/"+vm.row+"";
                this.rankUrl = url + "?complexOrNew="+vm.exType;

                if (web.guest) {
                    this.rankUrl = this.rankUrl + "&guest=true"
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

                    if(response.data.status==9000003){
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
                        vm.isShowMoreText =true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);



                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    if(vm.list.length==0&&vm.page==1){
                        vm.noContent = true
                    }else{
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
            if(this.$route.query.classId){
                this.classId = this.$route.query.classId;
            }
            this.exType =1;
            if(this.$route.query.orderType) this.exType = this.$route.query.orderType;
            this.titleVal = this.$route.query.title;
            $(".weui-tab__panel").height($(window).height()-50)
            this.getClassList();
            this.getList(0);

            xqzs.wx.setConfig(this, function () {
                var config = {
                    imgUrl:"http://oss.xqzs.cn/resources/psy/logo.jpg",
                    title:  "‘好一点’专业咨询师为你答疑解惑" ,
                    desc: '‘好一点’你的实用人生导师，专业咨询师60秒语音为你排忧解难',
                    link:  xqzs.wx.getPubUrl("asker/expert/list") ,
                };
                weshare.init(wx, config)
            });

        },
        beforeDestroy:function () {
            xqzs.voice.pause();
        },
        updated:function () {
            this.initActive()
            let h = $(window).height() - $('.class_list').outerHeight(true)-$('.asker_bottom_box').outerHeight(true)
            $('.noContent_icon').height(h);
            $('.class_item').click(function () {
                $('.class_item div').removeClass('clickImg');
                $(this).find('div').addClass('clickImg');
            })
        }

    }
</script>
<style>
    .header_addRightStyle{position: absolute;right:0;top:-0.02rem;display: flex;color:rgba(36,37,61,0.5);font-size: 0.24rem;font-weight:normal}

    .expert_list .noContent_icon{background: #fff;color:rgba(36,37,61,0.5);font-size: 0.26rem;text-align: center;}
    .expert_list  .noContent_icon div{margin-top: -1.29rem;}

    @keyframes  addPlaying {
        0%{
            background: url("http://oss.xqzs.cn/resources/psy/playing1.png") no-repeat;
            background-size:  100%;
        }
        50%{
            background: url("http://oss.xqzs.cn/resources/psy/playing2.png") no-repeat;
            background-size:  100%;
        }
        100%{
            background: url("http://oss.xqzs.cn/resources/psy/playing3.png") no-repeat;
            background-size:  100%;
        }
    }


</style>
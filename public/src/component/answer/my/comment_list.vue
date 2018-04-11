<template >
    <div style="height: 100%" class="answer_my_coment_list wbg">

        <div v-title class='hide_title'>我的评价</div>
        <div class="nothing comment" v-show="list.length==0&&!showLoad" >
            <div>
                <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                <div class="nothing_bottom">
                    <p>您还没收到任何评价</p>
                </div>
            </div>

        </div>
        <div v-show="list.length>0">
            <v-showLoad v-if="showLoad"></v-showLoad>
            <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                      :bottomHeight="0"
                      :isShowMoreText="isShowMoreText">

                <div class="top_tip">收到的评价</div>
                <div class="list">
                    <div class="item" v-for="(item,index) in list">
                        <div class="comment">
                            <div class="img">
                                <img v-if="item.isAnonymous==0" :src="item.userFaceUrl">
                                <img v-if="item.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                            </div>
                            <div class="info">
                                <div class="name">
                                    <span v-if="item.isAnonymous==0">{{item.nickName}}</span>
                                    <span v-if="item.isAnonymous==1">匿名用户</span>
                                    评价了我的回答</div>
                                <div class="star"><span class="on" v-for="i in item.point"></span><span   v-for="i in 5-item.point"></span></div>
                                <div class="content">
                                   {{item.content}}
                                </div>
                                <div class="tags">
                                    <span v-for="i in item.tag">{{i.title}}</span>

                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="reply" v-show="item.replyContent&&item.replyContent!=''">
                            <span>我的回复：</span>{{item.replyContent}}
                        </div>
                        <div class="question">
                            <div class="content">{{item.questionContent}}
                            </div>
                            <div class="answer">
                                <div class="img"><img
                                        :src="item.expertFaceUrl">
                                </div>
                                <div class="audio"  :class="{playing:item.playing,paused:item.paused}">
                                    <div class="audio_btn"   @click.stop="play(index)">
                                        <template v-if="!item.playing&&!item.paused">点击播放</template>
                                        <template v-if="item.playing">正在播放..</template>
                                        <template v-if="item.paused">播放暂停</template>
                                        <div class="second">{{(item.ct && item.ct!='00')?item.ct:item.length}}”</div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="time">{{formatTime(item.addTime)}}</div>
                        <div class="reply_btn" @click="reply(item,index)" v-if="!(item.replyContent&&item.replyContent!='')">回复</div>
                    </div>
                </div>
            </v-scroll>
        </div>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    import scroll from '../../include/scroll.vue';
    import Bus from '../../bus.js';


    export default {
        data() {
            return {
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText: false,
                showLoad: false,
                list: [],
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
            this.getList();
            xqzs.wx.setConfig(this);
        },
        methods:{



            timeout:function (play,time,index) {
                let _this=this;
                _this.timeOut = setTimeout(function () {
                    if(play==true){  //试听
                        if(time>0){
                            time = time -1 ;
                            if(time<10)time="0"+time
                            _this.timeout(play,time,index);
                        }else{
                            _this.playing=false;
                        }
                    }

                },1000);
                _this.list[index].ct =time;
                console.log(time)
                _this.$set(_this.list,index,_this.list[index])
            },

            clearTimeOut:function () {
                let _this=this;
                if(_this.timeOut!==null){
                    clearTimeout(_this.timeOut);
                }
            },
            play:function (index) {
                let _this=this;
                let list = _this.list;
                let CT= list[index].ct? list[index].ct: list[index].length;
                let T = list[index].length;
                console.log(CT)
                xqzs.voice.onEnded=function () {
                    list[index].paused=false;
                    list[index].playing=false;
                    _this.$set(_this.list,index,list[index]);
                    if(_this.playing)_this.clearTimeOut();
                    _this.playing = false;
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
                    list[index].paused=false;
                    list[index].playing=true;
                    _this.$set(_this.list,index,list[index])
                    xqzs.voice.play();
                    _this.timeout(true,CT,index)
                }else{
                    if(item.playing){    //播放中去做暂停操作
                        list[index].paused=true;
                        list[index].playing=false;
                        _this.$set(_this.list,index,list[index])
                        xqzs.voice.pause();
                        _this.clearTimeOut();
                        _this.playing = false;
                    }else{     //重新打开播放
                        let answerId= item.answerId;
                        xqzs.voice.getAnswerVoice(answerId,function (url) {
                            xqzs.voice.play(url);
                            list[index].playing=true;
                            list[index].paused=false;
                            _this.$set(_this.list,index,list[index]);
                            _this.playing=true;
                            _this.clearTimeOut();
                            _this.timeout(true,T,index)
                        })
                    }

                }

            },



            formatTime:function(time){
               return   xqzs.dateTime.formatDateTime(time)
            },
            reply:function (item,index) {
                let _this=this;
                xqzs.weui.actionSheetEdit( "回复", function (value) {
                    //回复操作

                    let data ={
                        userId:item.expertUserId,
                        expertId:item.expertId,
                        content:value,
                        id:item.id
                    }
                    _this.$http.put(web.API_PATH + "come/comment/reply", data)
                        .then(function (bt) {
                            if (bt.data && bt.data.status == 1) {

                                item.replyContent=value;
                                _this.$set(_this.list,index,item);

                            }
                        });
                    console.log(value)

                }, function () {
                    
                }, "回复 "+item.nickName,50,item.replyContent)
            },
            getList: function (done) {
                let expertId = cookie.get('expertId')
                let vm = this;
                let url = web.API_PATH + 'come/comment/query/page/' +expertId + '/' + vm.page + '/' + vm.row;
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

                    if (response.data.status != 1 && vm.page == 1) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;

                        vm.list = [];
                        return;
                    }
                    let arr = response.data.data;
//
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    }else{
                        vm.isShowMoreText = true
                    }



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
        },
        deactivated:function () {
            if(this.timeOut!=null){
                this.clearTimeOut();
            }
        }


    }
</script>
<style>


    .answer_my_coment_list .item{ padding: 0.30rem; border-bottom: 0.20rem solid #F4F4F7;}
    .answer_my_coment_list .comment .img{ width: 0.68rem; height: 100%; float:left; margin-right: 0.17rem;}
    .answer_my_coment_list .comment .img img{width: 0.68rem; height: 0.68rem; border-radius: 0.34rem;}
    .answer_my_coment_list .comment .info{ width: 5.78rem; float:left;}
    .answer_my_coment_list  .comment .info .name{ color:rgba(36,37,61,0.7); font-size:0.24rem; }
    .answer_my_coment_list  .comment .info .name span{ color:rgba(36,37,61,1); margin-right: 0.20rem;}
    .answer_my_coment_list .comment .info .times span{ font-weight: bold; color:#444}
    .answer_my_coment_list .comment .info .content{ color:rgba(36,37,61,1); font-size: 0.28rem; margin: 0.07rem  0}
    .answer_my_coment_list .comment .info .tags{}
    .answer_my_coment_list .comment .info .tags span{ display: inline-block;
        border:0.02rem solid #D1D1D1; color:#D1D1D1; text-align: center; line-height: 0.45rem; border-radius: 0.23rem; padding: 0 0.17rem; font-size:0.24rem; margin-right: 0.10rem;}
    .answer_my_coment_list .comment .info .star span{ display: inline-block; background: url(http://oss.xqzs.cn/resources/psy/star_no.png) no-repeat; background-size: 0.26rem;  height: 0.26rem; width: 0.26rem; margin-right: 0.14rem; }
    .answer_my_coment_list .comment .info .star span.on{background: url(http://oss.xqzs.cn/resources/psy/star.png) no-repeat;background-size: 0.26rem; }

    .answer_my_coment_list .reply{ background: #F1F1F5;  font-size:0.24rem; color:rgba(36,37,61,1); padding:0.17rem ; border-radius: 0.10rem; position: relative; margin-top: 0.30rem }
    .answer_my_coment_list .reply span{color:#999;}
    .answer_my_coment_list .reply:before{ content:" " ; display: block; position: absolute; left:0.27rem; top:-0.20rem;  width: 0;
        height: 0;
        border-left: 0.17rem solid transparent;
        border-right: 0.17rem solid transparent;
        border-bottom: 0.24rem solid #F1F1F5;}


    .answer_my_coment_list .answer .img{ width: 0.68rem; height: 0.68rem; float:left; margin-top: 0.07rem;  margin-right: 0.17rem;}
    .answer_my_coment_list .answer .img img{ width: 100%; height: 100%; border-radius: 50%;}
    .answer_my_coment_list .answer .audio{ width: 100%; margin-top: 0}
     .answer_my_coment_list .question{ border: 0.05rem solid #F1F1F5; border-radius: 0.14rem; padding: 0.21rem; margin-top:0.20rem; color:rgba(36,37,61,0.7); font-size: 0.24rem  }
    .answer_my_coment_list .question .content{ margin-bottom: 0.30rem}
    .answer_my_coment_list .item .time{ color:rgba(36,37,61,0.7); font-size: 0.27rem; margin-left: 0.00rem; margin-top: 0.10rem}
    .answer_my_coment_list .item{ position: relative}
    .answer_my_coment_list .item .reply_btn{font-size: 0.30rem; color:rgba(254,115,1,1); position: absolute;
        top:0.30rem; right:0.30rem;}
    .answer_my_coment_list .item .reply_btn:active{ background: #09ab07;
    }


</style>

<template >
    <div style="height: 100%" class="answer_comment_box"> 
        <div v-title>专家点评</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :bottomHeight="0"
                  :isShowMoreText="isShowMoreText">
            <div class="nums">
                   总体印象
            </div>
            <div class="pointDetail">
                <div class="point">{{point.toFixed(1)}}</div>
                <div class="point_right">
                    <div v-for="item in tags">
                        <div class="starBox"> <span v-for="i in item.point"></span></div>
                        <div class="point_pre">
                            <div class="point_pre_inner" :style="'width:'+ item.count/allCount*100 +'%;'"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pointDetail_bottom">
                <span>满分5分</span>
                <span>{{allCount}} 个评价</span>
            </div>
            <div class="list_top"  @click="changeType()" >
                <span class="img" :class="{not_on:viewType==0}"></span><span>只看有内容的评价</span>
            </div>
            <div class="list">
                    <div class="item" v-for="item in list">
                    <div class="img">
                        <img :src="item.faceUrl" v-if="item.isAnonymous==0">
                        <img v-if="item.isAnonymous==1" src="http://oss.xqzs.cn/resources/psy/isAnonymousImg.png" alt="">
                    </div>
                    <div class="info">
                        <div class="name">
                            <template v-if="item.isAnonymous==0">{{item.nickName}}</template>
                            <template v-if="item.isAnonymous==1">匿名用户</template>
                            <span class="time">{{formatTime(item.addTime)}}</span>
                        </div>
                        <div class="star"><span class="on" v-for="i in item.point"></span><span   v-for="i in 5-item.point"></span>
                        </div>
                        <div class="word">{{item.content}}</div>
                        <div class="reply" v-if="item.replyContent">{{item.replyContent}}</div>
                    </div>
                    <div class="clear"></div>
                </div>


            </div>
        </v-scroll>
    </div>

</template>

<script type="es6">
    import showLoad from '../include/showLoad.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../bus.js';

    export default {
        data() {
            return {
                expertId:0,
                tags:[],
                point:0,
                commentCount:0,
                viewType:0,
                page: 1,
                row: 5 ,
                isPageEnd: false,
                isShowMoreText:false,
                showLoad:false,
                list:[],
                allCount:0
            }
        }, components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll
        },
        mounted: function () {
            this.expertId = this.$route.query.expertId;
            this.getTags();
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
        methods: {
            changeType:function () {
                if(this.viewType==0){
                    this.viewType=1;
                }else{
                    this.viewType=0
                }
                this.page=1;
                this.isPageEnd=false;
                this.getList();
            },
            formatTime:function (time) {
                return xqzs.dateTime.formatDate(time);
            },
            getTags:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/expert/get/tag/'+this.expertId ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        console.log(data)
                        _this.tags= data.body.data.pointGroup;
                        _this.allCount = data.body.data.count
                        _this.point=data.body.data.point;
                        for(let i=0;i<_this.tags.length;i++){
                            _this.commentCount += _this.tags[i].count;
                        }

                    }
                }, function (error) {
                });
            },
            getList: function (done) {

                let vm= this;
                let url =web.API_PATH + 'come/expert/get/evaluate/'+this.expertId+"/"+vm.viewType+'/'+vm.page+'/'+vm.row;

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

                    if(response.data.status!=1&&vm.page==1){
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
        }
    }
</script>
<style>
    .answer_comment_box{background: #fff;}
    .answer_comment_box .nums{background: #fff; padding:0.30rem;color:rgba(36,37,61,1);font-size: 0.36rem;font-weight: bold;line-height: 1;padding-bottom: 0.37rem;}
    .answer_comment_box .nums .comment{ position: absolute; top:0.34rem; right:0.31rem; font-size:0.48rem;  }
    .answer_comment_box .title .txt{ font-size: 0.30rem; color:rgba(36,37,61,1); font-weight: bold;line-height: 1;margin-bottom: 0.37rem;}
    .answer_comment_box .title .star span{ background: url(http://oss.xqzs.cn/resources/psy/star_no.png); width: 0.26rem; height: 0.26rem;  background-size: 0.26rem; display: inline-block; margin-right: 0.10rem; }
    .answer_comment_box  .title .star span.on{background: url(http://oss.xqzs.cn/resources/psy/star.png);background-size: 0.26rem; }

    .answer_comment_box .comment_selects{ padding:0.30rem; background: #fff; padding-bottom: 0.10rem;}
    .answer_comment_box .comment_selects span{display: inline-block; float:left; height: 0.46rem; line-height: 0.46rem; border: 1px solid #999; border-radius:0.23rem ; font-size: 0.26rem; padding: 0 0.17rem; margin-right: 0.20rem; margin-bottom: 0.20rem; color:#999 }
    .answer_comment_box  .comment_selects span:first-child{border: 1px solid #D1D1D1; color:#D1D1D1 }

    .answer_comment_box .list_top{ border-top:0.16rem solid rgba(245,245,245,1); background: #fff;border-bottom: 1px solid #E0E0E1; font-size: 0.30rem; height: 0.88rem; line-height: 0.88rem; padding: 0  0.30rem }
    .answer_comment_box  .list_top span{ float:left; display: block}
    .answer_comment_box  .list_top .img{  background:url("http://oss.xqzs.cn/resources/psy/asker/comment_per.png") no-repeat; width: 0.40rem; height: 0.40rem;border-radius: 50%; margin-top: 0.24rem; margin-right:0.17rem;background-size: 0.40rem; border:1px solid #fff; }
    .answer_comment_box  .list_top .img.not_on{
        background: #fff; border:1px solid #ccc;
    }
    .pointDetail{position: relative;background: #fff;padding-bottom: 0.20rem;}
    .pointDetail .point{color:rgba(255,89,2,1);font-size: 1.20rem;line-height: 1;margin-left: 0.30rem;width:1.70rem;text-align: center;}
    .pointDetail .point_right{line-height: 1;position: absolute;top:0;left:35%;width:60%;}
    .pointDetail .point_right>div{height: 0.17rem;margin-bottom: 0.05rem;position: relative}
    .starBox{width:25%;text-align: right;}
    .pointDetail .point_right .point_pre{overflow:hidden;width:75%;background: rgba(245,245,245,1);border-radius: 2.5px;height:0.06rem;position: absolute;right:-0.07rem;top:0.17rem;}
    .pointDetail .point_right span{width: 0.17rem; height: 0.17rem;background: url('http://oss.xqzs.cn/resources/psy/point-star.png')no-repeat;margin-right: 0.04rem;display: inline-block;background-size:100%}
    .point_pre_inner{background: rgba(255,89,2,1);border-radius: 3px;height:100%;}
    .pointDetail_bottom{color:rgba(36,37,61,0.6);padding:0 0.30rem;font-size: 0.30rem;line-height: 1;background: #fff;padding-bottom: 0.25rem;}
    .pointDetail_bottom span:nth-of-type(1){width:1.70rem;text-align: center;display: inline-block}
    .pointDetail_bottom span:nth-of-type(2){float: right;font-size: 0.26rem;}
    .answer_comment_box  .list .item{ background: #fff; padding:0.30rem; border-bottom: 1px solid #E0E0E1 }
    .answer_comment_box .list .star span{ background: url(http://oss.xqzs.cn/resources/psy/starNew_no.png)no-repeat; width: 0.31rem; height: 0.29rem;  background-size: 100% 100%; display: inline-block; margin-right: 0.10rem; }
    .answer_comment_box  .list .star span.on{background: url(http://oss.xqzs.cn/resources/psy/starNew.png)no-repeat;background-size: 100% 100%; }

    .answer_comment_box  .list .item .img{ width: 0.68rem; height: 0.68rem; float:left; }
    .answer_comment_box  .list .item .img img{ width: 100%; height: 100%; border-radius: 50%; border: 1px solid #E0E0E1 }
    .answer_comment_box  .list .info{ float:left; margin-left:0.30rem;  width: 83%}
    .answer_comment_box  .list .info .name{ font-size: 0.24rem; color:rgba(36,37,61,0.5); margin-bottom: 0.07rem;}
    .answer_comment_box  .list .word{ font-size:0.28rem; font-weight: bold;  color:rgba(36,37,61,1);}
    .answer_comment_box  .list .reply{ font-size: 0.26rem;  color:rgba(36,37,61,1);background: rgba(245,245,245,1);border-radius: 0.10rem;padding:0.12rem 0.20rem;position: relative;margin-top: 0.30rem;}
    .answer_comment_box  .list .reply:after{content: '';width:0.30rem;height:0.30rem;background:rgba(245,245,245,1);position: absolute;top:-0.16rem;left:0.60rem ;transform:rotate(45deg);}
    .answer_comment_box  .list .time{ font-size:0.24rem; color:rgba(36,37,61,0.5); float: right}
    .answer_comment_box  .list .info .class_s { clear: both; margin-top: 0.10rem;}
    .answer_comment_box  .list .info .class_s span{ display: block; float:left; text-align: center; border-radius:0.21rem;  height: 0.42rem; line-height: 0.42rem;  border: 1px solid #C1C1C1 ; color:#C1C1C1 ; font-size: 0.24rem; padding: 0 0.24rem; margin-right:0.19rem;}
    .answer_comment_box  .list .reply span{color:#999}

</style>

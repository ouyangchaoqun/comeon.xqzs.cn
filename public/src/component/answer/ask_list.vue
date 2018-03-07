<template >
    <div style="height: 100%" class="answer_ask_list_box"  :class="{wbg:list.length==0&&!showLoad}">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="nothing comment" v-if="list.length==0">
            <div>
                <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                <div class="nothing_bottom">
                    <p>暂无待答问题</p>
                </div>
            </div>

        </div>
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :bottomHeight="50"
                  :isShowMoreText="isShowMoreText">
            <div v-title>待答</div>
            <div class="list  ">
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
    import Bus from '../../js/bus';
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

        mounted: function () {
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
            getList: function (done) {
                let vm= this;
                let expertId = cookie.get('expertId')
                let url =web.API_PATH + 'come/expert/query/to/be/answered/page/'+expertId+'/_userId_/'+vm.page+'/'+vm.row;
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
                    console.log(response.data.data.rows)
                    let arr = response.data.data.rows;
//
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    }else{
                        vm.isShowMoreText=true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);



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
    .answer_ask_list_box  .list .info .names{ font-size: 0.24rem;color:rgba(36,37,61,0.5);line-height:0.58rem;margin-bottom: 0.08rem; }
    .answer_ask_list_box .list .info .names span{ color:rgba(36,37,61,0.5); font-size: 0.26rem; margin-left: 0.20rem}
    .answer_ask_list_box  .list .info .content{ font-size: 0.30rem; color:rgba(36,37,61,1);margin-bottom: 0.28rem ;width:98%;}
    .answer_ask_list_box  .list .info .last_time{line-height:1;font-size: 0.26rem;color:rgba(36,37,61,0.5);margin-bottom: 0.18rem;}
    .answer_ask_list_box  .list .info .price{ float:right;font-size: 0.24rem;color:#FE7301; text-align: right; width: 2.38rem;}
</style>

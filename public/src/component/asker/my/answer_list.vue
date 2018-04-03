<template>
    <div style="height: 100%" class="asker_my_answer_list_box wbg">

        <div v-title class='hide_title'>我的关注</div>
        <div class="nothing answer" v-if="list.length==0&&!showLoad" >
            <div>
                <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                <div class="nothing_bottom">
                    <p>您还没有任何关注</p>
                    在专家详情页面可以点击关注按钮进行关注
                    <div @click="goAnswerIndex()">去关注</div>
                </div>
            </div>

        </div>

        <v-showLoad v-if="showLoad"></v-showLoad>
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :bottomHeight="0"
                  :isShowMoreText="isShowMoreText"  v-if="list.length>0">

            <div class="top_tip">共{{total}}位咨询师</div>
            <div class="answer_list">
                <div class="item" v-for="(item,index) in list"  v-if="item.expertId!=52">
                    <div @click="goDetail(item.expertId)">
                        <div class="itemDetail">
                            <div class="img"><img :src="item.faceUrl"></div>
                            <div class="itemDetail_right">
                                <div class="itemHeader">
                                    <div>{{item.nickName}}<span>{{item.cityName}}</span></div>
                                </div>
                                <div class="title">{{item.sign}}</div>
                                <div class="itemDetail_class_s">
                                    <span v-for="(tag,tagIndex) in item.domains" class="tab_i_i">{{tag.title}}</span>
                                </div>
                                <div class="class_s other">
                                    <span class="price" ><b>问价</b>  <a>￥{{item.price}}</a></span>
                                    <span  class="class_right">
                                        <span v-if="item.answerCount!=null">{{item.answerCount||0}} 个回答</span>
                                        <span>{{item.followCount||0}} 人关注</span>
                                    </span>

                                </div>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </div>
            </div>
        </v-scroll>
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
                isShowMoreText:false,
                showLoad:false,
                list:[],
                total:0
            }
        },
        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll
        },
        mounted:function () {
            if(!xqzs.user.isUserLogin()){
                return ;
            }
            this.initAll();
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
        },
        props:
            ['isKeepAlive']
        ,
        activated: function () {
            console.log(this.isKeepAlive);
            if(!this.isKeepAlive){
                this.initAll();
            }else{
                let st = xqzs.localdb.get("st_"+this.$route.path);
                console.log(st);
                if(st){
                    $('.yo-scroll').scrollTop(st);
                }
            }


        },
        methods:{
            initAll:function () {
                this.isPageEnd=false;
                this.page=1;
                this.list=[];
                this.isShowMoreText=false;
                this.getList();
            },
            goAnswerIndex:function () {
                this.$router.push("/asker/expert")
            },
            formatTime:function (time) {
                return xqzs.dateTime.formatDateTime(time)
            },
            goDetail:function (id) {
                this.$router.push("/asker/expert/detail/?id="+id)
            },
            getList: function (done) {

                let vm= this;
                let url =  'come/user/query/follow/page/_userId_/'+vm.page+'/'+vm.row;

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
                    if(done&&typeof(done)==='function'){
                        done()
                    }
                    vm.showLoad = false;
                    vm.isLoading = false;
//                    console.log(response)

                    if(response.data.status!=1&&vm.page==1){
                        vm.list = [];
                        vm.total=0
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;
                        Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                        return;
                    }
                    let arr = response.data.data.rows;
//
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    }else{
                        vm.isShowMoreText =false
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);

                    vm.total=response.data.data.total

                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    if (arr.length == 0) return;
                    vm.page = vm.page + 1;
                },function (error) {
                    vm.isLoading = false;
                    vm.showLoad = false;
                })
            },
            onInfinite(done) {
                this.getList(done);
            },
        }


    }
</script>
<style>
    .asker_my_answer_list_box .item:last-child{border-bottom: 0.02rem solid #eee;}
    .asker_my_answer_list_box .answer_list .item{margin-top: 0}
    .tab_i_i{color: #FF9966;
        background: rgba(204,204,204,0.3);
        border-radius: 0.05rem;
        padding: 0.07rem 0.10rem;
        margin: 0 0.10rem;}
</style>
<template id="fans">
    <div class="fansBox">
        <div v-title class='hide_title'>我的粉丝</div>
        <div v-if="count!=0">
            <v-showLoad v-if="showLoad"></v-showLoad>
            <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                      :bottomHeight="48"
                      :isShowMoreText="isShowMoreText">
                <div>
                    <header>共{{count}}位粉丝</header>
                    <ul class="fansItem">
                        <li v-for="item in list">
                            <img :src="item.faceUrl" alt="">
                            <div class="right">
                                <div>{{item.nickName}}</div>
                                <div class="bottom">
                                    <div><span>{{getFormatDate(item.followTime)}}</span><span>{{getFormatTime(item.followTime)}}</span></div>
                                    <div><span>{{item.questionCount}} 个问题</span><span>{{item.listenTimes}} 次偷听</span></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </v-scroll>
        </div>

        <div class="nothing comment" v-if="count==0">
            <div>
                <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                <div class="nothing_bottom">
                    <p>还没有粉丝</p>
                </div>
            </div>

        </div>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    import scroll from '../../include/scroll.vue';
    import Bus from '../../bus';

    export default {
        data() {
            return {
                showLoad:false,
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText:false,
                list:[],
                count:0
            }
        },


        activated: function () {
            this.page=1;
            this.isPageEnd=false;
            this.isShowMoreText=false;
            this.list=[];
            this.getList()
            xqzs.wx.setConfig(this);
        },
        methods:{
            getList: function (done) {
                let vm= this;
                let expertId = cookie.get('expertId')
                let url =web.API_PATH + 'come/expert/query/follows/page/'+expertId+'/'+vm.page+'/'+vm.row;
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
                    console.log(response.data.data)
                    vm.count = response.data.data.total
                    let arr = response.data.data.rows;
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
            getFormatDate: function (time) {
                return xqzs.dateTime.formatDate(time)
            },
            getFormatTime:function (time) {
                return xqzs.dateTime.formatTime(time)
            }
        },
        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
        }


    }
</script>
<style>
    .fansBox{
        background: #fff;
    }
    .fansBox header{
        height: 0.88rem;
        line-height: 0.88rem;
        background: #f4f4f7;
        font-size: 0.30rem;
        color: #999;
        text-align: center;
    }
    .fansItem li{
        height:1.24rem;
        border-bottom: 0.02rem solid #eee;
        padding-right:0.30rem;
        font-size: 0.22rem;
        color: #999;
        position: relative;
    }
    .fansItem li img{
        height: 0.88rem;
        width:0.88rem;
        border-radius: 50%;
        display: block;
        position: absolute;
        left:0.30rem;
        top:50%;
        margin-top:-0.44rem;
    }
    .fansItem li .right{
        margin-left:1.36rem;
        line-height: 1;
        padding-top: 0.34rem;
    }
    .fansItem li .right>div:nth-of-type(1){
        color: #333;
        font-size: 0.30rem;
        margin-bottom: 0.15rem;
    }
    .fansItem li .bottom>div:nth-of-type(1){
        float: left;
    }
    .fansItem li .bottom>div:nth-of-type(1) span:nth-of-type(1){
        margin-right:0.20rem;
    }
    .fansItem li .bottom>div:nth-of-type(2){
        float: right;
    }
    .fansItem li .bottom>div:nth-of-type(2) span:nth-of-type(1){
        margin-right:0.36rem;
    }
    .noFansBox{
        color: #B3B3B3;
        font-size: 0.26rem;
        text-align: center;
        height:100%;
        width:100%;
        position: relative;
    }
    .noFansBox div{
        position: absolute;
        width:100%;
        top:30%;
    }
</style>
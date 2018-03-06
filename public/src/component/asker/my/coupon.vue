<template>
    <div class="coupon_box">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title>优惠券</div>
        <v-scroll :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :bottomHeight="0"
                  :isShowMoreText="isShowMoreText">
        <div class="coupon_type">
            <div :class="{type_active:isUsed==0}" @click="changeType(0)">待使用</div>
            <div :class="{type_active:isUsed==1}" @click="changeType(1)">已失效</div>
        </div>
        <div class="coupon_list">
            <ul v-if="isUsed==0">
                <li v-for="item in list">
                    <div class="item_left">
                        <div>{{item.name}}</div>
                        <div>有效期：{{formatDate(item.addTime)}}至{{formatDate(item.expiredTime)}}</div>
                    </div>
                    <div class="item_right" @click="goListen()">
                        去使用
                    </div>
                </li>
            </ul>
            <ul v-if="isUsed==1" :class="{overTime:isUsed==1}">
                <li v-for="item in list">
                    <div class="item_left">
                        <div>{{item.name}}</div>
                        <div>有效期：{{formatDate(item.addTime)}}至{{formatDate(item.expiredTime)}}</div>
                    </div>
                    <div class="item_right">
                        <template v-if="item.isUsed==1">已使用</template>
                        <template v-if="item.isUsed==0">已过期</template>
                    </div>
                </li>
            </ul>
        </div>
            <div class="noCoupon" v-if="noCoupon">
                <img src="../../../images/asker/noCoupon.png" alt="">
                <div>暂无此类优惠券</div>
            </div>
        </v-scroll>

    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    import scroll from '../../include/scroll.vue';
    import Bus from '../../../js/bus.js';
    export default {
        data() {
            return {
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText: false,
                showLoad: false,
                list: [],
                isUsed : 0,
                active:true,
                noCoupon:false,
            }
        },

        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
        },
        mounted: function () {
            this.getList();
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
        },
        methods: {
            formatDate: function (time) {
                return xqzs.dateTime.formatDate(time)
            },
            changeType:function (v) {
                let _this = this;
                _this.isUsed=v;
                _this.noCoupon = false
                _this.page = 1;
                _this.list = [];
                _this.isPageEnd = false;
                _this.isShowMoreText = false;
                _this.getList();
            },
            getList: function (done) {

                let vm = this;
                let url = web.API_PATH + 'come/user/get/coupon/_userId_/' + vm.page + '/' + vm.row +'/'+vm.isUsed;
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
                    if (response.data.status != 1 && vm.page == 1) {
                        vm.list = [];
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;
                        Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                        return;
                    }
                    let arr = response.data.data;
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    }else{
                        vm.isShowMoreText = true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    if(vm.list.length==0){
                        vm.noCoupon = true
                    }else {
                        vm.noCoupon = false
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
            goListen:function () {
                this.$router.push("/asker/listen");
            }

        }


    }
</script>
<style>
    .coupon_box{
        background: #fff;
    }
    .coupon_type{
        line-height:2.588rem;
        color:rgba(155,155,155,1);
        font-size: 0.88235rem;
        display: flex;
        display: -webkit-box;
        display: -webkit-flex;
        border-bottom:1px solid rgba(216,216,216,1);
    }
    .coupon_type>div{
        flex-grow: 1;
        text-align: center;
        position: relative;
    }
    .coupon_type .type_active{
        color:rgba(248,123,7,1);
    }
    .coupon_type .type_active:after{
        content: '';
        width:38%;
        height:2px;
        background: rgba(248,123,7,1);
        position: absolute;
        bottom:0;
        left:50%;
        margin-left:-19%;
    }
    .coupon_list{
        padding:0.88235rem;
    }
    .coupon_list li{
        height:4.8235rem;
        line-height: 4.8235rem;
        background: url("../../../images/asker/coupon_bg.png") no-repeat;
        background-size: 100% 100%;
        margin-bottom: 0.588235rem;
        color:rgba(255,255,255,1);
        font-size: 0.9411rem;
    }
    .coupon_list .overTime li{
        background: url("../../../images/asker/coupon_used.png") no-repeat;
        background-size: 100% 100%;
    }
    .coupon_list li .item_left{
        float: left;
        width:78%;
        text-align: center;
        padding-top: 1.1764rem;
    }
    .coupon_list li .item_left div{
        line-height: 1.294rem;

    }
    .coupon_list li .item_left div:nth-of-type(2){
        font-size: 0.70588rem;
        line-height: 1rem;
        padding-top: 0.294rem;
    }
    .coupon_list li .item_right{
        float: right;
        height:100%;
        width:22%;
        text-align: center;
    }
    .noCoupon{
        width:100%;
        text-align: center;
        padding-top: 5rem;
        color:rgba(155,155,155,1);
        font-size: 0.88235rem;
    }
    .noCoupon img{
        width:32%;
        margin-bottom: 0.71rem;
    }
</style>
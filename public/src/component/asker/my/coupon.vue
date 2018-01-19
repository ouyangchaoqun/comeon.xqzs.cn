<template>
    <div class="coupon_box">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title>优惠券</div>
        <div class="coupon_type">
            <div :class="{type_active:type==1}" @click="changeType(1)">待使用</div>
            <div :class="{type_active:type==2}" @click="changeType(2)">已失效</div>
        </div>
        <div class="coupon_list">
            <ul v-if="type==1">
                <li>
                    <div class="item_left">
                        <div>偷听卡</div>
                        <div>有效期：2018-1-10至2018-2-10</div>
                    </div>
                    <div class="item_right">
                        去使用
                    </div>
                </li>
                <li>
                    <div class="item_left">
                        <div>偷听卡</div>
                        <div>有效期：2018-1-10至2018-2-10</div>
                    </div>
                    <div class="item_right">
                        去使用
                    </div>
                </li>
                <li>
                    <div class="item_left">
                        <div>偷听卡</div>
                        <div>有效期：2018-1-10至2018-2-10</div>
                    </div>
                    <div class="item_right">
                        去使用
                    </div>
                </li>
            </ul>
            <ul v-if="type==2" :class="{overTime:type==2}">
                <li>
                    <div class="item_left">
                        <div>偷听卡</div>
                        <div>有效期：2018-1-10至2018-2-10</div>
                    </div>
                    <div class="item_right">
                        去使用
                    </div>
                </li>
                <li>
                    <div class="item_left">
                        <div>偷听卡</div>
                        <div>有效期：2018-1-10至2018-2-10</div>
                    </div>
                    <div class="item_right">
                        去使用
                    </div>
                </li>
                <li>
                    <div class="item_left">
                        <div>偷听卡</div>
                        <div>有效期：2018-1-10至2018-2-10</div>
                    </div>
                    <div class="item_right">
                        去使用
                    </div>
                </li>
            </ul>
        </div>
        <div class="noCoupon" v-if="false">
            <div>暂时没有可用的优惠券</div>
        </div>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';

    export default {
        data() {
            return {

                showLoad: false,
                list: [],
                type: 1,
                active:true,
            }
        },
        props:{
            user:{
                type:Object
            }
        },

        components: {
            'v-showLoad': showLoad,
        },
        mounted: function () {

        },
        methods: {
            changeType:function (v) {
                let _this = this;
                _this.type=v;
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
        background: rgba(251,156,0,1);
        margin-bottom: 0.588235rem;
        color:rgba(255,255,255,1);
        font-size: 0.9411rem;
    }
    .coupon_list .overTime li{
        background: rgba(207,204,201,1);
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
</style>
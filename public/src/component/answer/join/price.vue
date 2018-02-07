<template >
    <div class="price_box">
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure sure_nor" v-if="price==''||Number(price)<10">确定</div>
                <div class="joinSet_sure" @click="setPrice()" v-if="price!=''&&Number(price)>=10">确定</div>
            </div>
            <div class="set_price">
                <input type="number" class="priceInput" v-model="price"  :value="price" placeholder="设置提问价格（元）例如：10">
            </div>
            <div class="price_bottom">
                <div>至少10元</div>
                <div @click="showNotice()">解答须知</div>
            </div>
        </div>
        <v-showLoad v-show="showLoad"></v-showLoad>
        <div class="weui-mask" v-show="noticeFlag">
            <div class="price_box_notice">
                <h2>解答须知</h2>
                <div class="notice_content">
                    <div>
                        <p>1. 用户向您个人提问：</p>
                        <p>（1）您可以统一设置用户向您提问的酬金；</p>
                        <p>（2）您在48小时内回答问题，将获得问题 酬金； </p>
                        <p>（3）您的回答将参与1元偷听，回答每被偷 听一次，您将获得￥0.3分成。</p>
                    </div>
                    <p>2. 抢答：</p>
                    <p>（1）平台将根据您设置的提问价格和您目前的等级， 为您优先匹配合适的问题，供您进行抢答；</p>
                    <p>（2）每个问题在48小时内最多可有5位咨询师抢答；</p>

                </div>
                <div class="notice_btn" @click="hideNotice()">我知道了</div>
            </div>
        </div>

    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                price:cookie.get('reg_price')?cookie.get('reg_price'):'',
                showLoad:false,
                edit:'',
                noticeFlag:false,
            }
        },
        props: {
            user:{
                type:Object
            }
        },
        mounted: function () {
            this.edit= this.$route.query.edit;
            if(this.edit==1){
                this.getExpertInfo();
            }
        } ,
        methods:  {
            getExpertInfo:function () {
                let expertId = cookie.get('expertId');
                this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+expertId+'/_userId_').then(function (data) {
                    if (data.body.status == 1) {
                        let showInfo = data.data.data;
                        this.price = showInfo.price
                    }
                }, function (error) {
                });
            },
            showNotice:function () {
              this.noticeFlag = true
            },
            hideNotice:function () {
                this.noticeFlag = false
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setPrice:function () {
                let _this = this;
                if(_this.edit==1){
                    let url = "come/expert/modify";
                    let msg = {
                        price: _this.price,
                        userId:_this.user.id,
                        id:_this.user.id,
                        expertId:cookie.get('expertId')
                    };
                    _this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                            }
                        );
                }
                cookie.set('reg_price',_this.price,1)
                _this.showLoad = true;
                setTimeout(function () {
                    _this.$router.go(-1);
                },300)
            },

        },
        components: {
            'v-showLoad': showLoad,
        }


    }
</script>
<style>
    .price_box{
        background: #fff;
    }
    .price_box_notice{
        width:74%;
        background: #fff;
        border-radius: 0.588rem;
        position: absolute;
        left: 50%;
        margin-left: -40%;
        top:15%;
        padding:0 3%;
    }
    .price_box_notice h2{
        font-size: 1.0588rem;
        color:RGBA(69, 75, 84, 1);
        line-height: 1.47rem;
        padding:0.88235rem 0;
        text-align: center;
    }
    .notice_content{
        font-size: 0.76471rem;
        color:RGBA(69, 75, 84, 1);
        background: RGBA(69, 75, 84, 0.05);
        margin-bottom: 1.176rem;
        padding:0.764rem 0.764rem 1.588rem 0.8235rem;
    }
    .notice_content>div{
        margin-bottom: 1.176rem;
    }
    .notice_btn{
        width:66%;
        height:2.0588rem;
        line-height: 2.1rem;
        border-radius: 1.32rem;
        background: RGBA(254, 122, 3, 1);
        color:RGBA(255, 255, 255, 1);
        font-size: 0.88235rem;
        text-align: center;
        margin: 0 auto;
        margin-bottom: 1.176rem;
    }
    .notice_btn:active{
        background: rgba(253,114,6,1);
    }
    .set_price{
        padding:0.88235rem;
        background: #fff;
        font-size: 0.8235rem;
        margin-bottom: 0.588rem;
    }
    .set_price input{
        width:100%;
        height:1.176rem;
        font-size: 0.8235rem;
        color:RGBA(69, 75, 84, 1) !important;;
    }
    .price_bottom{
        padding: 0 0.88235rem;
        line-height: 1.176rem;
    }
    .price_bottom div:nth-of-type(1){
        float: left;
        font-size: 0.8235rem;
        color:RGBA(69, 75, 84, 0.6);
    }
    .price_bottom div:nth-of-type(2){
        float: right;
        font-size: 0.70588rem;
        color:RGBA(69, 75, 84,0.55);
        background: url("../../../images/asker/tip.png") no-repeat left center;
        background-size: 0.8235294117647059rem;
        padding-left: 0.99rem;
        line-height: 0.88235rem;
    }
</style>
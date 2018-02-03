<template >
    <div class="price_box">
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure" @click="setPrice()">确定</div>
            </div>
            <div class="set_price">
                <input type="number" class="priceInput" v-model="price"  :value="price" placeholder="设置提问价格（元）例如：¥10">
            </div>
            <div class="price_bottom">
                <div>至少10元</div>
                <div>解答须知</div>
            </div>
        </div>
        <v-showLoad v-show="showLoad"></v-showLoad>

    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                price:"",
                showLoad:false,
                edit:''
            }
        },
        props: {
            user:{
                type:Object
            }
        },
        mounted: function () {
            this.edit= this.$route.query.edit;
        } ,
        methods:  {

            backStep:function () {
                this.$router.go(-1)
            },
            setPrice:function () {
                let url = "come/expert/register";
                let msg = {
                    price: this.price,
                    userId:this.user.id,
                    id:this.user.id
                };
                let _this = this;
                if(_this.price==''){
                    xqzs.weui.tip("请设置提问价格",function () {});
                }else if(Number(_this.price)<10){
                    xqzs.weui.tip("请设置正确的价格",function () {});
                }else {
                    if(_this.edit==1){
                        //修改
                        console.log('修改')
                        url = "come/expert/modify";
                        msg.expertId=cookie.get('expertId');
                    }
                    //入驻
                    _this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                                this.showLoad = true
                                this.$router.go(-1);
                            }
                        );
                }
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
    .set_price{
        padding:0.88235rem;
        background: #fff;
        font-size: 0.8235rem;
        margin-bottom: 0.35rem;
    }
    .set_price input{
        width:100%;
        height:1.176rem;
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
        color:RGBA(69, 75, 84,0.55)
    }
</style>
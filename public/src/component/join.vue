<template >
    <div class="join_box">

        <div v-title>平台介绍</div>
        <div class="top"></div>
        <div class="detail">
            <div class="detail_block">
                <div class="detail_number">01</div>
                <div class="block_titile title_left block_titile1">"好一点"平台介绍</div>
                <div class="block_box block_box1">
                    <p>“好一点”是一个专业的在线心理咨询平台，以<span>60秒语音</span>的方式，快速解决人生的小烦恼。<br/>作为一站式解决内容付费的多渠道变现平台，我们推出的偷听功能，能让咨询师<span>知识价值最大化</span>。</p>
                </div>
            </div>
            <div class="detail_block">
                <div class="detail_number detail_number_right">02</div>
                <div class="block_titile title_right block_titile2 title_rotate"></div>
                <div class="block_titile title_right right_html block_titile2">咨询方式</div>
                <div class="block_box">
                    <div class="circle_box">
                        +
                        <div>
                            <span>
                                <p>一对一</p>
                                <p>咨询方式</p>
                            </span>
                        </div>
                        <div>
                            <span>
                                <p>快速抢答</p>
                                <p>模式</p>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="detail_block">
                <div class="detail_number">03</div>
                <div class="block_titile title_left block_titile3">咨询师收益分成</div>
                <div class="block_box block_box3">
                    <ul>
                        <li><i></i>选为最佳答案，获<span>100%</span>的酬金</li>
                        <li><i></i>其他用户偷听，获<span>30%</span>的分成</li>
                        <li><i></i>产生收益后，随时<span>提现</span></li>
                    </ul>
                </div>
            </div>
            <div class="detail_block">
                <div class="detail_number detail_number_right">04</div>
                <div class="block_titile title_right block_titile4 title_rotate"></div>
                <div class="block_titile title_right right_html block_titile4">只需三步，免费入驻</div>
                <div class="block_box block_box4">
                    <ul>
                        <li>
                            点击底部立即入驻
                            <div>关注“好一点”公众号</div>
                            <img src="http://oss.xqzs.cn/resources/psy/asker/update_join_jt.jpg" alt="">
                        </li>
                        <li>
                            填写个人资料
                            <img src="http://oss.xqzs.cn/resources/psy/asker/update_join_jt.jpg" alt="">
                        </li>
                        <li>
                            提交审核
                            <div>（1个工作日内审核完成，即入驻成功）</div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="join_btn" @click="join()">立即入驻</div>
        <div class="service"  @click="service()">
            <span>微信客服</span>

        </div>

        <div id="ewm_box_service" style="display: none">
        <div class="ewm_box ">
            <div class="ewm">
                <img src="http://oss.xqzs.cn/resources/psy/service_ewm1.jpg" />
                <div class="info">
                    长按添加<br>
                    咨询师助理微信
                </div>
            </div>

        </div>

        </div>

        <div id="ewm_box_join" style="display: none">
        <div class="ewm_box ">
            <div class="ewm">
                <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGB8jwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyVURRWTQwNEpmTTIxMDAwMGcwM0sAAgS17ZxaAwQAAAAA" />
                <div class="info">
                    长按二维码关注<br>
                    好一点入驻
                </div>
            </div>

        </div>

        </div>
    </div>




</template>

<script type="es6">


    export default {
        data() {
            return {}
        },
        methods: {
            join:function () {

                let _this=this;
                xqzs.user.getUserInfo(function (user) {

                    if(user&&user.id){

                        xqzs.api.get(_this,'come/expert/query/detail/by/userId/_userId_',function (data) {
                            if (data.body.status == 1) {
                                if(data.data.data!=null){
                                    let status = data.data.data.status;
                                    if(status==2||status==-1){
                                        //修改
                                        xqzs.weui.tip("资料审核中，请稍后")
                                    }
                                    if(status==1){
                                        //已认证
                                        xqzs.weui.tip("您已成功入驻咨询师，请从公众号移步到咨询师。")
                                    }
                                    if(status==0){
                                        //未认证
                                        _this.goJoin()
                                    }
                                }else{
                                    //入驻
                                    _this.goJoin()
                                }
                            }
                        })


                    }else{
                        xqzs.weui.dialogCustom($("#ewm_box_join").html())
                    }

                })



            },
            goJoin:function () {
                this.showLoad = true;
                this.$router.push("/answer/join/joinstep");
            },
            service:function () {
                xqzs.weui.dialogCustom($("#ewm_box_service").html())
            }

        },
        mounted: function () {




            xqzs.wx.setConfig(this, function () {
                var config = {
                    imgUrl: 'http://oss.xqzs.cn/resources/psy/join_share_img3.jpg',
                    title:  '‘好一点’心理咨询平台诚邀您入驻' ,
                    desc: '‘好一点’心理咨询师的兼职平台，诚邀您入驻，一次回答，收益不断！',
                    link: "http://wx.xqzs.cn/comeon/guest#/join",
                };
                weshare.init(wx, config)
            });


        }


    }
</script>
<style>
    .join_box{
        background:RGBA(86, 196, 254, 0.2);
    }
    .join_box .top{  height: 5.44rem;background: url("http://oss.xqzs.cn/resources/psy/asker/update_join_header.png") no-repeat; width: 7.5rem; background-size: 100% 100%;}
    .join_box .detail{padding-top:2.16rem;margin-bottom: 2.48rem; }
    .detail_block{
        margin-bottom: 2.2rem;
        position: relative;
    }
    .detail_block .detail_number{
        color:RGBA(86, 196, 254, 0.4);
        font-size: 2.6rem;
        position: absolute;
        top:-2.5rem;
        left:0.4rem;
    }
    .detail_block .detail_number_right{
        left: auto;
        right:0.4rem;
    }
    .block_box{
        position: relative;
        background: RGBA(255, 255, 255, 0.7);
        width:6.7rem;
        border-radius: 0.3rem;
        height:3.94rem;
        margin: 0 auto;
        box-shadow: 0 0.04rem 0.28rem RGBA(86, 196, 254, 0.5);
    }
    .block_box1 p{
        color:RGBA(43, 53, 68, 1);
        font-size: 0.26rem;
        line-height: 0.48rem;
        padding:1.42rem 0.34rem 0 0.34rem;
    }
    .block_box1 p span{
        color:RGBA(86, 196, 245,1);
    }
    .block_box3{height:auto}
    .block_box3 ul{
        padding:1.62rem 0.56rem 0.26rem 0.56rem;
    }
    .block_box3 li{
        color:RGBA(43, 53, 68, 1);
        font-size: 0.28rem;
        line-height: 0.4rem;
        margin-bottom: 0.4rem;
    }
    .block_box3 li:nth-of-type(2){
        padding-left: 0.7rem;
    }
    .block_box3 li:nth-of-type(3){
        padding-left: 1.34rem;
    }
    .block_box3 span{
        color:RGBA(86, 196, 245,1);
        font-size: 0.34rem;
    }
    .block_box3 i{
        width:0.4rem;
        height:0.4rem;
        display: inline-block;
        vertical-align: sub;
        margin-right: 0.18rem;
    }
    .block_box3 li:nth-of-type(1) i{
        background: url("http://oss.xqzs.cn/resources/psy/asker/update_join_star1.png") no-repeat;
        background-size: 100% 100%;
    }
    .block_box3 li:nth-of-type(2) i{
        background: url("http://oss.xqzs.cn/resources/psy/asker/update_join_star2.png") no-repeat;
        background-size: 100% 100%;
    }
    .block_box3 li:nth-of-type(3) i{
        background: url("http://oss.xqzs.cn/resources/psy/asker/update_join_star3.png") no-repeat;
        background-size: 100% 100%;
    }
    .block_box4{
        height:auto;
    }
    .block_box4 ul{
        padding:1.72rem 0 0.26rem 0;
        text-align: center;
    }
    .block_box4 ul li{
        background: RGBA(86, 196, 254, 0.2);
        color:RGBA(43, 53, 68, 1);
        font-size: 0.32rem;
        width:4.4rem;
        line-height: 0.88rem;
        margin : 0 auto;
        border-radius: 0.1rem;
        margin-bottom: 1.1rem;
        position: relative;
    }
    .block_box4 li>div{
        position: absolute;
        color:RGBA(86, 196, 245,1);
        font-size: 0.26rem;
        line-height: 0.36rem;
        width:120%;
        left:50%;
        margin-left: -60%;
    }
    .block_box4 li>img{
        position: absolute;
        width:0.3rem;
        left:50%;
        margin-left: -0.15rem;
    }
    .block_box4 li:nth-of-type(1)>div{
        top:1.08rem;
    }
    .block_box4 li:nth-of-type(1) img{
        top:1.56rem;
    }
    .block_box4 li:nth-of-type(2) img{
        top:1.32rem;
    }
    .block_box4 li:nth-of-type(3)>div{
        top:1.2rem;
    }
    .circle_box{
        padding:1.62rem 0.5rem 0 0.5rem;
        line-height: 1.8rem;
        text-align: center;
        color:RGBA(86, 196, 245,1);
        font-size:0.6rem;
    }
    .circle_box div{
        width:1.8rem;
        height:1.8rem;
        border-radius: 50%;
        background: RGBA(255, 255, 255, 1);
        color:RGBA(43, 53, 68, 1);
        font-size: 0.3rem;
        box-shadow: 0 0.04rem 0.4rem RGBA(86, 196, 254, 0.4);
        position: relative;
    }
    .circle_box span{
        position: absolute;
        width:100%;
        top:0.5rem;
        left:50%;
        margin-left: -50%;
        text-align: center;
    }
    .circle_box p{line-height: 0.44rem;}
    .circle_box div:nth-of-type(1){
        float: left;
    }
    .circle_box div:nth-of-type(2){
        float: right;
    }
    .block_titile{
        position: absolute;
        z-index: 5;
        top:0.3rem;
        text-align: center;
        color:RGBA(255, 255, 255, 1);
        font-size: 0.36rem;
        height:0.72rem;
        background: url("http://oss.xqzs.cn/resources/psy/asker/update_join_bg2.png") no-repeat;
        line-height: 0.72rem;
        background-size: 100% 100%;
    }
    .right_html{
        background: none;
    }
    .title_left{
        left:0.26rem;
    }
    .title_right{
        right:0.26rem;

    }
    .block_titile1{
        width:3.78rem;
    }
    .block_titile2{
        width:2.98rem;
    }
    .title_rotate{
        transform:rotate(180deg);
    }
    .block_titile3{
        width:3.66rem;
    }
    .block_titile4{
        width:4.2rem;
    }
    .join_box .join_btn{
        width: 100%;
        height:1.09rem;
        background:rgba(86, 196, 254, 1);
        color:#fff;font-size: 0.36rem;
        line-height:1.09rem; text-align: center;  position: fixed; bottom:0;
        z-index: 1000;
    }

    .join_box .join_btn:active{   background: rgb(82, 181, 239);}
    .service{ height: 1.10rem; width:1.44rem; background: url(http://oss.xqzs.cn/resources/psy/join_service.png) no-repeat #fff; background-size:0.60rem; background-position: 0.48rem 0.07rem;  border-radius:0.55rem;box-shadow: -0.08rem 0.04rem 0.12rem #ddd; position: fixed; right:0; bottom:1.70rem; border-bottom-right-radius: 0; border-top-right-radius: 0; z-index: 100}
    .service:active{
        background: url(http://oss.xqzs.cn/resources/psy/join_service.png) no-repeat #f1f1f1; background-size:0.60rem; background-position: 0.48rem 0.07rem;
    }
    .service span{ margin-top: 0.61rem; margin-left:0.07rem;display: inline-block; width: 100%; text-align: center;color:rgba(69,75,84,1); font-size: 0.24rem;}


    .ewm_box{ position: fixed; top:50%; left:10%; width: 80%;background: #fff; border-radius: 0.27rem; margin-top: -3.40rem; height: 6.80rem; z-index: 1000}
    .ewm_box .ewm{width: 4.76rem;
        margin: 0.51rem auto 0;
    }
    .ewm_box .ewm .info{ text-align: center; color:#454B54; font-size: 0.30rem;}
    .ewm_box .ewm img{ width: 100%;}


</style>
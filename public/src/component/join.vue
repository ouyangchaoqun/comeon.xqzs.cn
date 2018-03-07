<template >
    <div class="join_box">

        <div v-title>平台介绍</div>
        <div class="top"></div>
        <div class="detail">
            <div class="center">  <div class="title">好一点平台介绍</div></div>
            <div class="content">
                <p>好一点是一个在线心理咨询平台，致力于让用 户与咨询师之间的沟通更加便捷、高效。</p>
                <p><b>特色模式：</b>每个问题仅需60秒语音回答，即可 获得提问酬金。还能获得额外的偷听分成，一 次回答，收益不断。 </p>
                <p><b>咨询话题领域：</b>情感恋爱、个人成长、婚姻家 庭、心理健康、人际关系、职场事业、情绪管 理、亲子教育、性心理等。</p>
            </div>
            <div class="center">  <div class="title">入驻标准</div></div>

            <ul class="content">
                <li><span>1</span>须持有心理咨询师资质证书；</li>
                <li><span>2</span>须填写本人有效手机号码；</li>
                <li><span>3</span>有专业咨询经验优先。</li>
            </ul>
            <div class="center"><div class="title">入驻流程</div></div>
            <div class="btn">点击底部立即入驻</div>
            <div class="tip">关注“好一点”公众号</div>
            <div class="down"></div>
            <div class="btn">填写个人资料</div>
            <div class="down mt"></div>
            <div class="btn">提交审核</div>
            <div class="tip">（1个工作日内审核完成，即入驻成功）</div>


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

                        _this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {//es5写法
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
                        }, function (error) {

                        });

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
        background: #F8C95F;height: 100%;
    }
    .join_box .top{  height: 8.64rem; background: url(http://oss.xqzs.cn/resources/psy/join_top.png)  no-repeat; width: 100%; background-size: 100%}
    .join_box .detail{ background: #fff; border-radius:0.20rem; margin: 0  0.26rem; padding-bottom:0.99rem; margin-bottom: 2.48rem; }
    .join_box .center{ text-align: center}
    .join_box .title{ font-size: 0.40rem; color:#454B54; display: inline-block; position: relative; margin: 0.60rem auto 0.40rem ; font-weight: bold}
    .join_box .title:after{width: 100%;
        height:0.20rem;
        background:rgba(254,122,3,0.2);
        position: absolute; bottom:0.07rem; content:''; left:0
    }
    .join_box .detail p{ margin: 0.34rem 0;}
    .join_box .content{ margin: 0 0.50rem; color:rgba(69,75,84,0.7); line-height: 0.51rem }
    .join_box .content b{ color:rgba(69,75,84,1); font-weight: bold }
    .join_box ul.content li span{ color:#fff; width:0.42rem;
        height:0.42rem;
        border-radius: 50%;
        background:rgba(254,122,3,0.7);display: inline-block; text-align: center; line-height:  0.42rem; margin-right: 0.20rem;
    }
    .join_box ul.content li{ margin: 0.17rem 0}


    .join_box .btn{
        width:5.46rem;
        height:0.98rem;
        background:rgba(252,241,222,1);
        border-radius: 0.10rem;
        border: dashed #F5A623 1px;
        text-align: center; line-height: 0.98rem;
        margin: 0 auto;
        color:#FE7A03 ; font-size: 0.40rem;  font-weight: bold;
    }
    .join_box .tip{color:rgba(69,75,84,0.6); font-size: 0.30rem; text-align: center; margin: 0.14rem   auto; }
    .join_box .down { height:0.30rem; width:0.38rem ; background: url(http://oss.xqzs.cn/resources/psy/join_down.png) no-repeat; background-size: 0.38rem; margin: 0 auto; margin-bottom: 0.41rem;  }
    .join_box .down.mt{ margin-top: 0.41rem;}
    .join_box .join_btn{
        width: 100%;
        height:1.09rem;
        background:rgba(254,122,3,1);
        color:#fff;font-size: 0.36rem;
        line-height:1.09rem; text-align: center;  position: fixed; bottom:0;
    }

    .join_box .join_btn:active{   background: rgb(218, 108, 3);}
    .service{ height: 1.10rem; width:1.44rem; background: url(http://oss.xqzs.cn/resources/psy/join_service.png) no-repeat #fff; background-size:0.60rem; background-position: 0.48rem 0.07rem;  border-radius:0.55rem; box-shadow: -4px 2px 6px #ddd; position: fixed; right:0; bottom:1.70rem; border-bottom-right-radius: 0; border-top-right-radius: 0; }
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
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





        }


    }
</script>
<style>
    .join_box{
        background: #F8C95F;height: 100%;
    }
    .join_box .top{ height: 25.411764705882352941176470588235rem; background: url(http://oss.xqzs.cn/resources/psy/join_top.png)  no-repeat; width: 100%; background-size: 100%}
    .join_box .detail{ background: #fff; border-radius:0.58823529411764705882352941176471rem; margin: 0  0.76470588235294117647058823529412rem; padding-bottom:2.9rem; margin-bottom: 7.29rem; }
    .join_box .center{ text-align: center}
    .join_box .title{ font-size: 1.1764705882352941176470588235294rem; color:#454B54; display: inline-block; position: relative; margin: 2rem auto ; font-weight: bold}
    .join_box .title:after{width: 100%;
        height:0.58823529411764705882352941176471rem;
        background:rgba(254,122,3,0.2);
        position: absolute; bottom:0.2rem; content:''; left:0
         }
    .join_box .detail p{ margin: 1rem 0;}
    .join_box .content{ margin: 0 1.4705882352941176470588235294118rem; color:rgba(69,75,84,0.7); line-height: 1.5rem }
    .join_box .content b{ color:rgba(69,75,84,1); font-weight: bold }
    .join_box ul.content li span{ color:#fff; width:1.2352941176470588235294117647059rem;
        height:1.2352941176470588235294117647059rem;
        border-radius: 50%;
        background:rgba(254,122,3,0.7);display: inline-block; text-align: center; line-height:  1.2352941176470588235294117647059rem; margin-right: 0.6rem;
       }
    .join_box ul.content li{ margin: 0.5rem 0}


    .join_box .btn{
        width:16.058823529411764705882352941176rem;
        height:2.8823529411764705882352941176471rem;
        background:rgba(252,241,222,1);
        border-radius: 0.29411764705882352941176470588235rem;
        border: dashed #F5A623 1px;
        text-align: center; line-height: 2.888823529411764705882352941176471rem;
        margin: 0 auto;
        color:#FE7A03 ; font-size: 1.1764705882352941176470588235294rem;  font-weight: bold;
    }
    .join_box .tip{color:rgba(69,75,84,0.6); font-size: 0.88235rem; text-align: center; margin: 0.4rem   auto; }
    .join_box .down { height:0.88235rem; width:1.1176470588235294117647058823529rem ; background: url(http://oss.xqzs.cn/resources/psy/join_down.png) no-repeat; background-size: 1.1176470588235294117647058823529rem; margin: 0 auto; margin-bottom: 1.2rem;  }
    .join_box .down.mt{ margin-top: 1.2rem;}
    .join_box .join_btn{
        width: 100%;
        height:3.2rem;
        background:rgba(254,122,3,1);
        color:#fff;font-size: 1.0588235294117647058823529411765rem;
        line-height:3.2rem; text-align: center;  position: fixed; bottom:0;
    }

    .join_box .join_btn:active{   background: rgb(218, 108, 3);}
    .service{ height: 3.2352941176470588235294117647059rem; width:4.2352941176470588235294117647059rem; background: url(http://oss.xqzs.cn/resources/psy/join_service.png) no-repeat #fff; background-size:1.7647058823529411764705882352941rem; background-position: 1.4rem 0.2rem;  border-radius:1.6176470588235294117647058823529rem; box-shadow: -4px 2px 6px #ddd; position: fixed; right:0; bottom:5rem; border-bottom-right-radius: 0; border-top-right-radius: 0; }
    .service:active{
        background: url(http://oss.xqzs.cn/resources/psy/join_service.png) no-repeat #f1f1f1; background-size:1.7647058823529411764705882352941rem; background-position: 1.4rem 0.2rem;
    }
    .service span{ margin-top: 1.8rem; margin-left:0.2rem;display: inline-block; width: 100%; text-align: center;color:rgba(69,75,84,1); font-size: 0.70588235294117647058823529411765rem;}


     .ewm_box{ position: fixed; top:50%; left:5%; width: 90%;background: #fff; border-radius: 0.8rem; margin-top: -10rem; height: 20rem; z-index: 1000}
     .ewm_box .ewm{width: 14rem;
        margin: 1.5rem auto 0;
    }
   .ewm_box .ewm .info{ text-align: center; color:#454B54; font-size: 0.88235rem;}
   .ewm_box .ewm img{ width: 100%;}


</style>
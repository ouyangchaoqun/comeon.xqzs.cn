<template >
    <div style="height: 100%" class="answer_my_index_box">
        <div v-title>我的</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="weui-tab__panel main">
            <div class="main">
                <div class="top" @click="goPerfect()">
                        <img class="img" :src="resizeImg(expertUpdatefaceUrl)">
                        <div class="name">
                            {{expertUpdateName}}
                        </div>
                        <div class="perfect">完善资料</div>
                    <div class="revamp"></div>
                        <div class="clear"></div>
                </div>
                <div class="income_margin">


                    <a  class="my_nav" @click="goExpertDetail()" >
                        <div class="my_diandou"></div>
                        <p>我的主页</p>
                    </a>
                    <router-link to="../../../asker/my/cash" class="my_nav">
                        <div class="my_money"></div>
                        <p>我的余额</p>
                    </router-link>
                    <router-link to="/answer/my/answer/list" class="my_nav">
                        <div class="my_ask"></div>
                        <p>我的回答</p>
                    </router-link>
                </div>
                <div class="main_lists">
                    <router-link to = "/answer/my/fans" class="fans" ><i></i>我的粉丝</router-link>
                    <router-link to = "/answer/my/comment/list" class="comment_list" ><i></i>收到的评价</router-link>
                    <!--<router-link to = "/answer/my/message" class="message" ><i></i>60”语音寄语</router-link>-->

                    <router-link to = "/answer/join/joinstep" class="prefffff" ><i></i>完善专业资料</router-link>
                    <!--<router-link to = "/answer/join/level?edit=1" class="qualification" ><i></i>更新证书</router-link>-->
                </div>
            </div>
        </div>
        <v-asker-bottom tabOnIndex="2" ></v-asker-bottom>
    </div>
</template>

<script type="">

    import askerBottom from "../include/bottom.vue";
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                user:'',
                income:0,
                showLoad:false,
                expertUpdateName:'',
                expertUpdatefaceUrl:'',
            }
        },
        props:{
            expert:{
                type:Object
            }
        },
        components: {
            "v-asker-bottom": askerBottom,
            'v-showLoad':showLoad
        },

        activated: function () {
            this.getUserInfo();
            this.getExpertUpdateInfo();
            var obj =  $(".asker_my_index_box .main a")
            xqzs.weui.active(obj);

            this.getExpertByUserId();
            this.getIncome();
            xqzs.wx.setConfig(this)

        },
        methods:{
            getUserInfo:function () {
                let _this = this;
                _this.$http({
                    method: 'GET',
                    type: "json",
                    url: web.API_PATH + 'user/find/by/user/Id/_userId_',
                }).then(function (data) {//es5写法
                    if (data.data.data !== null) {
                        _this.user = eval(data.data.data);
                    }
                }, function (error) {
                    //error
                });
            },
            goExpertDetail:function () {
                this.$router.push("../../../asker/expert/detail?isMe=1&&id="+this.expert.id);
            },
            resizeImg:function (v) {
                return   xqzs.oss.resizeImg(v,100,100)
            },
            formatPrice:function(price){
                return  xqzs.string.formatPrice(price)
            },
            goPerfect:function () {
                this.$router.push("./perfect")
            },
            goJoin:function () {
                this.$router.push("/answer/join/joinstep");
                //this.$router.push("/answer/join/join_update");
            },
            getExpertUpdateInfo:function () {
                this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        this.expert = data.data.data;
                        this.expertUpdateName = this.expert.nickName;
                        this.expertUpdatefaceUrl = this.expert.faceUrl;
                    }
                }, function (error) {
                });
            },
            getIncome:function () {

                let _this= this;
                let expertId= cookie.get("expertId")
                _this.$http.get(web.API_PATH + 'come/expert/query/income/'+expertId+'/_userId_' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.erpertId=data.body.data.id;
                        _this.income= data.body.data.inCome
                    }
                }, function (error) {
                });

            },
            getExpertByUserId:function () {
                let _this=this;
                if(_this.expert.id&&_this.expert.id!=null){

                }else{
                    _this.getExpertUpdateInfo()
                }

            }

        }


    }
</script>
<style>
    .answer_my_index_box{background: #F4F4F7}
    .answer_my_index_box .top{ height:  1.08rem; line-height:  1; font-size: 0.36rem;
        background:rgba(121,207,253,1);
        background:linear-gradient(to right,rgba(121,207,253,1),rgba(157,167,251,1));
        padding:0.5rem;}
    .answer_my_index_box .top:after{background: url(http://oss.xqzs.cn/resources/psy/arrow.png) ;width: 0.32rem; height: 0.32rem; background-size: 0.32rem;position: absolute; right:0.30rem;top:1.12rem;margin-top:-0.16rem; content: " "}
    /*.answer_my_index_box .top:active{background: linear-gradient(to right, rgb(238, 148, 25), rgb(226, 92, 6));}*/
    .answer_my_index_box .top img{ display: block;
        float: left;
        width: 1.08rem;
        height: 1.08rem;
        margin-right: 0.33rem;
        border: solid 0.08rem rgba(255,255,255,0.5);
        border-radius: 50%;}
    .answer_my_index_box .top .revamp {
        background: url(http://oss.xqzs.cn/resources/psy/asker/ask_fix.png) no-repeat;
        background-size: 100%;
        width: 0.3rem;
        height: 0.3rem;
        position: absolute;
        top: 1.12rem;
        left: 3.1rem;
    }
    .answer_my_index_box .top img{ display: block; float:left;     width: 1.08rem;
        height: 1.08rem;margin-right: 0.25rem;border-radius: 50%;}
    .answer_my_index_box .top .name{color:rgba(255,255,255,1);padding-top: 0.2rem; margin-bottom:0.2rem;font-weight: bold; font-size: 0.3rem; height: 0.3rem; overflow: hidden}
    .answer_my_index_box .top .perfect{color:rgba(255,255,255,1);font-size: 0.24rem;}
    .main_lists a{ position: relative; height:1.18rem;    line-height:1.18rem;
        color:rgba(36,37,61,1); font-size: 0.30rem; padding:  0  0.30rem;display: block; overflow: hidden; padding-left: 0.90rem; background: #fff;  }
    .main_lists a:after{ background: url(http://oss.xqzs.cn/resources/psy/arrow.png) ; width: 0.32rem; height: 0.32rem; background-size: 0.32rem; position: absolute;
        right:0.30rem;top:0.42rem ; content: " "}
    .main_lists a:before{ content: " "; height: 0.02rem; background: #eee; display: block; position: absolute; bottom:0;left: 1rem; width: 100%}
    .main_lists .income .price{position: absolute; right:0.75rem;top:0.00rem;color:#56C4FE}
    .main_lists a i{ display: block;width:0.40rem; height:0.40rem;  position: absolute; left: 0.26rem; top:50% ; margin-top: -0.20rem;}
    .main_lists a.income i{background: url("http://oss.xqzs.cn/resources/psy/answer/sy_icon.png") no-repeat;background-size: 100% 100%;}
    .main_lists a.level i{ background-position: 0 -0.58rem; }
    .main_lists a.setanswer i{}
    .main_lists a.answer_list i{ background: url("http://oss.xqzs.cn/resources/psy/answer/hd_icon.png") no-repeat;background-size: 100% 100%;}
    .main_lists a.fans i{ background: url("http://oss.xqzs.cn/resources/psy/answer/ex_fans.png") no-repeat;background-size: 100% ;}
    .main_lists a.comment_list i{ background: url("http://oss.xqzs.cn/resources/psy/answer/ex_comment.png") no-repeat;background-size: 100% 100%;}
    .main_lists a.message i{ background-position: 0 -3.48rem; }
    .main_lists a.qualification i{background: url("http://oss.xqzs.cn/resources/psy/answer/ex_update.png") no-repeat center; background-size: 0.40rem}
    .main_lists a.prefffff i{background: url("http://oss.xqzs.cn/resources/psy/answer/ex_prefffff.png") no-repeat center; background-size: 0.40rem}





    .main_lists a:active{background: #eee}
    .answer_my_index_box a.my_nav{
        width: 33.33%;
        height: 1.8rem;
        background: #fff;
        position: static;
        line-height: 1.6;
        color: rgba(36, 37, 61, 1);
        font-size: 0.28rem;
        padding: 0;
        display: block;
    }
    .answer_my_index_box a.my_nav:after{
        content: normal;
    }
    .answer_my_index_box a.my_nav:before{
        content: normal;
    }
    .answer_my_index_box a.my_nav div{
        width: 0.64rem;
        margin: 0 auto;
        margin-top: 0.4rem;
        height: 0.64rem;
    }
    .answer_my_index_box a.my_nav .my_money{
        width: 0.58rem;
        height: 0.66rem;
        background: url("http://oss.xqzs.cn/resources/psy/answer/ex_money.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
    }
    .answer_my_index_box  a.my_nav .my_diandou{
        background: url("http://oss.xqzs.cn/resources/psy/answer/ex_myIndex.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
    }
    .answer_my_index_box a.my_nav .my_ask{
        background: url("http://oss.xqzs.cn/resources/psy/answer/ex_answer.png") no-repeat;
        -webkit-background-size:100%;
        background-size:100%;
    }
    .answer_my_index_box  a.my_nav p{
        text-align: center;
    }
    .answer_my_index_box .income_margin{
        margin-bottom: 0.2rem;
        display: flex;
        overflow: hidden;
    }



</style>

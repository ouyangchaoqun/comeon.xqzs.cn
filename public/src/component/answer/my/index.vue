<template >
    <div style="height: 100%" class="answer_my_index_box">
        <div v-title>我的</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="weui-tab__panel main">
            <div class="main">
                <div class="top" @click="goJoin()">
                        <img class="img" :src="resizeImg(expert.faceUrl)">
                        <div class="name">
                            {{expert.nickName}}
                        </div>
                        <div class="perfect">完善资料</div>
                        <div class="clear"></div>
                </div>
                <div class="main_lists">
                    <router-link to = "/answer/my/income"  class="income"  ><i></i>我的余额
                        <div class="price">￥{{formatPrice(user.balance)}}</div>
                    </router-link>
                    <!--<router-link to = "/answer/my/level" class="level" ><i></i>我的等级</router-link>-->
                    <!--<router-link to = "/answer/my/setanswer" class="setanswer" ><i></i>解答设置</router-link>-->
                    <router-link to = "/answer/my/answer/list" class="answer_list" ><i></i>我的回答</router-link>
                    <router-link to = "/answer/my/fans" class="fans" ><i></i>我的粉丝</router-link>
                    <router-link to = "/answer/my/comment/list" class="comment_list" ><i></i>收到的评价</router-link>
                    <!--<router-link to = "/answer/my/message" class="message" ><i></i>60”语音寄语</router-link>-->
                    <router-link to = "/answer/join/level?edit=1" class="qualification" ><i></i>更新资质</router-link>
                </div>
            </div>
        </div>
        <v-asker-bottom tabOnIndex="2" ></v-asker-bottom>
    </div>
</template>

<script type="es6">

    import askerBottom from "../include/bottom.vue";
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                user:'',
                income:0,
                showLoad:false,
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

        mounted: function () {
            this.getUserInfo()
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
                        console.log( _this.user )
                    }
                }, function (error) {
                    //error
                });
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
                this.showLoad = true;
                this.$router.push("/answer/join/joinstep");
                //this.$router.push("/answer/join/join_update");
            },
            getIncome:function () {

                let _this= this;
                let expertId= cookie.get("expertId")
                _this.$http.get(web.API_PATH + 'come/expert/query/income/'+expertId+'/_userId_' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.income= data.body.data.inCome
                    }
                }, function (error) {
                });

            },
            getExpertByUserId:function () {
                let _this=this;
                if(_this.expert.id&&_this.expert.id!=null){

                }else{
                    this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {//es5写法
                        if (data.body.status == 1) {

                            _this.expert = data.data.data;
                            console.log(_this.expert)
                        }
                    }, function (error) {
                    });
                }

            }

        }


    }
</script>
<style>
    .answer_my_index_box{background: #fff}
    .answer_my_index_box .top{ height:  1.20rem; line-height:  1; font-size: 0.36rem; background: linear-gradient(to right, rgba(255,158,25,1), rgba(253,114,6,1)); padding:0.30rem;border-bottom: 0.20rem solid #f4f4f7;position: relative}
    .answer_my_index_box .top:after{background: url(http://oss.xqzs.cn/resources/psy/arrow.png) ;width: 0.32rem; height: 0.32rem; background-size: 0.32rem;position: absolute; right:0.30rem;top:50%;margin-top:-0.16rem; content: " "}
    .answer_my_index_box .top:active{background: linear-gradient(to right, rgb(238, 148, 25), rgb(226, 92, 6));}
    .answer_my_index_box .top img{ display: block; float:left; width:1.20rem; height: 1.20rem;margin-right: 0.33rem;border-radius: 0.10rem;}
    .answer_my_index_box .top .name{color:rgba(255,255,255,1);padding-top: 0.28rem;margin-bottom:0.18rem}
    .answer_my_index_box .top .perfect{color:rgba(255,255,255,1);font-size: 0.26rem;}
    .main_lists a{ position: relative; height:1.18rem;    line-height:1.18rem;
        color:rgba(36,37,61,1); font-size: 0.30rem; padding:  0  0.30rem;display: block; overflow: hidden; padding-left: 0.90rem; background: #fff;  }
    .main_lists a:after{ background: url(http://oss.xqzs.cn/resources/psy/arrow.png) ; width: 0.32rem; height: 0.32rem; background-size: 0.32rem; position: absolute;
        right:0.30rem;top:0.42rem ; content: " "}
    .main_lists a:before{ content: " "; height: 0.02rem; background: #eee; display: block; position: absolute; bottom:0;left: 0.32rem; width: 100%}
    .main_lists .income .price{position: absolute; right:0.75rem;top:0.00rem;color:#FE7301}
    .main_lists a i{ display: block;width:0.50rem; height:0.50rem;  position: absolute; left: 0.26rem; top:50% ; margin-top: -0.29rem;}
    .main_lists a.income i{background: url("http://oss.xqzs.cn/resources/psy/answer/sy_icon.png") no-repeat;background-size: 100% 100%;}
    .main_lists a.level i{ background-position: 0 -0.58rem; }
    .main_lists a.setanswer i{}
    .main_lists a.answer_list i{ background: url("http://oss.xqzs.cn/resources/psy/answer/hd_icon.png") no-repeat;background-size: 100% 100%;}
    .main_lists a.fans i{ background: url("http://oss.xqzs.cn/resources/psy/answer/fs_icon.png") no-repeat;background-size: 100% 100%;}
    .main_lists a.comment_list i{ background: url("http://oss.xqzs.cn/resources/psy/answer/pj_icon.png") no-repeat;background-size: 100% 100%;}
    .main_lists a.message i{ background-position: 0 -3.48rem; }
    .main_lists a.qualification i{background: url("http://oss.xqzs.cn/resources/psy/answer/update_level_icon.png") no-repeat center; background-size: 0.40rem}
    .main_lists a:active{background: #eee}




</style>

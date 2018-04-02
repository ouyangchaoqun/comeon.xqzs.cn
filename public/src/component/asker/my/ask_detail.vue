<template id="my_problem_detail">
    <div class="ask_detailBox">
        <div v-title>问题详情</div>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="my_problem_detail">
            <!--<div class="problem_detail_header">-->
            <!--问题类型:  <div class="titleColor">{{detail.title}}</div>-->
            <!--&lt;!&ndash;&ndash;&gt;-->
            <!--</div>-->
            <div class="add_askerInfo">
                <img :src="user.faceUrl" alt="">
                <span>{{user.nickName}}</span>
                <div>{{detail.title}}</div>  <!--￥{{formatPrice(detail.price)}}-->
            </div>
            <div class="problem_detail_content">
                {{detail.question}}
            </div>
            <div class="problem_wait_style">
                <!--待回答-->
                <div v-if="detail.questionStatus==0">
                    <span>待回答</span>  <span class="last_red_color">还{{formatTimeLastText(detail.endTime)}}</span>
                </div>
                <!--超时未回答-->
                <div v-if="detail.questionStatus==2">
                    <span>未解答</span>  <span>超过48小时，咨询师未回答你的问题，赏金已全额退还至你的微信钱包！</span>
                </div>
                <!--解答-->
                <div v-if="detail.questionStatus==1">
                    <span>已解答  <span class="last_red_color" v-if="detail.answers[0].evaluate.id==null&&!isOver"> 去评价老师的回答，你将免费获得1颗点豆！</span></span>
                </div>
            </div>


            <div class="steal_expert_info" :class="{answerInfo_border:detail.answerCount==0}">
                <img :src="detail.expert.faceUrl" alt="" @click="goDetail(detail.expertId)">
                <div>
                    <span class="steal_expert_name" @click="goDetail(detail.expertId)">{{detail.expert.nickName}}</span><span
                        class="steal_expert_fans">{{followCount}} 人关注</span>
                </div>
                <div class="steal_expert_des">{{detail.expert.sign}}</div>
                <div class="followed_box" v-if="!detail.expert.isFollow" @click="follow(detail.expertId)">关注</div>
                <div class="followed_box isfollow_style"  v-if="detail.expert.isFollow" @click="follow(detail.expertId)" >已关注</div>
            </div>
            <template v-for="(item,index) in detail.answers">
                <div class="problem_answer_info" >
                    <!--回答，专家语音-->
                    <div class="problem_answer_yy" v-if="detail.questionStatus==1">
                        <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                            <div class="audio_btn" @click.stop="play(index)">
                                <span v-if="!item.playing&&!item.paused">点击播放</span>
                                <span v-if="item.playing">正在播放..</span>
                                <span v-if="item.paused">播放暂停</span>
                                <div class="second">{{(item.ct && item.ct!='00')?item.ct:item.voiceLength}}”</div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="yy_bottomBorder"></div>
                <!--回答后底部显示详情-->
                <div class="problem_answer_bottom" v-if="detail.answerCount>0">
                    <div class="problem_answer_time">{{formatDateText(item.addTime)}}</div>
                    <div class="problem_answer_zan">
                        <div><span>听过</span> <span>{{item.ListenTimes}}</span></div>
                        <div @click="like(index)" class="good_care" :class="{good_cared:item.isLiked}"><span>{{item.likeTimes}}</span></div>
                    </div>
                </div>
                <div style="height:0.20rem;background: #f4f4f7"></div>
            </template>
        </div>


        <!--匿名评价-->
        <div class="problem_assess" v-if="detail.answers&&detail.answers.length>0&&detail.answers[0].evaluate&&detail.answers[0].evaluate.id==null&&!isOver"><!---->
            <h4>评价</h4>
            <div class="star">
                <div v-for="(item,index) in comText">
                    <span :class="{on:item.click<=point}" @click="clickStar(item.click)" >
                    </span>
                    <div :class="{color_on:item.click==point}">{{item.text}}</div>
                </div>

            </div>
            <div class="problem_assess_item">
                <div class="problem_assess_input">
                    <div class="comment_anonymous" :class="{comment_anonymous_on:anonFlag}" @click="getAnony()">
                        匿名
                    </div>
                    <textarea placeholder="分享你的咨询感受" id="content"></textarea>
                    <!--<div class="addIsOverHtml" v-show="isOver">{{contentOver}}</div>-->
                </div>
            </div>
            <div class="problem_assess_btn">
                <div class="weui-btn weui-btn_disabled weui-btn_primary" v-if="point==0">提交</div>
                <div class="weui-btn weui-btn_primary"  @click="comment()" v-if="point>0">提交</div>
            </div>
        </div>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    var my_problem_detail = {
        template: '#my_problem_detail'
    };

    export default {
        data() {
            return {
                problem_assess_flag:true,
                detail:{
                    expert:{}
                },
                point:0,
                tags:[],
                MAX_TAG_COUNT:5,
                comText:[{click:1,text:'极差'},{click:2,text:'不满意'},{click:3,text:'一般'},{click:4,text:'满意'},{click:5,text:'非常满意'}],
                isOver:false,
                contentOver:'',
                showLoad: false,
                followCount:0,
                anonFlag:false,
                timeInterval:null
            }
        },
        props:{
            user:{
                type:Object
            }
        },
        activated: function () {
            if(!xqzs.user.isUserLogin()){
                return ;
            }
            this.id= parseInt(this.$route.query.id);
            this.getDetail();
//            this.getTags();
            xqzs.wx.setConfig(this, function () {weshare.init(wx)});
            console.log(this.user)
        },
        methods: {
            timeIntervalFun:function () {
                let _this=this;
                if(_this.timeInterval!=null){
                    clearInterval(_this.timeInterval);
                }
                _this.timeInterval=   setInterval(function () {
                    _this.detail.endTime= _this.detail.endTime + 1;
                    _this.detail.endTime= _this.detail.endTime - 1
                },1000)
            },
            getAnony:function () {
               this.anonFlag = !this.anonFlag
            },
            goDetail:function (extId) {
                this.$router.push('/asker/expert/detail/?id='+extId)
            },
            like:function (index) {
                let  item = this.detail.answers[index];
                if(item.isLiked){
                    xqzs.weui.tip("已经点赞");
                    return ;
                }
                let _this=this;
                xqzs.api.put(_this,"come/user/like/answer/_userId_/"+item.id,{},function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        item.isLiked=1;
                        item.likeTimes=parseInt(item.likeTimes)+1;
                        _this.$set(_this.detail.answers,index,item);
                    }else if(bt.data.status==920006){
                        xqzs.weui.tip("已经点赞");

                    }
                })
            },
            formatPrice:function (v) {
                return xqzs.string.formatPrice(v)
            },



            timeout:function (play,time,index) {
                let _this=this;
                _this.timeOut = setTimeout(function () {
                    if(play==true){  //试听
                        if(time>0){
                            time = time -1 ;
                            if(time<10)time="0"+time
                            _this.timeout(play,time,index);
                        }else{
                            _this.playing=false;
                        }
                    }

                },1000);

                _this.detail.answers[index].ct =time;
                console.log(time)
                _this.$set(_this.detail.answers,index,_this.detail.answers[index])
            },

            clearTimeOut:function () {
                let _this=this;
                if(_this.timeOut!==null){
                    clearTimeout(_this.timeOut);
                }
            },
            play:function (index) {

                let _this=this;
                let list = _this.detail.answers;
                console.log(list[index]);
                 let CT= list[index].ct? list[index].ct: list[index].voiceLength;
                let T = list[index].voiceLength;
                 xqzs.voice.onEnded=function () {
                    list[index].paused=false;
                    list[index].playing=false;
                    if(_this.playing)_this.clearTimeOut();
                    _this.$set(_this.detail.answers,index,list[index])
                };

                //重置其他列表内容
                for(let i = 0;i<list.length;i++){
                    if(index!=i&&(list[i].playing||list[i].paused)){
                        list[i].paused=false;
                        list[i].playing=false;
                        _this.$set(_this.detail.answers,i,list[i]);
                    }
                }
                let item= list[index];
                if(item.paused){  //暂停中也就是已经获取到且为当前音频
                    list[index].paused=false;
                    list[index].playing=true;
                    _this.$set(_this.detail.answers,index,list[index])
                    xqzs.voice.play();
                    _this.timeout(true,CT,index)
                }else{
                    if(item.playing){    //播放中去做暂停操作
                        list[index].paused=true;
                        list[index].playing=false;
                        _this.$set(_this.detail.answers,index,list[index])
                        xqzs.voice.pause();
                        _this.clearTimeOut();
                    }else{     //重新打开播放
                        let answerId= item.id;
                        xqzs.voice.getAnswerVoice(answerId,function (url) {
                            xqzs.voice.play(url);
                            list[index].playing=true;
                            list[index].paused=false;
                            _this.$set(_this.detail.answers,index,list[index]);
                            _this.clearTimeOut();
                            _this.timeout(true,T,index)
                        })
                    }

                }

            },


//            selectTag:function (index) {
//
//                let count= 0;
//                let tags=this.tags;
//                if(tags[index].selected==true){
//                    tags[index].selected=false;
//                }else{
//                    for(let i=0;i<tags.length;i++){
//                        if(tags[i].selected==true){
//                            count++;
//                        }
//                    }
//                    if(count>=this.MAX_TAG_COUNT){
//
//                    }else{
//                        tags[index].selected=true;
//                    }
//                }
//
//                this.$set(this.tags,index,tags[index])
//
//            },
//            getTags:function () {
//
//                let _this= this;
//                _this.$http.get(web.API_PATH + 'come/expert/evaluate/tag').then(function (data) {//es5写法
//                    if (data.body.status == 1) {
//
//                        _this.tags= data.body.data
//                    }
//                }, function (error) {
//                });
//
//            },
            clickStar:function (v) {
                console.log(v)
                this.point=v;
            },
            formatDateText:function (time) {
                return xqzs.dateTime.getTimeFormatText(time)
            },
            formatTimeLastText:function (time) {
                return xqzs.dateTime.getTimeFormatLastText(time)
            },
            comment:function () {
                let that=this;
                let content = $("#content").val();
                let anonyVal;
                if(this.anonFlag){
                    anonyVal = 1;
                }else{
                    anonyVal = 0;
                }
                //that.contentOver = content;
                if(this.point==0){
                    xqzs.weui.toast('fail',"请选择分数",function () {

                    })
                    return;
                }

//                for(let i=0;i<this.tags.length;i++){
//                    if(this.tags[i].selected==true){
//                        tags .push(this.tags[i].id);
//                    }
//                }
//                if(tags.length==0){
//                    xqzs.weui.toast('fail',"请至少选择一个标签",function () {
//
//                    })
//                    return;
//                }
//                if(content.length==0){
//                    xqzs.weui.toast('fail',"请输入评论内容",function () {
//
//                    })
//                    return;
//                }

                xqzs.api.put(that,"come/user/evaluate/answer",{userId:"_userId_",answerId:this.detail.bestAnswerId, point:this.point,content:content,isAnonymous:anonyVal},function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        xqzs.weui.toast("success","评论成功",function () {
                            that.isOver = true;
                            console.log(that.isOver)
//                                window.location.href=window.location.href
                        })
                    }
                })
            },
            follow:function (id) {
                let that=this;
                xqzs.api.put(that, "come/expert/follow/expert/"+id+"/_userId_",{},function (bt) {
                    if (bt.data && bt.data.status == 1) {
                        if(that.detail.expert.isFollow==0){
                            that.detail.expert.isFollow=1;
                            that.followCount = that.followCount+1;
                            xqzs.weui.toast("success","关注成功")
                        }else {
                            that.detail.expert.isFollow=0;
                            that.followCount = that.followCount-1;
                            xqzs.weui.toast("success","取消成功")
                        }


                    }else if(bt.data.status ==900004){
                        xqzs.weui.tip("已经关注")
                    }else if(bt.data.status ==9000003){
                        xqzs.weui.tip("不能关注自己")
                    }else {
                        xqzs.weui.tip("关注失败")
                    }
                })
            },
            getDetail:function () {
                let _this= this;
                xqzs.api.get(_this,'come/user/query/question/_userId_/'+_this.id,function (data) {
                    if (data.body.status == 1) {
                        _this.showLoad = false
                        console.log(data.body.data.data)
                        _this.detail= data.body.data.data;
                        _this.followCount = _this.detail.expert.followCount;
                        console.log(_this.detail);
                        _this.timeIntervalFun()
                    }
                });
            }

        },
        components: {
            'v-showLoad': showLoad,
        },
        beforeDestroy:function () {
            xqzs.voice.pause();
        }


    }
</script>
<style>
    .problem_detail_inCome{
        position: absolute;
        right:0.30rem;
        top:0.30rem;
    }
    .ask_detailBox{
        background: #fff;
    }
    .ask_detailBox .good_care{
        margin-right:0;
    }
    .problem_detail_header .titleColor{
        color:rgba(102,102,153,1);
    }
    .ask_detailBox .problem_detail_header{
        line-height: 0.72rem;
        background: rgba(245,245,245,1);
        padding:0;
        padding-left: 0.30rem;
        margin-bottom: 0.30rem;
    }
    .ask_detailBox .my_problem_detail .add_askerInfo{
        line-height: 0.58rem;
        display: flex;
        font-size: 0.26rem;
        padding:0 0.30rem;
        position: relative;
        margin-bottom: 0.20rem;
        padding-top: 0.30rem;
    }
    .ask_detailBox .my_problem_detail .add_askerInfo img{
        width:0.58rem;
        height:0.58rem;
        border-radius: 50%;
        margin-right: 0.17rem;
    }
    .ask_detailBox .my_problem_detail .add_askerInfo span{
        color:rgba(51,51,51,0.5);
    }
    .ask_detailBox .my_problem_detail .add_askerInfo div{
        color:rgba(51,51,51,0.5);
        position: absolute;
        right:0.30rem;
    }
    .ask_detailBox .steal_expert_info{
        padding-left:1.42rem;
        padding-bottom: 0.51rem;
        border-top:0.20rem solid #f4f4f7;
    }
    .problem_wait_style{
        color:rgba(36,37,61,0.5);
        font-size: 0.24rem;
        line-height: 1;
        padding:0.30rem;
        position: relative;
    }
    .problem_wait_style span{
        margin-right: 0.30rem; line-height: 0.40rem
    }
    .ask_detailBox .answerInfo_border{border-bottom:0.02rem solid #eee;}
    .ask_detailBox .yy_bottomBorder{height:0;width:80%;margin: 0 auto;border-bottom: 0.02rem solid #eee;margin-bottom: 0.36rem;padding-top:0.51rem;}
    .ask_detailBox .problem_answer_bottom{padding:0;margin:0 auto;width:80%;margin-bottom: 0.28rem;}
    .problem_assess_btn .weui-btn{border-radius: 1rem;}
    .problem_assess .star>div{display: inline-block;line-height: 1;color:rgba(36,37,61,0.5);font-size: 0.22rem;margin-bottom: 0.63rem}
    .problem_assess .star span{ position:relative;display: inline-block;  height:0.56rem; width: 0.59rem; background: url(http://oss.xqzs.cn/resources/psy/starNew_no.png) no-repeat; background-size: 0.59rem; margin: 0.40rem  0.24rem 0.23rem 0.24rem;  }
    .problem_assess .star span.on{ background: url(http://oss.xqzs.cn/resources/psy/starNew.png) no-repeat; background-size: 0.59rem;}
    .problem_assess .star>div .color_on{color:rgba(253,198,10,1)}
    .ask_detailBox .problem_answer_info{
        padding:0 0.30rem;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        line-height: 0.96rem;
        font-size: 0.28rem;
        color: rgba(36,37,61,0.5);
    }
    .problem_assess{
        background: #fff;
        padding-top:0.40rem;
        font-size: 0.24rem;
        color: rgba(36,37,61,0.5);
        text-align: center;
        border-top: 0.14rem solid #f4f4f8;
        padding-bottom: 0.80rem;
    }
    .problem_assess h4{
        color: rgba(36,37,61,1);
        font-size: 0.30rem;
        font-weight: normal;
        line-height: 1;
    }
    .problem_assess img{
        display: inline-block;
        margin:0.20rem 0.07rem;
        width: 0.32rem;
    }
    .problem_assess_item span{
        padding:0 0.17rem;
        height:0.46rem;
        line-height: 0.46rem;
        border:0.02rem solid rgba(36,37,61,0.5);
        border-radius: 0.34rem;
        display: inline-block;
        margin-bottom:0.24rem;
        margin-right: 0.08rem;
    }
    .problem_assess_item span.on{
        border:0.02rem solid rgba(255,158,25,1);
        color:rgba(253,114,6,1);
    }
    .problem_assess_class{
        margin-bottom: 0.30rem;
        padding:0 0.72rem;
        text-align: left;
    }
    .problem_assess_input{
        width:92%;
        margin: 0 auto;
        position: relative;
        height:2.38rem;
        background: #EBEBEC;
        border-radius: 0.1rem;
        overflow: hidden;
        margin-bottom: 0.64rem;

    }
    .problem_assess_input .comment_anonymous{
        position: absolute;
        bottom:0.10rem;
        right:3%;
        color:RGBA(69, 75, 84, 0.49);
        font-size: 0.24rem;
        line-height: 0.34rem;
        background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_no.png") no-repeat left center;
        background-size: 0.28rem;
        padding-left: 0.34rem;
        height:0.32rem;
        z-index: 1000;
    }
    .problem_assess_input .comment_anonymous_on{
        background: url("http://oss.xqzs.cn/resources/psy/asker/user_income_on.png") no-repeat left center;
        background-size: 0.28rem;
    }
    .problem_assess_item textarea{
        height:1.43rem;
        background: #EBEBEC;
        resize: none;
        border:0;
        outline: none;
        width:94%;
        font-size: 0.24rem;
        color: rgba(36,37,61,1);
        padding:3% 3%;
        line-height: 1.6;
        letter-spacing: 0.04rem;
    }
    .problem_assess_btn{
        padding: 0 0.30rem;
    }
    .problem_wait_style>span:nth-of-type(1){
        font-size: 0.24rem;
        margin-right: 0.42rem;;
    }
    .problem_wait_style>span:nth-of-type(2){
        color: rgba(36,37,61,0.5) ;
        font-size: 0.24rem;
    }
    .problem_box_background{
        background: #F4F4F7;
    }
    .problem_answer_info .problem_answer_yy{margin:0 auto;}
    .problem_assess_input .addIsOverHtml{text-align: left;background: #EBEBEC;border-radius: 0.10rem;padding:0.10rem 0.30rem;}
    .ask_detailBox .addContentStyle{margin-bottom: 0}
</style>
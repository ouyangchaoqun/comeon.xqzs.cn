<template id="rob_problem">

    <div class="rob_problem">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title>问题详情</div>
        <div class="my_problem_detail">
            <div class="problem_detail_header">
                <!--问题类型:  <div class="race_titleColor">{{detail.title}}</div>-->
                <img :src="user.faceUrl" alt="">
                <div>{{user.nickName}}</div>
                <div>{{detail.title}}</div> <!--赏金￥{{detail.price}}-->
            </div>
            <div class="problem_detail_content">
                {{detail.question}}
            </div>

            <!--const QUESTION_CONTINUE = 0;//问题还在进行-->
            <!--const QUESTION_FINISH = 1;//问题结束有最佳答案-->
            <!--const QUESTION_FINISH_NOT_BEST = 2;//问题结束但是没有最佳答案-->
            <!--const QUESTION_NOT_PAY = 3;//问题还未支付-->
            <!--超时未解答-->
            <div class="rob_status_box" v-if="detail.questionStatus==2&&detail.answerCount==0">
                <div>未解答 </div>
                <div>48小时内无人抢答，赏金已全额退还。</div>
            </div>
            <!--正在进行中-->
            <div class="rob_status_box"  v-if="detail.questionStatus==0&&detail.answerCount==0">
                <div>还{{formatTimeLastText(detail.endTime)}} </div>
                <div>暂无人抢答，如果48小时内没有专家回答该问题，提问酬金将原路返还。</div>
            </div>
            <div class="rob_status_box" v-if="detail.questionStatus==0&&detail.answerCount!=0">
                <div>已解答 </div>
                <div>共有<span>{{detail.answerCount}}</span>人抢答，可选择一个最佳答案，其答主将获得全部赏金，并且你将获得{{set_award_dian_coin}}点豆奖励。</div>
            </div>
            <div class="rob_status_box" v-if="detail.questionStatus==1">
                <div>已解答 </div>
                <div>共有<span>{{detail.answerCount}}</span>人抢答，{{bestAnswer.expertNickName}}的回答被选为最佳答案,你已获得{{set_award_dian_coin}}点豆奖励。</div>
            </div>
            <div class="rob_status_box" v-if="detail.questionStatus==2">
                <div>已解答 </div>
                <div>共有<span>{{detail.answerCount}}</span>人抢答，抢答者平分赏金。</div>
            </div>


            <ul class="rob_lists">
                <li v-for="(item,index) in detail.answers">
                    <div class="comment_btn"  @click="showCommentBox()"  v-if="detail.bestAnswerId!=0&&detail.questionStatus!=0&&detail.bestAnswerId==item.answerId&&!detail.evaluate_ed">
                            去评价
                    </div>
                    <div class="rob_box_top">
                        <img :src="item.expertFaceUrl" alt="">
                        <div class="expertInfo">
                            <span>{{item.expertNickName}}</span>
                            <div><template v-for="(d,i) in item.domain">{{d.title}}<template v-if="i<item.domain.length-1">、</template></template></div>
                        </div>
                    </div>
                    <div class="rob_answer">
                        <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                            <div class="audio_btn" @click.stop="play(index)">
                                <template v-if="!item.playing&&!item.paused">点击播放</template>
                                <template v-if="item.playing">正在播放..</template>
                                <template v-if="item.paused">播放暂停</template>
                                <div class="second">{{item.voiceLength}}”</div>
                            </div>

                            <div class="clear"></div>
                            <div class="addBest_text" v-if="detail.bestAnswerId!=0&&detail.questionStatus!=0&&detail.bestAnswerId==item.answerId" >此回答为<a>最佳答案</a>，答主获得全部赏金</div>
                        </div>


                        <!--选择最佳答案选框-->
                        <div v-if="detail.bestAnswerId==0&&detail.questionStatus==0" @click="selectBestAnswerId(item)" class="best_answer" :class='[{best_answer_true:selBestAnswerId==item.answerId}]'></div>
                        <!--最佳确定-->
                        <div class="best_getMoney" v-if="detail.bestAnswerId!=0&&detail.questionStatus!=0&&detail.bestAnswerId==item.answerId" >
                            <div>￥{{detail.price}}</div>
                            <img src="../../../images/asker/getmoney.png" alt="">
                        </div>
                        <!--未选择最佳平分赏金-->
                        <div class="deuce_money_box" v-if="detail.bestAnswerId==0&&detail.questionStatus==2">
                            ￥{{detail.price/detail.answerCount}}
                        </div>
                    </div>
                    <div class="problem_answer_bottom rob_answer_bottom">
                        <div class="problem_answer_time">{{formatDateText(item.addTime)}}</div>
                        <div class="problem_answer_zan">
                            <div><span>听过</span> <span>{{item.ListenTimes}}</span></div>
                            <div @click="like(index)" class="good_care" :class="{good_cared:item.isLiked}"><span> {{item.likeTimes}}</span></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div id="comment_box" style="display: none">
            <div class="comment_box2">
                <ul class="stars">
                    <li  v-for="(item,index) in comments"  :v="item.v" :class="{on:item.v<=commentValue}" >
                        <div class="star"></div>
                        <div class="text">{{item.t}}</div>
                    </li>
                </ul>
                <div class="textarea">
                    <textarea placeholder="分享您的咨询感受" id="textarea_comment" ></textarea>
                    <div class="anFlag">
                        匿名
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="showOk" v-if="showOk">-->
            <!--<span >+2</span><img src="../../../images/asker/asker_left_dotCoin.png"></div>-->
    </div>
</template>

<script type="">

    import showLoad from '../../include/showLoad.vue';
    var rob_problem = {
        template: '#rob_problem'
    };

    export default {
        data() {
            return {
                comments:[{v:1,t:'极差'},{v:2,t:'不满意'},{v:3,t:'一般'},{v:4,t:'满意'},{v:5,t:'极满意'}],
                commentValue:0,
                detail:{},
                selBestAnswerId:0,
                selBestAnswer:null,
                bestAnswer:{},
                isOver:true,
                showLoad:false,
                anonyVal:0,
                set_award_dian_coin:0,
//                showOk:false,
            }
        },
        props:{
            user:{
                type:Object
            }
        },
        mounted: function () {

            this.id= parseInt(this.$route.query.id);
            this.getDetail();
            xqzs.wx.setConfig(this);
        },
        methods: {
            submitComment:function () {
                let that=this;
                that.showLoad = true;
                let content = $(".weui-dialog #textarea_comment").val();
                that.contentOver = content;
                if(that.commentValue==0){
                    xqzs.weui.toast('fail',"请选择评分",function () {

                    })
                    return;
                }

//                if(content.length==0){
//                    xqzs.weui.toast('fail',"请输入评论内容",function () {
//
//                    })
//                    return;
//                }
                this.showLoad=true
                that.$http.put(web.API_PATH + "come/user/evaluate/answer", {userId:"_userId_",answerId:this.detail.bestAnswerId, point:this.commentValue,content:content,isAnonymous :this.anonyVal})
                    .then(function (bt) {
                        that.showLoad=false;
                        if (bt.data && bt.data.status == 1) {

                            xqzs.weui.toast("success","评论成功",function () {
                                that.showLoad = false;
                                that.getDetail();
                            })
                        }
                    });
            },

            showCommentBox:function () {
                let _this=this;
                let isAnonymous = false;

                xqzs.weui.dialog("评价",$("#comment_box").html(),"",function () {

                },function () {

                    _this.submitComment();
                });

                $(".anFlag").click(function () {
                    isAnonymous = !isAnonymous;
                    if(isAnonymous==true){
                        $(this).addClass('anFlag_on')
                        _this.anonyVal = 1
                    }else {
                        $(this).removeClass('anFlag_on')
                        _this.anonyVal = 0
                    }
                    console.log(_this.anonyVal)
                })


                $(".comment_box2 .stars li ").click(function () {
                    let v= parseInt($(this).attr("v"))
                    _this.setCommentValue(v)
                })


            },


            setCommentValue:function (v) {
                let _this=this;
                _this.commentValue=v;
                $(".comment_box2 .stars li ").each(function () {
                    let v= parseInt($(this).attr("v"))
                    if( _this.commentValue>=v){
                        $(this).addClass("on")
                    }else{
                        $(this).removeClass("on")
                    }
                })
            },
            formatPrice:function (v) {
                return xqzs.string.formatPrice(v)
            },

            play:function (index) {
                let _this=this;
                console.log(_this.detail.answers)
                let list = _this.detail.answers;
                xqzs.voice.onEnded=function () {
                    list[index].paused=false;
                    list[index].playing=false;
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
                let item= this.detail.answers[index];
                if(item.paused){  //暂停中也就是已经获取到且为当前音频
                    console.log(list[index])
                    list[index].paused=false;
                    list[index].playing=true;
                    _this.$set(_this.detail.answers,index,list[index])
                    xqzs.voice.play();
                }else{
                    if(item.playing){    //播放中去做暂停操作
                        list[index].paused=true;
                        list[index].playing=false;
                        _this.$set(_this.detail.answers,index,list[index])
                        xqzs.voice.pause();
                    }else{     //重新打开播放
                        let answerId= item.answerId;
                        xqzs.voice.getAnswerVoice(answerId,function (url) {
                            xqzs.voice.play(url);
                            list[index].playing=true;
                            list[index].paused=false;
                            _this.$set(_this.detail.answers,index,list[index])
                        })
                    }

                }

            },


            like:function (index) {
                let  item = this.detail.answers[index];
                if(item.isLiked){
                    xqzs.weui.tip("已经点赞");
                    return ;
                }
                let _this=this;
                this.$http.put(web.API_PATH + "come/user/like/answer/_userId_/"+item.answerId, {})
                    .then(function (bt) {
                        if (bt.data && bt.data.status == 1) {
                            item.isLiked=1;
                            item.likeTimes=item.likeTimes+1;
                            _this.$set(_this.detail.answers,index,item);
                        }
                    });
            },
            setBestAnswerId:function () {
                let _this=this;
                this.$http.post(web.API_PATH + "come/user/set/question/best/answer", {userId:"_userId_",answerId:this.selBestAnswerId, questionId: this.id})
                    .then(function (bt) {
                        if (bt.data && bt.data.status == 1) {

                            _this.detail.bestAnswerId=_this.selBestAnswerId;
                            _this.detail.questionStatus=1



                        }
                    });
            },
            selectBestAnswerId:function (answer) {
                let _this=this;
                _this.selBestAnswerId=answer.answerId;
                xqzs.weui.dialog("最佳答案","选择<span style='color: black'>" + answer.expertNickName+"</span>的回答为最佳答案<br>领取<span style='color: black'>"+_this.set_award_dian_coin+"</span>点豆奖励","",function () {
                    _this.selBestAnswerId=0;

                },function () {
                    _this.selBestAnswer=answer;
                    _this.selBestAnswerId=answer.answerId;
//                    _this.showOk=true;
//                    setTimeout(function () {
//                        _this.showOk=false;
//                    },1000)

                    _this.setBestAnswerId();

                })



            },
            formatDateText:function (time) {
                return xqzs.dateTime.getTimeFormatText(time)
            },
            formatTimeLastText:function (time) {
                return xqzs.dateTime.getTimeFormatLastText(time)
            },
            getDetail:function () {
                let _this= this;
                _this.$http.get(web.API_PATH + 'come/user/query/question/_userId_/'+this.id ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        console.log(data.body.data.data)
                        _this.detail= data.body.data.data;
                        _this.set_award_dian_coin=data.body.data.set_award_dian_coin;
                        _this.detail.evaluate_ed=data.body.data.evaluate_ed
                        for(let i=0;i<_this.detail.answers.length;i++){
                            if(_this.detail.answers[i].isBestAnswer){
                                _this.bestAnswer=_this.detail.answers[i];
                            }
                        }
                    }
                }, function (error) {
                });

            },
            best_qx:function () {
                this.changeFlg = false;
                this.isactive=true;
            },
            best_confirm:function () {
                this.changeFlg = false;
                this.isactive=false
            },
            hide_dialog:function () {
                this.changeFlg = false;
            }
        }


    }
</script>
<style>
    .rob_status_box .race_detail_inCome{float: right;margin-right: 0.88235rem;color:rgba(36,37,61,0.5);}
     .comment_box2 .stars{ display: flex;margin-bottom: 1.1rem}
     .comment_box2 .stars li{ flex:1;}
     .comment_box2 .stars li .star{ background: url(../../../images/asker/ask_rack_comment_star.png) center no-repeat ; background-size:  1.4rem;;height:1.4rem; width: 1.4rem; color:#999; width: 100%; margin-bottom: 0.3rem; }
     .comment_box2 .stars li .text{color:#999 ; font-size: 0.7088235rem; text-align: center}
     .comment_box2 .stars li.on .star{background: url(../../../images/asker/ask_rack_comment_star_on.png) center no-repeat ; background-size:  1.4rem;}
     .comment_box2 .stars li.on .text{ color:#ffaa00}

     .comment_box2 .textarea{ width: 100%; height:7rem;position: relative;background: #f1f1f1; border-radius: 0.2rem;overflow: hidden;margin-bottom: 0.3rem; }
     .comment_box2 .textarea .anFlag{position: absolute;right:0.294rem;bottom:0.294rem;color:RGBA(69, 75, 84, 0.49);font-size: 0.70588rem;background: url("../../../images/asker/user_income_no.png")no-repeat left center;background-size: 0.8235rem;padding-left: 1rem;line-height: 1rem;z-index:1000;height:0.94rem;}
     .comment_box2 .textarea .anFlag_on{background: url("../../../images/asker/user_income_on.png") no-repeat left center;background-size: 0.8235rem;}
     .comment_box2 .textarea  textarea{ border: none; width: 92% ;font-size: 0.88235rem; line-height: 1.2rem; height: 5rem;background: #f1f1f1;padding-top: 0.588rem;}


    .rob_problem .rob_lists li{ position: relative}
    .rob_problem  .rob_lists li .comment_btn{ position: absolute; right:0.88235rem; top:0.88235rem; color:#FD7502; border: 0.05882352rem solid #FD7502; line-height:1.4rem; height: 1.4rem; padding: 0 0.8rem; border-radius: 0.2rem; font-size: 0.70588rem;  }
    .rob_problem  .rob_lists li .comment_btn:active{ color:#e36b02; border: 0.05882352rem solid #e36b02; }
    .rob_problem .addBest_text{
        color:rgba(153,153,153,1);
        font-size: 0.70588rem;
        line-height: 1;
        padding-top: 1rem;
    }
    .rob_problem .addBest_text a{
        color:rgba(253,87,57,1);
    }
    .problem_detail_header .race_titleColor{
        color: rgba(51,51,51,1);
    }
    .problem_detail_header img {
        height: 1.70588235rem;
        width: 1.70588235rem;
        display: block;
        border-radius: 50%;
        margin-right: 0.588235rem;
    }
    .submit_best_answer{ background: #fff; height:2.588235294117647rem; line-height: 2.588235294117647rem;  color:rgba(36,37,61,1); font-size: 0.76471rem; position: fixed; bottom:0;left:0; width: 100%;box-shadow: 2px 0px 10px rgba(0,0,0,0.3);padding-left:0.5rem;}
    .submit_btn { position: absolute;right:1.176471rem; top:50%; height: 1.588235rem;line-height: 1.588235rem; text-align: center; width: 4.71rem;border:0.0294rem solid rgba(252,66,42,1);color:rgba(252,66,42,1);border-radius: 0.88235rem;margin-top:-0.794rem;}
    .submit_btn:active{ background: #FE7301;color:#fff;}
    .rob_problem{
        background: #fff;
    }
    .rob_status_box{
        color: rgba(36,37,61,0.5);
        font-size: 0.70588235rem;
    }
    .rob_status_box>div:nth-of-type(1){
        margin-bottom: 0.8235rem;
        line-height: 1;
        padding-top: 0.5rem;
        padding-left: 0.88235rem;
    }
    .rob_status_box>div:nth-of-type(2){
        background: rgba(245,245,245,1);
        padding: 0.3rem 1.647rem;
        line-height: 1.176rem;
    }
    .rob_status_box span{
        color:rgba(255,153,0,1);
    }
    .rob_lists li{
        background: #fff;
        padding:1.176rem 0.88235rem 0px 0.88235rem;
        border-bottom: 0.588235rem solid rgba(245,245,245,1);
    }
    .rob_problem .audio{
        margin:0;
        padding-bottom: 1.1764rem;
        border-bottom: 1px solid rgba(238,238,238,1);
    }
    .rob_box_top{
        display: flex;
        display: -webkit-box;
        display: -webkit-flex;
        font-size: 0.76471rem;
        color: rgba(36,37,61,1);
        line-height: 2rem;
        margin-bottom: 1.0588rem;
    }
    .rob_box_top .expertInfo{
        line-height: 1;
    }
    .rob_box_top .expertInfo span{
        margin-bottom: 0.5rem;
        display: inline-block;
    }
    .rob_box_top .expertInfo div{
        color:rgba(36,37,61,0.4);
        font-size: 0.70588235rem;
    }
    .rob_box_top img{
        width: 2rem;
        height:2rem;
        display: block;
        border-radius: 50%;
        margin-right:0.588235rem;
    }
    .rob_answer{
        position: relative;
        margin-left: 2.588235rem;
        margin-bottom: 0.8235rem;
    }
    .answer_line{
        width:10.235rem;
        display: block;
    }
    .answer_play{
        height:0.941176471rem;
        display: block;
        position: absolute;
        top:50%;
        left:0.88235rem;
        margin-top:-0.6471rem;
    }
    .paly_html{
        color: #fff;
        width:5.88235rem;
        position: absolute;
        font-size: 0.88235rem;
        text-align: center;
        line-height: 1;
        top:0.70588rem;
        left:2.1176471rem;
    }
    .rob_answer span{
        position: absolute;
        left:10.88235rem;
        top:0.470588rem;
        color: rgba(36,37,61,0.5);
        font-size: 0.8235rem;
    }

    .rob_answer_bottom{
        padding: 0;
        padding-left: 2.588235rem;
    }
    .best_answer{
        height: 1.0588235rem;
        width:1.0588235rem;
        display: block;
        position: absolute;
        right:0;
        top: 50%;
        margin-top: -0.6470588rem;
        background: url("../../../images/asker/imgfalse.png") no-repeat;
        background-size: 100% 100%;
    }
    .best_answer_true{
        background: url("../../../images/asker/imgtrue.png") no-repeat;
        background-size: 100% 100%;
    }
    .problem_dialog{
        width:16.470588rem;
        background: #fff;
        border-radius: 5px;
        position: absolute;
        top:40%;
        left:50%;
        margin-left:-8.235294rem;
    }
    .problem_dialog_bd{
        height:5.588235rem;
        line-height: 5.588235rem;
        color: #7F7E7E;
        font-size: 0.94117647rem;
        text-align: center;
        border-bottom: 1px solid #D1D1D3;
    }
    .problem_dialog_bd span{
        color: rgba(36,37,61,1);
    }
    .problem_dialog_fd{
        display: flex;
        display: -webkit-box;
        display: -webkit-flex;
        height:2.8235rem;
        line-height: 2.8235rem;
    }
    .problem_dialog_fd div{
        flex: 1;
        text-align: center;
        font-size: 1.0588235rem;
    }
    .problem_dialog_fd div:active{
        background: #eee;
    }
    .problem_dialog_fd div:nth-of-type(1){
        color: rgba(36,37,61,0.7);
        position: relative;
    }
    .problem_dialog_fd div:nth-of-type(1)::after{
        content: '';
        height:100%;
        width:1px;
        background: #D1D1D3;
        position: absolute;
        right:0;
    }
    .problem_dialog_fd div:nth-of-type(2){
        color: #09bb07;
    }
    .best_dialog{
        width:14.470588rem;
        background: #fff;
        position: absolute;
        top:30%;
        left:50%;
        margin-left:-7.235294rem;
        padding-top:1.6470588rem;
        color: rgba(36,37,61,0.7);
        font-size: 0.76471rem;
        border-radius: 5px;
        z-index: 10001;
    }
    .best_dialog_header{
        position: relative;
        color: rgba(36,37,61,1);
        font-size: 1.23529411rem;
        text-align: center;
        margin-bottom: 1.176471rem;
    }
    .best_dialog_header div{
        margin-top:-1.176471rem;
    }
    .best_dialog_header img{
        height:6.529411rem;
        display: block;
        margin:0 auto;
    }
    .best_dialog_html{
        padding:0 0.941176471rem 1.176471rem 1.0588235rem;
        line-height: 1.176471rem;
        border-bottom: 1px solid #D1D1D3;
    }
    .best_dialog_fb{
        color: #FE7301;
        font-size: 1.0588235rem;
        text-align: center;
        height:2.941176rem;
        line-height: 2.941176rem;
    }
    .best_dialog_fb:active{
        background: #eee;
    }
    .deuce_money_box{
        color: #FF9900;
        font-size: 0.8235rem;
        position: absolute;
        top: 0.470588rem;;
        right:0;
    }
    .best_getMoney{
        position: absolute;
        right:0;
        top:0.76471rem;
    }
    .best_getMoney div{
        float: left;
        line-height: 1;
        margin-right:0.588235rem;
        color: #FF9900;
        font-size: 0.8235rem;
    }
    .best_getMoney img{
        width:1.0588235rem;
        display: block;
        float: left;
        margin-top:-0.41176rem;
    }
    .showOk{
        font-size: 3rem;
        position: absolute;
        top:50%;
        left: 30%;
        animation: showOk linear 1.5s;
    }
    @keyframes showOk {
        0%{
            font-size: 3rem;
            position: absolute;
            top:50%;
            color: #FE7A03;
            opacity: 1;
            left: 30%;
        }
        100%{
            font-size: 3rem;
            position: absolute;
            top:40%;
            color: #FE7A03;
            opacity:0;
            left: 30%;
        }
    }
</style>
<template>
    <div class="answer_join_top_box">
        <!--<div class="answer_join_top">-->

            <!--<div class="step">{{step}}<span>/10</span></div>-->

        <!--</div>-->
        <!--<div :class="{title:true,addStyle:step==2}">{{title}}</div>-->
        <div class="btn_box">
            <router-link replace :to="preUrl" v-if="preUrl!=''">上一项</router-link>
            <a   class="nextBtn"   @click="goNext()" v-if="nextUrl!=''">下一项</a>
            <a id="subBtn"  v-if="nextUrl==''&&step!=1" >提交审核</a>
        </div>
    </div>
</template>
<style>
    .answer_join_top_box{width:100%;position: absolute;bottom:0.50rem;}

    .btn_box{display: flex;display: -webkit-flex;text-align: center;line-height: 0.80rem}
    .btn_box a{flex: 1;border:0.02rem solid rgba(253,115,1,1);margin:0 0.30rem;border-radius: 0.40rem;color:rgba(253,115,1,1);background: #fff;}
    .btn_box a:last-of-type{color:#fff;background:linear-gradient(to right, rgba(255,158,25,1), rgba(253,114,6,1))}
    .btn_box a:active{
        color:#fff;background:linear-gradient(to right, rgb(217, 128, 25), rgb(220, 107, 6))
    }
    .text_area{ margin: 0 0.30rem; margin-top: 0.51rem; border:0.02rem solid #EAE7E7; border-radius: 0.10rem; overflow: hidden; background: #fff; padding: 0.17rem; position: relative }
    .text_area textarea{ width: 100%; font-size: 0.26rem; line-height: 0.41rem; border: none; max-height:4.50rem; }
    .text_area .count{ color:#999; position: absolute; right:0.14rem; bottom:0.14rem; font-size: 0.26rem;}
    .answer_join_top_box .addStyle{color:#999;height:0.72rem;line-height: 0.72rem;font-size: 0.24rem;background: #f4f4f7}
</style>
<script>
    export default {
        data() {
            return {}
        },
        props: {
            step: 1,
            preUrl: '',
            nextUrl: '',
            title:'',
            canGoNext:false,
            errorWord:''
        },
        methods:{
            goNext:function () {

                if(this.canGoNext){
                    this.$router.replace(this.nextUrl)
                }else{
                    xqzs.weui.tip(this.errorWord,function () {
                        
                    })
                }
            },
            submit:function () {
                let that=this;
                let data={
                    userId:"_userId_",
                    price:cookie.get("price"),
                    freeTime:cookie.get("freeTime"),
                    sign:unescape(cookie.get("sign")),
                    mediaId:cookie.get("mediaId"),
                    voiceLength:cookie.get("voiceLength"),
                    questionClassId:cookie.get("questionClassId").split(','),
                    jobTitle:unescape(cookie.get("jobTitle")),
                    certificateNo:unescape(cookie.get("certificateNo")),
                    certificateFile:cookie.get("certificateFile"),
                    introduction:unescape(cookie.get("introduction")),
                    experience:unescape(cookie.get("experience")),
                    goodat:unescape(cookie.get("goodAt"))

                };
                that.$http.put(web.API_PATH + "come/expert/register", data)
                    .then(function (bt) {
                        if (bt.data && bt.data.status == 1) {
                            this.$router.push("./reviewing")
                        }
                    });


            }
        }
    }
</script>
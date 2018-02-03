<template >
    <div class="personal_box">
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>

            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure" @click="setExpe()">确定</div>
            </div>
            <div class="text_area">
            <textarea placeholder="请输入专业培训经历，不超过200字
例如：
2007年，研究生主修心理学
2012年，获得国家二级心理咨询师证书
2013年，参加NLP生命教练技术、NLP心理咨询培训
2014年，学习家庭系统排列
2016年，林昆辉危机干预技术、家庭心理学、SR非事件 心理治疗培训" rows="10" v-model="experience">{{experience}}</textarea>
            </div>
        </div>

    </div>
</template>

<script type="es6">


    export default {
        data() {
            return {
                experience:'',
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
        },
        methods: {

            backStep:function () {
                this.$router.go(-1)
            },
            setExpe:function () {
                let url = "come/expert/register";
                let msg = {
                    experience: this.experience,
                    userId:this.user.id
                };
                let _this = this;
                if(_this.experience==''){
                    xqzs.weui.tip("请填写培训经历",function () {});
                }else {
                    if(_this.edit){
                        //修改
                        console.log('修改')
                        url = "come/expert/modify";
                        msg.expertId=cookie.get('expertId');
                    }
                    _this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                                console.log(response)
                                this.showLoad= false;
                                this.$router.go(-1);
                            }
                        );
                }
            }

        }



    }
</script>
<style>
    .personal_box{background: #fff}
    .personal_box .text_area{background: #fff;padding:0.47rem 0.88235rem;}
    .personal_box .text_area textarea{border:0;font-size: 0.8235rem;line-height: 1.176rem;width:100%;height:100%;}

</style>
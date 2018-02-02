<template >
    <div class="personal_box">

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
</template>

<script type="es6">


    export default {
        data() {
            return {
                experience:''
            }
        },


        mounted: function () {
            this.getCookie()
        },
        methods: {
            getCookie:function () {
                this.experience = cookie.get('register_experience')||'';
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setExpe:function () {
                let _this = this;
                if(_this.experience==''){
                    xqzs.weui.tip("请填写培训经历",function () {});
                }else {
                    cookie.set('register_experience',_this.experience,1)
                    _this.$router.go(-1)
                }
            }

        }



    }
</script>
<style>
    .personal_box{background: RGBA(69, 75, 84, 0.03);}
    .personal_box .text_area{background: #fff;padding:0.47rem 0.88235rem;}
    .personal_box .text_area textarea{border:0;font-size: 0.8235rem;line-height: 1.176rem;width:100%;height:100%;}

</style>
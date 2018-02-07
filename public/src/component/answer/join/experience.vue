<template >
    <div class="personal_box regcom_Style_back">
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>

            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure sure_nor" v-if="experience==''">确定</div>
                <div class="joinSet_sure" @click="setExpe()" v-if="experience!=''">确定</div>
            </div>
            <div class="text_area" @click="getFocus()">
                <div class="placeholder" v-show="placeFlag">
                    <p>请输入专业培训经历，不超过200字</p>
                    <p>例如：</p>
                    <p>2007年，研究生主修心理学</p>
                    <p>2012年，获得国家二级心理咨询师证书</p>
                    <p>2013年，参加NLP生命教练技术、NLP心理咨询培训</p>
                    <p>2014年，学习家庭系统排列</p>
                    <p>2016年，林昆辉危机干预技术、家庭心理学、SR非事件 心理治疗培训</p>
                </div>
            <textarea rows="10" v-model="experience" v-on:keyup="valChange()">{{experience}}</textarea>
            </div>
        </div>

    </div>
</template>

<script type="es6">


    export default {
        data() {
            return {
                experience:cookie.get('reg_experience')?unescape(cookie.get('reg_experience')):'',
                edit:'',
                placeFlag:true
            }
        },
        props: {
            user:{
                type:Object
            }
        },

        mounted: function () {
            this.edit= this.$route.query.edit;
            if(this.edit==1){
                this.getExpertInfo();
            }
            if(this.experience==''){
                this.placeFlag = true
            }else{
                this.placeFlag = false
            }
        },
        methods: {
            getFocus:function () {
                $('.text_area textarea').focus()
            },
            valChange:function () {
                if(this.experience==''){
                    this.placeFlag = true
                }else{
                    this.placeFlag = false
                }
            },
            getExpertInfo:function () {
                let expertId = cookie.get('expertId');
                this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+expertId+'/_userId_').then(function (data) {
                    if (data.body.status == 1) {
                        let showInfo = data.data.data;
                        this.experience = showInfo.experience;
                        if(this.experience==''){
                            this.placeFlag = true
                        }else{
                            this.placeFlag = false
                        }
                    }
                }, function (error) {
                });
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setExpe:function () {
                let _this = this;
                _this.showLoad= true;
                if(_this.edit==1){
                    //修改
                    console.log('修改')
                    let url = "come/expert/modify";
                    let msg = {
                        experience: _this.experience,
                        userId:_this.user.id,
                        id:_this.user.id,
                        expertId:cookie.get('expertId')
                    };
                    _this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                                console.log(response)
                            }
                        );
                }
                cookie.set('reg_experience',escape(_this.experience),1)
                setTimeout(function () {
                    _this.$router.go(-1);
                },300)

            }

        }



    }
</script>
<style>

</style>
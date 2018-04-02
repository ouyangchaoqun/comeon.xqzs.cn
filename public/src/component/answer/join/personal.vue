<template >
    <div class="personal_box regcom_Style_back">
        <v-showLoad v-show="showLoad"></v-showLoad>
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>

            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure sure_nor" v-if="introduction==''">确定</div>
                <div class="joinSet_sure" @click="setPersonal()" v-if="introduction!=''">确定</div>
            </div>
            <div class="text_area" @click="getFocus()">
                <div class="placeholder" v-show="placeFlag">
                    <p>请输入个人简介，展示给用户，不超过200个字</p>
                    <p>例如：</p>
                    <p>国家二级心理咨询师。专注婚恋情感咨询。擅长解决恋 爱婚姻中的情感创伤疗愈及应对，解决各种情感心理纠 葛。失恋安抚、恋人挽回、婚姻破裂挽回、婆媳关系处 理、婚外情等情感问题。幸福并不难，相信我，把问题 交给我，我们一起敲开幸福的大门。</p>
                </div>
            <textarea rows="10" v-model="introduction" @input="valChange()" maxlength="200">{{introduction}}</textarea>
            </div>
        </div>

    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';

    export default {
        data() {
            return {
                introduction:cookie.get('reg_introduction')?unescape(cookie.get('reg_introduction')):'',
                edit:'',
                showLoad:false,
                placeFlag:true
            }
        },
        props: {
            user:{
                type:Object
            }
        },

        activated: function () {
            this.placeFlag=true;
            this.edit= this.$route.query.edit;
            if(this.edit==1){
                this.getExpertInfo();
            }
            if(this.introduction==''){
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
                if(this.introduction==''){
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
                        this.introduction = showInfo.introduction;
                        if(this.introduction==''){
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
            setPersonal:function () {
                this.showLoad = true
                if(this.edit==1){
                    //修改
                    console.log('修改')
                    let url = "come/expert/modify";
                    let msg = {
                        introduction: this.introduction,
                        userId:this.user.id,
                        id:this.user.id,
                        expertId:cookie.get('expertId')
                    }
                    this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {

                            }
                        );
                }
                cookie.set('reg_introduction',escape(this.introduction),1);
                let _this = this;
                setTimeout(function () {
                    _this.$router.go(-1);
                },300)

            }

        },
        components: {
            'v-showLoad': showLoad,
        }



    }
</script>
<style>

</style>
<template >
    <div class="personal_box regcom_Style_back">
        <v-showLoad v-show="showLoad"></v-showLoad>
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>

            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure sure_nor" v-if="goodat==''">确定</div>
                <div class="joinSet_sure" @click="setPersonal()" v-if="goodat!=''">确定</div>
            </div>
            <div class="text_area" @click="getFocus()">
                <div class="placeholder" v-show="placeFlag">
                    <p>请对自己擅长的领域进行描述，不超过200个字</p>
                    <p>例如：</p>
                    <p>#家庭关系：婚姻挽救、情感修复、外遇分离、家暴危 机、婆媳关系、复婚帮助、离婚纠纷</p>
                    <p>#恋爱关系：失恋帮助、性格不合、沟通障碍、经常吵 架、父母反对、异地恋问题</p>
                    <p> #性心理：同性恋、恋母情结、偷窥幻想女性、性取向 问题</p>
                </div>
                <textarea rows="10" v-model="goodat" v-on:keyup="valChange()">
                    {{goodat}}
                </textarea>
            </div>
        </div>

    </div>
</template>

<script type="es6">

    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                goodat:cookie.get('reg_goodat')?unescape(cookie.get('reg_goodat')):'',
                edit:'',
                showLoad:false,
                placeFlag:false
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
            if(this.goodat==''){
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
                if(this.goodat==''){
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
                        this.goodat = showInfo.goodat;
                        if(this.goodat==''){
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
                        goodat: this.goodat,
                        userId:this.user.id,
                        id:this.user.id,
                        expertId:cookie.get('expertId')
                    };
                    this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                            }
                        );
                }
                cookie.set('reg_goodat',escape(this.goodat),1);
                let _this = this;
                setTimeout(function () {
                    _this.$router.go(-1);
                },300)

            },

        },
        components: {
            'v-showLoad': showLoad,
        }



    }
</script>
<style>

</style>
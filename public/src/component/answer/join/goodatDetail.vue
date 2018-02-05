<template >
    <div class="personal_box">
        <v-showLoad v-show="showLoad"></v-showLoad>
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>

            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure" @click="setPersonal()">确定</div>
            </div>
            <div class="text_area">
            <textarea placeholder="请对自己擅长的领域进行描述，不超过200个字
例如：
#家庭关系：婚姻挽救、情感修复、外遇分离、家暴危 机、婆媳关系、复婚帮助、离婚纠纷
#恋爱关系：失恋帮助、性格不合、沟通障碍、经常吵 架、父母反对、异地恋问题
#性心理：同性恋、恋母情结、偷窥幻想女性、性取向 问题" rows="10" v-model="goodat">{{goodat}}</textarea>
            </div>
        </div>

    </div>
</template>

<script type="es6">

    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                goodat:'',
                edit:'',
                showLoad:false
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
        },
        methods: {
            getExpertInfo:function () {
                let expertId = cookie.get('expertId');
                this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+expertId+'/_userId_').then(function (data) {
                    if (data.body.status == 1) {
                        let showInfo = data.data.data;
                        this.goodat = showInfo.goodat
                    }
                }, function (error) {
                });
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setPersonal:function () {
                if(this.goodat==''){
                    xqzs.weui.tip('请描述自己擅长的领域',function () {
                    })
                }else{
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
                    }else{
                        cookie.set('reg_goodat',escape(this.goodat),1)
                    }
                    this.showLoad = true
                    this.$router.go(-1);

                }


            },

        },
        components: {
            'v-showLoad': showLoad,
        }



    }
</script>
<style>
    .personal_box{background: #fff;}
    .personal_box .text_area{background: #fff;padding:0.47rem 0.88235rem;}
    .personal_box .text_area textarea{border:0;font-size: 0.8235rem;line-height: 1.176rem;width:100%;height:100%;}

</style>
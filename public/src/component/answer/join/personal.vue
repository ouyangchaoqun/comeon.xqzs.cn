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
            <textarea placeholder="请采用精简的语言介绍自己，以便用户快速地了解您！
例如：
国家二级心理咨询师。专注婚恋情感咨询。擅长解
决恋爱婚姻中的情感创伤疗愈及应对，解决各种情感心理
纠葛。失恋安抚、恋人挽回、婚姻破裂挽回、婆媳关系处
理、婚外情等情感问题。幸福并不难，相信我，把问题交
给我、我们一起敲开幸福的大门。" rows="10" v-model="introduction">{{introduction}}</textarea>
            </div>
        </div>

    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';

    export default {
        data() {
            return {
                introduction:'',
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
        },
        methods: {
            backStep:function () {
                this.$router.go(-1)
            },
            setPersonal:function () {
                if(this.introduction==''){
                    xqzs.weui.tip('请填写个人介绍',function () {

                    })
                }else {
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
                    }else {
                        cookie.set('reg_introduction',escape(this.introduction),1)
                    }
                    this.showLoad = true
                    this.$router.go(-1);
                }

            }

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
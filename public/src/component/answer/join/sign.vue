<template >
    <div class="sign_box regcom_Style_back">
        <v-showLoad v-show="showLoad"></v-showLoad>
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure sure_nor" v-if="sign==''">确定</div>
                <div class="joinSet_sure" @click="setSign()" v-if="sign!=''">确定</div>
            </div>
            <div class="text_area" @click="getFocus()" >
                <div class="placeholder" v-show="placeFlag">
                    <p>请输入一句话签名，展示给用户，不超过30个字</p>
                    <p>例如：</p>
                    <p>有阴影的地方就会有阳光</p>
                    <p>或：星洲易渡，心河难逾，与你共觅心河止舟</p>
                    <p>或：恋爱技巧，挽回感情，遭遇婚外情，告别前任</p>
                </div>
                <textarea v-model="sign" maxlength="25" id="" cols="30" rows="6" v-on:keyup="valChange()">

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
                sign:cookie.get('reg_sign')?unescape(cookie.get('reg_sign')):'',
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
        mounted: function () {
            this.edit= this.$route.query.edit;
            if(this.edit==1){
                this.getExpertInfo();
            }
            if(this.sign==''){
                this.placeFlag = true
            }else{
                this.placeFlag = false
            }
        },
        methods: {
            getFocus:function () {
                $('.text_area textarea').focus()
            },
            getExpertInfo:function () {
                let expertId = cookie.get('expertId');
                this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+expertId+'/_userId_').then(function (data) {
                    if (data.body.status == 1) {
                        let showInfo = data.data.data;
                        console.log(showInfo)
                        this.sign = showInfo.sign;
                        if(this.sign==''){
                            this.placeFlag = true
                        }else{
                            this.placeFlag = false
                        }


                    }
                }, function (error) {
                });
            },
            valChange:function () {
                console.log('text改变')
                console.log(this.sign)
                console.log($('.placeholder').html())
                if(this.sign==''){
                    this.placeFlag = true
                }else{
                    this.placeFlag = false
                }
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setSign:function () {
                this.showLoad = true;
                if(this.edit==1){
                    //修改
                    console.log('修改')
                    let msg = {
                        sign: this.sign,
                        userId:this.user.id,
                        id:this.user.id,
                        expertId:cookie.get('expertId')
                    };
                    let url = "come/expert/modify";
                    this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {

                            }
                        );
                }
                cookie.set('reg_sign',escape(this.sign),1)
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
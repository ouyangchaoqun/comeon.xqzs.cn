<template >
    <div class="personal_box">

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
给我、我们一起敲开幸福的大门。" rows="10" v-model="personal">{{personal}}</textarea>
        </div>
    </div>
</template>

<script type="es6">


    export default {
        data() {
            return {
                personal:'',
            }
        },


        mounted: function () {

        },
        methods: {
            getCookie:function () {
                this.personal = cookie.get('register_personal')||'';
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setPersonal:function () {
                let _this = this;
                if(_this.personal == ''){
                    xqzs.weui.tip('请填写个人简介',function () {})
                }else{
                    cookie.set('register_personal',_this.personal,1)
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
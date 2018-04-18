<template>
    <div style="height: 100%" class="imgs_box">
        <div v-title class='hide_title'>上传图片</div>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure sure_nor" v-if="checkedIndex<0">确定</div>
            <div class="joinSet_sure" @click="setFreeTime()">确定</div>
        </div>

        <div class="img_up_box">
            <div class="tips">工作照,会议照,证书照,学习照……(最多可上传5张)</div>
            <div class="item_box">
            <div v-for="i in list">
                <div class="img_item">
                    <img src="">
                    <div class="close"></div>
                </div>
            </div>

            </div>

        </div>
    </div>
</template>

<script>


    export default {
        data() {
            return {
                list: [0, 1, 2, 3, 4]
            }
        },

        methods: {},
        mounted: function () {


        },
        activated: function () {

        },

        beforeDestroy: function () {

        },


    }
</script>
<style>
    .imgs_box  .tips {
        font-size: 0.28rem;
        color: #999;
        text-align: center;
    }

    .imgs_box .img_up_box {
       padding:  0.5rem 0.2rem 0 0.5rem;
        height: 5.28rem;
    }

    .imgs_box .img_up_box .img_item {
        width: 1.96rem;
        height: 1.96rem;
        background: #FE7A03;
        float: left;
        margin:0.2rem 0;
        margin-top: 0.4rem;
        margin-right: 0.3rem;
        position: relative;
    }
    .imgs_box .img_up_box  .item_box{
        margin-top: 0.4rem;
    }
    .imgs_box .img_up_box .img_item .close{
        width: 0.4rem;
        height: 0.4rem;
        background: red;
        border-radius: 50%;
        position: absolute;
        right: -0.2rem;
        top:-0.2rem;
    }
</style>
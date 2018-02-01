<template >
    <div class="freeTime_box">
        <v-showLoad v-show="showLoad"></v-showLoad>
        <div v-title>入驻心理咨询师</div>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure" @click="setFreeTime()">确定</div>
        </div>
        <div class="time_type">
            <div class="time_typeTitle"></div>
            <ul>
                <li v-for="(item,index) in times" :class="{free_checked:index==checkedIndex}" @click="freeTimeChecked(index)">{{item.label}}</li>
            </ul>
            <div style="clear: both"></div>
        </div>

    </div>
</template>

<script type="es6">

    import answerTopStep from "./include/top_step.vue";

    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                times:[{
                    label: '不免费',
                    value: 0
                }, {
                    label: '30分钟',
                    value: 1
                }, {
                    label: '1小时',
                    value: 2
                },{
                    label: '2小时',
                    disabled: true,
                    value: 3
                }, {
                    label: '3小时',
                    value: 4
                },{
                    label: '6小时',
                    value: 5
                },{
                    label: '12小时',
                    value: 6
                },{
                    label: '24小时',
                    value: 7
                }],
                checkedIndex:''
            }
        },

        mounted: function () {


        } ,
        methods:  {
            freeTimeChecked:function (v) {
              console.log(v)
                this.checkedIndex = v;
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setFreeTime:function () {
                this.$router.go(-1)
            },
        },
        components: {
            'v-showLoad': showLoad,
        }


    }
</script>
<style>
    .freeTime_box{
        background: RGBA(69, 75, 84, 0.03);
    }
    .time_type{
        padding:0.88235rem 0 0.64rem 0;
    }
    .time_typeTitle{
        color:RGBA(69, 75, 84, 0.7);
        font-size: 0.8235rem;
    }
    .time_type li{float: left;width:26%;margin:0 3.33%;margin-bottom: 1.176rem;text-align: center;line-height: 1.7rem;height:1.7rem;border:1px solid RGBA(69, 75, 84, 0.2);border-radius: 0.294rem;font-size: 0.8235rem;color:RGBA(69, 75, 84, 0.5)}
    .time_type .free_checked{border-color: RGBA(254, 122, 3, 1);color:RGBA(254, 122, 3, 1);}
</style>
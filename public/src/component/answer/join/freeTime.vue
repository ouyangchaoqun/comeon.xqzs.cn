<template >
    <div class="freeTime_box">
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure" @click="setFreeTime()">确定</div>
            </div>
            <div class="time_type">
                <div class="time_typeTitle"></div>
                <ul>
                    <li v-for="(item,index) in times" :class="{free_checked:item.isSelect}" @click="freeTimeChecked(index)">{{item.label}}</li>
                </ul>
                <div style="clear: both"></div>
            </div>
        </div>
        <v-showLoad v-show="showLoad"></v-showLoad>


    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                times:[{
                    label: '不免费',
                    value: 0,
                    isSelect:false
                }, {
                    label: '30分钟',
                    value: 1,
                    isSelect:false
                }, {
                    label: '1小时',
                    value: 2,
                    isSelect:false
                },{
                    label: '2小时',
                    disabled: true,
                    value: 3,
                    isSelect:false
                }, {
                    label: '3小时',
                    value: 4,
                    isSelect:false
                },{
                    label: '6小时',
                    value: 5,
                    isSelect:false
                },{
                    label: '12小时',
                    value: 6,
                    isSelect:false
                },{
                    label: '24小时',
                    value: 7,
                    isSelect:false
                }],
                checkedIndex:'',
                freeTime:''
            }
        },

        mounted: function () {
            let checkedIndex = cookie.get("register_checkedIndex")||'';
            this.$nextTick(function () {
                if(checkedIndex){
                    this.freeTimeChecked(checkedIndex);
                }
            })
        } ,

        methods:  {
            freeTimeChecked:function (v) {
                let times=this.times;
                for(var i = 0;i<times.length;i++){
                    times[i].isSelect=false;
                }
                times[v].isSelect=true;
                this.checkedIndex = v;
                this.$set(this.times,v,times[v]);
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setFreeTime:function () {
                let _this = this;
                if(_this.checkedIndex==''){
                        xqzs.weui.tip("请设置免费偷听时间",function () {});
                }else {
                    _this.freeTime = this.times[_this.checkedIndex].label;
                    cookie.set('register_checkedIndex',_this.checkedIndex,1)
                    cookie.set('register_freeTime',_this.freeTime,1)
                    _this.$router.go(-1)
                }
            },
        },
        components: {
            'v-showLoad': showLoad,
        }


    }
</script>
<style>
    .freeTime_box{
        background: #fff;
    }
    .time_type{
        padding:0.88235rem 0 0.64rem 0;
        background: #fff;
    }
    .time_typeTitle{
        color:RGBA(69, 75, 84, 0.7);
        font-size: 0.8235rem;
    }
    .time_type li{float: left;width:26%;margin:0 3.33%;margin-bottom: 1.176rem;text-align: center;line-height: 1.7rem;height:1.7rem;border:1px solid RGBA(69, 75, 84, 0.2);border-radius: 0.294rem;font-size: 0.8235rem;color:RGBA(69, 75, 84, 0.5)}
    .time_type .free_checked{border-color: RGBA(254, 122, 3, 1);color:RGBA(254, 122, 3, 1);}
</style>
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
                edit:'',
                times:[{
                    label: '不免费',
                    value: 0,
                    isSelect:false
                }, {
                    label: '30分钟',
                    value: 30,
                    isSelect:false
                }, {
                    label: '1小时',
                    value: 60,
                    isSelect:false
                },{
                    label: '2小时',
                    value: 120,
                    isSelect:false
                }, {
                    label: '3小时',
                    value: 180,
                    isSelect:false
                },{
                    label: '6小时',
                    value: 360,
                    isSelect:false
                },{
                    label: '12小时',
                    value: 720,
                    isSelect:false
                },{
                    label: '24小时',
                    value: 1440,
                    isSelect:false
                }],
                checkedIndex:'',
                freeTime:'',
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
        } ,

        methods:  {
            freeTimeChecked:function (v) {
                let times=this.times;
                for(var i = 0;i<times.length;i++){
                    times[i].isSelect=false;
                }
                times[v].isSelect=true;
                this.checkedIndex = v;
                this.freeTime = this.times[v].value;
                console.log(this.freeTime)
                this.$set(this.times,v,times[v]);
            },

            backStep:function () {
                this.$router.go(-1)
            },
            changeTime:function (time) {

            },
            setFreeTime:function () {
                let url = "come/expert/register";
                let msg = {
                    freeTime: this.freeTime,
                    userId:this.user.id,
                    id:this.user.id
                };
                let _this = this;
                console.log(_this.checkedIndex)
                if(_this.checkedIndex<0){
                        xqzs.weui.tip("请设置免费偷听时间",function () {});
                }else {

                    if(this.edit==1){
                        //修改
                        console.log('修改')
                        url = "come/expert/modify";
                        msg.expertId=cookie.get('expertId');
                    }
                    //入驻
                    this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {
                                this.showLoad = true;
                                this.$router.go(-1);
                            }
                        );
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
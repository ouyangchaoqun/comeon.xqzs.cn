<template>
    <div class="filter_list">
        <div>主题 <span class="sanjiao" :class="{xsanjiao:true}"></span></div>
        <div>地区 <span class="sanjiao" :class="{xsanjiao:true}"></span></div>
        <div>排序 <span class="sanjiao" :class="{xsanjiao:true}"></span></div>
        <div>筛选 <span class="sanjiao" :class="{xsanjiao:true}"></span></div>
    </div>

</template>
<script>
    export default {
        props: ['urlType',"currtype","ordertype"],
        data() {
            return {
                sortList: [{label: "综合排序", value: 1, flag: true}, {label: "最新入驻", value: 2, flag: false}],
                classList:[],
                isShowSort:false,
                isShowClass:false,
                nowSort:"综合排序",
                nowClass:"全部",
                bottom:true,
                bottom1:true,
                classId:0,
                complexOrNew:1,
            }
        },
        mounted:function () {
            let _this=this;
            this. getClassList();

            if(this.urlType==1){
                //偷听
                console.log('偷听')
                this.sortList=[{label: "最热问题", value: 1, flag: true}, {label: "最新问题", value: 2, flag: false}]
                this.nowSort = '最新问题'
            }
            if(this.urlType==2){
                //专家
                console.log('专家')
                this.sortList= [{label: "综合排序", value: 1, flag: true}, {label: "最新入驻", value: 2, flag: false}]
                this.nowSort = '综合排序';
               setTimeout(function () {
                   if(_this.ordertype==2){
                       _this.nowSort = '最新入驻';
                   }
               },1)

            }


        },

        methods:{
            closeList:function () {
                    let _this=this;
                    _this.isShowSort=false;
                    _this.isShowClass=false;

            },
            getClassList:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.classList= data.body.data
                        _this.classList.splice(10,0,{id:0,title:'全部',code:'qb'})

                        for(let i=0;i<_this.classList.length;i++){
                            if(this.currtype==_this.classList[i].id){
                                this.nowClass=_this.classList[i].title;
                                break;
                            }
                        }


                    }
                }, function (error) {
                });
            },
            showSelect:function (n) {
                 let _this=this;
                if(n==1){
                    _this.isShowSort=!_this.isShowSort;
                    _this.bottom=!_this.bottom;
                    _this.bottom1=true;
                    _this.isShowClass=false;
                }
                else if(n==2){
                    _this.isShowSort=false;
                    _this.bottom1=!_this.bottom1;
                    _this.bottom=true;
                    _this.isShowClass=!_this.isShowClass;
                }

            },
            selectTab:function (item,n) {
                if(this.urlType==1){
                    //偷听
                    let typeVal;
                    let _this=this;
                    if(n==1){
                        _this.nowSort=item.label;
                        _this.isShowSort=false;
                        _this.bottom=true;
                        typeVal = item.value;
                        cookie.set('typeVal',typeVal,1)
                    }

                    if(n==2){
                        _this.nowClass=item.title;
                        _this.isShowClass=false;
                        _this.bottom1=true;
                        _this.$emit(
                            'classMessage',{
                                classId:item.id,
                                qType:cookie.get('typeVal')||1,
                                title:item.title
                            }
                        )
                    }
                    _this.$emit(
                        'downMessage',{
                            qType:typeVal,
                        }
                    )
                }
                if(this.urlType==2){
                    //专家
                    let exType;
                    let _this=this;
                    if(n==1){
                        _this.nowSort=item.label;
                        _this.isShowSort=false;
                        _this.bottom=true;
                        exType = item.value;
                        cookie.set('exType',exType,1)
                    }

                    if(n==2){
                        _this.nowClass=item.title;
                        _this.isShowClass=false;
                        _this.bottom1=true;
                        _this.$emit(
                            'classMessage',{
                                classId:item.id,
                                title:item.title,
                                exType:cookie.get('exType')||1
                            }
                        )
                    }
                    _this.$emit(
                        'downMessage',{
                            exType:exType,
                        }
                    )

                }


            },
        }
    }


</script>
<style>
    .filter_list{
        height:0.96rem;
        color:RGBA(43, 53, 68, 0.5);
        font-size: 0.3rem;
        text-align: center;
        display: flex;
        line-height:0.96rem;
    }
    .filter_list>div{
        flex: 1;
    }
    .sanjiao {
        width: 0;
        height: 0;
        display: inline-block;
        border-top: 0.14rem solid RGBA(69, 75, 84, 0.2);
        border-right: 0.14rem solid transparent;
        border-left: 0.14rem solid transparent;
        margin-left: 0.07rem;
        vertical-align: middle;
    }
    .xsanjiao{
        width: 0;
        height: 0;
        display: inline-block;
        border-top: 0;
        border-bottom: 0.14rem solid RGBA(69, 75, 84, 0.2);
        border-right: 0.14rem solid transparent;
        border-left: 0.14rem solid transparent;
        margin-left: 0.07rem;
    }
</style>
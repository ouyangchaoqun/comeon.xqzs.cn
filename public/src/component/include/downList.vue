<template>
    <div class="down_list">
        <div class="nav_select">
            <div class="sort_rank" @click="showSelect(1)">{{nowSort}}<span :class="{sanjiao:bottom,xsanjiao:!bottom}"></span></div>
            <div class="class_rank"  @click="showSelect(2)">{{nowClass}}<span :class="{sanjiao:bottom1,xsanjiao:!bottom1}"></span></div>
        </div>
        <div  v-if="isShowSort" class="sort_list" >
        <li v-for="item in sortList" :val="item.value" class="sort_list_item " :class="{selected:nowSort==item.label}" @click="selectTab(item,1)">{{item.label}}
        </li>
        </div>

        <div v-if="isShowClass" class="class_select">
            <li v-for="item in classList" :val="item.value" class="class_list_item" :class="{selected:nowClass==item.title}" @click="selectTab(item,2)">
                {{item.title}}
            </li>
        </div>
        <div class="downList_mask" v-if="isShowSort==true||isShowClass==true"></div>
    </div>

</template>
<script>
    export default {
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
            this. getClassList()
            console.log(this.urlType)
            if(this.urlType==1){
                //偷听
                console.log('偷听')
                this.sortList=[{label: "最新问题", value: 1, flag: true}, {label: "最热问题", value: 2, flag: false}]
                this.nowSort = '最新问题'
            }
            if(this.urlType==2){
                //专家
                console.log('专家')
                this.sortList= [{label: "综合排序", value: 1, flag: true}, {label: "最新入驻", value: 2, flag: false}]
                this.nowSort = '综合排序'
            }


        },
        props: ['urlType',"currtype"],
        methods:{
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
                                qType:cookie.get('typeVal')||1
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
    .sort_rank {
        width: 50%;
        height: 100%;
        float: left
    }

    .class_rank {
        width: 50%;
        height: 100%;
        float: right
    }

    .sanjiao {
        width: 0;
        height: 0;
        display: inline-block;
        border-top: 0.4rem solid RGBA(69, 75, 84, 0.2);
        border-right: 0.4rem solid transparent;
        border-left: 0.4rem solid transparent;
        margin-left: 0.2rem;
    }
   .xsanjiao{
        width: 0;
        height: 0;
        display: inline-block;
        border-bottom: 0.4rem solid RGBA(69, 75, 84, 0.2);
        border-right: 0.4rem solid transparent;
        border-left: 0.4rem solid transparent;
        margin-left: 0.2rem;
    }

    .sort_list_item {
        width: 100%;
        font-size: 0.88rem;
        height: 2.82rem;
        text-align: center;
        line-height: 2.82rem;
        background: #fff;
        border-bottom: 1px solid #eee;
    }
    .sort_list{
        height: 9.29rem;
        position: absolute;
        z-index: 55;
        width: 100%;
    }
    .class_select {
        width: 100%;
        height: 9.29rem;
        background: #fff;
        position: absolute;
        z-index: 55;
    }

    .class_list_item {
        float: left;
        width: 4.588rem;
       margin-left: 0.65rem;
        margin-top: 0.73rem;
        font-size: 0.88rem;
        height: 1.765rem;
        text-align: center;
        line-height: 1.765rem;
        color: RGBA(69, 75, 84, 0.5);
        background: #fff;
        border-radius: 0.3rem;
        border: 1px solid #eee;
    }

    .sort_list_item.selected {
        color: #FE7A03;
    }

    .class_list_item.selected {
        color: #fff;
        background: #FE7A03;
    }
    .downList_mask{
        width: 100%;
        height: 48rem;
        background: rgba(0,0,0,0.5);
        position: absolute;
        z-index: 54;
    }
    .nav_select{ background:RGBA(69, 75, 84, 0.05) ;height: 2.82rem;width: 100%; overflow: hidden;text-align: center;line-height: 2.82rem;font-size: 0.88rem;color: RGBA(69, 75, 84, 0.5)}
</style>
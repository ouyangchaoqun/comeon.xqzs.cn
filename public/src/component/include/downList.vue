<template>
    <div class="down_list">
        <div class="nav_select">
            <div class="sort_rank" @click="showSelect(1)">{{nowSort}}<span :class="{sanjiao:bottom,xsanjiao:!bottom}"></span></div>
            <div class="class_rank"  @click="showSelect(2)">{{nowClass}}<span :class="{sanjiao:bottom1,xsanjiao:!bottom1}"></span></div>
        </div>
        <div  v-if="isShowSort" class="sort_list"  >
        <li v-for="item in sortList" :val="item.value" class="sort_list_item " :class="{selected:nowSort==item.label}" @click="selectTab(item,1)">{{item.label}}
        </li>
        </div>

        <div v-if="isShowClass" class="class_select" >
            <li v-for="item in classList" :val="item.value" class="class_list_item" :class="{selected:nowClass==item.title}" @click="selectTab(item,2)">
                {{item.title}}
            </li>
        </div>
        <div class="downList_mask" v-if="isShowSort==true||isShowClass==true" @click="closeList()" @touchmove.prevent></div>
    </div>

</template>
<script>
    export default {
        props: ["currtype","ordertype"],
        data() {
            return {
                sortList: [{label: "最热问题", value: 1, flag: true}, {label: "最新问题", value: 2, flag: false},{label: "精选问题", value: 3, flag: false}],
                classList:[],
                isShowSort:false,
                isShowClass:false,
                nowSort:"最新问题",
                nowClass:"全部",
                bottom:true,
                bottom1:true,
                classId:0,
                complexOrNew:1,
                screenHeight:document.body.clientHeight,
            }
        },
        mounted:function () {
            this. getClassList();
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
            },
        },
    }


</script>
<style>

</style>
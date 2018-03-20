<template>
    <div>
        <div class="filter_list">
            <div v-for="(item,index) in tabs" @click="showSelect(index)" :class="{activeColor:index == num}">
                {{item}}
                <span class="sanjiao" :class="{xsanjiao:index==num}"></span>
            </div>
        </div>
        <div class="tabCon">
            <ul class="class_select" v-show="num==0">
                <li v-for="(item,index) in classList" :val="item.value" class="class_list_item" :class="{selected:index==classIndex}" @click="selectTab(index)">
                    {{item.title}}
                </li>
                <div style="clear: both"></div>
                <div class="class_btn">确定</div>
            </ul>
            <ul class="city_box" v-show="num==1">
                <li v-for="(item,index ) in cityDate" @click.stop="citySel(index)" :class="{cityStyle:index== cityIndex}" >
                    <div class="city_left">{{item.label}}</div>
                    <div class="city_right"  v-show="index== cityIndex">
                        <div v-for="(child,index) in item.children" @click.stop="setCity(index)" :class="{activeColor:index==childIndex}">{{child.label}}</div>
                    </div>
                </li>

            </ul>
            <ul v-show="num==2" class="sort_box">
                <li v-for="(item,index) in sortList" @click="sortSel(index)" :class="{activeColor:index == sortIndex}">
                    {{item.name}}
                </li>
            </ul>
            <div v-show="num==3" class="last_box">
                <div class="title">年龄</div>
                <ul class="age_box">
                    <li v-for="(item,index) in ageList" @click="ageSel(index)" :class="{selected:index==ageIndex}">{{item.name}}</li>
                    <div style="clear: both"></div>
                </ul>
                <div class="title">性别</div>
                <ul class="sexBox">
                    <li v-for="(item,index) in sexList" @click="sexSel(index)" :class="{selected:index==sexIndex}">{{item.name}}</li>
                    <div style="clear: both"></div>
                </ul>
                <div class="set_btn">
                    <div>重置</div>
                    <div>确定</div>
                </div>
            </div>
        </div>

        <div class="downList_mask" v-show="num>-1" @click="closeList()"></div>
    </div>
</template>
<script>
    export default {
        props: ["currtype","ordertype"],
        data() {
            return {
                tabs:['主题','地区','排序','筛选'],
                classList:[],
                isShowSort:false,
                isShowClass:false,
                nowSort:"综合排序",
                nowClass:"全部",
                bottom:true,
                bottom1:true,
                classId:0,
                complexOrNew:1,
                num:-1,
                classIndex:-1,
                cityIndex:-1,
                sortIndex:-1,
                ageIndex:-1,
                sexIndex:-1,
                childIndex:-1,
                cityDate:[],
                sortList:[
                    {name:'综合排序',value:1},
                    {name:'最新入驻',value:2},
                    {name:'活跃度从高到低',value:3},
                    {name:'回答次数从高到低',value:4},
                ],
                ageList:[
                    {name:'90后',lable:1},
                    {name:'85后',lable:2},
                    {name:'80后',lable:3},
                    {name:'75后',lable:4},
                    {name:'70后',lable:5},
                    {name:'70前',lable:6},
                ],
                sexList:[
                    {name:'男咨询师',lable:1},
                    {name:'女咨询师',lable:2}
                ]
            }
        },
        mounted:function () {

            this. getClassList();
            this.getCity();

        },
        methods:{
            getCity:function () {
                let  _this = this;
                $.get('/src/js/city.json', function (data) {
                    for(let i = 0 ;i<data.length;i++){
                        _this.$set(_this.cityDate, i, data[i]);
                    }
                });
            },
            closeList:function () {
                this.num=-1;
            },
            citySel:function (index) {
                this.cityIndex = index;
                this.childIndex = -1;
            },
            setCity:function (index) {
                this.childIndex = index;
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
            showSelect:function (index) {
                 let _this=this;
                _this.num = index;

            },
            selectTab:function (index) {
                console.log(index)
                this.classIndex = index;
            },
            sortSel:function (index) {
                this.sortIndex = index
            },
            ageSel:function (index) {
                this.ageIndex = index
            },
            sexSel:function (index) {
                this.sexIndex = index
            }
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
        background: #fff;
        border-bottom: 0.08rem solid #eee;
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
        border-bottom: 0.14rem solid #56C4FE;
        border-right: 0.14rem solid transparent;
        border-left: 0.14rem solid transparent;
        margin-left: 0.07rem;
    }
    .activeColor{
        color:#56C4FE !important;
    }
    .tabCon{
        position: absolute;
        z-index: 55;
        background: #fff;
        width: 100%;
        top:1.65rem;
    }
    .class_select {
        height: 4.48rem;
    }
    .city_box{
        height:9rem;
        font-size: 0.28rem;
        overflow: auto;
        position: relative;
    }
    .sort_box{
        padding-left: 0.6rem;
    }
    .last_box{
        padding:0.32rem 0rem 0.3rem 0.6rem;
    }
    .last_box .title{
        font-size: 0.28rem;
        color:RGBA(43, 53, 68, 0.7);
        line-height: 0.4rem;
        margin-bottom: 0.2rem;
    }
    .last_box li{
        float: left;
        width:1.76rem;
        margin-right: 0.5rem;
        margin-bottom: 0.3rem;
        text-align: center;
        color:RGBA(43, 53, 68, 0.5);
        border-radius: 0.1rem;
        line-height: 0.6rem;
        border:0.02rem solid RGBA(238, 238, 238, 1);
        font-size: 0.26rem;
    }
    .sort_box li{
        line-height: 0.96rem;
        font-size: 0.28rem;
        border-bottom: 0.02rem solid RGBA(238, 238, 238, 1);
        color:RGBA(69, 75, 84, 0.5);
    }
    .city_box li{
        display: flex;
        line-height: 0.92rem;
    }
    .cityStyle .city_left{
        background:#fff;
        color:RGBA(43, 53, 68, 0.5)
    }
    .city_left{
        width:30%;
        text-align: center;
        color:RGBA(43, 53, 68, 0.6);
        background: RGBA(244, 244, 247, 1);
        position: absolute;
        left:0;
        top:0;
        height:100%;
    }
    .city_right{
        position: absolute;
        right:0;
        width:70%;
        color:RGBA(43, 53, 68, 0.5);
        z-index:55;
        top:0;
        line-height: 0.9rem;
    }
    .city_right>div{
        border-bottom: 0.02rem solid RGBA(238, 238, 238, 1);
        padding-left: 10%;
    }
    .age_box{margin-bottom: 0.2rem;}
    .sexBox{margin-bottom: 0.3rem;}
    .class_select .class_btn{
        line-height: 0.88rem;
        color:RGBA(255, 255, 255, 1);
        background: RGBA(86, 196, 254, 1);
        text-align: center;
        font-size: 0.36rem;
        border-radius: 0.1rem;
        width:92%;
        margin: 0 auto;
        margin-top: 0.6rem;
    }
    .class_list_item {
        float: left;
        width: 1.56rem;
        margin-left: 0.22rem;
        margin-top: 0.25rem;
        font-size: 0.30rem;
        height: 0.60rem;
        text-align: center;
        line-height: 0.60rem;
        color: RGBA(69, 75, 84, 0.5);
        background: #fff;
        border-radius: 0.10rem;
        border: 1px solid #eee;
    }
    .downList_mask{
        width: 100%;
        height: 16.32rem;
        background: rgba(0,0,0,0.5);
        position: absolute;
        z-index: 54;
    }
    .selected{
        color:#56C4FE !important;
        border-color: #56C4FE !important;
    }
    .set_btn{
        padding-right: 0.5rem;
        line-height: 0.7rem;
        height:0.66rem;
    }
    .set_btn>div{
        width:2.76rem;
        font-size: 0.36rem;
        text-align: center;
        border-radius: 0.1rem;
        border:0.02rem solid  RGBA(86, 196, 254, 1);
    }
    .set_btn>div:nth-of-type(1){
        float: left;
        color: RGBA(86, 196, 254, 1);
        margin-left: -0.1rem;
    }
    .set_btn>div:nth-of-type(2){
        float: right;
        color:#fff;
        background: RGBA(86, 196, 254, 1);
    }
</style>
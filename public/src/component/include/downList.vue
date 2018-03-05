<template>
    <div class="down_list">
        <div class="nav_select">
            <div class="sort_rank">综合排序<span class="sanjiao"></span></div>
            <div class="class_rank">全部分类<span class="sanjiao"></span></div>
        </div>
        <li v-for="item in sortList" :val="item.value" class="sort_list " :class="{selected:item.flag}">{{item.label}}
        </li>

        <div class="class_select">
            <li v-for="item in classList" :val="item.value" class="class_list" :class="{selected:item.flag}">
                {{item.label}}
            </li>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                sortList: [{label: "综合排序", value: 1, flag: true}, {label: "最新入驻", value: 2, flag: false}],
                ClassList:[],
            }
        },
        mounted:function () {
            this. getClassList()
        },
        methods:{
            getClassList:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.classList= data.body.data
                        _this.classList.splice(10,0,{id:0,title:'全部',code:'qb'})
                    }
                }, function (error) {
                });
            },
            goClass:function (classId) {
                let _this =  this;
                _this.classId  = classId;
                console.log(_this.classId)
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

    .sort_list {
        width: 100%;
        font-size: 0.88rem;
        height: 2.82rem;
        text-align: center;
        line-height: 2.82rem;
        background: #fff;
        border-bottom: 1px solid #eee;
    }

    .class_select {
        width: 100%;
        height: 9.29rem;
    }

    .class_list {
        float: left;
        width: 4.588rem;
        margin: 0rem 0.3rem;
        margin-top: 0.73rem;
        font-size: 0.88rem;
        height: 1.765rem;
        text-align: center;
        line-height: 2.82rem;
        color: RGBA(69, 75, 84, 0.5);
        background: #fff;
        border-radius: 0.3rem;
        border: 1px solid #FE7A03;
    }

    .sort_list.selected {
        color: #FE7A03;
    }

    .class_list.selected {
        color: #fff;
        background: #FE7A03;
    }
</style>
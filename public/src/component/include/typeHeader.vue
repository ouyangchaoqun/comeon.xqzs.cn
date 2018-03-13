<template >
    <div class="class_list">
        <div class="class_item" v-for="(item,index) in classList" @click="goClass(item.id,item.title)"
             :class="{on:item.id==classId}">
            <div class="addClassImg"  :class="item.code" ></div>
            <span>{{item.title}}</span>
        </div>
        <div class="clear"></div>
    </div>

</template>
<style>
    .class_list{  padding:0.16rem 0;background: #fff;}
    .class_list .class_item{  float:left ; width: 20%; text-align: center;color:RGBA(69, 75, 84, 1);font-size: 0.21rem;padding: 6px 0 ; }
    .addClassImg{height:0.88rem; width:0.88rem; margin:0 auto;margin-bottom: 0.12rem;background: url("http://oss.xqzs.cn/resources/psy/typeHeader_blue_bg.png") no-repeat;background-size: 7rem; border-radius: 50%}

    .class_item:active .addClassImg{background-color: #eee;}

    .class_list .qb{background-position:0.00rem 0.00rem;  }
    .class_list  .qgkh{background-position:-1.54rem -0rem;  }
    .class_list .xxl{background-position:-6.1rem -1.82rem; }
    .class_list .rjgx{background-position:-6.1rem 0.00rem;}
    .class_list .zcsy{background-position:-0rem -1.82rem ; }
    .class_list .hyjt{background-position:-4.6rem -0rem;}
    .class_list .grcz{background-position:-3.07rem -0rem ;}
    .class_list .qxgl{background-position:-3.07rem -1.82rem;   }
    .class_list .xljk{background-position:-1.54rem -1.82rem ;}
    .class_list .qzjy{background-position:-4.6rem -1.82rem; }



</style>

<script>

    export default {

        data() {
            return {
                classList:[],
                classId:0,
            }
        },
        props: ['urlType'],
        mounted:function () {
            this. getClassList();
//            console.log(this.urlType)
        },

        methods:{
            getClassList:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.classList= data.body.data
                   _this.classList.splice(0,0,{id:0,title:'全部',code:'qb'})
                    }
                }, function (error) {
                });
            },
            goClass:function (classId,title) {
                console.log(classId)
                //偷听类型
                if(this.urlType==1){
                    this.$router.push('listen/list?classId='+classId+'&&title='+title)
                }
                if(this.urlType==2){
                    //专家类型
                    this.$router.push('expert/list?classId='+classId+'&&title='+title)
                }

            },
        }
    }




</script>
<template >
    <div class="class_list">
        <div class="class_item" v-for="(item,index) in classList" @click="goClass(item.id)"
             :class="{on:item.id==classId}">
            <div class="addClassImg" ></div>
            <span>{{item.title}}</span>
        </div>
        <div class="clear"></div>
    </div>
</template>
<style>
    .class_list{  padding:0.47rem 0;background: #fff;}
    .class_list .class_item{  float:left ; width: 20%; text-align: center;color:#999;font-size: 0.6176471rem;padding: 6px 0 ; }
    .class_list .class_item.on,  .class_list .class_item:active{ color:#333; font-weight: bold}
    .addClassImg{height:2.5rem; width:2.5rem; margin:0 auto;margin-bottom: 0.3529411rem;background: url("../../images/answer/index_class_nor.png") no-repeat #f1f1f1;background-size: 11.76470588235294rem; border-radius: 1.021176470588235rem}
    .class_list>div:active:nth-of-type(1) div{ background-color: #6b8be9  }
    .class_list>div:active:nth-of-type(2) div{ background-color: #cb665c  }
    .class_list>div:active:nth-of-type(3) div{background-color: #c87334  }
    .class_list>div:active:nth-of-type(4) div{ background-color: #6c89e8  }
    .class_list>div:active:nth-of-type(5) div{  background-color: #d0655b  }
    .class_list>div:active:nth-of-type(6) div{ background-color: #ca645a  }
    .class_list>div:active:nth-of-type(7) div{ background-color: #5bb06b  }
    .class_list>div:active:nth-of-type(8) div{ background-color: #6c89e8  }
    .class_list>div:active:nth-of-type(9) div{ background-color: #5bb06b  }
    .class_list>div:active:nth-of-type(10) div{ background-color: #d37c36  }

    .class_list>div:nth-of-type(1) div{background-position:0.1rem 0rem; background-color:#7198FC}
    .class_list>div:nth-of-type(2) div{background-position:-2.3rem 0.1rem; background-color:#E0685E }
    .class_list>div:nth-of-type(3) div{background-position:-4.65rem 0rem; background-color:#F19437 }
    .class_list>div:nth-of-type(4) div{background-position:-4.72rem -2.352941176470588rem; background-color:#7198FC}
    .class_list>div:nth-of-type(5) div{ background-position:-7.0rem 0rem ; background-color:#E0685E}
    .class_list>div:nth-of-type(6) div{background-position:0.05rem -2.352941176470588rem ; background-color:#E0685E}
    .class_list>div:nth-of-type(7) div{background-position:-2.33rem -2.352941176470588rem; background-color:#75CF85 }
    .class_list>div:nth-of-type(8) div{background-position:-9.33rem 0.1rem; background-color:#7198FC  }
    .class_list>div:nth-of-type(9) div{background-position:-6.98rem -2.31rem ; background-color:#75CF85}
    .class_list>div:nth-of-type(10) div{background-position:-9.35rem -2.352941176470588rem ; background-color:#F19437}
</style>
<script>

    export default {

        data() {
            return {
                classList:[],
                classId:0
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
                        _this.classList.splice(0,0,{id:0,title:'全部',code:'qb'})
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
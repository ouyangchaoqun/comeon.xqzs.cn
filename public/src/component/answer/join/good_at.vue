<template >
    <div class="goodAt_box">
        <div class="joinstep_background">
            <div v-title>入驻心理咨询师</div>
            <div class="joinSet_top">
                <div class="joinSet_cancel" @click="backStep()">取消</div>
                <div class="joinSet_sure" @click="setGoodAt()">确定</div>
            </div>
            <div class="goodat_types">
                <div class="goodat_title">请选择您最擅长的3个领域</div>
                <ul>
                    <li v-for="(item,index) in types" @click="select(index)" :class="{'item_checked':item.isSelect}">
                        {{item.title}}
                    </li>
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
                types:[],
                MAX_COUNT:3,
                canGoNext:false,
                questionClassId:[],
                expertId:'',
                expertInfo:{},
                edit:'',
                showLoad:false,
            }
        },
        props: {
            user:{
                type:Object
            }
        },
        mounted: function () {
            this.edit= this.$route.query.edit;
            this.getClassList();
        },
        methods: {
            getClassList:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.types= data.body.data;
                        let questionClassId = cookie.get("questionClassId")
                        if(questionClassId&&questionClassId!=''){
                            _this.canGoNext=true;
                            let ids=  questionClassId.split(",")
                            for(let i=0;i<_this.types.length;i++){
                                for(let j =0;j<ids.length;j++){
                                    if(_this.types[i].id==ids[j]){
                                        _this.types[i].isSelect=true;
                                    }
                                }
                            }
                        }else{
                            _this.canGoNext=false;
                        }
                    }
                }, function (error) {
                });
            },
            select:function (index) {
                let count=0;
                let types=this.types;
                if(types[index].isSelect){
                    types[index].isSelect=false
                }else{
                    for(let i=0;i<types.length;i++){
                        if(types[i].isSelect){
                            count++
                        }
                    }
                    if( count>=this.MAX_COUNT){
                    }else{
                        types[index].isSelect=true
                    }
                }
                this.$set(this.types,index,types[index]);
                let ids= '';
                for(let i=0;i<types.length;i++){
                    if(types[i].isSelect){
                        ids+=types[i].id+",";
                    }
                }

                if(ids.length>0){
                    ids=ids.substr(0,ids.length-1);
                    this.canGoNext=true;
                }else{
                    this.canGoNext=false;
                    xqzs.weui.tip('请选择您最擅长的领域',function () {

                    })
                }
                cookie.set("questionClassId",ids);
            },
            backStep:function () {
                this.$router.go(-1)
            },
            setGoodAt:function () {
                this.showLoad = true
                if(this.edit==1){
                    //修改
                    console.log('修改')
                    let url = "come/expert/modify";
                    let msg = {
                        questionClassId: cookie.get("questionClassId").split(","),
                        userId:this.user.id,
                        id:this.user.id,
                        expertId:cookie.get('expertId')
                    };
                    this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {

                            }
                        );
                }
                let _this = this;
                setTimeout(function () {
                    _this.$router.go(-1);
                },300)

            }

        },
        components: {
            'v-showLoad': showLoad,
        }


    }
</script>
<style>
    .goodAt_box{
        background: #fff;
    }
    .goodat_types{background: #fff;padding:1.0588rem 0 0.294rem 0;}
    .goodat_title{color:RGBA(69, 75, 84, 0.7);font-size: 0.8235rem;line-height: 1.176rem;padding-left: 0.88235rem;margin-bottom: 1.0588rem;}
    .goodat_types li{float: left;width:26%;margin:0 3.33%;margin-bottom: 1.176rem;text-align: center;line-height: 1.7rem;height:1.7rem;border:1px solid RGBA(69, 75, 84, 0.2);border-radius: 0.294rem;font-size: 0.8235rem;color:RGBA(69, 75, 84, 0.5)}
    .goodat_types .item_checked{border-color: RGBA(254, 122, 3, 1);color:RGBA(254, 122, 3, 1);}
</style>
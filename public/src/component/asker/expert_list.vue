<template >
    <div style="height: 100%" class="expert_list">
        <div v-title>{{titleVal}}</div>
        <v-showLoad v-if="showLoad"></v-showLoad>

        <div class="filter_box">
            <div class="filter_list">
                <div v-for="(item,index) in filter_tabs" @click="showSelect(index)" :class="{activeColor:index==filter_num||item.active}">
                    {{item.name}}
                    <span class="sanjiao" :class="{xsanjiao:index==filter_num,activeSanjiao:item.active}"></span>
                </div>
            </div>
            <div class="tabCon">
                <ul class="class_select" v-show="filter_num==0">
                    <li v-for="(item,index) in classList" class="class_list_item" :class="{selected:item.active}" @click="selectTab(index,item.id,item.title)">
                        {{item.title}}
                    </li>
                    <div style="clear: both"></div>
                    <div class="class_btn" @click="setClass_sure()">确定</div>
                </ul>
                <div class="city_box" v-show="filter_num==1">
                    <ul>
                        <li v-for="(item,index ) in filter_cityDate" @click.stop="citySel(index,item.value)" :class="{cityStyle:index== fliter_cityIndex}">
                            <div class="city_left">{{item.label}}</div>
                            <div class="city_right" >
                                <div v-for="(child,index) in item.children" @click.stop="setCity(index,child.value)" :class="{activeColor:index==childIndex}">{{child.label}}</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <ul v-show="filter_num==2" class="sort_box">
                    <li v-for="(item,index) in sortList" @click="sortSel(index,item.value)" :class="{activeColor:index == fliter_sortIndex}">
                        {{item.name}}
                    </li>
                </ul>
                <div v-show="filter_num==3" class="last_box">
                    <div class="title">年龄</div>
                    <ul class="age_box">
                        <li v-for="(item,index) in ageList" @click="ageSel(index,item.lable)" :class="{selected:index==fliter_ageIndex}">{{item.name}}</li>
                        <div style="clear: both"></div>
                    </ul>
                    <div class="title">性别</div>
                    <ul class="sexBox">
                        <li v-for="(item,index) in sexList" @click="sexSel(index,item.lable)" :class="{selected:index==fliter_sexIndex}">{{item.name}}</li>
                        <div style="clear: both"></div>
                    </ul>
                    <div class="set_btn">
                        <div @click="init_lastSel()">重置</div>
                        <div @click="last_sure()">确定</div>
                    </div>
                </div>
            </div>

            <div class="downList_mask" v-show="filter_num>-1" @click="filter_closeList()" @touchmove.prevent></div>
        </div>


        <v-scroll :class="{banScroll:filter_num>-1}" :on-refresh="onRefresh" :isNotRefresh="true" :on-infinite="onInfinite" :isPageEnd="isPageEnd"
                  :isShowMoreText="isShowMoreText" :bottomHeight="0">


            <div class="answer_list">
                <div class="item" v-for="(item,index) in list">
                    <div @click="goDetail(item.expertId)">
                        <div class="itemDetail">
                            <div class="img"><img :src="item.faceUrl"></div>
                            <div class="itemDetail_right">
                                <div class="itemHeader">
                                    <div>{{item.nickName}}<span>{{item.city}}</span></div>
                                    <div class="header_addRightStyle" v-if="false">
                                        <div class="headerImg" @click.stop="play(index)">
                                            <div :class="{addPlaying:item.playing,addPaused:item.paused}"></div>
                                        </div>
                                        {{item.length}}''
                                    </div>
                                </div>
                                <div class="title">{{item.sign}}</div>
                                <div class="itemDetail_class_s">
                                    <span v-for="(good,goodIndex) in item.goodAt" class="tab_i_i">{{good.title}}</span>
                                </div>
                                <div class="class_s other">
                                    <span class="price" ><b>问价</b>  <a>￥{{item.price}}</a></span>
                                    <span  class="class_right">
                                        <span v-if="item.answerCount!=null">{{item.answerCount}} 个回答</span>
                                        <span v-if="item.listenCount!=null">{{item.listenCount}} 次被偷听</span>
                                    </span>

                                </div>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </div>
                <div class="item" v-for="(item,index) in list" v-if="false">
                    <div @click="goDetail(item.expertId)">
                        <div class="info">
                            <div class="problem_answer_yy">
                                <div class="audio" :class="{playing:item.playing,paused:item.paused}">
                                    <div class="audio_btn" @click.stop="play(index)">
                                        <template v-if="!item.playing&&!item.paused">点击播放</template>
                                        <template v-if="item.playing">正在播放..</template>
                                        <template v-if="item.paused">播放暂停</template>
                                        <div class="second">{{item.length}}”</div>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="noContent_icon" v-if="noContent">
                    <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                    <div>暂无该方面问题</div>
                </div>
            </div>
        </v-scroll>

     </div>
</template>

<script type="">
    import showLoad from '../include/showLoad.vue';
    import scroll from '../include/scroll.vue';
    import Bus from '../bus.js';
    import askerBottom from "./include/bottom.vue";

    export default {
        data() {
            return {
                classList:[],
                list:[],
                page: 1,
                row: 10,
                isPageEnd: false,
                isShowMoreText:false,
                showLoad:false,
                noContent:false,
                titleVal:'',
                filter_tabs:[{name:'主题',active:false},{name:'地区',active:false},{name:'排序',active:false},{name:'筛选',active:false}],
                filter_num:-1,
                fliter_cityIndex:-1,
                fliter_sortIndex:-1,
                fliter_ageIndex:-1,
                fliter_sexIndex:-1,
                childIndex:-1,
                filter_cityDate:[],
                classIdArray:[],
                provinceId:'',
                cityId:'',
                sortList:[
                    {name:'综合排序',value:'complex'},
                    {name:'最新入驻',value:'new'},
                    {name:'活跃度从高到低',value:'login'},
                    {name:'回答次数从高到低',value:'answer'},
                ],
                order:'',
                age:'',
                ageVal:'',
                sex:'',
                sexVal:'',
                ageList:[
                    {name:'90后',lable:'1990'},
                    {name:'85后',lable:'1985'},
                    {name:'80后',lable:'1980'},
                    {name:'75后',lable:'1975'},
                    {name:'70后',lable:'1970'},
                    {name:'70前',lable:'1970b'},
                ],
                sexList:[
                    {name:'男咨询师',lable:1},
                    {name:'女咨询师',lable:2}
                ]
            }
        },

        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
            "v-asker-bottom": askerBottom,

        },
        methods: {
            //filter
            filter_getCity:function () {
                let  _this = this;
                $.get('/src/js/city.json', function (data) {
                    data.unshift({label:'全国',value:'',children:[{label:'不限',value:''}]});
                    _this.filter_cityDate = data;
                    _this.$set(_this.filter_cityDate);
                });
            },
            filter_closeList:function () {
                this.filter_num=-1;
            },
            citySel:function (index,pId) {
                this.provinceId = pId;
                this.fliter_cityIndex = index;
                this.childIndex = -1;
            },
            setCity:function (index,cId) {
                this.cityId = cId;
                this.childIndex = index;
                this.filter_tabs[1].active = true;
                this.initGetList();
                this.filter_closeList();
            },
            showSelect:function (index) {
                let _this = this;
                _this.filter_num = index;

            },
            selectTab:function (index,id,title) {
                this.classList[index].active = !this.classList[index].active;
                this.$set(this.classList,index,this.classList[index]);
                this.titleVal = title;
                if(this.classList[index].active){
                    this.classIdArray.push(id)
                }else{
                    this.classIdArray.splice(this.classIdArray.indexOf(id),1)
                }
                console.log(this.classIdArray)
            },
            setClass_sure:function () {
                if(this.classIdArray.length>0){
                    console.log(this.classIdArray)
                    this.initGetList();
                    this.filter_tabs[0].active = true;
                }
                this.filter_closeList();
            },
            sortSel:function (index,val) {
                this.order = val;
                this.initGetList();
                this.filter_tabs[2].active = true;
                this.filter_closeList();
                this.fliter_sortIndex = index
            },
            ageSel:function (index,age) {
                this.age = age;
                this.fliter_ageIndex = index;
            },
            sexSel:function (index,sex) {
                this.sex = sex;
                this.fliter_sexIndex = index
            },
            init_lastSel:function () {
                this.sex='';
                this.age = '';
                this.filter_tabs[3].active = false;
                this.fliter_ageIndex = -1;
                this.fliter_sexIndex = -1;
            },
            last_sure:function () {
                if(this.age||this.sex){
                    this.ageVal = this.age;
                    this.sexVal = this.sex;
                    this.filter_tabs[3].active = true;
                    this.initGetList();
                }
                this.filter_closeList();
            },
            initGetList:function () {
                this.isPageEnd = false;
                this.page = 1;
                this.isShowMoreText = false;
                this.getList();
            },
            initActive:function () {
                var obj =  $(".answer_list .item")
                xqzs.weui.active(obj);

                $(".audio ").on("touchstart",function () {
                    event.stopPropagation();
                })
            },

            play:function (index) {

                let _this=this;
                let list = _this.list;
                xqzs.voice.onEnded=function () {
                    list[index].paused=false;
                    list[index].playing=false;
                    _this.$set(_this.list,index,list[index])
                };
                //重置其他列表内容
                for(let i = 0;i<list.length;i++){
                    if(index!=i&&(list[i].playing||list[i].paused)){
                        list[i].paused=false;
                        list[i].playing=false;
                        _this.$set(_this.list,i,list[i]);
                    }
                }
                let item= list[index];
                if(item.paused){  //暂停中也就是已经获取到且为当前音频
                    console.log(1)
                    item.paused=false;
                    item.playing=true;
                    _this.$set(_this.list,index,item)
                    xqzs.voice.play();
                }else{
                    if(item.playing){    //播放中去做暂停操作
                        console.log(2)
                        item.paused=true;
                        item.playing=false;
                        _this.$set(_this.list,index,item)
                        xqzs.voice.pause();
                    }else{     //重新打开播放
                        xqzs.voice.getExpertVoice(item.expertId,function (url) {
                            console.log(3)

                            if(url!=null&&url!=undefined&&url!=''){
                                xqzs.voice.play(url);
                                item.playing=true;
                                item.paused=false;
                                _this.$set(_this.list,index,item)
                            }

                        })
                    }

                }

            },



            goDetail:function (extId) {
                this.$router.push('/asker/expert/detail/?id='+extId)
            },
            getClassList:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.classList= data.body.data
                        _this.classList.splice(0,0,{id:0,title:'全部',code:'qb'})
                        for(let i = 0 ; i<_this.classList.length;i++){
                            _this.classList[i].active = false
                        }
                    }
                }, function (error) {
                });
            },
            getList: function (done) {

                let vm= this;
                let url = web.API_PATH + "come/expert/query/"+vm.page+"/"+vm.row+"";
                this.rankUrl = url + "?";
                if (web.guest) {
                    this.rankUrl = this.rankUrl + "guest=true"
                }
                if (web.guest) {
                    this.rankUrl = this.rankUrl + ""
                }
                if (vm.isLoading || vm.isPageEnd) {
                    return;
                }
                if (vm.page == 1) {
                    vm.showLoad = true;
                }
                let msg = {
                    class: this.classIdArray.join(','),
                    provinceId: this.provinceId,
                    cityId:this.cityId,
                    order:this.order,
                    age:this.ageVal,
                    gender:this.sexVal
                }
                vm.isLoading = true;
                vm.$http.get(vm.rankUrl,{params: msg}).then((response) => {
                    if(done&&typeof(done)==='function'){
                        done()
                    }
                    vm.showLoad = false;
                    vm.isLoading = false;
//                    console.log(response)

                    if(response.data.status==9000003){
                        vm.list = [];
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false;
                        Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);
                        return;
                    }

                    let arr = response.data.data;

//
                    if (arr.length < vm.row) {
                        vm.isPageEnd = true;
                        vm.isShowMoreText = false
                    }else{
                        vm.isShowMoreText =true;
                    }
                    Bus.$emit("scrollMoreTextInit", vm.isShowMoreText);



                    if (vm.page == 1) {
                        vm.list = arr;
                    } else {
                        vm.list = vm.list.concat(arr);
                    }
                    if(vm.list.length==0&&vm.page==1){
                        vm.noContent = true
                    }else{
                        vm.noContent = false
                    }
                    if (arr.length == 0) return;
                    vm.page = vm.page + 1;

                }, (response) => {
                    vm.isLoading = false;
                    vm.showLoad = false;
                });

            },
            onInfinite(done) {
                this.getList(done);

            },

        },
        mounted: function () {
            this.titleVal = this.$route.query.title;
            $(".weui-tab__panel").height($(window).height()-50)
            this.getClassList();
            this.getList(0);
            this.filter_getCity();
            xqzs.wx.setConfig(this, function () {
                var config = {
                    imgUrl:"http://oss.xqzs.cn/resources/psy/logo.jpg",
                    title:  "‘好一点’专业咨询师为你答疑解惑" ,
                    desc: '‘好一点’你的实用人生导师，专业咨询师60秒语音为你排忧解难',
                    link:  xqzs.wx.getPubUrl("asker/expert/list") ,
                };
                weshare.init(wx, config)
            });

        },
        beforeDestroy:function () {
            xqzs.voice.pause();
        },
        updated:function () {
            this.initActive()
            let h = $(window).height() - $('.class_list').outerHeight(true)-$('.asker_bottom_box').outerHeight(true)
            $('.noContent_icon').height(h);
            $('.class_item').click(function () {
                $('.class_item div').removeClass('clickImg');
                $(this).find('div').addClass('clickImg');
            })
        }

    }
</script>
<style>
    .banScroll{
        overflow: hidden !important;
    }
    .expert_list .yo-scroll .inner{
        top:0.3rem;
    }
    .header_addRightStyle{position: absolute;right:0;top:-0.02rem;display: flex;color:rgba(36,37,61,0.5);font-size: 0.24rem;font-weight:normal}

    .expert_list .noContent_icon{background: #fff;color:rgba(36,37,61,0.5);font-size: 0.26rem;text-align: center;}
    .expert_list  .noContent_icon div{margin-top: -1.29rem;}

    @keyframes  addPlaying {
        0%{
            background: url("http://oss.xqzs.cn/resources/psy/playing1.png") no-repeat;
            background-size:  100%;
        }
        50%{
            background: url("http://oss.xqzs.cn/resources/psy/playing2.png") no-repeat;
            background-size:  100%;
        }
        100%{
            background: url("http://oss.xqzs.cn/resources/psy/playing3.png") no-repeat;
            background-size:  100%;
        }
    }


</style>
<template >
    <div style="height: 100%" class="expert_list">
        <div v-title class='hide_title'>{{titleVal}}</div>
        <v-showLoad v-if="showLoad"></v-showLoad>

        <div class="filter_box" v-if="noFilter">
            <div class="filter_list">
                <div v-for="(item,index) in filter_tabs" @click="showSelect(index)" :class="{activeColor:index==filter_num||item.active}">
                    <span>{{cityStr(item.name)}}</span>
                    <span v-show="index!=3" class="sanjiao" :class="{xsanjiao:index==filter_num,activeSanjiao:item.active}"></span>
                    <span v-show="index==3" class="filter_icon" :class="{filter_iconActive:index==filter_num||item.active}"></span>
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
                            <div class="city_right"  v-show="index==fliter_cityIndex">
                                <div v-for="(child,index) in item.children" @click.stop="setCity(index,child.value,child.label)" :class="{activeColor:index==childIndex}">
                                    {{child.label}}
                                    <img class="sure_icon" v-show="index==childIndex" src="http://oss.xqzs.cn/resources/psy/asker/filter_sure_icon.png" alt="">
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <ul v-show="filter_num==2" class="sort_box">
                    <li v-for="(item,index) in sortList" @click="sortSel(index,item.value)" :class="{activeColor:index == fliter_sortIndex}">
                        {{item.name}}
                        <img v-show="index == fliter_sortIndex" class="sure_icon" src="http://oss.xqzs.cn/resources/psy/asker/filter_sure_icon.png" alt="">
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
                            <div class="img">
                                <img :src="resizeImg(item.faceUrl)">
                                <div v-if="item.voicePath!=null" class="expert_voice" :class="{expert_voice_play:item.playing}" @click.stop="play(index)"></div>
                            </div>
                            <div class="itemDetail_right">
                                <div class="itemHeader">
                                    <div>{{item.nickName}}<span>{{item.city}}</span></div>
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
                <div class="noContent_icon" v-if="noContent">
                    <img src="http://oss.xqzs.cn/resources/psy/asker/newNoContent.png" alt="">
                    <div>暂无符合该条件专家</div>
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
    let defaultData={
        noFilter:true,
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
        classId:0,
        filter_num:-1,
        fliter_cityIndex:0,
        fliter_sortIndex:0,
        fliter_ageIndex:-1,
        fliter_sexIndex:-1,
        childIndex:0,
        filter_cityDate:[],
        classIdArray:[],
        provinceId:'',
        cityId:'',
        sortList:[
            {name:'最新登录',value:'login'},
            {name:'综合排序',value:'complex'},
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
        ]};
    export default {
        data() {
            return defaultData
        },

        components: {
            'v-showLoad': showLoad,
            'v-scroll': scroll,
            "v-asker-bottom": askerBottom,

        },
        methods: {
            initAll:function () {
                this.classId = this.$route.query.classId;
                this.titleVal = this.$route.query.title;
                let noFilter = this.$route.query.noFilter;
                if(noFilter){
                    this.noFilter = false;
                    $('.expert_list .yo-scroll .inner').css({'top':'-0.68rem'})
                }else{
                    this.noFilter = true;
                    $('.expert_list .yo-scroll .inner').css({'top':'0.48rem'})
                }
                if(this.$route.query.orderType){
                    this.order = this.$route.query.orderType;
                    console.log(this.order)
                    if(this.order == 'new'){
                        this.fliter_sortIndex = 0;
                    }else {
                        this.fliter_sortIndex = -1;
                    }

                }

                $(".weui-tab__panel").height($(window).height()-50)
                this.getClassList();

                this.filter_getCity();
                xqzs.wx.setConfig(this, function () {
                    var config = {
                        imgUrl:"http://oss.xqzs.cn/resources/psy/logo.jpg",
                        title:  "‘好一点’专业咨询师为你答疑解惑" ,
                        desc: '‘好一点’你的实用人生导师，专业咨询师60秒语音为你排忧解难',
                        link: weshare.getShareUrl("asker/expert/list" ,true),
                    };
                    weshare.init(wx, config)
                });

            },
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
            setCity:function (index,cId,cName) {
                //filter_tabs
                this.filter_tabs[1].name = cName;
                this.cityId = cId;
                this.childIndex = index;
                this.filter_tabs[1].active = true;
                this.initGetList();
                this.filter_closeList();
            },
            cityStr:function (name) {
                return name.substring(0,4)
            },
            showSelect:function (index) {
                let _this = this;
                _this.filter_num = index;
            },
            selectTab:function (index,id,title) {
                this.titleVal = title;
                if(id==0){
                    for(let i = 0;i<this.classList.length;i++){
                        this.classList[i].active = false;
                    }
                }else{
                    this.classList[0].active = false;
                }
                this.classList[index].active = !this.classList[index].active;
                this.$set(this.classList,index,this.classList[index]);
            },
            setClass_sure:function () {
                this.classIdArray = [];
                for(let i = 0;i<this.classList.length;i++){
                    if(this.classList[i].active){
                        this.classIdArray.push(this.classList[i].id )
                    }
                }
                if(this.classIdArray.length>0){
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

                    this.ageVal = this.age;
                    this.sexVal = this.sex;
                    this.filter_tabs[3].active = true;


                this.initGetList();
                this.filter_closeList();
            },
            initGetList:function () {
                this.isPageEnd = false;
                this.page = 1;
                this.isShowMoreText = false;
                this.getList();
            },
            initActive:function () {
//                var obj =  $(".answer_list .item")
//                xqzs.weui.active(obj);

                $(".audio ").on("touchstart",function () {
                    event.stopPropagation();
                })
            },

            play:function (index) {

                let _this=this;
                let list = _this.list;
                xqzs.voice.onEnded=function () {
                    xqzs.voice.pause();
                    list[index].paused=false;
                    list[index].playing=false;
                    _this.$set(_this.list,index,list[index])
                };
                //重置其他列表内容
                for(let i = 0;i<list.length;i++){
                    if(index!=i&&(list[i].playing||list[i].paused)){
                        xqzs.voice.pause();
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
                        console.log('play---暂停')
                        item.paused=true;
                        item.playing=false;
                        _this.$set(_this.list,index,item)
                        xqzs.voice.pause();
                    }else{     //重新打开播放
                        if(item.voicePath!=null){
                            console.log(item.voicePath);
                            console.log(item.voiceBgmPath)
                            xqzs.voice.play(item.voicePath,item.voiceBgmPath);
                            item.playing=true;
                            item.paused=false;
                            _this.$set(_this.list,index,item)
                        }

                    }

                }

            },



            goDetail:function (extId) {
                this.$router.push('/asker/expert/detail/?id='+extId)
            },
            getClassList:function () {
                let _this=this;
                xqzs.api.get(_this, 'come/listen/question/class/list',function (data) {
                    if (data.body.status == 1) {
                        _this.classList= data.body.data
                        _this.classList.splice(0,0,{id:0,title:'全部',code:'qb'})
                        for(let i = 0 ; i<_this.classList.length;i++){
                            _this.classList[i].active = false;
                            if(_this.classList[i].id==_this.classId){
                                _this.classList[i].active = true;
                                _this.classIdArray.push(_this.classId)
                            }
                        }
                        _this.getList();
                    }
                })

            },
            getList: function (done) {

                let vm= this;
                let url =   "come/expert/query/"+vm.page+"/"+vm.row+"";
                this.rankUrl = url + "?";
                if (web.guest) {
                    this.rankUrl = this.rankUrl + "guest=true"
                }
                if (web.guest) {
                    this.rankUrl = this.rankUrl + ""
                }
                if (vm.isLoading || vm.isPageEnd) {
                    if (done && typeof(done) === 'function') {
                        done()
                    }
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
                xqzs.api.get(vm,vm.rankUrl,function (response) {
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
                },function (error) {
                    vm.isLoading = false;
                    vm.showLoad = false;
                },{params: msg})




            },
            onInfinite(done) {
                this.getList(done);

            },

        },
        activated:function () {
            console.log("this.isKeepAlive"+this.isKeepAlive)
            if(!this.isKeepAlive){
                this.filter_tabs=[{name:'主题',active:false},{name:'地区',active:false},{name:'排序',active:false},{name:'筛选',active:false}];
                this.titleVal = '';
                this.classId = 0;
                this.filter_num = -1;
                this.fliter_cityIndex = 0;
                this.fliter_sortIndex = 0;
                this.fliter_ageIndex = -1;
                this.fliter_sexIndex = -1;
                this.childIndex = 0;
                this.filter_cityDate = [];
                this.classIdArray = [];
                this.provinceId = '';
                this.cityId = '';
                this.sortList = [
                    {name:'最新登录',value:'login'},
                    {name:'综合排序',value:'complex'},
                    {name:'回答次数从高到低',value:'answer'},
                ];
                this.order = '';
                this.age = '';
                this.ageVal = '';
                this.sex = '';
                this.sexVal = '';
                this.ageList = [
                    {name: '90后', lable: '1990'},
                    {name: '85后', lable: '1985'},
                    {name: '80后', lable: '1980'},
                    {name: '75后', lable: '1975'},
                    {name: '70后', lable: '1970'},
                    {name: '70前', lable: '1970b'},
                ];
                this.sexList = [
                    {name: '男咨询师', lable: 1},
                    {name: '女咨询师', lable: 2}
                ];


                this.isPageEnd = false;
                this.page = 1;
                this.isShowMoreText = false;
                this.initAll();
            }else{
                let st = xqzs.localdb.get("st_"+this.$route.path);
                console.log(st);
                if(st){
                    $('.yo-scroll').scrollTop(st);
                }
            }
        },
        props:
            ['isKeepAlive','user']
        ,
        mounted: function () {
            this.initAll();
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
    .expert_voice{
        width:0.5rem;
        height:0.5rem;
        background: #fff;
        border-radius: 50%;
        position: absolute;
        left:50%;
        margin-left: -0.25rem;
        bottom:-0.25rem;
        border:0.02rem solid #eee;
    }
    .expert_voice:before{
        width: 0.22rem;
        position: absolute;
        content: " ";
        display: block;
        height: 0.26rem;
        background: url('http://oss.xqzs.cn/resources/psy/asker/voice_play3.png') no-repeat;
        background-size: 0.22rem 0.26rem;
        z-index: 2;
        left:0.15rem;
        top:50%;
        margin-top: -0.13rem;
    }
    .expert_voice_play:before{
        animation: voice_playing 1.5s ease-in-out infinite;
        -webkit-animation: voice_playing 1.5s ease-in-out infinite;
    }
    /*voice gif*/
    @keyframes voice_playing{
        0%{
            background: url('http://oss.xqzs.cn/resources/psy/asker/voice_play1.png') no-repeat;
            background-size: 0.22rem 0.26rem;
        }
        50%{
            background: url('http://oss.xqzs.cn/resources/psy/asker/voice_play2.png') no-repeat;
            background-size: 0.22rem 0.26rem;
        }
        100%{
            background: url('http://oss.xqzs.cn/resources/psy/asker/voice_play3.png') no-repeat;
            background-size: 0.22rem 0.26rem;
        }
    }
    @-webkit-keyframes voice_playing /* Safari 与 Chrome */
    {
        0%{
            background: url('http://oss.xqzs.cn/resources/psy/asker/voice_play1.png') no-repeat;
            background-size: 0.22rem 0.26rem;
        }
        50%{
            background: url('http://oss.xqzs.cn/resources/psy/asker/voice_play2.png') no-repeat;
            background-size: 0.22rem 0.26rem;
        }
        100%{
            background: url('http://oss.xqzs.cn/resources/psy/asker/voice_play3.png') no-repeat;
            background-size: 0.22rem 0.26rem;
        }
    }
    .banScroll{
        overflow: hidden !important;
    }
    .expert_list .answer_list .item{
        border-top: none;
        border-bottom: 0.02rem solid #eee;
    }
    .header_addRightStyle{position: absolute;right:0;top:-0.02rem;display: flex;color:#999;font-size: 0.24rem;font-weight:normal}

    .expert_list .noContent_icon{background: #fff;color:#999;font-size: 0.26rem;text-align: center;}
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
<template >
    <div class="join_stepBox">
        <v-mobile class="mobile_box" style="display: none"></v-mobile>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title>入驻心理咨询师</div>
        <header @click="changeHeadpic()">
            <img v-if="isModify==0&&faceUrl==''" src="../../../images/joinHeaderImg.png" alt="">
            <img v-if="isModify==1" :src="isShowInfo.faceUrl" alt="">
            <img v-if="isModify==0" :src="faceUrl" alt="">
            <div class="li_right">
                <div>
                    <template v-if="isModify==0">请上传头像</template>
                    <template v-if="isModify==1">更换头像</template>
                </div>
                <i></i>
            </div>
            <div style="clear: both"></div>
        </header>
        <div class="step_detailBox" :class="{detailBox_bottom:isModify==1}">
            <ul>
                <li @click="setLevel()" v-if="btnFlag">
                    资质证书
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&reg_jobTitle==''">请选择资质</template>
                            <template v-if="isModify==0">{{reg_jobTitle}}</template>
                            <template v-if="isModify==1">{{isShowInfo.jobTitle}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="setNickname()">
                    咨询师昵称
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&reg_nickName==''">请填写昵称</template>
                            <template v-if="isModify==0">{{reg_nickName}}</template>
                            <template v-if="isModify==1">{{isShowInfo.nickName}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="goMobile()">
                    手机号码
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&reg_mobile==''">请填写手机号</template>
                            <template v-if="isModify==0">{{reg_mobile}}</template>
                            <template v-if="isModify==1">{{user.mobile}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="getSexPicker()">
                    性别
                    <div class="li_right">
                        <div>
                            <template v-if="isModify==0&&sex==''">请选择性别</template>
                            <template v-if="isModify==0">{{sex}}</template>
                            <template v-if="isModify==1">{{user.sex==2?'女':'男'}}</template>
                        </div>

                        <i></i>
                    </div>
                </li>

                <li @click="areaPicker()">
                    所在城市
                    <div class="li_right">
                        <div>
                            <span v-if="isModify==0&&provinceName==''">请选择所在城市</span>
                            <template v-if="isModify==0&&provinceName!=''">
                                <span>{{provinceName}}</span>
                                <span>{{cityName}}</span>
                                <span>{{areaName}}</span>
                            </template>
                            <template v-if="isModify==1">
                                <span>{{user.provinceName}}</span>
                                <span>{{user.cityName}}</span>
                                <span>{{user.areaName}}</span>
                            </template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="setSign()">
                   一句话签名
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&reg_sign==''">请填写个人签名</template>
                            <template v-if="isModify==0">{{reg_sign}}</template>
                            <template v-if="isModify==1">{{isShowInfo.sign}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="setPerson()">
                    个人简介
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&reg_introduction==''">请填写个人简介</template>
                            <template v-if="isModify==0">{{reg_introduction}}</template>
                            <template v-if="isModify==1">{{isShowInfo.introduction}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="setGoodat()">
                    擅长领域
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&showTypes.length==0">选择自己擅长的领域</template>
                            <template v-if="isModify==1&&isShowInfo.domains">
                                <span v-for="item in isShowInfo.domains" style="margin-left: 0.294rem">{{item.title}}</span>
                            </template>
                            <template v-if="isModify==0&&showTypes.length!=0" >
                                <span style="margin-left: 0.294rem" v-for="item in showTypes">{{item}}</span>
                            </template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="goGoodatDetail()">
                    擅长领域描述
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&reg_goodat==''">请描述自己擅长的领域</template>
                            <template v-if="isModify==0">{{reg_goodat}}</template>
                            <template v-if="isModify==1">{{isShowInfo.goodat}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="setExperience()">
                    专业培训经历
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&reg_experience==''">请填写培训经历</template>
                            <template v-if="isModify==0">{{reg_experience}}</template>
                            <template v-if="isModify==1">{{isShowInfo.experience}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="goPrice()">
                    提问酬金
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0&&reg_price==''">请设置提问酬金</template>
                            <template v-if="isModify==0">{{reg_price}}</template>
                            <template v-if="isModify==1">{{isShowInfo.price}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li @click="goFreeTime()">
                    限时免费偷听时间
                    <div class="li_right" >
                        <div>
                            <template v-if="isModify==0">{{changeTime(reg_freeTime)}}</template>
                            <template v-if="isModify==1">{{changeTime(isShowInfo.freeTime)}}</template>
                        </div>
                        <i></i>
                    </div>
                </li>
            </ul>
        </div>
        <div class="joinStep_bottom" v-show="btnFlag">
            <div class="join_agre">
                提交审核，即表示您同意遵守<span @click="showAgre()">《好一点专家入驻协议》</span>我们会尽快对您的资质进行审核，审核通过后将以好一点客服消息通知您
            </div>
            <div class="join_sub" @click="msgSubmit()" v-show="btnFlag">
                提交审核
            </div>
        </div>

        <!--协议-->
        <div class="weui-mask" @touchmove.prevent v-show="agreFlag">
            <div class="agre_box">
                <h2>好一点专家入驻协议</h2>
                <div class="agre_content">
                    <p>
                        尊敬的心理咨询师、精神科医生、心理治疗师（以 下简称“专家”），欢迎您入驻杭州蓝江网络科技有 限公司（以下简称“本公司”）开发与运营的“好一点 ”心理咨询平台（包含但不限于微信公众号、微信小 程序，以下简称“本平台”或“平台”）。您在入驻或 使用本平台服务前，请仔细阅读本协议。您使用本 公司任何服务，均意味着本协议已经生效，您已经 认可本协议的全部条款，将自愿遵守本协议的规定， 并接受本公司的统一管理。
                    </p>
                    <h3>一、定义</h3>
                    <p>1、服务平台：指本公司开发和运营的“好一点”心 理咨询服务平台，即“本平台”。</p>
                    <p>2、专家：指同意并遵守本协议规定，通过本平台 向平台用户提供心理咨询解答服务的具有相应资质 的心理咨询师、精神科医生、心理治疗师、催眠师、 婚姻家庭指导师等专业人士。</p>
                    <p> 3、服务：指专家通过本平台向平台用户提供的心理</p>
                </div>
                <div class="agre_btn" @click="hideAgre()">我知道了</div>
            </div>
        </div>

    </div>
</template>

<script type="es6">
    import mobile from './mobile.vue';
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                user:'',
                showLoad:false,
                sexIndex:'',
                defaultCity: '[330000, 330100, 330102]',
                provinceName:cookie.get('reg_provinceName')?unescape(cookie.get('reg_provinceName')):'',
                cityName: cookie.get('reg_cityName')?unescape(cookie.get('reg_cityName')):'',
                areaName: cookie.get('reg_areaName')?unescape(cookie.get('reg_areaName')):'',
                provinceId: cookie.get('reg_provinceId')?cookie.get('reg_provinceId'):'',
                cityId: cookie.get('reg_cityId')?cookie.get('reg_cityId'):'',
                areaId: cookie.get('reg_areaId')?cookie.get('reg_areaId'):'',
                reg_mobile:cookie.get('reg_mobile')?cookie.get('reg_mobile'):'',
                reg_sign:cookie.get('reg_sign')?unescape(cookie.get('reg_sign')):'',
                reg_introduction:cookie.get('reg_introduction')?unescape(cookie.get('reg_introduction')):'',
                reg_goodat:cookie.get('reg_goodat')?unescape(cookie.get('reg_goodat')):'',
                reg_experience:cookie.get('reg_experience')?unescape(cookie.get('reg_experience')):'',
                reg_price:cookie.get('reg_price')?cookie.get('reg_price'):'',
                reg_freeTime:cookie.get('reg_freeTime')?cookie.get('reg_freeTime'):'',
                reg_nickName:cookie.get('reg_nickName')?unescape(cookie.get('reg_nickName')):'',
                reg_jobTitle:cookie.get('reg_jobTitle')?unescape(cookie.get('reg_jobTitle')):'',
                faceUrl:cookie.get('reg_faceUrl')?cookie.get('reg_faceUrl'):'',
                sex:cookie.get('reg_sex')?unescape(cookie.get('reg_sex')):'',
                alioss:null,
                uploadpicinfo:null,
                identityFile1:'',
                goodAt:'',
                mobileBox:false,
                agreFlag:false,
                questionClassId:[],
                isModify:0,
                isShowInfo:{
                    freeTime:''
                },
                btnFlag:true,
                types:'',
                showTypes:[],
            }
        },
        props: {
            user:{
                type:Object
            }
        },

        mounted: function () {
            this.isJoin();
            this.initOss();
            this.getUserInfo();
            this.getClassList();
        },
        methods: {
            getCookie:function (v) {
                return unescape(cookie.get(v))
            },
            //判断是否入驻，获取入驻信息
            isJoin:function () {
                this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {
                    if (data.body.status == 1) {
                        if(data.data.data){
                            let expertId = data.data.data.id;
                            let status = data.data.data.status;
                            this.getExpertInfo(expertId);
                            this.isModify = 1;
                            if(status==1){
                                //已认证
                                console.log('已认证')
                                this.btnFlag = false;
                            }
                            if(status==2||status==-1){
                                //认证中，提交中
                                console.log('认证中，提交中')
                                this.btnFlag = false;
                            }
                            if(status==0){
                                //未认证通过，需要修改
                                console.log('未认证通过，需要修改')
                                this.btnFlag = true;
                            }

                        }else{
                            console.log('首次注册');
                            this.btnFlag = true;
                            this.isModify = 0;
                        }
                    }
                }, function (error) {
                });
            },
            //获取最新资料信息
            getExpertInfo:function (expertId) {

                this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+expertId+'/_userId_').then(function (data) {
                    if (data.body.status == 1) {
                        this.isShowInfo = data.data.data||{};
                        console.log(this.isShowInfo)
                    }
                }, function (error) {
                });
            },
            getUserInfo:function(){
                let _this=this;
                _this.showLoad = true;
                _this.$http({
                    method: 'GET',
                    type: "json",
                    url: web.API_PATH + 'user/find/by/user/Id/_userId_',
                }).then(function (data) {//es5写法
                    _this.showLoad = false;
                    if (data.data.data !== null) {
                        _this.user = eval(data.data.data);
                        console.log(_this.user)
                    }
                }, function (error) {
                    //error
                });
            },
            changeHeadpic:function () {
                let _this=this;
                xqzs.image.showClip(this.uploadpicinfo,this.alioss,function(){
                    _this.showLoad=true;
                },function (json,ix) {
                    let facePath = json.data.path
                    _this.showLoad=false;
                    _this.faceUrl=facePath;
                    _this.isShowInfo.faceUrl = facePath;
                    if(_this.isModify){
                        //修改
                        console.log('修改')
                        let url = "come/expert/modify";
                        let msg = {
                            userId:_this.user.id,
                            id:_this.user.id,
                            expertId:cookie.get('expertId'),
                            faceUrl: facePath,
                        };
                        _this.$http.post(web.API_PATH + url, msg)
                            .then(
                                (response) => {
                                }
                            );
                    }else{
                        cookie.set('reg_faceUrl',facePath,1)
                    }

//
//                    let data ={
//
//                        faceUrl: _this.faceUrl,
//                        expertId:cookie.get("expertId"),
//                        userId:"_userId_"
//                    }
//                    _this.$http.post(web.API_PATH + "come/expert/modify", data)
//                        .then(function (bt) {
//                            if (bt.data && bt.data.status == 1) {
//
//                            }
//                        });
                    xqzs.image.hideClip()
                });
            },
            initOss:function () {
                this.uploadpicinfo = {
                    token: xqzs.string.guid(),
                    smallpic: xqzs.constant.PIC_SMALL,
                    middlepic: xqzs.constant.PIC_MIDDLE,
                    removepicurl: web.BASE_PATH + 'api/removepicture',
                    uploadbase64url: web.BASE_PATH + 'api/upfilebase64',
                    aliossgeturl: web.BASE_PATH + 'api/aliyunapi/oss_getsetting'
                };
                this.alioss = new aliyunoss({
                    url:this.uploadpicinfo.aliossgeturl,
                    token:this.uploadpicinfo.token
                });
            },
            showAgre:function () {
                this.agreFlag = true
            },
            hideAgre:function () {
                this.agreFlag = false
            },
            setLevel:function () {
              this.$router.push('./level?edit='+this.isModify)
            },
            setNickname:function () {
                this.$router.push('./nickname?edit='+this.isModify)
            },
            setSign:function () {
                this.$router.push('./sign?edit='+this.isModify)
            },
            setPerson:function () {
                this.$router.push('./personal?edit='+this.isModify)
            },
            setGoodat:function () {
                this.$router.push('./good/at?edit='+this.isModify)
            },
            goGoodatDetail:function () {
                this.$router.push('./good/detail?edit='+this.isModify)
            },
            getClassList:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.types= data.body.data;
                        if(cookie.get('questionClassId')){
                            let ids=  cookie.get('questionClassId').split(",");
                            for(let i=0;i<_this.types.length;i++){
                                for(let j =0;j<ids.length;j++){
                                    if(_this.types[i].id==ids[j]){
                                        console.log(_this.types[i].title)
                                        _this.showTypes.push(_this.types[i].title)
                                    }
                                }
                            }
                        }


                    }
                }, function (error) {
                });
            },
            setExperience:function () {
                this.$router.push('./experience?edit='+this.isModify)
            },
            goPrice:function () {
                this.$router.push('./price?edit='+this.isModify)
            },
            goFreeTime:function () {
                this.$router.push('./freetime?edit='+ this.isModify)
            },
            changeTime:function (v) {
                if(v==''){
                    return '请设置免费偷听时间'
                }
                if(v==0){
                    return '不免费'
                }
                if(Number(v)<=30){
                    return v + '分钟'
                }else{
                    return v/60 + '小时'
                }

            },
            getSexPicker:function () {
                let _this = this;
                weui.picker([{
                    label: '女',
                    value: 2
                }, {
                    label: '男',
                    value: 1
                }], {
                    onChange: function (result) {

                    },
                    onConfirm: function (result) {
                        console.log(result)

                        _this.sexIndex = result[0].value;
                        _this.sex =  result[0].label;
                        console.log(_this.sex)
                        if(_this.isModify){
                            //修改
                            console.log('修改')
                            let url = "come/expert/modify";
                            let msg = {
                                sex: _this.sexIndex,
                                userId:_this.user.id,
                                id:_this.user.id,
                                expertId:cookie.get('expertId')
                            };
                            _this.$http.post(web.API_PATH + url, msg)
                                .then(
                                    (response) => {
                                    }
                                );
                        }else{
                            cookie.set('reg_sex',escape(result[0].label),1)
                        }


                    }
                });
            },
            areaPicker: function () {
                let _this = this;
                $.get('/src/js/city.json', function (data) {
                    weui.picker(data, {
                        depth: 3,
                        defaultValue: _this.defaultCity,
                        onChange: function onChange(result) {

                        },
                        onConfirm: function onConfirm(result) {
                            if (result[0]) {
                                _this.provinceId = result[0].value;
                                _this.provinceName = result[0].label;
                            }
                            if (result[1]) {
                                _this.cityId = result[1].value;
                                _this.cityName = result[1].label;
                            }
                            if (result[2]) {
                                _this.areaId = result[2].value;
                                _this.areaName = result[2].label;
                            } else {
                                _this.areaId = '';
                                _this.areaName = '';
                            }
                            if(_this.isModify){
                                //修改
                                console.log('修改')
                                let url = "come/expert/modify";
                                let msg = {
                                    userId:_this.user.id,
                                    provinceId:_this.provinceId,
                                    cityId:_this.cityId,
                                    areaId:_this.areaId,
                                    id:_this.user.id,
                                    expertId:cookie.get('expertId')
                                };
                                _this.$http.post(web.API_PATH + url, msg)
                                    .then(
                                        (response) => {

                                        }
                                    );
                            }
                            cookie.set('reg_provinceName',escape(_this.provinceName),1)
                            cookie.set('reg_cityName',escape(_this.cityName),1)
                            cookie.set('reg_areaName',escape(_this.areaName),1)
                            cookie.set('reg_provinceId',_this.provinceId,1)
                            cookie.set('reg_areaId',_this.areaId,1)
                            cookie.set('reg_cityId',_this.cityId,1)
                        },
                        id: 'cascadePicker'
                    });
                });

            },
            goMobile:function () {
                $('.mobile_box').show()
            },
            msgSubmit: function () {

                let _this = this;
                let url, price ,freeTime,jobTitle,introduction,experience,goodat,faceUrl,nickName,sign,provinceId,sex,questionClassId,certificateNo,certificateFile1,cityId,areaId;
                let reg_questionClassId;
                if(cookie.get("questionClassId")){
                    reg_questionClassId = cookie.get("questionClassId").split(",");
                }else{
                    reg_questionClassId=''
                }
                let reg_certificateNo = cookie.get('reg_certificateNo')?cookie.get('reg_certificateNo'):'';
                let reg_certificateFile1 = cookie.get('reg_certificateFile1')?cookie.get('reg_certificateFile1'):'';
                if(_this.isModify==0){
                    url = "come/expert/register";
                    price = _this.reg_price;
                    freeTime = _this.reg_freeTime;
                    jobTitle = _this.reg_jobTitle;
                    introduction =_this.reg_introduction;
                    experience = _this.reg_experience;
                    goodat = _this.reg_goodat;
                    faceUrl = _this.reg_faceUrl;
                    nickName = _this.reg_nickName;
                    sign = _this.reg_sign;
                    provinceId=_this.provinceId;
                    sex=_this.sexIndex;
                    questionClassId=reg_questionClassId;
                    certificateNo=reg_certificateNo;
                    certificateFile1 = reg_certificateFile1;
                    cityId = _this.cityId;
                    areaId=_this.areaId;
                }

                if(_this.isModify==1){
                   url = "come/expert/modify";
                   price = _this.isShowInfo.price;
                   freeTime =_this.isShowInfo.freeTime;
                   jobTitle=_this.isShowInfo.jobTitle;
                   introduction = _this.isShowInfo.introduction;
                    experience = _this.isShowInfo.experience;
                    goodat = _this.isShowInfo.goodat;
                    faceUrl = _this.isShowInfo.faceUrl;
                    nickName = _this.isShowInfo.nickName;
                    sign = _this.isShowInfo.sign;
                    provinceId = _this.user.provinceId;
                    sex = _this.user.sex;
                    questionClassId = _this.isShowInfo.domains;
                    certificateNo = _this.isShowInfo.certificateNo;
                    certificateFile1 = _this.isShowInfo.certificateFile1;
                    cityId = _this.user.cityId;
                    areaId = _this.user.areaId;
                }
                if(price==''){
                    xqzs.weui.tip('请填写价格')
                    return
                }else if(freeTime==''){
                    xqzs.weui.tip('请设置免费时间')
                    return
                }else if(jobTitle==''){
                    xqzs.weui.tip('请选择资质')
                    return
                }else if(certificateNo==''){
                    xqzs.weui.tip('请填写证书编号')
                    return
                }
                else if(certificateFile1==''){
                    xqzs.weui.tip('请上传证件照')
                    return
                }
                else if(introduction==''){
                    xqzs.weui.tip('请填写个人简介')
                    return
                }else if(experience==''){
                    xqzs.weui.tip('请填写培训经历')
                    return
                }else if(goodat==''){
                    xqzs.weui.tip('请填写擅长详情')
                    return
                }
                else if(faceUrl==''){
                    xqzs.weui.tip('请上传头像')
                    return
                }
                else if(nickName==''){
                    xqzs.weui.tip('请填写昵称')
                    return
                }else if(sign==''){
                    xqzs.weui.tip('请填写个人签名')
                    return
                }else if(questionClassId==''){
                    xqzs.weui.tip('请选择擅长领域')
                    return
                }else if(provinceId==''){
                    xqzs.weui.tip('请选择城市')
                    return
                }else if(sex==null){
                    xqzs.weui.tip('请选择性别')
                    return
                }
                let msg = {
                    userId:_this.user.id,
                    id:_this.user.id,
                    countryId:0,
                    provinceId:provinceId,
                    cityId:cityId,
                    areaId:areaId,
                    sex:sex,
                    price:price,
                    freeTime:freeTime,
                    sign:sign,
                    questionClassId:questionClassId,
                    jobTitle:jobTitle,
                    certificateNo:certificateNo,
                    certificateFile1:certificateFile1,
                    introduction:introduction,
                    experience:experience,
                    goodat:goodat,
                    faceUrl:faceUrl,
                    nickName:nickName,
                    finish:1
                };
                if(_this.isModify==1){
                    msg.expertId = cookie.get('expertId')
                }
                console.log(msg)

                _this.$http.post(web.API_PATH + url, msg)
                    .then(
                        (response) => {
                            _this.showLoad= true;
                            _this.$router.go(-1);
                        }
                    );
            },

        },
        components:{
            'v-mobile':mobile,
            'v-showLoad': showLoad,
        },


    }
</script>
<style>
    .join_stepBox{background: #fff;}
    .join_stepBox .li_right{float: right;padding-right: 1.5rem;}
    .join_stepBox header{padding:0.88235rem;border-bottom: 0.588235rem solid rgba(69, 75, 84, 0.09);line-height: 3.52rem;position: relative}
    .join_stepBox header img{width:3.52rem;height:3.52rem;float: left}
    .detailBox_bottom{margin-bottom: 2rem}
    .join_stepBox .step_detailBox li{height: 2.94rem;line-height:2.94rem;color:rgba(69, 75, 84, 1);border-bottom: 1px solid rgba(224,224,225,1);padding:0 0.88235rem;font-size: 0.8235rem;position: relative;}
    .join_stepBox .step_detailBox li .li_right{float: right;padding-right:1.5rem;width:55%;color: rgba(69, 75, 84, 0.7) !important;}
    .li_right>div{ width:100%;text-align: right;font-size: 0.8235rem;color: rgba(69, 75, 84, 0.7); overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
    .join_stepBox .li_right i{background: url('../../../images/arrow.png');width: 0.94rem;  height: 0.94rem;  background-size: 0.94rem;  position: absolute;  right: 0.88235rem;  top: 50%;margin-top: -0.47rem;  }
    .joinStep_bottom{padding:1.76471rem 0.88235rem;}
    .join_agre{color:rgba(53, 58, 66, 1);font-size: 0.70588rem;line-height: 1rem;margin-bottom: 1.8rem;}
    .join_agre span{color:rgba(255, 99, 0, 1)}
    .join_sub{height:2.94rem;line-height: 2.94rem;color:rgba(255, 255, 255, 1);background: rgba(254, 122, 3, 1);font-size: 1.0588rem;border-radius:6.17rem;text-align: center }
    .agre_box{width:86%;position: absolute;top:5%;left:50%;margin-left: -46%;background: #fff;padding:0.88235rem 3%;border-radius: 0.588235rem;}
    .agre_box h2{color:RGBA(69, 75, 84, 1);font-size: 1.0588rem;line-height: 1.47rem;text-align: center;margin-bottom: 0.88235rem;}
    .agre_box .agre_content{color:RGBA(69, 75, 84, 1);font-size: 0.76471rem;line-height: 1.176471rem;background: RGBA(69, 75, 84, 0.05);padding:1.4rem 0.764rem 0.294rem 0.764rem;margin-bottom: 1.176rem;}
    .agre_content h3{padding-top: 1.176rem}
    .agre_btn{width:60%;height:2.058rem;line-height: 2.1rem;background: RGBA(254, 122, 3, 1);border-radius: 1.323rem;color:RGBA(255, 255, 255, 1);text-align: center;font-size: 0.88235rem;margin:0 auto;margin-bottom: 0.294rem;}
    .join_stepBox .lut_box{position: absolute;top:0;left:5rem}
    .join_stepBox .lut{float: left;background: #ececec;color: rgba(36,37,61,1);height: 1.76471rem;line-height: 1.76471rem;padding: 0 0.588235rem;margin-top: 0.35294rem;font-size: 0.8235rem;}
    .join_stepBox .lut.on{float: left;background: linear-gradient(to right, rgba(255,158,25,1), rgba(253,114,6,1));color: #fff;}
    .imgBox{padding-right: 0.88235rem;padding-top:2.35rem;height:6.0294rem;margin-bottom: 4rem}
    .imgBox img{display: block;width: 9.4rem;float: left;height:6.0294rem}
    .imgBox img:nth-of-type(2){float: right}
    .join_stepBox .joinStep_nor_btn{width:100%;background: linear-gradient(rgba(255,158,25,0.5),rgba(253,115,1,0.5));    font-size: 1.0588235rem;text-align: center;line-height: 2.5294rem;color:rgba(255,255,255,1)}
    .join_stepBox .joinStep_per_btn{background: linear-gradient(rgba(255,158,25,1),rgba(253,115,1,1)); }
</style>
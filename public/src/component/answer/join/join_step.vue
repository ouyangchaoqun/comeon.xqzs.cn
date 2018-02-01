<template >
    <div class="join_stepBox">
        <v-mobile class="mobile_box" style="display: none"></v-mobile>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title>入驻心理咨询师</div>
        <header>
            <img src="../../../images/joinHeaderImg.png" alt="">
            <div class="li_right">
                <input type="text" class="nickName" @input="changeNickName()" placeholder="请上传头像" :value="nickName">
                <i></i>
            </div>
            <div style="clear: both"></div>
        </header>
        <div class="step_detailBox">
            <ul>
                <li>
                    资质证书
                    <div class="li_right">
                        <input type="text" placeholder="请选择资质">
                        <i></i>
                    </div>
                </li>

                <li>
                    咨询师昵称
                    <div class="li_right">
                        <input type="text" class="nickName" @input="changeNickName()" placeholder="请填写昵称" :value="nickName">
                        <i></i>
                    </div>
                </li>

                <li>
                    手机号码
                    <div class="li_right" @click="goMobile()">
                        <!--<div class="mobile">{{mobileVal}}</div>-->
                        <input type="text" class="mobile" placeholder="请输入手机号" :value="mobileVal">
                        <i></i>
                    </div>
                </li>

                <li @click="getSexPicker()">
                    性别
                    <div class="li_right">
                        <div>{{sex}}</div>
                        <i></i>
                    </div>
                </li>

                <li @click="areaPicker()">
                    所在城市
                    <div class="li_right">
                        <div>
                            <span v-if="!provinceName||!cityName||!areaName">{{initCityValue}}</span>
                            <span v-if="provinceName">{{provinceName}}</span>
                            <span v-if="cityName">{{cityName}}</span>
                            <span v-if="areaName">{{areaName}}</span>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li>
                   一句话签名
                    <div class="li_right">
                        <input type="text" placeholder="请填写个人签名">
                        <i></i>
                    </div>
                </li>

                <li>
                    个人简介
                    <div class="li_right">
                        <input type="text" placeholder="请填写个人简介">
                        <i></i>
                    </div>
                </li>

                <li>
                    擅长领域
                    <div class="li_right">
                        <input type="text" placeholder="选择自己擅长的领域">
                        <i></i>
                    </div>
                </li>

                <li>
                    专业培训经历
                    <div class="li_right">
                        <input type="text" placeholder="请填写培训经历">
                        <i></i>
                    </div>
                </li>

                <li>
                    提问酬金
                    <div class="li_right">
                        <input type="text" placeholder="请设置提问酬金">
                        <i></i>
                    </div>
                </li>

                <li>
                    限时免费偷听时间
                    <div class="li_right">
                        <input type="text" placeholder="请设置免费偷听时间">
                        <i></i>
                    </div>
                </li>

                <!--<li>-->
                    <!--真实姓名-->
                    <!--<div class="li_right">-->
                        <!--<input type="text" class="realName" @input="changeRealName()" placeholder="还未填写（如张三）" :value="realName">-->
                        <!--<i></i>-->
                    <!--</div>-->
                <!--</li>-->


                <!--<li @click="showDate()" v-if="false">-->
                    <!--生日-->
                    <!--<div class="lut_box">-->
                        <!--<span class="lut" :class="{on:!isLunar}" @click.stop="lutSelect(0)">阳历</span>-->
                        <!--<span class="lut" :class="{on:isLunar}"  @click.stop="lutSelect(1)">阴历</span>-->
                    <!--</div>-->
                    <!--<div class="li_right" style="width:40%" >-->
                        <!--<div v-if="birthday">-->
                            <!--<template v-if="!isLunar">-->
                                <!--<span>{{year}}年 </span>-->
                                <!--<span>{{month}}月 </span>-->
                                <!--<span>{{day}}日 </span>-->
                            <!--</template>-->
                            <!--<template v-if="isLunar">-->
                                <!--<span>{{year}}年 </span>-->
                                <!--<span>{{month}}月 </span>-->
                                <!--<span>{{day}}日 </span>-->
                            <!--</template>-->
                        <!--</div>-->
                        <!--<i></i>-->
                    <!--</div>-->
                <!--</li>-->

                <!--<li>-->
                    <!--邮 箱-->
                    <!--<div class="li_right">-->
                        <!--<input type="email" class="email" :value="email" @blur="fouceOut()">-->
                        <!--<i></i>-->
                    <!--</div>-->
                <!--</li>-->

                <!--<li>-->
                    <!--身份证号-->
                    <!--<div class="li_right">-->
                        <!--<input type="text" class="identityNo"  @input="idcardChange()" :value="identityNo" pattern="[0-9a-zA-Z]*"   @blur="checkId()">-->
                        <!--<i></i>-->
                    <!--</div>-->
                <!--</li>-->
            </ul>
            <!--<div class="imgBox"  >-->
                <!--<template v-if="!isEdit">-->
                <!--<img v-if="identityFile1!=''"  :src="identityFile1" alt="" @click="upload(1)">-->
                <!--<img v-else="" src="../../../images/positive.png" alt="" @click="upload(1)">-->
                <!--<img v-if="identityFile2!=''" :src="identityFile2" alt="" @click="upload(2)">-->
                <!--<img v-else="" src="../../../images/negative.png" alt="" @click="upload(2)">-->
                <!--</template>-->
            <!--</div>-->
        </div>
        <div class="joinStep_bottom">
            <div class="join_agre">
                提交审核，即表示您同意遵守<span>《好一点专家入驻协议》</span>我们会尽快对您的资质进行审核，审核通过后将以好一点客服消息通知您
            </div>
            <div class="join_sub">
                提交审核
            </div>
        </div>

        <!--协议-->
        <div class="weui-mask" @touchmove.prevent>
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
                <div class="agre_btn">我知道了</div>
            </div>
        </div>


        <!--<div class="joinStep_nor_btn" :class="{joinStep_per_btn:check_step(false)}"   @click="msgSubmit()">下一步</div>-->

    </div>
</template>

<script type="es6">
    import mobile from './mobile.vue';
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                showLoad:false,
                sex:'请选择性别',
                initCityValue:'请选择所在城市',
                sexIndex:'',
                defaultCity: '[330000, 330100, 330102]',
                provinceName: '',
                cityName: '',
                areaName: '',
                isLunar: 0,
                year: '',
                month: '',
                day: '',
                provinceId: '',
                cityId: '',
                areaId: '',
                lunarDateData:[],
                solarDateDate:[],
                isLeapMonth:false,
                birthday:'',
                user:'',
                alioss:null,
                uploadpicinfo:null,
                identityNo:'',
                identityFile1:'',
                identityFile2:'',
                email:'',
                mobileVal:'123',
                realName:'',
                nickName:'',
                mobileBox:false,
                idcard:'',
                ema : /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/,
                isEdit:false


            }
        },
        mounted: function () {
            this.isEdit=this.$route.query.edit;
            if(this.$route.query.edit){
                this.isEdit=true;
            }else{
                this.isEdit=false;
            }

             xqzs.wx.setConfig(this);
            let realNameVal = $('.realName').val()
            this.realName = realNameVal
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



            xqzs.wx.setConfig(this);
            let _this = this;

            xqzs.wx.setConfig(_this);
            this.getUserInfo();

            this.lunarDateData=xqzs.dateTime.getLunarData(1949,2017);
            this.solarDateDate= xqzs.dateTime.getSolarData(1949,2017);
        },
        methods: {
            checkId:function () {
                let identityNo= $(".identityNo").val();
                return xqzs.string.isCardID(identityNo)
            },
            getUserInfo:function () {
                let _this = this;
                //用户信息
                _this.showLoad = true;
                this.$http({
                    method: 'GET',
                    type: "json",
                    url: web.API_PATH + 'user/find/by/user/Id/_userId_',
                }).then(function (data) {//es5写法
                    _this.showLoad = false;
                    if (data.data.data !== null) {
                        _this.user = eval(data.data.data);
                        _this.realName =  _this.user.realName;
                        _this.identityNo = _this.user.idcard ;
                        _this.sex=_this.user.sex==1?'男':'女';
                        _this.cardType=_this.user.cardType;
                        _this.email = _this.user.email;
                        _this.mobileVal = _this.user.mobile;
                        _this.birthday = _this.user.birthday;

                        if (_this.birthday) {
                            let date = _this.birthday.split(',');
                            _this.year = date[0];
                            _this.month = date[1];
                            _this.day = date[2];
                            if( _this.user.isLunar==1||_this.user.isLunar==2){
                                _this.isLunar=true;
                                _this.yearN = date[0]+'年';
                                _this.monthN =  calendar.toChinaMonth(date[1]);
                                if(_this.user.isLunar==2) {
                                    _this.isLeapMonth=true;
                                    _this.monthN= "闰"+ _this.monthN;
                                }
                                _this.dayN = calendar.toChinaDay(date[2]);
                            }

                        }
                        _this.provinceName = _this.user.provinceName;
                        _this.cityName = _this.user.cityName;
                        _this.areaName = _this.user.areaName;
                        _this.provinceId = _this.user.provinceId;
                        _this.cityId = _this.user.cityId;
                        _this.areaId = _this.user.areaId;
                        _this.defaultCity = [_this.provinceId, _this.cityId, _this.areaId];
                        this.getExpertByUserId();
                    }
                }, function (error) {
                    //error
                });
            },
            getExpertByUserId:function () {
                let _this=this;
                this.$http.get(web.API_PATH + 'come/expert/query/detail/by/userId/_userId_' ).then(function (data) {//es5写法
                    if (data.body.status == 1&&data.body.data!=null) {
                        _this.faceUrl = data.data.data.faceUrl;
                        if( data.data.data.nickName&& data.data.data.nickName!='') _this.nickName = data.data.data.nickName;
                    }
                }, function (error) {
                });
            },
            changeRealName:function () {
                let realNameVal = $('.realName').val()
                this.realName = realNameVal
            },
            changeNickName:function () {
                let nickName = $('.nickName').val()
                this.nickName = nickName
            },
            fouceOut:function () {
                console.log('shiqushiqu');
                let _this = this;
                let emailVal = $('.email').val()
                if (!_this.ema.test(emailVal)){
                    xqzs.weui.tip("请填写正确的邮箱");
                }else {
                    _this.email = emailVal
                }
                console.log(_this.email)
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
                        _this.sex=result[0].label
                        _this.sexIndex = result[0].value
                        console.log(_this.sex)
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

                        },
                        id: 'cascadePicker'
                    });
                });

            },
            lutSelect:function (v) {
                let _this= this;
                if(v==0){
                    if( !this.isLunar) return ;

                    if(this.birthday&&this.birthday!=''){
                        let date = this.birthday.split(',');
                        let solar=  calendar.lunar2solar(parseInt(date[0]),parseInt(date[1]),parseInt(date[2]),_this.isLeapMonth); //阳历
                        this.birthday= solar.cYear+","+solar.cMonth+"," +solar.cDay ; //阳历
                        console.log(solar)
                        _this.year = solar.cYear;
                        _this.month = solar.cMonth;
                        _this.day = solar.cDay;
                    }
                    this.isLunar=false;

                }else if(v==1){
                    if( this.isLunar) return ;
                    this.isLunar=true;
                    if(this.birthday&&this.birthday!=''){
                        let date = this.birthday.split(',');
                        let lunar=  calendar.solar2lunar(date[0],date[1],date[2]); //农历
                        console.log(lunar)
                        this.birthday= lunar.lYear+","+lunar.lMonth+"," +lunar.lDay  //农历
                        _this.isLeapMonth=lunar.isLeap;
                        _this.yearN =  lunar.lYear+"年";
                        _this.monthN = lunar.IMonthCn;
                        _this.dayN =lunar.IDayCn;
                        _this.year = lunar.lYear;
                        _this.month = lunar.lMonth;
                        _this.day = lunar.lDay;
                        if(lunar.isLeap){
                            _this.month = lunar.lMonth+"_1";
                        }
                    }


                }
            },
            showDate: function () {
                let _this = this;
                let defaultValue = [1988, 1, 1];
                if (this.year != '' && this.month != '' && this.day != '') {
                    defaultValue = [this.year, this.month, this.day]
                }

                console.log(defaultValue)

                if (this.isLunar) {

                    weui.picker(  this.lunarDateData, {
                        depth: 3,
                        defaultValue: defaultValue,
                        id:"id"+Math.random(),
                        onChange: function (result) {
                            console.log(result);
                        },
                        onConfirm: function (result) {
                            _this.yearN = result[0].label;
                            _this.monthN = result[1].label;
                            _this.dayN = result[2].label;
                            //闰月
                            let monthValue =  result[1].value;
                            if(typeof(monthValue)=="string"&&monthValue.indexOf("_")){
                                _this.isLeapMonth=true;
                                monthValue=result[1].value.split("_")[0];
                            }else{
                                _this.isLeapMonth=false;
                            }
                            _this.year = result[0].value;
                            _this.month = result[1].value;
                            _this.day = result[2].value;
                            _this.birthday = result[0].value + ',' +monthValue + ',' + result[2].value;

                        },
                    });

                } else {

                    weui.picker(  this.solarDateDate, {
                        depth: 3,
                        defaultValue: defaultValue,
                        id:"id"+Math.random(),
                        onChange: function (result) {
                            console.log(result);
                        },
                        onConfirm: function (result) {
                            _this.year = result[0].value;
                            _this.month = result[1].value;
                            _this.day = result[2].value;
                            _this.birthday = result[0].value + ',' + result[1].value + ',' + result[2].value;

                        },
                    });


                }
            },
            upload:function (v) {
                let _this=this;
                xqzs.wx.takePhotos(['camera','album'],1,_this.uploadpicinfo,_this.alioss,function (filecount) {
                    _this.showLoad=true;

                },function (json,ix) {
                    _this.showLoad=false;
                    if(v==1){
                        _this.identityFile1 = json.data.path;
                    }else{
                        _this.identityFile2 = json.data.path;
                    }


                },function (e) {
                    console.info(e);
                })
            },
            idcardChange:function (v) {
                let _this = this;
                let identityNo = $(".identityNo").val();
                console.log( _this.identityNo )
                cookie.set('identityNo',identityNo)
                _this.identityNo = identityNo
            },
//            emailChange:function () {
//                let _this = this;
//                let ema = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
//                let emailVal = $('.email').val()
//                if (!ema.test(emailVal)){
//                    xqzs.weui.tip("请填写正确的邮箱");
//                }else {
//                    _this.email = emailVal
//                }
//
//            },
            goMobile:function () {
                $('.mobile_box').show()
            },
            check_step:function (showTip) {
                let _this = this;
                let re=true;
                let tip = '';
                if (_this.realName == '') {
                    re = false;
                    tip = "请填写真实姓名";
                } else if (_this.mobileVal == '') {
                    re = false;
                    tip = "请填写手机号码";
                } else if (_this.provinceName == '') {
                    re = false;
                    tip = "请选择常驻城市";
                } else if (_this.identityNo == '') {
                    re = false;
                    tip = "请填写身份证号";
                } else if (!_this.checkId()) {
                    re = false;
                    tip = "请填写正确的身份证号码";
                }else  if(!_this.isEdit){
                    if (_this.identityFile1 == '') {
                        re = false;
                        tip = "请上传身份证正面照";
                    } else if (_this.identityFile2 == '') {
                        re = false;
                        tip = "请上传身份证反面照";
                    }
                }

                console.log(showTip)
                if (showTip && !re) {
                    console.log(showTip)
                    xqzs.weui.tip(tip)
                }
                return re;
            },
            msgSubmit: function () {

                let _this = this;
                if(!_this.check_step(true)){
                    return;
                }
                _this.showLoad= true;
                let mobileVal = $('.li_right .mobile').text();
                let nickName = _this.nickName;


                let msg = {
                    "id": _this.user.id,
                    "realName": _this.realName,
                    "nickName": nickName,
                    "sex": _this.sexIndex,
                    "countryId": 0,
                    'identityNo':_this.identityNo,
                    'cardImage':[unescape(cookie.get("identityFile1")),unescape(cookie.get("identityFile2"))],
                    "provinceId": _this.provinceId,
                    "cityId": _this.cityId,
                    "areaId": _this.areaId,
                    "isLunar":_this.isLunar?_this.isLeapMonth?2:1:0,
                    'mobile':mobileVal,
                    'email':_this.email
                };
                let url = "come/expert/register";
                if(_this.isEdit){
                    msg.expertId=cookie.get('expertId');
                    msg.userId= _this.user.id;
                    url = "come/expert/modify";
                }
                console.log(msg);


                _this.$http.post(web.API_PATH + url, msg)
                    .then(
                        (response) => {
                            console.log(response)
                            _this.showLoad= false;
                            let gourl =  "/answer/join/joinmore";
                            if(_this.isEdit){
                                gourl +="?edit=true"
                            }
                            _this.$router.replace(gourl)
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
    .join_stepBox .step_detailBox li{height: 2.94rem;line-height:2.94rem;color:rgba(69, 75, 84, 1);border-bottom: 1px solid rgba(224,224,225,1);padding:0 0.88235rem;font-size: 0.8235rem;position: relative;}
    .join_stepBox .step_detailBox li .li_right{float: right;color:rgba(69, 75, 84, 0.7);padding-right:1.5rem;font-size: 0.76471rem; width:50%;overflow: hidden;text-align: right;text-overflow: ellipsis;white-space: nowrap;}
    .li_right input{border:0;outline: none;text-align: right;height:80%;}
    .join_stepBox .li_right i{background: url('../../../images/arrow.png');width: 0.94rem;  height: 0.94rem;  background-size: 0.94rem;  position: absolute;  right: 0.88235rem;  top: 50%;margin-top: -0.47rem;  }
    .joinStep_bottom{padding:1.76471rem 0.88235rem;}
    .join_agre{color:rgba(53, 58, 66, 1);font-size: 0.70588rem;line-height: 1rem;margin-bottom: 1.8rem;}
    .join_agre span{color:rgba(255, 99, 0, 1)}
    .join_sub{height:2.94rem;line-height: 2.94rem;color:rgba(255, 255, 255, 1);background: rgba(254, 122, 3, 1);font-size: 1.0588rem;border-radius:6.17rem;text-align: center }
    .agre_box{width:86%;position: absolute;top:20%;left:50%;margin-left: -46%;background: #fff;padding:0.88235rem 3%;}
    .agre_box h2{color:RGBA(69, 75, 84, 1);font-size: 1.0588rem;line-height: 1.47rem;text-align: center;margin-bottom: 0.88235rem;}
    .agre_box .agre_content{color:RGBA(69, 75, 84, 1);font-size: 0.76471rem;line-height: 1.176471rem;background: RGBA(69, 75, 84, 0.05)}

    .join_stepBox .lut_box{position: absolute;top:0;left:5rem}
    .join_stepBox .lut{float: left;background: #ececec;color: rgba(36,37,61,1);height: 1.76471rem;line-height: 1.76471rem;padding: 0 0.588235rem;margin-top: 0.35294rem;font-size: 0.8235rem;}
    .join_stepBox .lut.on{float: left;background: linear-gradient(to right, rgba(255,158,25,1), rgba(253,114,6,1));color: #fff;}
    .imgBox{padding-right: 0.88235rem;padding-top:2.35rem;height:6.0294rem;margin-bottom: 4rem}
    .imgBox img{display: block;width: 9.4rem;float: left;height:6.0294rem}
    .imgBox img:nth-of-type(2){float: right}
    .join_stepBox .joinStep_nor_btn{width:100%;background: linear-gradient(rgba(255,158,25,0.5),rgba(253,115,1,0.5));    font-size: 1.0588235rem;text-align: center;line-height: 2.5294rem;color:rgba(255,255,255,1)}
    .join_stepBox .joinStep_per_btn{background: linear-gradient(rgba(255,158,25,1),rgba(253,115,1,1)); }
</style>
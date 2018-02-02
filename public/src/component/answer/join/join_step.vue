<template >
    <div class="join_stepBox">
        <v-mobile class="mobile_box" style="display: none"></v-mobile>
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title>入驻心理咨询师</div>
        <header>
            <img src="../../../images/joinHeaderImg.png" alt="">
            <div class="li_right">
                <input type="text"  placeholder="请上传头像">
                <i></i>
            </div>
            <div style="clear: both"></div>
        </header>
        <div class="step_detailBox">
            <ul>
                <li>
                    资质证书
                    <div class="li_right" @click="setLevel()">
                        <div>{{level}}</div>
                        <i></i>
                    </div>
                </li>

                <li>
                    咨询师昵称
                    <div class="li_right" @click="setNickname()">
                        <div>{{nickName}}</div>
                        <i></i>
                    </div>
                </li>

                <li>
                    手机号码
                    <div class="li_right" @click="goMobile()">
                        <div>{{mobileVal}}</div>
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
                            <span v-if="!(provinceName&&cityName&&areaName)">{{initCityValue}}</span>
                            <span v-if="provinceName">{{provinceName}}</span>
                            <span v-if="cityName">{{cityName}}</span>
                            <span v-if="areaName">{{areaName}}</span>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li>
                   一句话签名
                    <div class="li_right" @click="setSign()">
                        <div>{{sign}}</div>
                        <i></i>
                    </div>
                </li>

                <li>
                    个人简介
                    <div class="li_right" @click="setPerson()">
                        <div>{{personal}}</div>
                        <i></i>
                    </div>
                </li>

                <li>
                    擅长领域
                    <div class="li_right" @click="setGoodat()">
                        <div>
                            <template v-if="showType.length==0">{{goodAt}}</template>
                            <template v-if="showType.length!=0">
                                <span v-for="item in showType" style="margin-left: 0.294rem">{{item}}</span>
                            </template>
                        </div>
                        <i></i>
                    </div>
                </li>

                <li>
                    擅长领域描述
                    <div class="li_right" @click="goGoodatDetail()">
                        <div>{{goodatDetail}}</div>
                        <i></i>
                    </div>
                </li>

                <li>
                    专业培训经历
                    <div class="li_right" @click="setExperience()">
                        <div>{{experience}}</div>
                        <i></i>
                    </div>
                </li>

                <li>
                    提问酬金
                    <div class="li_right" @click="goPrice()">
                        <div>{{askPrice}}</div>
                        <i></i>
                    </div>
                </li>

                <li>
                    限时免费偷听时间
                    <div class="li_right" @click="goFreeTime()">
                        <div>{{freeTime}}</div>
                        <i></i>
                    </div>
                </li>
            </ul>
        </div>
        <div class="joinStep_bottom">
            <div class="join_agre">
                提交审核，即表示您同意遵守<span @click="showAgre()">《好一点专家入驻协议》</span>我们会尽快对您的资质进行审核，审核通过后将以好一点客服消息通知您
            </div>
            <div class="join_sub">
                提交审核
            </div>
        </div>

        <!--协议-->
        <div class="weui-mask" @touchmove.prevent v-if="agreFlag">
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
                showLoad:false,
                sexIndex:'',
                defaultCity: '[330000, 330100, 330102]',
                provinceName: '',
                cityName: '',
                areaName: '',
                provinceId: '',
                cityId: '',
                areaId: '',
                user:'',
                alioss:null,
                uploadpicinfo:null,
                identityFile1:'',
                identityFile2:'',
                email:'',
                mobileVal:'',
                realName:'',
                sex:'请选择性别',
                initCityValue:'请选择所在城市',
                level:'请选择资质',
                nickName:'',
                sign:'',
                personal:'',
                goodAt:'选择自己擅长的领域',
                goodatDetail:'',
                experience:'请填写培训经历',
                askPrice:'请设置提问酬金',
                freeTime:'请设置免费偷听时间',
                mobileBox:false,
                idcard:'',
                ema : /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/,
                isEdit:false,
                agreFlag:false,
                showType:[]
            }
        },
        mounted: function () {
            this.registerInfo()
            this.getClassList()
        },
        methods: {
            registerInfo:function () {
                this.nickName = cookie.get('register_nickname')||'请填写昵称';
                this.sign = cookie.get('register_sign')||'请填写个人签名';
                this.mobileVal = cookie.get('register_mobile')||'请输入手机号';
                this.personal = cookie.get('register_personal')||'请填写个人简介';
                this.goodatDetail = cookie.get('register_goodatDetail')||'请描述自己擅长的领域';
                this.experience = cookie.get('register_experience')||'请填写培训经历';
            },
            showAgre:function () {
                this.agreFlag = true
            },
            hideAgre:function () {
                this.agreFlag = false
            },
            setLevel:function () {
              this.$router.push('./level')
            },
            setNickname:function () {
                this.$router.push('./nickname')
            },
            setSign:function () {
                this.$router.push('./sign')
            },
            setPerson:function () {
                this.$router.push('./personal')
            },
            setGoodat:function () {
                this.$router.push('./good/at')
            },
            goGoodatDetail:function () {
                this.$router.push('./good/detail')
            },
            getClassList:function () {
                let _this=this;
                _this.$http.get(web.API_PATH + 'come/listen/question/class/list' ).then(function (data) {//es5写法
                    if (data.body.status == 1) {
                        _this.types= data.body.data;
                        let questionClassId = cookie.get("questionClassId")
                        if(questionClassId&&questionClassId!=''){
                            let ids=  questionClassId.split(",");
                            for(let i=0;i<_this.types.length;i++){
                                for(let j =0;j<ids.length;j++){
                                    if(_this.types[i].id==ids[j]){
                                        _this.showType.push(_this.types[i].title);
                                    }
                                }
                            }
                            console.log(_this.showType)
                        }else{
                        }
                    }
                }, function (error) {
                });
            },
            setExperience:function () {
                this.$router.push('./experience')
            },
            goPrice:function () {
                this.$router.push('./price')
            },
            goFreeTime:function () {
                this.$router.push('./freetime')
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
                    'cardImage':[unescape(cookie.get("identityFile1")),unescape(cookie.get("identityFile2"))],
                    "provinceId": _this.provinceId,
                    "cityId": _this.cityId,
                    "areaId": _this.areaId,
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
    .join_stepBox .step_detailBox li .li_right{float: right;color:rgba(69, 75, 84, 0.7);padding-right:1.5rem;font-size: 0.76471rem; width:60%;}
    .li_right>div{
        width:100%;overflow: hidden;text-align: right;text-overflow: ellipsis;white-space: nowrap;
    }
    .li_right input{border:0;outline: none;text-align: right;height:80%;}
    .join_stepBox .li_right i{background: url('../../../images/arrow.png');width: 0.94rem;  height: 0.94rem;  background-size: 0.94rem;  position: absolute;  right: 0.88235rem;  top: 50%;margin-top: -0.47rem;  }
    .joinStep_bottom{padding:1.76471rem 0.88235rem;}
    .join_agre{color:rgba(53, 58, 66, 1);font-size: 0.70588rem;line-height: 1rem;margin-bottom: 1.8rem;}
    .join_agre span{color:rgba(255, 99, 0, 1)}
    .join_sub{height:2.94rem;line-height: 2.94rem;color:rgba(255, 255, 255, 1);background: rgba(254, 122, 3, 1);font-size: 1.0588rem;border-radius:6.17rem;text-align: center }
    .agre_box{width:86%;position: absolute;top:10%;left:50%;margin-left: -46%;background: #fff;padding:0.88235rem 3%;border-radius: 0.588235rem;}
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
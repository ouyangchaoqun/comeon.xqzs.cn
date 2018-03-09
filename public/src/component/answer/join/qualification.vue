<template >
    <div style="height: 100%" class="wbg answer_join_quali">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div v-title>入驻心理咨询师</div>
        <div>
        <div class="checks">
            <div class="level_types">
                <div class="item"  v-for="(item,index) in level" @click="getItemClass(index)"  :index="index" ><div class="level_item " :class="{checked_item:item.name==jobTitle}"></div><span >{{item.name}}</span></div>
                <div class="otherInput"><input name="otherTitle"  class="changeotherTitle" :value="otherTitle" @input="changeotherTitle()"></div>
                <div class="clear"></div>
            </div>

        </div>

            <div >
                <div class="number">
                    <div>
                        <span>证书编号</span>
                        <div class="input_box">
                            <input class="certificateNo"
                                   name="certificateNo" :value="certificateNo" @input="changeCertificateNo()"
                                   pattern="[0-9a-zA-Z]*"/>
                        </div>
                    </div>
                </div>
                <div class="photo_img">
                    <div class="img" @click="upload(1)">
                        <template  v-if="certificateFile1==''" >
                            <div>
                                <b>+</b>
                            </div>
                            <p>上传证书头像页面</p>
                        </template>

                        <img v-if="certificateFile1!=''" :src="certificateFile1" />
                    </div>
                    <div class="img" @click="upload(2)" v-if="!otherType">
                        <template  v-if="certificateFile2==''" >
                            <div>
                                <b>+</b>
                            </div>
                            <p>上传证书信息页面</p>
                        </template>

                        <img  v-if="certificateFile2!=''" :src="certificateFile2" />
                    </div>
                </div>
            </div>
        </div>
         <div class="over_nor_btn over_per_btn" @click="qua_sure()">保存</div>
    </div>
</template>

<script type="es6">


    import showLoad from '../../include/showLoad.vue';
    import answerTopStep from "./include/top_step.vue";
    export default {
        data() {
            return {
                otherType:false,
                certificateNo:'',
                showLoad:false,
                canGoNext:false,
                certificateFile1:'',
                certificateFile2:'',
                alioss:null,
                uploadpicinfo:null,
                level:[
                    {name:'国家二级咨询师'},
                    {name:'国家三级咨询师'},
                    {name:'注册系统咨询师'},
                    {name:'注册系统督导师'},
                    {name:'其它'}
                ],
                jobTitle:''
            }
        },


        mounted: function () {


            this.initOss();
            let _this=this;
            _this.getExpertByUserId();
            xqzs.wx.setConfig(this);

        } ,
        methods:{
            getExpertByUserId:function () {
                let _this=this;
                _this.expertId = cookie.get('expertId');
                _this.showLoad = true;
                _this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+ _this.expertId+'/_userId_' ).then(function (data) {//es5写法
                    _this.showLoad = false;
                    _this.expertInfo=data.data.data;
                    _this.certificateFile1=    _this.expertInfo.certificateFile1;
                    _this.certificateFile2=    _this.expertInfo.certificateFile2;
                    _this.certificateNo=    _this.expertInfo.certificateNo;
                    let jobTitle=   _this.expertInfo.jobTitle;
                    if(jobTitle&&jobTitle!=''){
                        let jobTitleC=jobTitle;
                        console.log(jobTitleC+"++++++++++++++++++")
                        if(jobTitleC.indexOf("其它")>=0){
                            let t= jobTitleC.split("：");
                            _this.jobTitle=t[0];
                            _this.otherType=true ;
                            console.log(t[1]+"++++++++++++++++++");
                            _this.otherTitle= t[1];
                        }else{
                            _this.jobTitle = jobTitleC;
                        }
                    }


                }, function (error) {
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
            upload:function (v) {
                let _this=this;
                xqzs.wx.takePhotos(['camera','album'],1,_this.uploadpicinfo,_this.alioss,function (filecount) {
                    _this.showLoad=true;

                },function (json,ix) {
                    _this.showLoad=false;


                    if(v===1){
                        _this.certificateFile1 = json.data.path;
                    }else{
                        _this.certificateFile2 = json.data.path;
                    }
                    console.log(json.data)
                },function (e) {
                    console.info(e);
                })
            },


            changeotherTitle: function (v) {
                if(!this.otherType){
                    $(".changeotherTitle").val('')
                    return
                }
                let otherTitle = $(".changeotherTitle").val();
                this.otherTitle = '其它：' + otherTitle;

            },


            changeCertificateNo:function (v) {
                this.certificateNo =  $(".certificateNo").val()
            },
            getItemClass:function (index) {
                let _this = this;
                let v = _this.level[index].name
                console.log(index)
                console.log(v)
                this.jobTitle=v;
                cookie.set("jobTitle",escape(v));
                if(v=='其它'){
                    _this.otherType=true;
                }else {
                    _this.otherType=false;
                }


            },
            check_step:function (showTip) {
                let _this = this;
                let re= true;
                let tip = '';
                if(_this.jobTitle==''){
                    re=false;
                    tip="请选择资质";
                }else if(_this.certificateNo==''){
                    re=false;
                    tip="请填写证件编号";
                }else if(_this.certificateFile1==''){
                    re=false;
                    tip="请上传证件照";

                }else if(!_this.otherType&&_this.certificateFile2==''){
                    re=false;
                    tip="请上传证件照";
                }
                if(showTip&&!re){
                    xqzs.weui.tip(tip);
                }
                return re;
            },

            qua_sure:function () {
                let _this = this;
                if(!_this.check_step(true)){
                    return;
                }
                let data={
                    userId:"_userId_",
                    expertId:cookie.get("expertId"),
                    jobTitle:_this.jobTitle,
                    certificateNo:_this.certificateNo,
                    certificateFile:[_this.certificateFile1,_this.certificateFile2]

                };
                _this.$http.post(web.API_PATH + 'come/expert/modify/certificate', data)
                    .then(
                        (response) => {
                            _this.$router.go(-1)
                        }
                    );

            },
            
        }


    }
</script>
<style>


    .answer_join_quali .answer_join_top_box{position: static;margin-bottom: 0.50rem;padding-top: 1.02rem;}
    .answer_join_quali .sub_title{font-size: 0.24rem; color:#999;line-height: 1; text-align: center;}
    .answer_join_quali  .weui-cells:after, .weui-cells:before,.answer_join_quali  .weui-cell:before{ display: none}
    .answer_join_quali  .weui-check__label{ width: 42%; float:left; font-size: 0.28rem !important}
    .answer_join_quali  .upload { text-align: center; color:#A9A7A7}
    .answer_join_quali .upload span{ color:#09bb07; font-size: 0.48rem;line-height: 0.80rem; vertical-align: bottom
    }
    .number{line-height:0.80rem }
    .number>div{display: flex;padding-left:0.60rem;position: relative}
    .number span{color:rgba(36,37,61,1);font-size: 0.28rem;}
    .weui-check__label:active{ background: none}
    .answer_join_quali .photo_img{display: flex;display: -webkit-flex;justify-content:space-between;padding-top:0.40rem;}
    .answer_join_quali .photo_img .img{background:#f4f4f7;width:44%;height:2.30rem;position: relative}
    .answer_join_quali .photo_img .img:nth-of-type(1){margin-left: 4%;}
    .answer_join_quali .photo_img .img:nth-of-type(2){margin-right: 4%;}
    .answer_join_quali .photo_img .img img{ max-height: 100%; max-width: 100%; position: absolute; top:0; left:0; z-index: 100}
    .img div{width:0.88rem;height:0.88rem;border-radius: 50%;background: #fff;text-align: center;line-height: 0.80rem;position: absolute;top:25%;left:50%;margin-left:-0.44rem;}
    .img div b{color:rgba(253,114,6,1);font-size: 0.54rem}
    .img p{color:#A9A7A7;font-size: 0.24rem;text-align: center;position: absolute;width:100%;top:75%;}
    .checks .level_types{padding:0 0.30rem;padding-top: 0.40rem}
    .checks .level_types .item{width:38%;float: left;margin:0 6%;margin-bottom: 0.30rem;position: relative;font-size:0.28rem;color:#666}
    .checks .level_types .item span{margin-left: 0.50rem;}
    .checks .level_item{margin:0;height:0.28rem;width:0.28rem;position: absolute;border-radius: 50%;border:0.02rem solid #D2D2D2;top:0.05rem;left:0}
    .checks .checked_item{border-color: rgba(253,114,6,1)}
    .checks .checked_item::after{  content: '';  width:0.20rem;  height: 0.20rem;  background: rgba(253,114,6,1);  border-radius: 50%;  position: absolute;  top:50%;  margin-top:-0.10rem;  left: 50%;  margin-left:-0.10rem;  }
    .photo,.number .weui-cell__hd{font-size: 0.28rem;color:#666}
    .quaBtn{line-height: 0.86rem;background:linear-gradient(rgba(255,158,25,0.5),rgba(253,115,1,0.5));text-align: center;color:rgba(255,255,255,1);font-size: 0.36rem;position: absolute;bottom:0;width:100%; }


    .certificateNo{background: rgba(245,245,245,1);height:0.80rem;width: 100%;border-radius: 0.10rem;font-size: 0.26rem;padding-left:0.20rem;}
    .number .input_box{width:65%;position: absolute;left:2.00rem;}

    .level_types { position: relative}
    .otherInput input{background: rgba(245,245,245,1);height:0.61rem;width: 40%;border-radius: 0.10rem;font-size: 0.26rem;padding-left:0.20rem;  position: absolute; bottom:0.17rem; left:2.00rem;}
</style>


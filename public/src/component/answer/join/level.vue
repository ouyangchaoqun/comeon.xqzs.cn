<template >
    <div class="setLevel_Box">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure" @click="subLevel()">确定</div>
        </div>
        <div class="level_types">
            <div class="level_type" :class="{checked_type:item.name==jobTitle}" v-for="(item,index) in level" @click="getItemClass(index)"  :index="index" >{{item.name}}</div>
            <div style="clear: both"></div>
        </div>
        <div class="level_detail">
            <div class="level_number">
                <span>证书编号：</span>
                <input type="text" placeholder="请填写" :value="certificateNo" v-model="certificateNo"/>
            </div>
            <div class="level_photo">
                <span>资质证书：</span>
                <div class="photo_box" @click="upload()">
                    <template v-if="certificateFile1==''" >
                        <img class="addIcon"  src="../../../images/level_addIBtnIcon.png" alt="">
                        <p>请上传有姓名编号的一页，确保内容清晰可进见</p>
                    </template>
                    <img class="imgFile" v-if="certificateFile1!=''" :src="certificateFile1Show" />
                </div>
            </div>
        </div>
    </div>
</template>

<script type="es6">
    import showLoad from '../../include/showLoad.vue';
    export default {
        data() {
            return {
                level:[
                    {name:'国家二级咨询师'},
                    {name:'国家三级咨询师'},
                    {name:'注册系统咨询师'},
                    {name:'注册系统督导师'},
                    {name:'其它'}
                ],
                jobTitle:'',
                certificateFile1:'',
                certificateFile1Show:'',
                certificateNo:'',
                uploadpicinfo:null,
                alioss:null,
                edit:'',
                showLoad:false
            }
        },
        props: {
            user:{
                type:Object
            }
        },
        mounted: function () {
            this.edit= this.$route.query.edit;
            this.initOss();
            if(this.edit==1){
                this.getExpertInfo();
            }
        },
        methods: {
            getExpertInfo:function () {
                let expertId = cookie.get('expertId');
                this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+expertId+'/_userId_').then(function (data) {
                    if (data.body.status == 1) {
                        let showInfo = data.data.data;
                        for (let i=0;i<this.level.length;i++){
                            if(this.level[i].name==showInfo.jobTitle){
                                this.getItemClass(i)
                            }
                        }
                        let fileWidth = parseInt($('.photo_box').width());
                        let fileHeight = parseInt($('.photo_box').height());
                        this.certificateNo = showInfo.certificateNo;
                        this.certificateFile1Show = showInfo.certificateFile1+'?x-oss-process=image/resize,m_lfit,,h_'+fileHeight+',w_'+fileWidth;

                    }
                }, function (error) {
                });
            },
            getItemClass:function (index) {
                let _this = this;
                let v = _this.level[index].name
                this.jobTitle=v;
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
            upload:function () {
                let _this=this;
                xqzs.wx.takePhotos(['camera','album'],1,_this.uploadpicinfo,_this.alioss,function (filecount) {
                    _this.showLoad=true;

                },function (json,ix) {
                    _this.showLoad=false;
                    let fileWidth = parseInt($('.photo_box').width());
                    let fileHeight = parseInt($('.photo_box').height());
                    _this.certificateFile1 = json.data.path
                    _this.certificateFile1Show = json.data.path+'?x-oss-process=image/resize,m_lfit,,h_'+fileHeight+',w_'+fileWidth;
                },function (e) {
                })
            },
            backStep:function () {
                this.$router.go(-1)
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
                }
                else if(_this.certificateFile1==''){
                    re=false;
                    tip="请上传证件照";
                }
                if(showTip&&!re){
                    xqzs.weui.tip(tip);
                }
                return re;
            },

            subLevel:function () {
                let _this = this;
                if(!_this.check_step(true)){
                    return;
                }
                if(_this.edit==1){
                    //修改
                    console.log('修改')
                    let url = "come/expert/modify";
                    let msg = {
                        userId:this.user.id,
                        jobTitle:_this.jobTitle,
                        certificateNo:_this.certificateNo,
                        certificateFile1:_this.certificateFile1,
                        id:this.user.id,
                        expertId:cookie.get('expertId')
                    };
                    _this.$http.post(web.API_PATH + url, msg)
                        .then(
                            (response) => {

                            }
                        );
                }
                cookie.set('reg_jobTitle',escape(_this.jobTitle),1)
                cookie.set('reg_certificateNo',_this.certificateNo,1)
                cookie.set('reg_certificateFile1',_this.certificateFile1,1)
                _this.showLoad= true;
                _this.$router.go(-1);
            }
        },
        components:{
            'v-showLoad': showLoad,
        },


    }
</script>
<style>
    .setLevel_Box{
        background: #fff;
    }
    .setLevel_Box .level_types{
        padding:1.47rem 0.0066% 0.529rem 0.0066%;
    }
    .level_types .level_type{
        width:42.4%;
        height:2.35rem;
        line-height: 2.4rem;
        color:  RGBA(74, 74, 74, 0.5);
        font-size: 0.88235rem;
        border:1px solid RGBA(69, 75, 84, 0.1);
        border-radius: 0.294rem;
        text-align: center;
        float: left;
        margin:0 3.33% 1.1764rem 3.33%;
    }
    .level_types .checked_type{
        color:RGBA(254, 122, 3, 1);
        border-color: RGBA(254, 122, 3, 1);
    }
    .level_detail{padding: 0 0.88235rem;}
    .level_detail>div{
        line-height: 1.235rem;
    }
    .level_detail span{
        color:RGBA(69, 75, 84, 1);
        font-size: 0.88235rem;
    }
    .level_number{
        border-bottom:1px solid RGBA(69, 75, 84, 0.09);
        margin-bottom: 0.70588rem;
        padding-bottom:0.70588rem;
    }
    .level_number input{
        text-align: center;
        width:70%;
        height:1.235rem;
        font-size: 0.88235rem;
        color:RGBA(69, 75, 84, 0.7) !important;;
    }
    .level_photo span{
        display: block;
        padding-bottom:0.70588rem;
    }
    .level_photo .photo_box{
        min-height:8rem;
        border:1px solid RGBA(69, 75, 84, 0.09);
        margin:0 auto;
        position: relative;
    }
    .photo_box p{
        color:RGBA(69, 75, 84, 0.5);
        font-size: 0.70588rem;
        text-align: center;
        position: absolute;
        width:100%;
        top:60%;
    }
    .photo_box .addIcon{
        width:2.52rem;
        position: absolute;
        left:50%;
        margin-left: -1.26rem;
        top:20%;
    }
    .photo_box .imgFile{
        display: block;
        max-width: 100%;
        margin:0 auto;
    }
</style>
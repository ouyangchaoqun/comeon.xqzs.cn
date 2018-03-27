<template >
    <div class="setLevel_Box">
        <v-showLoad v-if="showLoad"></v-showLoad>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure sure_nor" v-if="jobTitle==''||certificateNo==''||certificateFile1==''">确定</div>
            <div class="joinSet_sure" @click="subLevel()" v-if="jobTitle!=''&&certificateNo!=''&&certificateFile1!=''">确定</div>
        </div>
        <div class="level_types">
            <div class="level_type" :class="{checked_type:item.name==jobTitle}" v-for="(item,index) in level" @click="getItemClass(index)"  :index="index" >{{item.name}}</div>
            <div style="clear: both"></div>
        </div>
        <div class="level_detail">
            <div class="level_number">
                <span>证书编号：</span>
                <input type="text" placeholder="请填写" :value="certificateNo" class="certificateNo" @input="changeCertificateNo()"/>
            </div>
            <div class="level_photo">
                <span>资质证书：</span>
                <div class="photo_box" @click="upload()">
                    <template v-if="certificateFile1==''" >
                        <img class="addIcon"  src="http://oss.xqzs.cn/resources/psy/level_addIBtnIcon.png" alt="">
                        <p>请上传有姓名编号的一页，确保内容清晰可进见</p>
                    </template>
                    <img class="imgFile" v-if="certificateFile1!=''" :src="certificateFile1Show" />
                </div>
            </div>
        </div>
        <div style="visibility: hidden">
            <div>{{certificateFile1}}-----</div>
            <div>{{certificateFile1Show}}****</div>
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
                jobTitle:cookie.get('reg_jobTitle')?unescape(cookie.get('reg_jobTitle')):'',
                certificateFile1:cookie.get('reg_certificateFile1')?unescape(cookie.get('reg_certificateFile1')):'',
                certificateFile1Show:'',
                certificateNo:cookie.get('reg_certificateNo')?cookie.get('reg_certificateNo'):'',
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
            let _this = this;
            if(_this.edit==0){
                _this.getPhoto(_this.certificateFile1)
            }
            if(_this.edit==1){
                _this.getExpertInfo();
            }
        },
        methods: {
            changeCertificateNo:function (v) {
                this.certificateNo =  $(".certificateNo").val()
            },
            getPhoto:function (val) {
                let fileWidth = parseInt($('.photo_box').width());
                let fileHeight = parseInt($('.photo_box').height());
                this.certificateFile1Show = val +'?x-oss-process=image/resize,m_lfit,,h_'+fileHeight+',w_'+fileWidth;
            },
            getExpertInfo:function () {
                let expertId = cookie.get('expertId');
                this.$http.get(web.API_PATH + 'come/expert/query/detail/for/edit/'+expertId+'/_userId_').then(function (data) {
                    if (data.body.status == 1) {
                        let showInfo = data.data.data;
                        if(this.jobTitle!=''){
                            for (let i=0;i<this.level.length;i++){
                                if(this.level[i].name==showInfo.jobTitle){
                                    this.getItemClass(i)
                                }
                            }
                        }
                        this.certificateNo = showInfo.certificateNo;
                        this.certificateFile1 = showInfo.certificateFile1;
                        this.getPhoto(this.certificateFile1);

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
            subLevel:function () {
                let _this = this;
                if(_this.edit==1){
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
                setTimeout(function () {
                    _this.$router.go(-1);
                },300)

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
        padding:0.50rem 0.0066% 0.18rem 0.0066%;
    }
    .level_types .level_type{
        width:42.4%;
        height:0.80rem;
        line-height: 0.82rem;
        color:  RGBA(74, 74, 74, 0.5);
        font-size: 0.30rem;
        border:0.02rem solid RGBA(69, 75, 84, 0.1);
        border-radius: 0.10rem;
        text-align: center;
        float: left;
        margin:0 3.33% 0.40rem 3.33%;
    }
    .level_types .checked_type{
        color:RGBA(86, 196, 245,1);
        border-color: RGBA(86, 196, 245,1);
    }
    .level_detail{padding: 0 0.30rem;}
    .level_detail>div{
        line-height: 0.42rem;
    }
    .level_detail span{
        color:RGBA(69, 75, 84, 1);
        font-size: 0.30rem;
    }
    .level_number{
        border-bottom:0.02rem solid RGBA(69, 75, 84, 0.09);
        margin-bottom: 0.24rem;
        padding-bottom:0.24rem;
    }
    .level_number input{
        text-align: center;
        width:70%;
        height:0.42rem;
        font-size: 0.30rem;
        color:RGBA(69, 75, 84, 1) !important;;
    }
    .level_photo span{
        display: block;
        padding-bottom:0.24rem;
    }
    .level_photo .photo_box{
        min-height:2.72rem;
        border:0.02rem solid RGBA(69, 75, 84, 0.09);
        margin:0 auto;
        position: relative;
    }
    .photo_box p{
        color:RGBA(69, 75, 84, 0.5);
        font-size: 0.24rem;
        text-align: center;
        position: absolute;
        width:100%;
        top:60%;
    }
    .photo_box .addIcon{
        width:0.86rem;
        position: absolute;
        left:50%;
        margin-left: -0.43rem;
        top:20%;
    }
    .photo_box .imgFile{
        display: block;
        max-width: 100%;
        margin:0 auto;
    }
</style>

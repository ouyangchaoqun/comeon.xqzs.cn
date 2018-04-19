<template>
    <div style="height: 100%" class="imgs_box">
        <div v-title class='hide_title'>上传图片</div>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure sure_nor" v-if="checkedIndex<0">确定</div>
            <div class="joinSet_sure" @click="setFreeTime()">确定</div>
        </div>
            <div class="tips">传五张照片</div>

        <div  :class="{'weui-actionsheet':true,'weui-actionsheet_toggle':activeFlag}">
            <div class="weui-actionsheet__menu">
                <div class="weui-actionsheet__cell" @click = "getCam()" id="btn">拍照</div>
                <div class="weui-actionsheet__cell" @click = "getPho()">从手机相册选择</div>
            </div>
            <div class="weui-actionsheet__action">
                <div class="weui-actionsheet__cell" @click = "hideAction()">取消</div>
            </div>
        </div>
        <div  class="swiper-container edit_lists" style="height:280px;">
            <div class="swiper-wrapper">
                <div class="swiper-slidetrue" ><!--optionFrist-->
                    <div class="optionFrist_box">
                        <div v-for="(pic,index) in pictures" v-bind:key="index" class="upload-images">
                            <div v-if="pic.isloading" class="item">
                                <div class="weui-loading"></div>
                            </div>
                            <div class="item item-image" v-else>
                                <div class="del-img" @click="deletePic(index,pic.pictype)"></div>
                                <img v-bind:src="smallPic(pic.image.path)" @click="viewBigPics(pic.image.path)"/>
                            </div>
                        </div>
                        <div v-if="pictures.length<5" class="item item-up-btn">
                            <img class="optionAdd" src="//oss.xqzs.cn/resources/mood/tjzp.gif" alt="" @click="showAction()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>


    export default {
        data() {
            return {
                maskFlag:false,
                activeFlag:false,
                pictures: [],
                maxPhotoCount: 5,
                alioss: null,//optionFrist end
                uploadpicinfo: {
                    token: '',
                    smallpic: '',
                    middlepic: '',
                    removepicurl: web.BASE_PATH + 'api/removepicture',
                    uploadbase64url: web.BASE_PATH + 'api/upfilebase64',
                    aliossgeturl: web.BASE_PATH + 'aliyunapi/oss_getsetting'
                },
            }
        },

        methods: {
            showPositionList:function(t){
                return this.showModule == t;
            },

            showAction:function () {
                this.uploadImage(['camera','album']);
            },
            //小图格式
            smallPic:function (src) {
                return src + xqzs.oss.Size.fill(65,65);
            },
            //展示大图
            viewBigPics:function (src) {
                var pics = [];
                src = src + xqzs.oss.Size.resize(750, 750)
                for (var i = 0, l = this.pictures.length; i < l; i++) {
                    if (this.pictures[i].image) {
                        pics.push(this.pictures[i].image.path + xqzs.oss.Size.resize(750, 750));
                    }
                }
                xqzs.wx.previewImage(src, pics)
            },
            //图片占位
            _showloadingpic:function (id) {

                id = 'up_loading_'+id;
                this.pictures.push({isloading:true,id:id});
                console.log(this.pictures);
            },
            //删除图片
            deletePic:function (i,tp) {
                    this.pictures = this.pictures.slice(0, i).concat(this.pictures.slice(i + 1, this.pictures.length));
                    //
                    console.info('删除图片');

            },
            //关闭蒙层
            hideAction:function () {
                this.activeFlag = false
                this.maskFlag = false
            },
            //判断加载中
            _fillloadingpic:function (id,data) {
                id = 'up_loading_'+id;
                for(var i =0,l=this.pictures.length;i<l;i++) {
                    if (id == this.pictures[i].id) {
                        this.pictures[i].isloading = false;
                        this.pictures[i]['pictype'] = '';
                        this.pictures[i].image = data;
                    }
                }
            },
            uploadImage:function (sourceType) {
                let that = this;
                var id = 'uf_'+new Date().getTime();
                //
                xqzs.wx.takePhotos(sourceType,that.maxPhotoCount -  this.pictures.length,that.uploadpicinfo,that.alioss,function (filecount) {
                    console.log(" xqzs.wx.takePhotos")
                    for(var i=0;i<filecount;i++){
                        console.log("xuanhuan"+i)
                        that._showloadingpic(id+i);
                    }
                },function (json,ix) {
                    console.log("_fillloadingpic")
                    console.log(ix)
                    that._fillloadingpic(id+ix,json.data);
                },function (e) {
                    console.info(e);
                })
            },

        },
        mounted: function () {


        },
        activated: function () {
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

        beforeDestroy: function () {

        },


    }
</script>
<style>
    .imgs_box  .tips {
        font-size: 0.28rem;
        color: #999;
        text-align: center;
    }



    .imgs_box .img_up_box  .item_box{
        margin-top: 0.4rem;
    }
    .imgs_box .img_up_box .img_item .close{
        width: 0.4rem;
        height: 0.4rem;
        background: red;
        border-radius: 50%;
        position: absolute;
        right: -0.2rem;
        top:-0.2rem;
    }
    #Edit .sogo-enter-active {
        animation-name: sgo;
        animation-duration: .2s;
    }

    @keyframes sgo {
        0% {
            transform: translate3d(100%, 0, 0);
            -webkit-transform: translate3d(100%, 0, 0);
        }
        100% {
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
            z-index: 2;
        }
    }

    .mood_box {
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }

    .grids_box {
        margin: 0 0.30rem;
    }

    .weui-grids {
        height: 5.52rem
    }

    .weui-grids .grid_33 {
        height: 1.84rem
    }

    .weui-grids {
        overflow: inherit
    }

    .weui-grid {
        padding: 0;
        position: relative;
    }

    .scene_top {
        margin-top: 0.368rem;
        margin-bottom: 0.170rem;
    }

    .scene_top img {
        width: 0.640rem;
        height: 0.640rem;
        display: block;
        margin: 0 auto;
    }

    .scene_box .grid_25 {
        width: 25%;
    }

    .mood_box {
        background: url(//oss.xqzs.cn/resources/mood/add_mood_bj.jpg) #fff;
        background-size: cover;
        border-top-left-radius: 0.10rem;
        border-top-right-radius: 0.10rem;
        position: absolute;
        bottom: 0;
        width: 100%;
        padding-top: 0.46rem;
        padding-bottom: 0.50rem;
        overflow: hidden;
        z-index: 11118;
    }

    .scene_box {
        width: 100%;
        display: block;
    }

    .moodBox_header {
        font-size: 0.36rem;
        color: #666666;
        text-align: center;
        margin-bottom: 0.480rem;
    }

    .grid_top {
        margin-bottom: 0.160rem;
    }

    .grid_top img {
        display: block;
        width: 0.800rem;
        height: 0.800rem;
        margin: auto;
        margin-top: 0.24rem;
    }

    .grid_bottom {
        font-size: 0.24rem;
    }

    .scene_col {
        color: #333333;
    }

    .grid_row1 {
        color: #fe6103;
    }

    .grid_row2 {
        color: #747474;
    }

    .grid_row3 {
        color: #0eb80e;
    }

    .mood_box.go_down {
        -webkit-animation: go_down .2s forwards;
        animation: go_down .2s forwards;
    }

    @keyframes go_down {
        0% {
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
        }
        100% {
            transform: translate3d(0, 100%, 0);
            -webkit-transform: translate3d(0, 100%, 0);
        }
    }

    .mood_box.go_up {
        -webkit-animation: go_up .2s forwards;
        animation: go_up .2s forwards;
    }

    @keyframes go_up {
        0% {
            transform: translate3d(0, 100%, 0);
            -webkit-transform: translate3d(0, 100%, 0);
        }
        100% {
            transform: translate3d(0, 0, 0);
            -webkit-transform: translate3d(0, 0, 0);
        }
    }

    .edit_option .open_box {
        border-left: 0.02rem solid #eee;
        height: 0.48rem !important;
        font-size: 0.24rem;
        color: #999;
        -webkit-box-flex: 1.68;
        -webkit-flex: 1.68;
        flex: 1.68;
        overflow: auto;
        padding-top: 0.03rem;
    }

    .edit_option .open_box .open_btn {
        float: left;
        display: inline-block;
        margin-left: 0.17rem;
    }

    .edit_option .open_box .open_btn:nth-child(2) {
        margin-left: 0.14rem;
    }

    .edit_option .open_box .open_btn:before {
        content: ' ';
        border: 0.02rem solid #999;
        margin-right: 0.07rem;
        margin-top: 0.07rem;
        display: block;
        height: 0.20rem;
        width: 0.20rem;
        border-radius: 50%;
        float: left;
    }

    .edit_option .open_box .open_btn.on:before {
        border: 0.02rem solid #0b900c;
        background: #0BB20C
    }



    .swiper-container, .swiper-container .swiper-wrapper {
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0)
    }

    .addEdit img {
        width: 0.80rem;
        height: 0.80rem;
        margin-left: 0.30rem;
        float: left;
        margin-top: 0.24rem;
    }



    .optionFrist_box {
        position: relative;
        padding-top: 0.40rem;
        padding-left: 0.30rem;
    }

    .optionFrist_box .optionjt {
        left: 0.48rem;
    }

    .optionAdd {
        height: 1.96rem;
        width: 1.96rem;
    }

    .weui-actionsheet__cell {
        font-size: 0.32rem;
        color: #545454;
        height: 0.98rem;
        line-height: 0.98rem;
        padding: 0;
    }

    .upload-images {
        float: left
    }

    .upload-images .item-image img, .upload-images .item, .item-up-btn {
        width: 1.96rem;
        height: 1.96rem;
    }

    .upload-images .item, .item-up-btn {
        float: left
    }

    .upload-images .item {
        margin-top: 0;
        margin-right: 0.30rem;
    }

    /*.item-up-btn{text-align: center;padding-top: 0.26rem;height: 1.34rem}*/
    .weui-loading {
        width: 0.60rem;
        height: 0.60rem;
        margin: 0.24rem 0 0 0.24rem;
    }

    .upload-images .item-image {
        position: relative
    }

    .upload-images .item-image .del-img {
        position: absolute;
        right: -0.20rem;
        top: -0.20rem;
        width: 0.40rem;
        height: 0.40rem;
        background-image: url(//oss.xqzs.cn/resources/mood/close.png);
        background-size: 0.40rem;
    }

    .funnyPictures .upload-images, .funnyPictures .item-image {
        width: auto;
        height: auto;
    }

    .funnyPictures .item-image img {
        max-width: 4.00rem;
        height: auto !important;
        width: auto !important;
    }


    .edit_loc img {
        float: left;
        display: block;
        width: 0.20rem;
        margin-top: 0.14rem;
        margin-left: 0.20rem;
        margin-right: 0.10rem;
    }



    .edit_option > div {
        height: 0.71rem;
    }

    .edit_option div {
        -webkit-box-flex: 0.9;
        -webkit-flex: 0.9;
        flex: 0.9;
        position: relative;
    }

    .edit_option img {
        display: block;
        margin: 0 auto;
    }

    .edit_option .optionjt {
        position: absolute;
        left: 50%;
        margin-left: -0.24rem;
        width: 0.48rem;
        bottom: -0.04rem;
        visibility: hidden;
    }

    .edit_option .optionjtFlag {
        visibility: visible;
    }


</style>
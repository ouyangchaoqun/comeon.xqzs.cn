<template>
    <div style="height: 100%" class="imgs_box">
        <div v-title class='hide_title'>上传图片</div>
        <div class="joinSet_top">
            <div class="joinSet_cancel" @click="backStep()">取消</div>
            <div class="joinSet_sure sure_nor" v-if="checkedIndex<0">确定</div>
            <div class="joinSet_sure" @click="setImgs()">确定</div>
        </div>
        <div class="tips">工作照,会议照,证书照,学习照……（最多可上传5张）</div>
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
                <div class="optionAdd" @click="showAction()"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script>


    export default {
        data() {
            return {
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
        props: {
            user:{
                type:Object
            },
            expert:{
                type:Object
            }
        },

        methods: {
            showPositionList: function (t) {
                return this.showModule == t;
            },

            showAction: function () {
                this.uploadImage(['camera', 'album']);
            },
            //小图格式
            smallPic: function (src) {
                return src + xqzs.oss.Size.fill(65, 65);
            },
            //展示大图
            viewBigPics: function (src) {
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
            _showloadingpic: function (id) {

                id = 'up_loading_' + id;
                this.pictures.push({isloading: true, id: id});
                console.log(this.pictures);
            },
            //删除图片
            deletePic: function (i, tp) {
                this.pictures = this.pictures.slice(0, i).concat(this.pictures.slice(i + 1, this.pictures.length));
                //
                console.info('删除图片');

            },

            //判断加载中
            _fillloadingpic: function (id, data) {
                id = 'up_loading_' + id;
                for (var i = 0, l = this.pictures.length; i < l; i++) {
                    if (id == this.pictures[i].id) {
                        this.pictures[i].isloading = false;
                        this.pictures[i]['pictype'] = '';
                        this.pictures[i].image = data;
                    }
                }
            },
            uploadImage: function (sourceType) {
                let that = this;
                var id = 'uf_' + new Date().getTime();
                //
                xqzs.wx.takePhotos(sourceType, that.maxPhotoCount - this.pictures.length, that.uploadpicinfo, that.alioss, function (filecount) {
                    console.log(" xqzs.wx.takePhotos")
                    for (var i = 0; i < filecount; i++) {
                        console.log("xuanhuan" + i)
                        that._showloadingpic(id + i);
                    }
                }, function (json, ix) {
                    console.log("_fillloadingpic")
                    console.log(ix)
                    console.log(json)

                    that._fillloadingpic(id + ix, json.data);
                }, function (e) {
                    console.info(e);
                })
            },
            backStep: function () {
                this.$router.go(-1);
            },

            setImgs: function () {
                let _this=this;
                var List=[];
                for(var i=0;i<_this.pictures.length;i++){
                    List.push(_this.pictures[i].image.id)
                }
                console.log(List)
                xqzs.api.put(this,"come/expert/picture/add",{"userId":_this.user.id,"pictures":List},function (response) {
                    if(response.data.status==1){
                        xqzs.weui.toast("success","提交成功")
                        _this.$router.go(-1);
                    }else{
                        xqzs.weui.toast("fail","失败")
                    }
                },)
            }

        },

        activated: function () {
            let _this=this;
            this.uploadpicinfo = {
                token: xqzs.string.guid(),
                smallpic: xqzs.constant.PIC_SMALL,
                middlepic: xqzs.constant.PIC_MIDDLE,
                removepicurl: web.BASE_PATH + 'api/removepicture',
                uploadbase64url: web.BASE_PATH + 'api/upfilebase64',
                aliossgeturl: web.BASE_PATH + 'api/aliyunapi/oss_getsetting'
            };
            this.alioss = new aliyunoss({
                url: this.uploadpicinfo.aliossgeturl,
                token: this.uploadpicinfo.token
            });
            let expert_id=cookie.get("expertId");
            console.log('expert_idexpert_idexpert_id'+expert_id)
                xqzs.api.get(this,"come/expert/query/detail/for/edit/"+expert_id+"/_userId_",function (res) {
                _this.pictures=res.body.data.pictures;
                console.log(_this.pictures+'************')
            })
            //加载咨询师图片

        },

        beforeDestroy: function () {

        },


    }
</script>
<style>
    .imgs_box .tips {
        background: #fff;
        font-size: 0.28rem;
        color: #999;
        text-align: center;
        padding: 0.3rem;
    }

    .swiper-container, .swiper-container .swiper-wrapper {
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0)
    }

    .optionFrist_box {
        position: relative;
        padding-left: 0.46rem;
        background: #fff;
        padding-bottom: 0.3rem;
    }

    .optionFrist_box .optionjt {
        left: 0.48rem;
    }

    .optionAdd {
        height: 1.96rem;
        width: 1.96rem;
        background: url("http://oss.xqzs.cn/resources/runmini/camon.png") no-repeat center;
        background-size: 40%;
        background-color: #EEEEEE;
    }

    .upload-images {
        float: left;
        position: relative;
        margin-top: 0.2rem;
        margin-right: 0.3rem;
    }

    .item-up-btn {
        margin-top: 0.3rem;
        margin-right: 0.3rem;
    }

    .upload-images .item-image img, .upload-images .item, .item-up-btn {
        width: 1.96rem;
        height: 1.96rem;

    }

    .upload-images .item {
        border: 0.02rem solid #eee;
    }

    .upload-images .item, .item-up-btn {
        float: left
    }

    /*.item-up-btn{text-align: center;padding-top: 0.26rem;height: 1.34rem}*/
    .imgs_box  .item .weui-loading {
        width: 0.60rem;
        height: 0.60rem;
        left: 50%;
        margin-left: -0.3rem;
        top: 50%;
        margin-top: -0.3rem;
        position: absolute;
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


</style>
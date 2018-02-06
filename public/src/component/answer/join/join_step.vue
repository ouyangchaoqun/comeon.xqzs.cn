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
                            <template>{{sex}}</template>
                        </div>

                        <i></i>
                    </div>
                </li>

                <li @click="areaPicker()">
                    所在城市
                    <div class="li_right">
                        <div>
                            <span v-if="isModify==0&&provinceName==''">请选择所在城市</span>
                            <template v-if="provinceName!=''">
                                <span>{{provinceName}}</span>
                                <span>{{cityName}}</span>
                                <span>{{areaName}}</span>
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
        <div class="weui-mask"  v-show="agreFlag" >
            <div class="agre_box">
                <h2>好一点专家入驻协议</h2>
                <div class="agre_content">
                    <p>
                        尊敬的心理咨询师、精神科医生、心理治疗师（以 下简称“专家”），欢迎您入驻杭州蓝江网络科技有 限公司（以下简称“本公司”）开发与运营的“好一点 ”心理咨询平台（包含但不限于微信公众号、微信小 程序，以下简称“本平台”或“平台”）。您在入驻或 使用本平台服务前，请仔细阅读本协议。您使用本 公司任何服务，均意味着本协议已经生效，您已经 认可本协议的全部条款，将自愿遵守本协议的规定， 并接受本公司的统一管理。
                    </p>
                    <h3>一、定义</h3>
                    <p>1、服务平台：指本公司开发和运营的“好一点”心 理咨询服务平台，即“本平台”。</p>
                    <p>2、专家：指同意并遵守本协议规定，通过本平台 向平台用户提供心理咨询解答服务的具有相应资质 的心理咨询师、精神科医生、心理治疗师、催眠师、 婚姻家庭指导师等专业人士。</p>
                    <p> 3、服务：指专家通过本平台向平台用户提供的心理辅导、心理咨询等服务。</p>
                    <p>4、付费服务：指专家在服务平台开通的收费服务，服务价格由专家自由定价，经本公司审核通过后生效。</p>
                    <h3>二、免责条款</h3>
                    <p>1、不论在何种情况下，本公司均不对由于第三方原因（包括但不限于互联网连接故障、手机、通讯或其他系统的故障、黑客攻击等）及不可抗力（包括但不限于罢工、劳动争议、暴乱、起义、骚乱、生产力或生产资料不足、火灾、洪水、风暴、爆炸、战争、政府行为等）而造成的不能服务或延迟服务承担责任。</p>
                    <p>2、你明确并同意，本公司不对一下原因造成的任何损失（包括但不限于利润损失，服务中断，资料灭失及后续赔偿费用）承担任何形式的责任：</p>
                    <p>1）未经授权的存取或修改数据或数据的传输。</p>
                    <p>2）平台用户及其他第三方通过平台所作的陈述或行为。</p>
                    <h3>三、协议内容及生效</h3>
                    <p>1、本协议内容包括协议正文及所有服务平台已经发布的或将来可能发布的各类规则。所有规则为协议不可分割的一部分，与协议正文具有同等法律效力。</p>
                    <p>2、专家在使用本平台提供的各项服务的同时，承受接受并遵守各项相关规则的规定。本公司有权根据需要不定时制定、修改本协议或各类规则，如本协议有任何变更，本公司将提前通过服务平台以公告形式通知专家。如专家不同意修订、变更后的协议或规则，专家应立即终止使用本平台的一切服务并注销账户，若专家在协议修订、变更生效后依然使用本平台的服务或继续保有本平台的账户，则视为自动接收修订、变更后的协议或规则。</p>
                    <p>3、专家签署或在线点击接收本协议时本协议成立，但并不导致本协议立即生效，经过本公司审核通过专家的相应资质并向专家发出账户开通通知后，在专家首次以其专有账户登录平台时，本协议即在专家和本公司之间产生法律效力。本协议不涉及专家与平台用户之间因平台服务而产生的法律关系及法律纠纷。</p>
                    <h3>四、证明文件提交</h3>
                    <p>1、专家欲使用本公司的服务平台，必须向本公司提交相关证明。具体证明信息内容如下：</p>
                    <p>（1）执业资格</p>
                    <p>拥有执业所在地法律要求的执业资格。</p>
                    <p>在中国大陆执业的服务专家：需持有国家二级心理咨询师、国家三级心理咨询师，或精神科医生、心理治疗师、催眠师或婚姻家庭指导师等从业资格；</p>
                    <p>在港澳台地区及海外执业的专家：需持有当地法律认可的执业资格。</p>
                    <p>（2）专业培训</p>
                    <p>心理咨询师或婚姻家庭指导师已完成至少一个心理咨询或婚姻家庭辅导的系统培训。</p>
                    <p>（3）网上入驻专家需要提供：</p>
                    <p>1）个人形象照（必须）</p>
                    <p>2）执业所在地法律要求的执业资格证（必须）</p>
                    <p>3）身份证明（必填）</p>
                    <p>4）证书编号（非必填）</p>
                    <p>5）培训证明（非必填）</p>
                    <p>6）擅长领域（必填）</p>
                    <p>专家本人必须承诺提供给“好一点”的资料真实、准确、有效、合法。若有违反，由此引发的一切后果，将由专家本人独立承担。</p>
                    <p>2、信息变更的通知：协议期内，上述信息（包括相关证明文件）发生任何变更，您可以修改入驻资质，并由“好一点”进行审核通过后，即可生效。</p>
                    <p>3、责任条款：若专家未能按本协议的规定及时通知本平台其应提供的信息，专家应承担由此产生的全部责任。专家不可撤销的保证其向本公司提供的全部信息、证明文件均真实、准确且不存在超过时效问题（即保证所有证明文件在整个合同履行期间都处于有效期内）。如因上述原因发生纠纷或被相关国家主管机关处罚，专家应当独立承担全部责任，如给本公司（包括其合作伙伴、代理人、职员等）造成损失的，专家应承担相应的赔偿责任。专家亦同意，在此种情况下，平台有权直接从专家的平台账户中扣除相应的款项。</p>
                    <h3>五、专家的权力和义务</h3>
                    <p>1、专家应符合相应的申请入驻条件，且向本公司提供真实、合法、准确、有效的身份资料，并保证其诸如联系电话、联系地址等内容的有效性及安全性，保证本公司可以通过上述联系方式与专家进行联系。同时，专家也有义务在相关资料实际变更时及时更新入驻资质。</p>
                    <p>2、专家在本平台上的言行需遵守国家法律、法规等规范性文件、行业规范等自律性文件及本公司的各项规则和要求，不得违背社会公共利益或公共道德，不损害他人的合法权益，不违反本协议及相关规则。如果因此产生任何法律后果的，专家应以自己的名义独立承担法律责任。</p>
                    <p>3、专家不得开展任何形式的不正当竞争（包括但不限于以虚构或歪曲事实的方式不当评价其他本平台上的专家、采取不正当方式制造或提高自身的评价度及信用度、采取不正当方式制造或提高（降低）其他本平台上的专家的评价度及信用度等）。本公司有权对专家违反本条规范的行为进行指正、警告、中止或终止其专有账户的使用。</p>
                    <p>4、专家在本平台上的工作不得影响专家在所属单位或者机构中的本职工作。专家因此与专家所属单位或者机构产生的纠纷，由专家自行承担，本公司不负任何法律责任。</p>
                    <p>5、专家应就其在签署和履行本协议过程中所熟知或获取的本公司的有关信息和资料予以保密。未经本公司书面同意，专家不得向第三方透露相关信息。该保密义务不因本协议的无效、解除或终止而无效、解除或终止。如本协议无效、解除或终止，专家应继续持续履行相关的保密协议。</p>
                    <p>6、在本平台注册的专家在通过平台进行服务时不得发出带有个人电话、QQ账号、微信账户、其他类似即时通讯工具账户、电子邮箱以及真实地址等联系方式的内容，若由此出现被扰或其他伤害情况，相关责任由专家自行承担。</p>
                    <h3>六、用户服务基本原则</h3>
                    <p>专家通过本平台向平台用户提供服务或者付费服务过程中，应始终严格遵守基本原则，具体内容如下：</p>
                    <p>1、专家有责任向用户说明心理咨询工作者的保密原则，以及应用这一原则的限度；</p>
                    <p>2、心理咨询工作中的有关资料，包括个案记录、录音和其他资料，均属专业信息，应在用户允许、严格保密的情况下进行保存，不得列入其他资料之中；</p>
                    <p>3、当专家在接受卫生、司法或公安机关询问时，不得做虚伪的陈述或报告，由此引发的法律责任，本公司不负任何责任。</p>
                    <h3>七、本公司的权力和义务</h3>
                    <p>1、本公司承诺在服务平台所开展的服务均取得国家规定的相关资质，并受到中国法律保护；本公司有义务在现有技术上维护本平台上服务的正常运行，并努力提升和改进技术，使专家的服务得以顺利进行。</p>
                    <p>2、对专家在入驻使用服务中所遇到的与服务或入驻有关的问题及反映的情况，本公司应及时作出回复。</p>
                    <p>3、因本平台提供信息之服务的特殊性，本公司无法对专家的服务行为以及服务质量进行事先审查，但如存在下列情况，本公司将展开事后审查：</p>
                    <p>1）第三方通知本公司，认为某个具体专家或具体服务事项可能存在重大问题；</p>
                    <p>2）专家或第三方向本公司告知服务平台上有违法或不当行为的；本公司以普通非专业人员的知识水平标准对相关内容进行判别，可以认为这些内容或行为具有违法可能或不当性质的，本公司有权根据不同情况选择删除相关信息和中止或终止相关专家专有账户的使用，并追究相关法律责任。</p>
                    <p>4、本公司有权对专家的身份信息及服务行为进行审核，发现或有合理理由怀疑身份信息或服务行为中存在任何问题或怀疑，均有权向专家发出询问或要求改正的通知，中止或终止相关专家专有账户的使用。</p>
                    <p>5、本公司有权根据服务平台上用户对专家回答问题质量的评价以及其他专业、学术指标将专家与其他本平台注册的专家进行排名。专家明确并同意，相关排名并不代表本平台对于专家专业能力的评价。</p>
                    <p>6、专家需向使用服务的平台用户明确：通过本平台提供的所有语音信息皆不作为诊断结果和医疗的证据，用户只能作为参考。本公司与专家均不承担用户使用该等服务可能产生的医疗风险和责任。</p>
                    <p>7、本公司有义务保护专家的保密资料及隐私信息（需要公布在平台中的资料和信息除外）的安全，不得泄露专家的隐私，但根据法律、法规要求及本协议约定予以公告的除外。</p>
                    <p>8、本公司有权根据业务调整情况将本协议项下的全部权利义务一并转让给本公司关联公司，此种情况将会提前以平台公告的形式通知专家。专家承诺对此不持有异议。</p>
                    <p>9、本公司对服务平台上注册的专家所发布的内容进行监督，发现不符合上述规定的内容，本公司有权利给予屏蔽或删除，并保留对不符合规定言论进行处理而不通知发布者的权利。</p>
                    <h3>八、结算方式</h3>
                    <p>1、专家明确并同意，本平台有权待其收取平台用户支付的服务费，并有权自服务费中直接扣取应由本平台收取的相应运营费用。</p>
                    <p>2、专家的服务费将自动存入专家平台账号中，专家可在平台上提交提现申请，本公司将在3个工作日内审核提现申请，审核通过后直接将服务费转入专家的微信钱包。上述专家结算，本公司有权按照本公司制定的结算规则执行。本公司有权制定和修改规则。如有变动，则会提前一周以通知方式告知专家。</p>
                    <h3>九、协议的终止</h3>
                    <p>1、本公司单方解除权：</p>
                    <p>1）如专家违反本公司的任何规则或本协议中的任何承诺或保证，包括但不限于本协议项下的任何约定，本公司都有权立刻终止本协议，且按有关规则要求专家进行赔偿；</p>
                    <p>2）若专家屡次违反本协议或相关规定，经本公司提醒仍不改正的；或因专家的言行对本公司造成损失的，本公司有权终止与专家的协议，并或终止相关专家专有账户的使用。</p>
                    <p>3）专家连续终止使用后一年或专家通过电子邮件形式告知本公司，本协议将自动终止。在协议终止前、账号注销前，专家均可随时自行再次开通使用。</p>
                    <p>2、本协议规定的其他协议终止条件发生或实现导致本协议终止的，本协议终止。</p>
                    <p>3、协议终止后有关事项的处理：</p>
                    <p>1）协议终止后，本公司没有义务为专家保留本公司账户中或与之相关的任何信息，或转发任何未曾阅读或发送的信息给专家或第三方。亦不就终止协议而对专家或任何第三者承担任何责任；</p>
                    <p>2）无论本协议因何原因终止，在协议终止前的行为所导致的任何赔偿和责任，专家应完全且独立地承担；</p>
                    <p>3）协议终止后，本公司有权保留该专家的注册数据及以前的服务行为记录。如专家在协议终止前在服务平台上存在违法行为或违反协议的行为，本公司仍可行使本协议所规定的权利。</p>
                    <h3>十、协议期限</h3>
                    <p>本协议自专家确认接受之时起成立，在满足相应约定的情况下生效，除非本协议规定的终止条件发生，本协议将持续有效。双方另有约定的除外。</p>
                    <h3>十一、保密条款</h3>
                    <p>1、本协议所称商业秘密包括但不限于本协议、任何补充协议所述内容及在合作过程中涉及的其他产生商业利益的信息。任何一方未经商业秘密提供方同意，均不得将该信息向任何第三方披露、传播、编辑或展示。本协议终止后保密双方仍承担此条款下的保密义务。</p>
                    <p>2、因对方书面同意以及国家、行政、司法强制行为而披露商业秘密的，披露方不承担责任；该商业秘密已为公众所知悉的，披露方不承担责任。</p>
                    <h3>十二、违约责任</h3>
                    <p>1、专家同意赔偿由于违反本协议而给本公司造成的任何损失（包括由此产生的全额的诉讼费用和律师费）。专家同意任何因其通过本平台发布的言论、资料给平台用户造成的损失由专家自行全部承担，与本平台无关。</p>
                    <p>2、专家承诺不得因任何原因、以任何方式（包括但不限于洽谈、教唆、强迫、威胁等）：（1）与咨询用户达成任何以心理咨询师向咨询用户提供心理咨询相关服务为内容的线下咨询合意；或（2）要求或是接受咨询用户将相应服务费用通过线下方式（包括但不限于现金交易、线下移动终端转账等）向其进行划转、支付。若心理咨询师违反本条款之约定，本公司有权冻结咨询师咨询款项，并根据《入驻协议》的约定要求心理咨询师承担一切后续责任。</p>
                    <p>3、除本协议及服务平台规则另有约定之外，如一方发生违约行为，守约方可以电话或者邮件通知方式要求违约方在指定的时限内停止违约行为，并就违约行为造成的损失进行索赔，如违约方未能按时停止违约行为，则守约方有权立即终止本协议。</p>
                    <h3>十三、争议解决及其他</h3>
                    <p>1、本协议之解释与适用，以及与本协议有关的争议，均应依照中华人民共和国法律予以处理。</p>
                    <p>2、任何因本协议或在本协议履行过程中产生的争议，双方均应通过友好协商解决。若无法通过协商解决，任何乙方均有权将争议提交本公司住所地有管辖权的法院诉讼解决。</p>
                    <p>3、如本协议的任何条款被视作无效或无法执行，则上述条款可被分离，其余部份则仍具有法律效力。</p>
                    <p>4、本公司于专家过失或违约时放弃本协议规定的权利的，不得视为其对专家的其他或以后同类之过失或违约行为弃权。</p>
                    <p>本协议最终归属权、解释权归杭州蓝江网络科技有限公司所有。</p>
                    <p style="text-align: right">杭州蓝江网络科技有限公司</p>
                    <p style="text-align: right">2018年1月1日</p>
                    <h3>附：《好一点专家回复规则》</h3>
                    <p>《好一点专家回复规则》</p>
                    <h3>一、专家回复原则</h3>
                    <p>1、对用户所表述的症状从心理咨询角度进行分析；</p>
                    <p>2、对用户的症状给出可能性的判断，并表述标准；</p>
                    <p>3、对其诊断的心理问题给出相应的解决方案或治疗建议；</p>
                    <p>4、给用户制定一套符合自己的日常心理保健方法；</p>
                    <p>5、让用户心理得到成长，树立自信心，为用户制定适合的解决方案。</p>
                    <h3>二、专家解答注意事项</h3>
                    <p>1、网络咨询不是面询，不能给出用户肯定的诊断结果；</p>
                    <p>2、不允许为用户推荐心理咨询机构或留自己的联系方式；</p>
                    <p>3、不可开处方（包括指导用户具体用药剂量）、推荐处方及各种符合处方范畴内的药物；</p>
                    <p>4、思路清晰，语言通俗易懂，不应出现有歧义的内容；</p>
                    <p>5、对于用户提问内容一定要详细解答，不可敷衍了事；</p>
                    <p>6、不可进行无意义、万金油、答非所问等形式的回答；</p>
                    <h3>三、绝对禁止的部分</h3>
                    <p>1、含有反动政策的；</p>
                    <p>2、含有暴力、恐怖的；</p>
                    <p>3、具有广告性质的；</p>
                    <p>4、含有人身攻击、侮辱的；</p>
                    <p>5、含有违背医学伦理道德的；</p>
                    <p>6、其他违反现行各项法律、法规与政策的。</p>
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
                sexIndex:1,
                defaultCity: '[330000, 330100, 330102]',
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
                provinceName:cookie.get('reg_provinceName')?unescape(cookie.get('reg_provinceName')):'',
                cityName:cookie.get('reg_cityName')?unescape(cookie.get('reg_cityName')):'',
                areaName:cookie.get('reg_areaName')?unescape(cookie.get('reg_areaName')):'',
                provinceId: cookie.get('reg_provinceId')?cookie.get('reg_provinceId'):'',
                cityId: cookie.get('reg_cityId')?cookie.get('reg_cityId'):'',
                areaId:cookie.get('reg_areaId')?cookie.get('reg_areaId'):'',
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
                        if(this.isModify = 1){
                            this.sex = this.user.sex==2?'女':'男';
                            this.provinceName = this.user.provinceName;
                            this.cityName = this.user.cityName;
                            this.areaName = this.user.areaName;
                            this.provinceId = this.user.provinceId;
                            this.cityId = this.user.cityId;
                            this.areaId = this.user.areaId;
                        }
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
                    }
                    cookie.set('reg_faceUrl',facePath,1)

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
                        }
                        cookie.set('reg_sex',escape(result[0].label),1)
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
                this.$router.push('./joinmobile?edit='+this.isModify);
            },
            msgSubmit: function () {
                let _this = this;
                console.log('提交')
                let url, price ,freeTime,jobTitle,introduction,experience,goodat,faceUrl,nickName,sign,provinceId,sex,questionClassId,certificateNo,certificateFile1,cityId,areaId;
                let reg_questionClassId;
                if(cookie.get("questionClassId")){
                    reg_questionClassId = cookie.get("questionClassId").split(",");
                }else{
                    reg_questionClassId=''
                }
                let reg_certificateNo = cookie.get('reg_certificateNo')?cookie.get('reg_certificateNo'):'';
                let reg_certificateFile1 = cookie.get('reg_certificateFile1')?cookie.get('reg_certificateFile1'):'';
                let classId=[];
                if(_this.isModify==1){
                    for(let i=0;i<_this.isShowInfo.domains.length;i++){
                        classId.push(_this.isShowInfo.domains[i].classId)
                    }
                }
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
                    provinceId = _this.provinceId;
                    sex = _this.sexIndex;
                    questionClassId = classId;
                    certificateNo = _this.isShowInfo.certificateNo;
                    certificateFile1 = _this.isShowInfo.certificateFile1;
                    cityId = _this.cityId;
                    areaId = _this.areaId;
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
    .join_stepBox .step_detailBox li{height: 2.94rem;line-height:2.94rem;color:rgba(69, 75, 84, 0.7);border-bottom: 1px solid rgba(224,224,225,1);padding:0 0.88235rem;font-size: 0.8235rem;position: relative;}
    .join_stepBox .step_detailBox li .li_right{float: right;padding-right:1.5rem;width:55%;color: rgba(69, 75, 84, 1) !important;}
    .li_right>div{ width:100%;text-align: right;font-size: 0.8235rem;color: rgba(69, 75, 84, 1); overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
    .join_stepBox .li_right i{background: url('../../../images/arrow.png');width: 0.94rem;  height: 0.94rem;  background-size: 0.94rem;  position: absolute;  right: 0.88235rem;  top: 50%;margin-top: -0.47rem;  }
    .joinStep_bottom{padding:1.76471rem 0.88235rem;}
    .join_agre{color:rgba(53, 58, 66, 1);font-size: 0.70588rem;line-height: 1rem;margin-bottom: 1.8rem;}
    .join_agre span{color:rgba(255, 99, 0, 1)}
    .join_sub{height:2.94rem;line-height: 2.94rem;color:rgba(255, 255, 255, 1);background: rgba(254, 122, 3, 1);font-size: 1.0588rem;border-radius:6.17rem;text-align: center }
    .agre_box{width:86%;position: absolute;top:10%;left:50%;margin-left: -46%;background: #fff;padding:0.88235rem 3%;border-radius: 0.588235rem;height:80%}
    .agre_box h2{color:RGBA(69, 75, 84, 1);font-size: 1.0588rem;line-height: 1.47rem;text-align: center;margin-bottom: 0.88235rem;}
    .agre_box .agre_content{color:RGBA(69, 75, 84, 1);font-size: 0.76471rem;background: RGBA(69, 75, 84, 0.05);padding:0.88235rem 0.764rem;margin-bottom: 1.176rem;height:75%;overflow: auto;line-height: 1.4rem}
    .agre_content h3{padding-top: 1.176rem;padding-bottom: 0.294rem;}
    .agre_content p{margin-bottom: 0.294rem;}
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
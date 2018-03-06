/**
 * Created by lanjiangweb on 2018/3/5.
 */
const listenQuestion= resolve => require(['../../component/asker/listen_question.vue'], resolve)
module.exports = {
    path: vueRoutesBaseUrl+'/asker/listen/question',
    component: listenQuestion,

}
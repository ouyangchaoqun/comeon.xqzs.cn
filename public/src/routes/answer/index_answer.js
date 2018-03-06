/**
 * Created by lanjiangweb on 2018/3/5.
 */
const indexAnswer = resolve => require(['../../component/answer/index_answer.vue'], resolve)
module.exports = {
    path: vueRoutesBaseUrl+'/answer/index/answer',
    component: indexAnswer,

}
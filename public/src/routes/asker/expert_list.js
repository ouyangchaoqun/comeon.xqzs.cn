/**
 * Created by lanjiangweb on 2018/3/5.
 */
const askerExpertList = resolve => require(['../../component/asker/expert_list.vue'], resolve)
module.exports = {
    path: vueRoutesBaseUrl+'/asker/expert/list',
    component: askerExpertList,

}
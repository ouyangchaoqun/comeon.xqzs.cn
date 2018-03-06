/**
 * Created by lanjiangweb on 2018/3/5.
 */
const listenList= resolve => require(['../../component/asker/listen_list.vue'], resolve)
module.exports = {
    path: vueRoutesBaseUrl+'/asker/listen/list',
    component: listenList,

}
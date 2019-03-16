import Report from './components/report.vue';
import Vue from 'vue';
import Search from './components/search.vue';
import PortalVue from 'portal-vue';
Vue.use(PortalVue);
Vue.component('search', Search);
const app = new Vue({
    components:{
        report: Report,
    },
    el: '#app',
    data(){
        return {

        }
    }
})
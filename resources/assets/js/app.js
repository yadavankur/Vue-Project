
require('./bootstrap');

import router  from './router'
import store from './store/index';
import jwtToken from './helpers/jwt-token';
import accounting from 'accounting';
import moment from 'moment';

import FileSaver from 'file-saver';
import AppMain   from './components/AppMain.vue'

import iView from 'iview';
import locale from 'iview/src/locale/lang/en-US';
import 'iview/dist/styles/iview.css'
import VueSweetAlert from 'vue-sweetalert'
import VueEvents from 'vue-events'

import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

Vue.use(VueEvents);
Vue.use(VueSweetAlert);
Vue.use(iView, { locale });

// when release production version
// need to add the below to
// disable the dev tools and debug
// Vue.config.devtools = false
// Vue.config.debug = false
// Vue.config.silent = true

Vue.component('appmain', AppMain);

// common lib
Vue.mixin
({  methods: 
    { getLocationDefaultSettings(payload) 
        {  this.$store.dispatch('getLocationDefaultSettings', payload) .then
            ((response) => 
            {   console.log('/resources/assets/js/app.js-getLocationDefaultSettings response=', response);
             this.defaultSettings = response.body; }
            )
            .catch((error) => { console.log('/resources/assets/js/app.js-getLocationDefaultSettings error=', error);  });
        },
     getDefaulSetting(key, settings) 
     {  console.log('/resources/assets/js/app.js-getDefaulSetting key=', key);
        let setting = settings.filter
        ( (element) => { console.log('/resources/assets/js/app.js-getDefaulSetting element=', element);
              return (element.type == key); }
        );
        console.log('/resources/assets/js/app.js-getDefaulSetting setting=', setting);
        return setting? setting[0].value: '';
     },
    getDowellNoteTypeOptions() 
     {  this.$store.dispatch('getDowellNoteTypeOptions')
                .then((response) => {
                    console.log('/resources/assets/js/app.js-getDowellNoteTypeOptions response=', response);
                    this.noteTypeOptions = response.body;
                   })
                .catch((error) => {
                    console.log('/resources/assets/js/app.js-getDowellNoteTypeOptions error=', error);
                });
     },
        hasAssignedResources(user) 
        {      let filteredTab = user.aclTabs.filter( (tab) => { return (tab.permission != 'H' && tab.permission != 'D');  });
            let filteredMenu = user.aclMenus.filter( (menu) => { return (menu.permission != 'H' && menu.permission != 'D'); });
            return !((filteredTab.length <= 0) && (filteredMenu.length <= 0));
        },
        setStatusName(value) 
        {   let statusId = parseInt(value);
            console.log('/resources/assets/js/app.js-setStatusName statusId=', statusId);
            let statusName = '';
            switch (statusId)
            {   case 2:
                    statusName = 'DELAY';
                    return '<span class="label label-danger">' + statusName + '</span>'; break;
                case 3:
                    statusName = 'DEFERRAL';
                    return '<span class="label label-danger">' + statusName + '</span>';  break;
                case 1:
                    statusName = 'NEW';
                    return '<span class="label label-info">' + statusName + '</span>';    break;
                case 4:
                    statusName = 'ON TIME';
                    return '<span class="label label-primary">' + statusName + '</span>'; break;
                case 5:
                    statusName = 'COMPLETED';
                    return '<span class="label label-success">' + statusName + '</span>'; break;
            }
            statusName = 'NEW';
            return '<span class="label label-info">' + statusName + '</span>';
        },
        downLoad(response) {
            let fileType = response.headers.get('content-type');
            let blob = new Blob([response.data], {type: fileType});
            let contentDisposition = response.headers.get('Content-Disposition') || '';
            let filename = contentDisposition.split('filename=')[1];
            filename = filename.replace(/"/g,"");
            FileSaver.saveAs(blob, filename);
        },
        parseJsObject(obj) 
        {   let result = {type: Object, default: null};
            for (let p in obj) {  if( obj.hasOwnProperty(p) ) { result[p] = obj[p];  }  }
            return result;
        },
        formatPrice(value) {  return '$' + accounting.formatNumber(value, 2);   },
        getCurrentDatetime() {  return moment();  },
        formatDatetime (value, fmt='LLL') {
            // + ' ' +  moment(value).format('LTS')
            return (value == null)
                ? ''
                : moment(value).format(fmt)
        },
        dateCompare(date1, date2) {
            // console.log('dateCompare date1=', date1);
            // console.log('dateCompare date2=', date2);
            let momentA = moment(date1, 'YYYY-MM-DD');
            let momentB = moment(date2, 'YYYY-MM-DD');
            // console.log('dateCompare momentA=', momentA);
            // console.log('dateCompare momentB=', momentB);
            return (momentA.isSameOrAfter(momentB));
        },
        isAfter(date1, date2) {
            // console.log('dateCompare date1=', date1);
            // console.log('dateCompare date2=', date2);
            let momentA = moment(date1, 'YYYY-MM-DD');
            let momentB = moment(date2, 'YYYY-MM-DD');
            // console.log('dateCompare momentA=', momentA);
            // console.log('dateCompare momentB=', momentB);
            return (momentA.isAfter(momentB));
        },
        getFormatDate(value, fmt = 'DD/MM/YYYY') {
            return ((value == null) || (value == ''))
                ? ''
                : moment(value, fmt)
        },
        formatDate (value, fmt = 'D MMM YYYY', ofmt='YYYY-MM-DD') {
            return ((value == null) || (value == ''))
                ? ''
                : moment(value, ofmt).format(fmt)
        },
        trim(str) {
            str = str.replace(/^\s+/, '');
            for (let i = str.length - 1; i >= 0; i--) {
                if (/\S/.test(str.charAt(i))) {
                    str = str.substring(0, i + 1);
                    break;
                }
            }
            return str;
        },
        isEmpty(obj) {
            // null and undefined are "empty"
            if (obj == null) return true;
            if (typeof obj == "string") obj = this.trim(obj);
            // Assume if it has a length property with a non-zero value
            // that that property is correct.
            if (obj.length > 0)    return false;
            if (obj.length === 0)  return true;

            // If it isn't an object at this point
            // it is empty, but it can't be anything *but* empty
            // Is it empty?  Depends on your application.
            if (typeof obj !== "object") return true;

            // Otherwise, does it have any properties of its own?
            // Note that this doesn't handle
            // toString and valueOf enumeration bugs in IE < 9
            for (var key in obj) {
                if (obj.hasOwnProperty(key)) {
                    return false;
                }
            }
            return true;
        },
        getAssingedLocations() {
            this.$store.dispatch('getAssignedLocationOptions')
                .then((response) => {
                    console.log('/resources/assets/js/app.js-created getAssignedLocationOptions success=', response);
                    this.setAssignedLocationOptions(response.data);
                })
                .catch((error) => {
                    console.error('/resources/assets/js/app.js-getAssignedLocationOptions error=', error);
                });
        },
        setAssignedLocationOptions(locations) {
            console.log('/resources/assets/js/app.js-setAssignedLocationOptions locations=',locations);
            let options = [];
            for (let location in locations) {
                options.push({value: locations[location].id, label: locations[location].name});
            }
            this.locationOptions = options;
        },
        getCascadeLocationServices() {
            this.$store.dispatch('getCpmCascadeServiceOptions')
                .then((response) => {
                    console.log('/resources/assets/js/app.js-created getCpmCascadeServiceOptions success=', response);
                    this.cascadeServiceOptions = response.data.locationServices;
                })
                .catch((error) => {
                    console.error('/resources/assets/js/app.js-getCpmCascadeServiceOptions error=', error);
                });
        },
        getCascadeServiceGroupOptionsOfLocation() {
            console.log('/resources/assets/js/app.js-OrderActivityListTable -> getCascadeServiceGroupOptionsOfLocation');
            let payload = {
                location: this.location,
            };
            this.$store.dispatch('getCascadeServiceGroupOptionsOfLocation', payload)
                .then((response) => {
                    console.log('/resources/assets/js/app.js-getCascadeServiceGroupOptionsOfLocation response=', response);
                    this.cascadeServiceGroupOptions = response.body.serviceGroupOptions;
                })
                .catch((error) => {
                    console.log('/resources/assets/js/app.js-getCascadeServiceGroupOptionsOfLocation error=', error);
                });
        },
    }
});
const applyOnHttp = true;

Vue.http.interceptors.push
((request, next) => 
    {  console.log('/resources/assets/js/app.js- Vue.http.interceptors.push request=', request);  //---manoj6
       if (!request.url) return;
       request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);
       if(jwtToken.getToken()) { request.headers.set('Authorization', 'Bearer '+ jwtToken.getToken());}
       // show loading
       let showLoadingBar = 'showLoadingBar' in request ? request.showLoadingBar : applyOnHttp;
       if (showLoadingBar)  store.dispatch('updateLoading', true);
       else  NProgress.start();
       next((response) => 
         {  console.log('/resources/assets/js/app.js-Vue.http.interceptors.push next response=', response);//--- manoj
            if (showLoadingBar)   store.dispatch('updateLoading', false);
            else NProgress.done();
            if(!response.ok && response.status == 444)  {   router.push({name: 'nopermission'});  }
            else if 
            ((!response.ok && response.status == 401) || (!response.ok && response.body.error && (response.body.error === "token_invalid" || response.body.error === "token_expired" || response.body.error === 'token_not_provided')) )
              { store.dispatch('showErrorNotification', response.body.error);
                store.dispatch('logoutRequest')
                .then(()=> { store.dispatch('hideAllNotifications');  router.push({name: 'login'});  });
              }
            else if(!response.ok && response.body.error)
              {  store.dispatch('showErrorNotification', response.body.error);    }
            else if(!response.ok)
              {  store.dispatch('showErrorNotification', response.bodyText);      }
         });
   }
);

const app = new Vue({
    el: '#app',
    router: router,
    store,
    render: app => app(AppMain)
});

import Vue from 'vue';
import * as api from './../../config';

export default {
    state: {
    },
    getters: {
    },
    mutations: {
    },
    actions: {
        getBusinessCalendarFilterOptions({dispatch}, payload)
        {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/business-calender.js-getBusinessCalendarFilterOptions');
                Vue.http.get(api.getBusinessCalendarFilterOptions, {params: payload})
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        saveBusinessCalendarRequest({dispatch}, payload)
        {
            console.log('/resources/assets/js/store/modules/business-calender.js-saveBusinessCalendarRequest payload=', payload);
            return new Promise((resolve, reject) => {
                Vue.http.post(api.saveBusinessCalendar, payload)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/business-calender.js-saveBusinessCalendarRequest get http success response.body=', response.body);
                        dispatch('showSuccessNotification', 'The calendar has been successfully saved.');
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/business-calender.js-saveBusinessCalendarRequest error =', error);
                        dispatch('showErrorNotification', error.body);
                        reject(error);
                    });
            });
        },
        updateBusinessCalendarRequest({dispatch}, payload)
        {
            console.log('/resources/assets/js/store/modules/business-calender.js-updateBusinessCalendarRequest payload=', payload);
            return new Promise((resolve, reject) => {
                Vue.http.post(api.updateBusinessCalendar, payload)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/business-calender.js-updateBusinessCalendarRequest get http success response.body=', response.body);
                        dispatch('showSuccessNotification', 'The calendar has been successfully updated.');
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/business-calender.js-updateBusinessCalendarRequest error =', error);
                        dispatch('showErrorNotification', error.body);
                        reject(error);
                    });
            });
        },
        deleteBusinessCalendarDateRequest({dispatch}, payload)
        {
            console.log('/resources/assets/js/store/modules/business-calender.js-deleteBusinessCalendarDateRequest payload=', payload);
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteBusinessCalendar, payload)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/business-calender.js-deleteBusinessCalendarDateRequest get http success response.body=', response.body);
                        dispatch('showSuccessNotification', 'The calendar has been successfully deleted.');
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/business-calender.js-deleteBusinessCalendarDateRequest error =', error);
                        dispatch('showErrorNotification', error.body);
                        reject(error);
                    });
            });
        },
    }
}
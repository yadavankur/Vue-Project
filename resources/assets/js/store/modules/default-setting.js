import Vue from 'vue';
import * as api from './../../config';

export default {
    state: {
    },
    mutations: {
    },
    actions: {
        updateDefaultSettingRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('updateDefaultSettingRequest formData=', formData);
                Vue.http.post(api.updateDefaultSetting, formData)
                    .then(response => {
                        let actionType = formData.action_type;
                        dispatch('showSuccessNotification', 'Setting has been ' + actionType + '.');
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('showSuccessNotification', response.body.error);
                        reject(response);
                    });
            })
        },
        getLocationDefaultSettings: ({dispatch}, payload) => {
            return new Promise((resolve, reject) => {
                console.log('getLocationDefaultSettings');
                Vue.http.get(api.locationDefaultSettings, {params: payload} )
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
    }
}
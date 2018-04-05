import Vue from 'vue';
import * as api from './../../config';

export default {
    state: {
    },
    mutations: {
    },
    actions: {
        updateTaskMappingRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('updateTaskMappingRequest formData=', formData);
                Vue.http.post(api.updateTaskMapping, formData)
                    .then(response => {
                        let actionType = formData.type;
                        dispatch('showSuccessNotification', 'Mapping has been ' + actionType + '.');
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('showSuccessNotification', response.body.error);
                        reject(response);
                    });
            })
        },
    }
}
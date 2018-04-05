import Vue from 'vue';
import * as api from './../../config';

export default {
    state: {
    },
    mutations: {
    },
    actions: {
        addCpmServiceGroupRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('addCpmServiceGroupRequest formData=',formData);
                Vue.http.post(api.updateCpmServiceGroup, formData)
                    .then(response => {
                        dispatch('showSuccessNotification', 'Service group has been added.');
                        resolve(response);
                    })
                    .catch(response => {
                        if(response.body.error) {
                            dispatch('showErrorNotification', response.body.error);
                        }
                        reject(response);
                    });
            })
        },
        updateCpmServiceGroupRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('updateCpmServiceGroupRequest formData=',formData);
                Vue.http.post(api.updateCpmServiceGroup, formData)
                    .then(response => {
                        dispatch('showSuccessNotification', 'Service group has been updated.');
                        resolve(response);
                    })
                    .catch(response => {
                        if(response.body.error) {
                            dispatch('showErrorNotification', response.body.error);
                        }
                        reject(response);
                    });
            })
        },
        deleteCpmServiceGroupRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('deleteCpmServiceGroupRequest formData=',formData);
                Vue.http.post(api.updateCpmServiceGroup, formData)
                    .then(response => {
                        dispatch('showSuccessNotification', 'Service group has been deleted.');
                        resolve(response);
                    })
                    .catch(response => {
                        if(response.body.error) {
                            dispatch('showErrorNotification', response.body.error);
                        }
                        reject(response);
                    });
            })
        },
        getCpmCascadeServiceGroupOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('getCpmCascadeServiceGroupOptions');
                Vue.http.get(api.cpmCascadeServiceGroupOptions)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        getCpmServiceGroupOptions: ({dispatch}, payload) => {
            return new Promise((resolve, reject) => {
                console.log('getCpmServiceGroupOptions');
                Vue.http.get(api.cpmServiceGroupOptions, {params: payload})
                    .then(response => {
                        console.log('getCpmServiceGroupOptions success response=', response);
                        resolve(response);
                    })
                    .catch(response => {
                        console.log('getCpmServiceGroupOptions error response=', response);
                        reject(response);
                    });
            })
        },
    }
}
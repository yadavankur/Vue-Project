import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        customerTierLevelNodes: null,
        showModal: false,
        customerTierLevelData: null,
        modalForm: {
            customerTierLevel: null,
        },
    },
    getters: {
        allCustomerTierLevels: state => state.customerTierLevels,
        allCustomerTierLevelNodes: state => state.customerTierLevelNodes
    },
    mutations: {
        [types.ADD_CUSTOMERTIERLEVEL_SUCCESS] (state, payload) {
            console.log('types.ADD_CUSTOMERTIERLEVEL_SUCCESS payload=', payload);
        },
        [types.ADD_CUSTOMERTIERLEVEL_FAILURE] (state, payload) {
            console.log('types.ADD_CUSTOMERTIERLEVEL_FAILURE payload=', payload);
        },
        [types.UPDATE_CUSTOMERTIERLEVEL_SUCCESS] (state, payload) {
            console.log('types.UPDATE_CUSTOMERTIERLEVEL_SUCCESS payload=', payload);
        },
        [types.UPDATE_CUSTOMERTIERLEVEL_FAILURE] (state, payload) {
            console.log('types.UPDATE_CUSTOMERTIERLEVEL_FAILURE payload=', payload);
        },
        [types.DELETE_CUSTOMERTIERLEVEL_SUCCESS] (state, payload) {
            console.log('types.DELETE_CUSTOMERTIERLEVEL_SUCCESS payload=', payload);
        },
        [types.DELETE_CUSTOMERTIERLEVEL_FAILURE] (state, payload) {
            console.log('types.DELETE_CUSTOMERTIERLEVEL_FAILURE payload=', payload);
        },
        [types.SET_CUSTOMERTIERLEVELS] (state, payload) {
            console.log('types.SET_CUSTOMERTIERLEVELS payload=', payload);
            console.log('types.SET_CUSTOMERTIERLEVELS state=', state);
        },
        [types.UNSET_CUSTOMERTIERLEVELS] (state, payload) {
            console.log('types.UNSET_CUSTOMERTIERLEVELS payload=', payload);
            console.log('types.UNSET_CUSTOMERTIERLEVELS state=', state);
        },
        [types.SET_CUSTOMERTIERLEVEL_NODES] (state, payload) {
            console.log('types.SET_CUSTOMERTIERLEVEL_NODES payload=', payload.customerTierLevelNodes);
            console.log('types.SET_CUSTOMERTIERLEVEL_NODES state=', state);
            state.customerTierLevelNodes = payload.customerTierLevelNodes;
        },
        [types.UNSET_CUSTOMERTIERLEVEL_NODES] (state, payload) {
            console.log('types.UNSET_CUSTOMERTIERLEVEL_NODES payload=', payload);
            console.log('types.UNSET_CUSTOMERTIERLEVEL_NODES state=', state);
            state.customerTierLevelNodes = null;
        },
        [types.SET_CUSTOMERTIERLEVEL_SHOW_MODAL] (state, payload) {
            console.log('types.SET_CUSTOMERTIERLEVEL_SHOW_MODAL payload=', payload.data);
            console.log('types.SET_CUSTOMERTIERLEVEL_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.customerTierLevelData = payload.data.data;
        },
    },
    actions: {
        updateCustomerTierLevelRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('updateCustomerTierLevelRequest formData=',formData);
                Vue.http.post(api.updateCustomerTierLevel, formData)
                    .then(response => {
                        dispatch('updateCustomerTierLevelSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updateCustomerTierLevelFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateCustomerTierLevelSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_CUSTOMERTIERLEVEL_SUCCESS,
                customerTierLevel: body.customerTierLevel
            });
            dispatch('showSuccessNotification', 'CustomerTierLevel has been updated.');
        },
        updateCustomerTierLevelFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_CUSTOMERTIERLEVEL_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteCustomerTierLevelRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteCustomerTierLevel, formData)
                    .then(response => {
                        dispatch('deleteCustomerTierLevelSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deleteCustomerTierLevelFailure', response.body);
                        reject(response);
                    });
            })
        },
        deleteCustomerTierLevelSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_CUSTOMERTIERLEVEL_SUCCESS,
                customerTierLevel: body.customerTierLevel
            });
            dispatch('showSuccessNotification', 'CustomerTierLevel has been deleted.');
        },
        deleteCustomerTierLevelFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_CUSTOMERTIERLEVEL_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addCustomerTierLevelRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addCustomerTierLevel, formData)
                    .then(response => {
                        dispatch('addCustomerTierLevelSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('addCustomerTierLevelRequest response.body=', response.body);
                        dispatch('addCustomerTierLevelFailure', response.body);
                        reject(response);
                    });
            })
        },
        addCustomerTierLevelSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_CUSTOMERTIERLEVEL_SUCCESS,
                customerTierLevel: body.customerTierLevel
            });
            dispatch('showSuccessNotification', 'CustomerTierLevel has been added.');
        },
        addCustomerTierLevelFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_CUSTOMERTIERLEVEL_FAILURE,
                errors: body
            });
            console.error('addCustomerTierLevelFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setCustomerTierLevelNodes: ({commit, dispatch}) => {
            console.log('setCustomerTierLevelNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentCustomerTierLevelNodes)
                    .then(response => {
                        console.log('setCustomerTierLevelNodes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_CUSTOMERTIERLEVEL_NODES,
                            customerTierLevelNodes: response.body.customerTierLevelNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setCustomerTierLevelNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetCustomerTierLevelNodes: ({commit}) => {
            commit({
                type: types.UNSET_CUSTOMERTIERLEVEL_NODES
            });
        },
        setCustomerTierLevels: ({commit, dispatch}) => {
            console.log('setCustomerTierLevels');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentCustomerTierLevels)
                    .then(response => {
                        console.log('setCustomerTierLevels get http success response.body=', response.body);
                        commit({
                            type: types.SET_CUSTOMERTIERLEVELS,
                            customerTierLevels: response.body.customerTierLevels
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setCustomerTierLevels error =', error);
                        reject(error);
                    });
            });
        },
        unsetCustomerTierLevels: ({commit}) => {
            commit({
                type: types.UNSET_CUSTOMERTIERLEVELS
            });
        },
        setCustomerTierLevelShowModal:({commit}, data) => {
            commit({
                type: types.SET_CUSTOMERTIERLEVEL_SHOW_MODAL,
                data: data
            });
        },
        getCustomerTierLevelOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('getCustomerTierLevelOptions');
                Vue.http.get(api.customerTierLevelOptions)
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
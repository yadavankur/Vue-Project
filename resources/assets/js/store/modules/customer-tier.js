import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        customerTierNodes: null,
        showModal: false,
        customerTierData: null,
        modalForm: {
            customerTier: null,
        },
    },
    getters: {
        allCustomerTiers: state => state.customerTiers,
        allCustomerTierNodes: state => state.customerTierNodes
    },
    mutations: {
        [types.ADD_CUSTOMERTIER_SUCCESS] (state, payload) {
            console.log('types.ADD_CUSTOMERTIER_SUCCESS payload=', payload);
        },
        [types.ADD_CUSTOMERTIER_FAILURE] (state, payload) {
            console.log('types.ADD_CUSTOMERTIER_FAILURE payload=', payload);
        },
        [types.UPDATE_CUSTOMERTIER_SUCCESS] (state, payload) {
            console.log('types.UPDATE_CUSTOMERTIER_SUCCESS payload=', payload);
        },
        [types.UPDATE_CUSTOMERTIER_FAILURE] (state, payload) {
            console.log('types.UPDATE_CUSTOMERTIER_FAILURE payload=', payload);
        },
        [types.DELETE_CUSTOMERTIER_SUCCESS] (state, payload) {
            console.log('types.DELETE_CUSTOMERTIER_SUCCESS payload=', payload);
        },
        [types.DELETE_CUSTOMERTIER_FAILURE] (state, payload) {
            console.log('types.DELETE_CUSTOMERTIER_FAILURE payload=', payload);
        },
        [types.SET_CUSTOMERTIERS] (state, payload) {
            console.log('types.SET_CUSTOMERTIERS payload=', payload);
            console.log('types.SET_CUSTOMERTIERS state=', state);
        },
        [types.UNSET_CUSTOMERTIERS] (state, payload) {
            console.log('types.UNSET_CUSTOMERTIERS payload=', payload);
            console.log('types.UNSET_CUSTOMERTIERS state=', state);
        },
        [types.SET_CUSTOMERTIER_NODES] (state, payload) {
            console.log('types.SET_CUSTOMERTIER_NODES payload=', payload.customerTierNodes);
            console.log('types.SET_CUSTOMERTIER_NODES state=', state);
            state.customerTierNodes = payload.customerTierNodes;
        },
        [types.UNSET_CUSTOMERTIER_NODES] (state, payload) {
            console.log('types.UNSET_CUSTOMERTIER_NODES payload=', payload);
            console.log('types.UNSET_CUSTOMERTIER_NODES state=', state);
            state.customerTierNodes = null;
        },
        [types.SET_CUSTOMERTIER_SHOW_MODAL] (state, payload) {
            console.log('types.SET_CUSTOMERTIER_SHOW_MODAL payload=', payload.data);
            console.log('types.SET_CUSTOMERTIER_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.customerTierData = payload.data.data;
        },
    },
    actions: {
        updateCustomerTierRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('updateCustomerTierRequest formData=',formData);
                Vue.http.post(api.updateCustomerTier, formData)
                    .then(response => {
                        dispatch('updateCustomerTierSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updateCustomerTierFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateCustomerTierSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_CUSTOMERTIER_SUCCESS,
                customerTier: body.customerTier
            });
            dispatch('showSuccessNotification', 'CustomerTier has been updated.');
        },
        updateCustomerTierFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_CUSTOMERTIER_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteCustomerTierRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteCustomerTier, formData)
                    .then(response => {
                        dispatch('deleteCustomerTierSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deleteCustomerTierFailure', response.body);
                        reject(response);
                    });
            })
        },
        deleteCustomerTierSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_CUSTOMERTIER_SUCCESS,
                customerTier: body.customerTier
            });
            dispatch('showSuccessNotification', 'CustomerTier has been deleted.');
        },
        deleteCustomerTierFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_CUSTOMERTIER_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addCustomerTierRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addCustomerTier, formData)
                    .then(response => {
                        dispatch('addCustomerTierSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('addCustomerTierRequest response.body=', response.body);
                        dispatch('addCustomerTierFailure', response.body);
                        reject(response);
                    });
            })
        },
        addCustomerTierSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_CUSTOMERTIER_SUCCESS,
                customerTier: body.customerTier
            });
            dispatch('showSuccessNotification', 'CustomerTier has been added.');
        },
        addCustomerTierFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_CUSTOMERTIER_FAILURE,
                errors: body
            });
            console.error('addCustomerTierFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setCustomerTierNodes: ({commit, dispatch}) => {
            console.log('setCustomerTierNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentCustomerTierNodes)
                    .then(response => {
                        console.log('setCustomerTierNodes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_CUSTOMERTIER_NODES,
                            customerTierNodes: response.body.customerTierNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setCustomerTierNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetCustomerTierNodes: ({commit}) => {
            commit({
                type: types.UNSET_CUSTOMERTIER_NODES
            });
        },
        setCustomerTiers: ({commit, dispatch}) => {
            console.log('setCustomerTiers');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentCustomerTiers)
                    .then(response => {
                        console.log('setCustomerTiers get http success response.body=', response.body);
                        commit({
                            type: types.SET_CUSTOMERTIERS,
                            customerTiers: response.body.customerTiers
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setCustomerTiers error =', error);
                        reject(error);
                    });
            });
        },
        unsetCustomerTiers: ({commit}) => {
            commit({
                type: types.UNSET_CUSTOMERTIERS
            });
        },
        setCustomerTierShowModal:({commit}, data) => {
            commit({
                type: types.SET_CUSTOMERTIER_SHOW_MODAL,
                data: data
            });
        },

    }
}
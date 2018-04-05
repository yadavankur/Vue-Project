import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        cpmMasters: [],
        cpmMasterNodes: null,
        showModal: false,
        cpmMasterData: null,
        modalForm: {
            cpmMaster: null,
        },
    },
    getters: {
        allCpmMasters: state => state.cpmMasters,
        allCpmMasterNodes: state => state.cpmMasterNodes
    },
    mutations: {
        [types.ADD_CPMMASTER_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.ADD_CPMMASTER_SUCCESS payload=', payload);
        },
        [types.ADD_CPMMASTER_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.ADD_CPMMASTER_FAILURE payload=', payload);
        },
        [types.UPDATE_CPMMASTER_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.UPDATE_CPMMASTER_SUCCESS payload=', payload);
        },
        [types.UPDATE_CPMMASTER_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.UPDATE_CPMMASTER_FAILURE payload=', payload);
        },
        [types.DELETE_CPMMASTER_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.DELETE_CPMMASTER_SUCCESS payload=', payload);
        },
        [types.DELETE_CPMMASTER_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.DELETE_CPMMASTER_FAILURE payload=', payload);
        },
        [types.SET_CPMMASTERS] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.SET_CPMMASTERS payload=', payload.cpmMasters[0].children);
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.SET_CPMMASTERS state=', state);
            state.cpmMasters = payload.cpmMasters[0].children;
        },
        [types.UNSET_CPMMASTERS] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.UNSET_CPMMASTERS payload=', payload);
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.UNSET_CPMMASTERS state=', state);
            state.cpmMasters = [];
        },
        [types.SET_CPMMASTER_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.SET_CPMMASTER_NODES payload=', payload.cpmMasterNodes);
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.SET_CPMMASTER_NODES state=', state);
            state.cpmMasterNodes = payload.cpmMasterNodes;
        },
        [types.UNSET_CPMMASTER_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.UNSET_CPMMASTER_NODES payload=', payload);
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.UNSET_CPMMASTER_NODES state=', state);
            state.cpmMasterNodes = null;
        },
        [types.SET_CPMMASTER_SHOW_MODAL] (state, payload) {
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.SET_CPMMASTER_SHOW_MODAL payload=', payload.data);
            console.log('/resources/assets/js/store/modules/cpm-master.js-types.SET_CPMMASTER_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.cpmMasterData = payload.data.data;
        },
    },
    actions: {
        updateCpmMasterRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/cpm-master.js-updateCpmMasterRequest formData=',formData);
                Vue.http.post(api.updateCpmMaster, formData)
                    .then(response => {
                        dispatch('updateCpmMasterSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updateCpmMasterFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateCpmMasterSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_CPMMASTER_SUCCESS,
                cpmMaster: body.cpmMaster
            });
            dispatch('showSuccessNotification', 'CpmMaster has been updated.');
        },
        updateCpmMasterFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_CPMMASTER_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteCpmMasterRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteCpmMaster, formData)
                    .then(response => {
                        dispatch('deleteCpmMasterSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deleteCpmMasterFailure', response.body);
                        reject(response);
                    });
            })
        },
        deleteCpmMasterSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_CPMMASTER_SUCCESS,
                cpmMaster: body.cpmMaster
            });
            dispatch('showSuccessNotification', 'CpmMaster has been deleted.');
        },
        deleteCpmMasterFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_CPMMASTER_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addCpmMasterRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addCpmMaster, formData)
                    .then(response => {
                        dispatch('addCpmMasterSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/cpm-master.js-addCpmMasterRequest response.body=', response.body);
                        dispatch('addCpmMasterFailure', response.body);
                        reject(response);
                    });
            })
        },
        addCpmMasterSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_CPMMASTER_SUCCESS,
                cpmMaster: body.cpmMaster
            });
            dispatch('showSuccessNotification', 'CpmMaster has been added.');
        },
        addCpmMasterFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_CPMMASTER_FAILURE,
                errors: body
            });
            console.error('/resources/assets/js/store/modules/cpm-master.js-addCpmMasterFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setCpmMasterNodes: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/cpm-master.js-setCpmMasterNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentCpmMasterNodes)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/cpm-master.js-setCpmMasterNodes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_CPMMASTER_NODES,
                            cpmMasterNodes: response.body.cpmMasterNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/cpm-master.js-setCpmMasterNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetCpmMasterNodes: ({commit}) => {
            commit({
                type: types.UNSET_CPMMASTER_NODES
            });
        },
        setCpmMasters: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/cpm-master.js-setCpmMasters');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentCpmMasters)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/cpm-master.js-setCpmMasters gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_CPMMASTERS,
                            cpmMasters: response.body.cpmMasters
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/cpm-master.js-setCpmMasters error =', error);
                        reject(error);
                    });
            });
        },
        unsetCpmMasters: ({commit}) => {
            commit({
                type: types.UNSET_CPMMASTERS
            });
        },
        setCpmMasterShowModal:({commit}, data) => {
            commit({
                type: types.SET_CPMMASTER_SHOW_MODAL,
                data: data
            });
        },
    }
}
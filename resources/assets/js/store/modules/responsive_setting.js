import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        responsiveSettings: [],
        responsiveSettingNodes: null,
        showModal: false,
        responsiveSettingData: null,
        modalForm: {
            responsiveSetting: null,
        },
    },
    getters: {
        allResponsiveSettings: state => state.responsiveSettings,
        allResponsiveSettingNodes: state => state.responsiveSettingNodes
    },
    mutations: {
        [types.ADD_RESPONSIVESETTING_SUCCESS] (state, payload) {
            console.log('types.ADD_RESPONSIVESETTING_SUCCESS payload=', payload);
        },
        [types.ADD_RESPONSIVESETTING_FAILURE] (state, payload) {
            console.log('types.ADD_RESPONSIVESETTING_FAILURE payload=', payload);
        },
        [types.UPDATE_RESPONSIVESETTING_SUCCESS] (state, payload) {
            console.log('types.UPDATE_RESPONSIVESETTING_SUCCESS payload=', payload);
        },
        [types.UPDATE_RESPONSIVESETTING_FAILURE] (state, payload) {
            console.log('types.UPDATE_RESPONSIVESETTING_FAILURE payload=', payload);
        },
        [types.DELETE_RESPONSIVESETTING_SUCCESS] (state, payload) {
            console.log('types.DELETE_RESPONSIVESETTING_SUCCESS payload=', payload);
        },
        [types.DELETE_RESPONSIVESETTING_FAILURE] (state, payload) {
            console.log('types.DELETE_RESPONSIVESETTING_FAILURE payload=', payload);
        },
        [types.SET_RESPONSIVESETTINGS] (state, payload) {
            console.log('types.SET_RESPONSIVESETTINGS payload=', payload.responsiveSettings[0].children);
            console.log('types.SET_RESPONSIVESETTINGS state=', state);
            state.responsiveSettings = payload.responsiveSettings[0].children;
        },
        [types.UNSET_RESPONSIVESETTINGS] (state, payload) {
            console.log('types.UNSET_RESPONSIVESETTINGS payload=', payload);
            console.log('types.UNSET_RESPONSIVESETTINGS state=', state);
            state.responsiveSettings = [];
        },
        [types.SET_RESPONSIVESETTING_NODES] (state, payload) {
            console.log('types.SET_RESPONSIVESETTING_NODES payload=', payload.responsiveSettingNodes);
            console.log('types.SET_RESPONSIVESETTING_NODES state=', state);
            state.responsiveSettingNodes = payload.responsiveSettingNodes;
        },
        [types.UNSET_RESPONSIVESETTING_NODES] (state, payload) {
            console.log('types.UNSET_RESPONSIVESETTING_NODES payload=', payload);
            console.log('types.UNSET_RESPONSIVESETTING_NODES state=', state);
            state.responsiveSettingNodes = null;
        },
        [types.SET_RESPONSIVESETTING_SHOW_MODAL] (state, payload) {
            console.log('types.SET_RESPONSIVESETTING_SHOW_MODAL payload=', payload.data);
            console.log('types.SET_RESPONSIVESETTING_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.responsiveSettingData = payload.data.data;
        },
    },
    actions: {
        updateResponsiveSettingRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('updateResponsiveSettingRequest formData=',formData);
                Vue.http.post(api.updateResponsiveSetting, formData)
                    .then(response => {
                        dispatch('updateResponsiveSettingSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updateResponsiveSettingFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateResponsiveSettingSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_RESPONSIVESETTING_SUCCESS,
                responsiveSetting: body.responsiveSetting
            });
            dispatch('showSuccessNotification', 'ResponsiveSetting has been updated.');
        },
        updateResponsiveSettingFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_RESPONSIVESETTING_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteResponsiveSettingRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteResponsiveSetting, formData)
                    .then(response => {
                        dispatch('deleteResponsiveSettingSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deleteResponsiveSettingFailure', response.body);
                        reject(response);
                    });
            })
        },
        deleteResponsiveSettingSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_RESPONSIVESETTING_SUCCESS,
                responsiveSetting: body.responsiveSetting
            });
            dispatch('showSuccessNotification', 'ResponsiveSetting has been deleted.');
        },
        deleteResponsiveSettingFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_RESPONSIVESETTING_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addResponsiveSettingRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addResponsiveSetting, formData)
                    .then(response => {
                        dispatch('addResponsiveSettingSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('addResponsiveSettingRequest response.body=', response.body);
                        dispatch('addResponsiveSettingFailure', response.body);
                        reject(response);
                    });
            })
        },
        addResponsiveSettingSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_RESPONSIVESETTING_SUCCESS,
                responsiveSetting: body.responsiveSetting
            });
            dispatch('showSuccessNotification', 'ResponsiveSetting has been added.');
        },
        addResponsiveSettingFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_RESPONSIVESETTING_FAILURE,
                errors: body
            });
            console.error('addResponsiveSettingFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setResponsiveSettingNodes: ({commit, dispatch}) => {
            console.log('setResponsiveSettingNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentResponsiveSettingNodes)
                    .then(response => {
                        console.log('setResponsiveSettingNodes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_RESPONSIVESETTING_NODES,
                            responsiveSettingNodes: response.body.responsiveSettingNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setResponsiveSettingNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetResponsiveSettingNodes: ({commit}) => {
            commit({  type: types.UNSET_RESPONSIVESETTING_NODES   });
        },
        setResponsiveSettings: ({commit, dispatch}) => {
            console.log('setResponsiveSettings');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentResponsiveSettings)
                    .then(response => {
                        console.log('setResponsiveSettings gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_RESPONSIVESETTINGS,
                            responsiveSettings: response.body.responsiveSettings
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setResponsiveSettings error =', error);
                        reject(error);
                    });
            });
        },
        unsetResponsiveSettings: ({commit}) => {
            commit({  type: types.UNSET_RESPONSIVESETTINGS  });
        },
        setResponsiveSettingShowModal:({commit}, data) => {
            commit({
                type: types.SET_RESPONSIVESETTING_SHOW_MODAL,
                data: data
            });
        },
    }
}
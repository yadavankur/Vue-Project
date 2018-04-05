import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        deviceTypes: [],
        deviceTypeNodes: null,
        showModal: false,
        deviceTypeData: null,
        modalForm: {
            deviceType: null,
        },
    },
    getters: {
        allDeviceTypes: state => state.deviceTypes,
        allDeviceTypeNodes: state => state.deviceTypeNodes
    },
    mutations: {
        [types.ADD_DEVICETYPE_SUCCESS] (state, payload) {
            console.log('types.ADD_DEVICETYPE_SUCCESS payload=', payload);
        },
        [types.ADD_DEVICETYPE_FAILURE] (state, payload) {
            console.log('types.ADD_DEVICETYPE_FAILURE payload=', payload);
        },
        [types.UPDATE_DEVICETYPE_SUCCESS] (state, payload) {
            console.log('types.UPDATE_DEVICETYPE_SUCCESS payload=', payload);
        },
        [types.UPDATE_DEVICETYPE_FAILURE] (state, payload) {
            console.log('types.UPDATE_DEVICETYPE_FAILURE payload=', payload);
        },
        [types.DELETE_DEVICETYPE_SUCCESS] (state, payload) {
            console.log('types.DELETE_DEVICETYPE_SUCCESS payload=', payload);
        },
        [types.DELETE_DEVICETYPE_FAILURE] (state, payload) {
            console.log('types.DELETE_DEVICETYPE_FAILURE payload=', payload);
        },
        [types.SET_DEVICETYPES] (state, payload) {
            console.log('types.SET_DEVICETYPES payload=', payload.deviceTypes[0].children);
            console.log('types.SET_DEVICETYPES state=', state);
            state.deviceTypes = payload.deviceTypes[0].children;
        },
        [types.UNSET_DEVICETYPES] (state, payload) {
            console.log('types.UNSET_DEVICETYPES payload=', payload);
            console.log('types.UNSET_DEVICETYPES state=', state);
            state.deviceTypes = [];
        },
        [types.SET_DEVICETYPE_NODES] (state, payload) {
            console.log('types.SET_DEVICETYPE_NODES payload=', payload.deviceTypeNodes);
            console.log('types.SET_DEVICETYPE_NODES state=', state);
            state.deviceTypeNodes = payload.deviceTypeNodes;
        },
        [types.UNSET_DEVICETYPE_NODES] (state, payload) {
            console.log('types.UNSET_DEVICETYPE_NODES payload=', payload);
            console.log('types.UNSET_DEVICETYPE_NODES state=', state);
            state.deviceTypeNodes = null;
        },
        [types.SET_DEVICETYPE_SHOW_MODAL] (state, payload) {
            console.log('types.SET_DEVICETYPE_SHOW_MODAL payload=', payload.data);
            console.log('types.SET_DEVICETYPE_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.deviceTypeData = payload.data.data;
        },
    },
    actions: {
        updateDeviceTypeRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('updateDeviceTypeRequest formData=',formData);
                Vue.http.post(api.updateDeviceType, formData)
                    .then(response => {
                        dispatch('updateDeviceTypeSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updateDeviceTypeFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateDeviceTypeSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_DEVICETYPE_SUCCESS,
                deviceType: body.deviceType
            });
            dispatch('showSuccessNotification', 'DeviceType has been updated.');
        },
        updateDeviceTypeFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_DEVICETYPE_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteDeviceTypeRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteDeviceType, formData)
                    .then(response => {
                        dispatch('deleteDeviceTypeSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deleteDeviceTypeFailure', response.body);
                        reject(response);
                    });
            })
        },
        deleteDeviceTypeSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_DEVICETYPE_SUCCESS,
                deviceType: body.deviceType
            });
            dispatch('showSuccessNotification', 'DeviceType has been deleted.');
        },
        deleteDeviceTypeFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_DEVICETYPE_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addDeviceTypeRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addDeviceType, formData)
                    .then(response => {
                        dispatch('addDeviceTypeSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('addDeviceTypeRequest response.body=', response.body);
                        dispatch('addDeviceTypeFailure', response.body);
                        reject(response);
                    });
            })
        },
        addDeviceTypeSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_DEVICETYPE_SUCCESS,
                deviceType: body.deviceType
            });
            dispatch('showSuccessNotification', 'DeviceType has been added.');
        },
        addDeviceTypeFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_DEVICETYPE_FAILURE,
                errors: body
            });
            console.error('addDeviceTypeFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setDeviceTypeNodes: ({commit, dispatch}) => {
            console.log('setDeviceTypeNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentDeviceTypeNodes)
                    .then(response => {
                        console.log('setDeviceTypeNodes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_DEVICETYPE_NODES,
                            deviceTypeNodes: response.body.deviceTypeNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setDeviceTypeNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetDeviceTypeNodes: ({commit}) => {
            commit({
                type: types.UNSET_DEVICETYPE_NODES
            });
        },
        setDeviceTypes: ({commit, dispatch}) => {
            console.log('setDeviceTypes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentDeviceTypes)
                    .then(response => {
                        console.log('setDeviceTypes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_DEVICETYPES,
                            deviceTypes: response.body.deviceTypes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setDeviceTypes error =', error);
                        reject(error);
                    });
            });
        },
        unsetDeviceTypes: ({commit}) => {
            commit({
                type: types.UNSET_DEVICETYPES
            });
        },
        setDeviceTypeShowModal:({commit}, data) => {
            commit({
                type: types.SET_DEVICETYPE_SHOW_MODAL,
                data: data
            });
        },
        getDeviceTypeOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('getDeviceTypeOptions');
                Vue.http.get(api.deviceTypeOptions)
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
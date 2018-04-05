import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        resourceTypes: [],
        resourceTypeNodes: null,
        showModal: false,
        resourceTypeData: null,
        modalForm: {
            resourceType: null,
        },
    },
    getters: {
        allResourceTypes: state => state.resourceTypes,
        allResourceTypeNodes: state => state.resourceTypeNodes
    },
    mutations: {
        [types.ADD_RESOURCETYPE_SUCCESS] (state, payload) {
            console.log('types.ADD_RESOURCETYPE_SUCCESS payload=', payload);
        },
        [types.ADD_RESOURCETYPE_FAILURE] (state, payload) {
            console.log('types.ADD_RESOURCETYPE_FAILURE payload=', payload);
        },
        [types.UPDATE_RESOURCETYPE_SUCCESS] (state, payload) {
            console.log('types.UPDATE_RESOURCETYPE_SUCCESS payload=', payload);
        },
        [types.UPDATE_RESOURCETYPE_FAILURE] (state, payload) {
            console.log('types.UPDATE_RESOURCETYPE_FAILURE payload=', payload);
        },
        [types.DELETE_RESOURCETYPE_SUCCESS] (state, payload) {
            console.log('types.DELETE_RESOURCETYPE_SUCCESS payload=', payload);
        },
        [types.DELETE_RESOURCETYPE_FAILURE] (state, payload) {
            console.log('types.DELETE_RESOURCETYPE_FAILURE payload=', payload);
        },
        [types.SET_RESOURCETYPES] (state, payload) {
            console.log('types.SET_RESOURCETYPES payload=', payload.resourceTypes[0].children);
            console.log('types.SET_RESOURCETYPES state=', state);
            state.resourceTypes = payload.resourceTypes[0].children;
        },
        [types.UNSET_RESOURCETYPES] (state, payload) {
            console.log('types.UNSET_RESOURCETYPES payload=', payload);
            console.log('types.UNSET_RESOURCETYPES state=', state);
            state.resourceTypes = [];
        },
        [types.SET_RESOURCETYPE_NODES] (state, payload) {
            console.log('types.SET_RESOURCETYPE_NODES payload=', payload.resourceTypeNodes);
            console.log('types.SET_RESOURCETYPE_NODES state=', state);
            state.resourceTypeNodes = payload.resourceTypeNodes;
        },
        [types.UNSET_RESOURCETYPE_NODES] (state, payload) {
            console.log('types.UNSET_RESOURCETYPE_NODES payload=', payload);
            console.log('types.UNSET_RESOURCETYPE_NODES state=', state);
            state.resourceTypeNodes = null;
        },
        [types.SET_RESOURCETYPE_SHOW_MODAL] (state, payload) {
            console.log('types.SET_RESOURCETYPE_SHOW_MODAL payload=', payload.data);
            console.log('types.SET_RESOURCETYPE_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.resourceTypeData = payload.data.data;
        },
    },
    actions: {
        updateResourceTypeRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('updateResourceTypeRequest formData=',formData);
                Vue.http.post(api.updateResourceType, formData)
                    .then(response => {
                        dispatch('updateResourceTypeSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updateResourceTypeFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateResourceTypeSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_RESOURCETYPE_SUCCESS,
                resourceType: body.resourceType
            });
            dispatch('showSuccessNotification', 'ResourceType has been updated.');
        },
        updateResourceTypeFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_RESOURCETYPE_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteResourceTypeRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteResourceType, formData)
                    .then(response => {
                        dispatch('deleteResourceTypeSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deleteResourceTypeFailure', response.body);
                        reject(response);
                    });
            })
        },
        deleteResourceTypeSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_RESOURCETYPE_SUCCESS,
                resourceType: body.resourceType
            });
            dispatch('showSuccessNotification', 'ResourceType has been deleted.');
        },
        deleteResourceTypeFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_RESOURCETYPE_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addResourceTypeRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addResourceType, formData)
                    .then(response => {
                        dispatch('addResourceTypeSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('addResourceTypeRequest response.body=', response.body);
                        dispatch('addResourceTypeFailure', response.body);
                        reject(response);
                    });
            })
        },
        addResourceTypeSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_RESOURCETYPE_SUCCESS,
                resourceType: body.resourceType
            });
            dispatch('showSuccessNotification', 'ResourceType has been added.');
        },
        addResourceTypeFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_RESOURCETYPE_FAILURE,
                errors: body
            });
            console.error('addResourceTypeFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setResourceTypeNodes: ({commit, dispatch}) => {
            console.log('setResourceTypeNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentResourceTypeNodes)
                    .then(response => {
                        console.log('setResourceTypeNodes get http success response.body=', response.body);
                        commit({
                            type: types.SET_RESOURCETYPE_NODES,
                            resourceTypeNodes: response.body.resourceTypeNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setResourceTypeNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetResourceTypeNodes: ({commit}) => {
            commit({
                type: types.UNSET_RESOURCETYPE_NODES
            });
        },
        setResourceTypes: ({commit, dispatch}) => {
            console.log('setResourceTypes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentResourceTypes)
                    .then(response => {
                        console.log('setResourceTypes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_RESOURCETYPES,
                            resourceTypes: response.body.resourceTypes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setResourceTypes error =', error);
                        reject(error);
                    });
            });
        },
        unsetResourceTypes: ({commit}) => {
            commit({
                type: types.UNSET_RESOURCETYPES
            });
        },
        setResourceTypeShowModal:({commit}, data) => {
            commit({
                type: types.SET_RESOURCETYPE_SHOW_MODAL,
                data: data
            });
        },
    }
}
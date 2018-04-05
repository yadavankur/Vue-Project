import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        permissions: [],
        permissionNodes: null,
        showModal: false,
        permissionData: null,
        modalForm: {
            permission: null,
        },
    },
    getters: {
        allPermissions: state => state.permissions,
        allPermissionNodes: state => state.permissionNodes
    },
    mutations: {
        [types.ADD_PERMISSION_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.ADD_PERMISSION_SUCCESS payload=', payload);
        },
        [types.ADD_PERMISSION_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.ADD_PERMISSION_FAILURE payload=', payload);
        },
        [types.UPDATE_PERMISSION_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.UPDATE_PERMISSION_SUCCESS payload=', payload);
        },
        [types.UPDATE_PERMISSION_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.UPDATE_PERMISSION_FAILURE payload=', payload);
        },
        [types.DELETE_PERMISSION_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.DELETE_PERMISSION_SUCCESS payload=', payload);
        },
        [types.DELETE_PERMISSION_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.DELETE_PERMISSION_FAILURE payload=', payload);
        },
        [types.SET_PERMISSIONS] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.SET_PERMISSIONS payload=', payload.permissions[0].children);
            console.log('/resources/assets/js/store/modules/permission.js-types.SET_PERMISSIONS state=', state);
            state.permissions = payload.permissions[0].children;
        },
        [types.UNSET_PERMISSIONS] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.UNSET_PERMISSIONS payload=', payload);
            console.log('/resources/assets/js/store/modules/permission.js-types.UNSET_PERMISSIONS state=', state);
            state.permissions = [];
        },
        [types.SET_PERMISSION_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.SET_PERMISSION_NODES payload=', payload.permissionNodes);
            console.log('/resources/assets/js/store/modules/permission.js-types.SET_PERMISSION_NODES state=', state);
            state.permissionNodes = payload.permissionNodes;
        },
        [types.UNSET_PERMISSION_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.UNSET_PERMISSION_NODES payload=', payload);
            console.log('/resources/assets/js/store/modules/permission.js-types.UNSET_PERMISSION_NODES state=', state);
            state.permissionNodes = null;
        },
        [types.SET_PERMISSION_SHOW_MODAL] (state, payload) {
            console.log('/resources/assets/js/store/modules/permission.js-types.SET_PERMISSION_SHOW_MODAL payload=', payload.data);
            console.log('/resources/assets/js/store/modules/permission.js-types.SET_PERMISSION_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.permissionData = payload.data.data;
        },
    },
    actions: {
        updatePermissionRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/permission.js-updatePermissionRequest formData=',formData);
                Vue.http.post(api.updatePermission, formData)
                    .then(response => {
                        dispatch('updatePermissionSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updatePermissionFailure', response.body);
                        reject(response);
                    });
            })
        },
        updatePermissionSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_PERMISSION_SUCCESS,
                permission: body.permission
            });
            dispatch('showSuccessNotification', 'Permission has been updated.');
        },
        updatePermissionFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_PERMISSION_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deletePermissionRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deletePermission, formData)
                    .then(response => {
                        dispatch('deletePermissionSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deletePermissionFailure', response.body);
                        reject(response);
                    });
            })
        },
        deletePermissionSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_PERMISSION_SUCCESS,
                permission: body.permission
            });
            dispatch('showSuccessNotification', 'Permission has been deleted.');
        },
        deletePermissionFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_PERMISSION_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addPermissionRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addPermission, formData)
                    .then(response => {
                        dispatch('addPermissionSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/permission.js-addPermissionRequest response.body=', response.body);
                        dispatch('addPermissionFailure', response.body);
                        reject(response);
                    });
            })
        },
        addPermissionSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_PERMISSION_SUCCESS,
                permission: body.permission
            });
            dispatch('showSuccessNotification', 'Permission has been added.');
        },
        addPermissionFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_PERMISSION_FAILURE,
                errors: body
            });
            console.error('/resources/assets/js/store/modules/permission.js-addPermissionFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setPermissionNodes: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/permission.js-setPermissionNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentPermissionNodes)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/permission.js-setPermissionNodes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_PERMISSION_NODES,
                            permissionNodes: response.body.permissionNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/permission.js-setPermissionNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetPermissionNodes: ({commit}) => {
            commit({
                type: types.UNSET_PERMISSION_NODES
            });
        },
        setPermissions: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/permission.js-setPermissions');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentPermissions)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/permission.js-setPermissions gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_PERMISSIONS,
                            permissions: response.body.permissions
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/permission.js-setPermissions error =', error);
                        reject(error);
                    });
            });
        },
        unsetPermissions: ({commit}) => {
            commit({
                type: types.UNSET_PERMISSIONS
            });
        },
        setPermissionShowModal:({commit}, data) => {
            commit({
                type: types.SET_PERMISSION_SHOW_MODAL,
                data: data
            });
        },
    }
}
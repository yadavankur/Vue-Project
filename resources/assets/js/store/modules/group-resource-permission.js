import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        grpNodes: null,
        showModal: false,
        grpData: null,
        modalForm: {
            grp: null,
        },
    },
    getters: {
        allGrpNodes: state => state.grpNodes
    },
    mutations: {
        [types.ADD_GRP_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.ADD_GRP_SUCCESS payload=', payload);
        },
        [types.ADD_GRP_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.ADD_GRP_FAILURE payload=', payload);
        },
        [types.UPDATE_GRP_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.UPDATE_GRP_SUCCESS payload=', payload);
        },
        [types.UPDATE_GRP_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.UPDATE_GRP_FAILURE payload=', payload);
        },
        [types.DELETE_GRP_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.DELETE_GRP_SUCCESS payload=', payload);
        },
        [types.DELETE_GRP_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.DELETE_GRP_FAILURE payload=', payload);
        },
        [types.SET_GRP_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.SET_GRP_NODES payload=', payload.grpNodes);
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.SET_GRP_NODES state=', state);
            state.grpNodes = payload.grpNodes;
        },
        [types.UNSET_GRP_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.UNSET_GRP_NODES payload=', payload);
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.UNSET_GRP_NODES state=', state);
            state.grpNodes = null;
        },
        [types.SET_GRP_SHOW_MODAL] (state, payload) {
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.SET_GRP_SHOW_MODAL payload=', payload.data);
            console.log('/resources/assets/js/store/modules/group-resource-permission.js-types.SET_GRP_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.grpData = payload.data.data;
        },
    },
    actions: {
        updateGrpRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/group-resource-permission.js-updateGrpRequest formData=',formData);
                Vue.http.post(api.updateGrp, formData)
                    .then(response => {
                        dispatch('/resources/assets/js/store/modules/group-resource-permission.js-updateGrpSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        dispatch('/resources/assets/js/store/modules/group-resource-permission.js-updateGrpFailure', response.body);
                        reject();
                    });
            })
        },
        updateGrpSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_GRP_SUCCESS,
                grp: body.grp
            });
            dispatch('/resources/assets/js/store/modules/group-resource-permission.js-showSuccessNotification', 'Resource permission has been updated.');
            // dispatch('setGrpNodes');
        },
        updateGrpFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_GRP_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('/resources/assets/js/store/modules/group-resource-permission.js-showErrorNotification', body.error);
            }
        },
        deleteGrpRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteGrp, formData)
                    .then(response => {
                        dispatch('/resources/assets/js/store/modules/group-resource-permission.js-deleteGrpSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        dispatch('/resources/assets/js/store/modules/group-resource-permission.js-deleteGrpFailure', response.body);
                        reject();
                    });
            })
        },
        deleteGrpSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_GRP_SUCCESS,
                grp: body.grp
            });
            dispatch('showSuccessNotification', 'Resource permission has been deleted.');
            //dispatch('setGrpNodes');
        },
        deleteGrpFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_GRP_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addGrpRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addGrp, formData)
                    .then(response => {
                        dispatch('addGrpSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        console.error('addGrpRequest response.body=', response.body);
                        dispatch('addGrpFailure', response.body);
                        reject();
                    });
            })
        },
        addGrpSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_GRP_SUCCESS,
                grp: body.grp
            });
            dispatch('showSuccessNotification', 'Resource permission has been added.');
            //dispatch('setGrpNodes');
        },
        addGrpFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_GRP_FAILURE,
                errors: body
            });
            console.error('addGrpFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setGrpNodes: ({commit, dispatch}) => {
            console.log('setGrpNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentGrpNodes)
                    .then(response => {
                        console.log('setUserNodes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_GRP_NODES,
                            grpNodes: response.body.grpNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setGrpNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetGrpNodes: ({commit}) => {
            commit({
                type: types.UNSET_GRP_NODES
            });
        },
        setGrpShowModal:({commit}, data) => {
            commit({
                type: types.SET_GRP_SHOW_MODAL,
                data: data
            });
        },
        getResourceTypeOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('getResourceTypeOptions');
                Vue.http.get(api.resourceTypeOptions)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        getResourceOptions: ({dispatch}, selectedResourceType) => {
            return new Promise((resolve, reject) => {
                console.log('getResourceOptions selectedResourceType=',selectedResourceType);
                Vue.http.get(api.resourceOptions + '?selectedResourceType=' + selectedResourceType)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        getPermissionOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('getPermissionOptions');
                Vue.http.get(api.permissionOptions)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        getResourceName: ({dispatch}, data) => {
            return new Promise((resolve, reject) => {
                console.log('getResourceName data=',data);
                Vue.http.get(api.resourceName + '?resource_type='
                            + data.resource_type + '&resource_id=' + data.resource_id)
                    .then(response => {
                        console.log('getResourceName response.data.resourceName=',response.data.resourceName);
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        getCascadeResourceOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('getCascadeResourceOptions');
                Vue.http.get(api.cascadeResourceOptions)
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
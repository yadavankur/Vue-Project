import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        groups: [],
        groupNodes: null,
        showModal: false,
        groupData: null,
        modalForm: {
            group: null,
        },
    },
    getters: {
        allGroups: state => state.groups,
        allGroupNodes: state => state.groupNodes
    },
    mutations: {
        [types.ADD_GROUP_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.ADD_GROUP_SUCCESS payload=', payload);
        },
        [types.ADD_GROUP_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.ADD_GROUP_FAILURE payload=', payload);
        },
        [types.UPDATE_GROUP_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.UPDATE_GROUP_SUCCESS payload=', payload);
        },
        [types.UPDATE_GROUP_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.UPDATE_GROUP_FAILURE payload=', payload);
        },
        [types.DELETE_GROUP_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.DELETE_GROUP_SUCCESS payload=', payload);
        },
        [types.DELETE_GROUP_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.DELETE_GROUP_FAILURE payload=', payload);
        },
        [types.SET_GROUPS] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.SET_GROUPS payload=', payload.groups[0].children);
            console.log('/resources/assets/js/store/modules/group.js-types.SET_GROUPS state=', state);
            state.groups = payload.groups[0].children;
        },
        [types.UNSET_GROUPS] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.UNSET_GROUPS payload=', payload);
            console.log('/resources/assets/js/store/modules/group.js-types.UNSET_GROUPS state=', state);
            state.groups = [];
        },
        [types.SET_GROUP_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.SET_GROUP_NODES payload=', payload.groupNodes);
            console.log('/resources/assets/js/store/modules/group.js-types.SET_GROUP_NODES state=', state);
            state.groupNodes = payload.groupNodes;
        },
        [types.UNSET_GROUP_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.UNSET_GROUP_NODES payload=', payload);
            console.log('/resources/assets/js/store/modules/group.js-types.UNSET_GROUP_NODES state=', state);
            state.groupNodes = null;
        },
        [types.SET_GROUP_SHOW_MODAL] (state, payload) {
            console.log('/resources/assets/js/store/modules/group.js-types.SET_GROUP_SHOW_MODAL payload=', payload.data);
            console.log('/resources/assets/js/store/modules/group.js-types.SET_GROUP_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.groupData = payload.data.data;
        },
    },
    actions: {
        updateGroupRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/group.js-updateGroupRequest formData=',formData);
                Vue.http.post(api.updateGroup, formData)
                    .then(response => {
                        dispatch('updateGroupSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updateGroupFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateGroupSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_GROUP_SUCCESS,
                group: body.group
            });
            dispatch('showSuccessNotification', 'Group has been updated.');
        },
        updateGroupFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_GROUP_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteGroupRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteGroup, formData)
                    .then(response => {
                        dispatch('deleteGroupSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deleteGroupFailure', response.body);
                        reject(response);
                    });
            })
        },
        deleteGroupSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_GROUP_SUCCESS,
                group: body.group
            });
            dispatch('showSuccessNotification', 'Group has been deleted.');
        },
        deleteGroupFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_GROUP_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addGroupRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addGroup, formData)
                    .then(response => {
                        dispatch('addGroupSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/group.js-addGroupRequest response.body=', response.body);
                        dispatch('addGroupFailure', response.body);
                        reject(response);
                    });
            })
        },
        addGroupSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_GROUP_SUCCESS,
                group: body.group
            });
            dispatch('showSuccessNotification', 'Group has been added.');
        },
        addGroupFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_GROUP_FAILURE,
                errors: body
            });
            console.error('/resources/assets/js/store/modules/group.js-addGroupFailure body.error=', body.error);
            if(body.error) {
                dispatch('/resources/assets/js/store/modules/group.js-showErrorNotification', body.error);
            }
        },
        setGroupNodes: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/group.js-setGroupNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentGroupNodes)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/group.js-setGroupNodes gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_GROUP_NODES,
                            groupNodes: response.body.groupNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/group.js-setGroupNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetGroupNodes: ({commit}) => {
            commit({
                type: types.UNSET_GROUP_NODES
            });
        },
        setGroups: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/group.js-setGroups');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentGroups)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/group.js-setGroups gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_GROUPS,
                            groups: response.body.groups
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/group.js-setGroups error =', error);
                        reject(error);
                    });
            });
        },
        unsetGroups: ({commit}) => {
            commit({
                type: types.UNSET_GROUPS
            });
        },
        setGroupShowModal:({commit}, data) => {
            commit({
                type: types.SET_GROUP_SHOW_MODAL,
                data: data
            });
        },
    }
}
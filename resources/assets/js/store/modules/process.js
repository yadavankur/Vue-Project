import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        processes: [],
        processNodes: null,
        showModal: false,
        processData: null,
        modalForm: {
            process: null,
        },
    },
    getters: {
        allProcesses: state => state.processes,
        allProcessNodes: state => state.processNodes
    },
    mutations: {
        [types.ADD_PROCESS_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.ADD_PROCESS_SUCCESS payload=', payload);
        },
        [types.ADD_PROCESS_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.ADD_PROCESS_FAILURE payload=', payload);
        },
        [types.UPDATE_PROCESS_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.UPDATE_PROCESS_SUCCESS payload=', payload);
        },
        [types.UPDATE_PROCESS_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.UPDATE_PROCESS_FAILURE payload=', payload);
        },
        [types.DELETE_PROCESS_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.DELETE_PROCESS_SUCCESS payload=', payload);
        },
        [types.DELETE_PROCESS_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.DELETE_PROCESS_FAILURE payload=', payload);
        },
        [types.SET_PROCESSES] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.SET_PROCESSES payload=', payload.processes[0].children);
            console.log('/resources/assets/js/store/modules/process.js-types.SET_PROCESSES state=', state);
            state.processes = payload.processes[0].children;
        },
        [types.UNSET_PROCESSES] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.UNSET_PROCESSES payload=', payload);
            console.log('/resources/assets/js/store/modules/process.js-types.UNSET_PROCESSES state=', state);
            state.processes = [];
        },
        [types.SET_PROCESS_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.SET_PROCESS_NODES payload=', payload.processNodes);
            console.log('/resources/assets/js/store/modules/process.js-types.SET_PROCESS_NODES state=', state);
            state.processNodes = payload.processNodes;
        },
        [types.UNSET_PROCESS_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.UNSET_PROCESS_NODES payload=', payload);
            console.log('/resources/assets/js/store/modules/process.js-types.UNSET_PROCESS_NODES state=', state);
            state.processNodes = null;
        },
        [types.SET_PROCESS_SHOW_MODAL] (state, payload) {
            console.log('/resources/assets/js/store/modules/process.js-types.SET_PROCESS_SHOW_MODAL payload=', payload.data);
            console.log('/resources/assets/js/store/modules/process.js-types.SET_PROCESS_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.processData = payload.data.data;
        },
    },
    actions: {
        updateProcessRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/process.js-updateProcessRequest formData=',formData);
                Vue.http.post(api.updateProcess, formData)
                    .then(response => {
                        dispatch('updateProcessSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('updateProcessFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateProcessSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_PROCESS_SUCCESS,
                process: body.process
            });
            dispatch('showSuccessNotification', 'Process has been updated.');
        },
        updateProcessFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_PROCESS_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteProcessRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteProcess, formData)
                    .then(response => {
                        dispatch('deleteProcessSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        dispatch('deleteProcessFailure', response.body);
                        reject(response);
                    });
            })
        },
        deleteProcessSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_PROCESS_SUCCESS,
                process: body.process
            });
            dispatch('showSuccessNotification', 'Process has been deleted.');
        },
        deleteProcessFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_PROCESS_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addProcessRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addProcess, formData)
                    .then(response => {
                        dispatch('addProcessSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/process.js-addProcessRequest response.body=', response.body);
                        dispatch('addProcessFailure', response.body);
                        reject(response);
                    });
            })
        },
        addProcessSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_PROCESS_SUCCESS,
                process: body.process
            });
            dispatch('showSuccessNotification', 'Process has been added.');
        },
        addProcessFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_PROCESS_FAILURE,
                errors: body
            });
            console.error('/resources/assets/js/store/modules/process.js-addProcessFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setProcessNodes: ({commit, dispatch}) => {
            console.log('setProcessNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentProcessNodes)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/process.js-setProcessNodes get http success response.body=', response.body);
                        commit({
                            type: types.SET_PROCESS_NODES,
                            processNodes: response.body.processNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/process.js-setProcessNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetProcessNodes: ({commit}) => {
            commit({
                type: types.UNSET_PROCESS_NODES
            });
        },
        setProcesses: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/process.js-setProcesses');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentProcesses)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/process.js-setProcesses get http success response.body=', response.body);
                        commit({
                            type: types.SET_PROCESSES,
                            processes: response.body.processes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/process.js-setProcesses error =', error);
                        reject(error);
                    });
            });
        },
        unsetProcesses: ({commit}) => {
            commit({
                type: types.UNSET_PROCESSES
            });
        },
        setProcessShowModal:({commit}, data) => {
            commit({
                type: types.SET_PROCESS_SHOW_MODAL,
                data: data
            });
        },
        getProcessComponentOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/process.js-getProcessComponentOptions');
                Vue.http.get(api.processComponentOptions)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        getProcessCascadeComponentOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/process.js-getProcessCascadeComponentOptions');
                Vue.http.get(api.processCascadeComponentOptions)
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
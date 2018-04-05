
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

const COMPONENT_CREDIT_INFO = 'CreditInfo';

export default {
    state: {  processes: [], },
    getters: {    allProcessesOfCreditInfo: state => state.processes,  },
    mutations: {
        [types.SET_CREDIT_INFO_PROCESSES] (state, payload) {
            console.log('types.SET_CREDIT_INFO_PROCESSES payload=', payload.processes);
            console.log('types.SET_CREDIT_INFO_PROCESSES state=', state);
            let processesArray = payload.processes;
            console.log('types.SET_CREDIT_INFO_PROCESSES componentsArray=', processesArray);
            if (processesArray.length > 0)
                state.processes = JSON.parse(processesArray);
        },
        [types.UNSET_CREDIT_INFO_PROCESSES] (state, payload) {
            console.log('types.UNSET_CREDIT_INFO_PROCESSES payload=', payload);
            console.log('types.UNSET_CREDIT_INFO_PROCESSES state=', state);
            state.processes = [];
        },
    },
    actions: {
        setProcessesOfCreditInfo: ({commit, dispatch}) => {
            console.log('setProcessesOfCreditInfo');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentProcesses, {params: {componentName: COMPONENT_CREDIT_INFO}} )
                    .then(response => {
                        //console.log('setProcessesOfCreditInfo gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_CREDIT_INFO_PROCESSES,
                            processes: response.body.processes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('setProcessesOfCreditInfo error =', error);
                        reject(error);
                    });
            });
        },
        unsetProcessesOfCreditInfo: ({commit}) => {
            commit({
                type: types.UNSET_CREDIT_INFO_PROCESSES
            });
        },
    }
}
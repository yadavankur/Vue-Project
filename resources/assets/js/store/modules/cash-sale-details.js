
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

const COMPONENT_CASH_SALE_DETAILS = 'CashSaleDetails';

export default {
    state: {
        processes: [],
    },
    getters: {
        allProcessesOfCashSaleDetails: state => state.processes,
    },
    mutations: {
        [types.SET_CASH_SALE_DETAILS_PROCESSES] (state, payload) {
            console.log('/resources/assets/js/store/modules/cash-sale-details.js-types.SET_CASH_SALE_DETAILS_PROCESSES payload=', payload.processes);
            console.log('/resources/assets/js/store/modules/cash-sale-details.js-types.SET_CASH_SALE_DETAILS_PROCESSES state=', state);
            let processesArray = payload.processes;
            console.log('/resources/assets/js/store/modules/cash-sale-details.js-types.SET_CASH_SALE_DETAILS_PROCESSES componentsArray=', processesArray);
            if (processesArray.length > 0)
                state.processes = JSON.parse(processesArray);
        },
        [types.UNSET_CASH_SALE_DETAILS_PROCESSES] (state, payload) {
            console.log('/resources/assets/js/store/modules/cash-sale-details.js-types.UNSET_CASH_SALE_DETAILS_PROCESSES payload=', payload);
            console.log('/resources/assets/js/store/modules/cash-sale-details.js-types.UNSET_CASH_SALE_DETAILS_PROCESSES state=', state);
            state.processes = [];
        },
    },
    actions: {
        setProcessesOfCashSaleDetails: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/cash-sale-details.js-setProcessesOfCashSaleDetails');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentProcesses, {params: {componentName: COMPONENT_CASH_SALE_DETAILS}} )
                    .then(response => {
                        //console.log('setProcessesOfCashSaleDetails gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_CASH_SALE_DETAILS_PROCESSES,
                            processes: response.body.processes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/cash-sale-details.js-setProcessesOfCashSaleDetails error =', error);
                        reject(error);
                    });
            });
        },
        unsetProcessesOfCashSaleDetails: ({commit}) => {
            commit({
                type: types.UNSET_CASH_SALE_DETAILS_PROCESSES
            });
        },
    }
}
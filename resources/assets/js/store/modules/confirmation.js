
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

const PAGE_CONFIRMATION = 'confirmation';

export default {
    state: {
        components: [],
        selectedOrder: null,
        details: null,
    },
    // getters: {
    //     allConfirmationComponents: state => state.components,
    // },
    mutations: {
        [types.SET_CONFIRMATION_COMPONENTS] (state, payload) {
            console.log('/resources/assets/js/store/modules/confirmation.js-types.SET_CONFIRMATION_COMPONENTS payload=', payload.components);
            console.log('/resources/assets/js/store/modules/confirmation.js-types.SET_CONFIRMATION_COMPONENTS state=', state);

            let componentsArray = payload.components;
            console.log('/resources/assets/js/store/modules/confirmation.js-types.SET_CONFIRMATION_COMPONENTS componentsArray=', componentsArray);
            if (componentsArray.length > 0)
                state.components = JSON.parse(componentsArray);
        },
        [types.UNSET_CONFIRMATION_COMPONENTS] (state, payload) {
            console.log('/resources/assets/js/store/modules/confirmation.js-types.UNSET_CONFIRMATION_COMPONENTS payload=', payload);
            console.log('/resources/assets/js/store/modules/confirmation.js-types.UNSET_CONFIRMATION_COMPONENTS state=', state);
            state.components = [];
        },
        [types.SET_CONFIRMATION_SELECTED_ORDER] (state, payload) {
            console.log('/resources/assets/js/store/modules/confirmation.js-types.SET_CONFIRMATION_SELECTED_ORDER payload=', payload.selectedOrder);
            console.log('/resources/assets/js/store/modules/confirmation.js-types.SET_CONFIRMATION_SELECTED_ORDER state=', state);
            state.selectedOrder = payload.selectedOrder;
        },
        [types.SEND_TEXT_MESSAGE_REQUEST_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/confirmation.js-types.SEND_TEXT_MESSAGE_REQUEST_SUCCESS payload=', payload);
        },
        [types.SEND_TEXT_MESSAGE_REQUEST_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/confirmation.js-types.SEND_TEXT_MESSAGE_REQUEST_FAILURE payload=', payload);
        },
        [types.SET_CONFIRMATION_ORDER_DETAILS] (state, payload) {
            console.log('/resources/assets/js/store/modules/confirmation.js-types.SET_CONFIRMATION_ORDER_DETAILS payload=', payload);
            state.details = payload.details;
        },
    },
    actions: {
        setConfirmationComponents: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/confirmation.js-setConfirmationComponents');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentComponents, {params: {pageName: PAGE_CONFIRMATION}} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/confirmation.js-setConfirmationComponents get http success response.body=', response.body);
                        commit({
                            type: types.SET_CONFIRMATION_COMPONENTS,
                            components: response.body.components
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/confirmation.js-setConfirmationComponents error =', error);
                        reject(error);
                    });
            });
        },
        unsetConfirmationComponents: ({commit}) => {
            commit({
                type: types.UNSET_CONFIRMATION_COMPONENTS
            });
        },
        setConfirmationSelectedOrder:({commit, dispatch}, data) => {
            if (data != null)
            {
                let payload = {
                    orderNo: data.UDF1,
                    quoteId: data.quote_id,
                    location: data.QUOTE_NUM_PREF,
                };
                dispatch('getConfirmationOrderDetail', payload)
                    .then((response) => {
                        console.log('/resources/assets/js/store/modules/confirmation.js-getConfirmationOrderDetail response=', response);
                    })
                    .catch((error) => {
                    })
            }
            commit({
                type: types.SET_CONFIRMATION_SELECTED_ORDER,
                selectedOrder: data
            });

        },
        getPrintConfirmationList: ({dispatch}, payload) => {
            console.log('/resources/assets/js/store/modules/confirmation.js-getPrintConfirmationList');
            return new Promise((resolve, reject) => {
                Vue.http.post(api.getPrintConfirmationList, payload, {responseType: 'arraybuffer'} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/confirmation.js-getPrintConfirmationList get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/confirmation.js-getPrintConfirmationList error =', error);
                        reject(error);
                    });
            });
        },
        sendTextMessageRequest: ({dispatch}, formData) => {
            console.log('/resources/assets/js/store/modules/confirmation.js-sendTextMessageRequest formData=', formData);
            return new Promise((resolve, reject) => {
                Vue.http.post(api.sendTextMessageRequest, formData)
                    .then(response => {
                        dispatch('sendTextMessageRequestSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/confirmation.js-sendTextMessageRequest response.body=', response.body);
                        dispatch('sendTextMessageRequestFailure', response.body);
                        reject(response);
                    });
            })
        },
        sendTextMessageRequestSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.SEND_TEXT_MESSAGE_REQUEST_SUCCESS,
            });
            dispatch('showSuccessNotification', 'The text message has been successfully queued.');
        },
        sendTextMessageRequestFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.SEND_TEXT_MESSAGE_REQUEST_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        getConfirmationOrderDetail: ({commit, dispatch}, payload) => {
            console.log('/resources/assets/js/store/modules/confirmation.js-getConfirmationOrderDetail');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentOrderDetail, {params: payload} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/confirmation.js-getConfirmationOrderDetail get http success response.body=', response.body);
                        commit({
                            type: types.SET_CONFIRMATION_ORDER_DETAILS,
                            details: response.body.details
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/confirmation.js-getConfirmationOrderDetail error =', error);
                        reject(error);
                    });
            });
        },
        refreshSelectedOrder: ({commit, dispatch}, payload) => {
            console.log('/resources/assets/js/store/modules/confirmation.js-refreshSelectedOrder');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.refreshSelectedOrder, {params: payload} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/confirmation.js-refreshSelectedOrder success response.body=', response.body);
                        dispatch('setConfirmationSelectedOrder', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/confirmation.js-refreshSelectedOrder error =', error);
                        reject(error);
                    });
            });
        },
    }
}
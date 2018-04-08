
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

const PAGE_ORDER_ITEMS = 'items';

export default {
    state: {
        components: [],
        items: null,
    },
    getters: {
        allOrderItemsComponents: state => state.components,
        //allPODProcesses: state => state.processes,
    },
    mutations: {
        [types.SET_ORDER_ITEMS] (state, payload) {
            console.log('/store/modules/order-items.js-types.SET_ORDER_ITEMS payload=', payload);
            console.log('/store/modules/order-items.js-types.SET_ORDER_ITEMS state=', state);
            state.items = payload.items;
        },
        [types.UNSET_ORDER_ITEMS] (state, payload) {
            console.log('/store/modules/order-items.js-types.UNSET_ORDER_ITEMS payload=', payload);
            console.log('/store/modules/order-items.js-types.UNSET_ORDER_ITEMS state=', state);
            state.items = [];
        },
        [types.SET_ORDER_ITEMS_COMPONENTS] (state, payload) {
           // console.log('/resources/assets/js/store/modules/order-items.js-types.SET_ORDER_ITEMS_COMPONENTS payload=', payload.components);
            console.log('/store/modules/order-items.js-types.SET_ORDER_ITEMS_COMPONENTS payload=', payload);
            console.log('/store/modules/order-items.js-types.SET_ORDER_ITEMS_COMPONENTS state=', state);

            let componentsArray = payload.components;
           // console.log('/store/modules/order-items.js-types.SET_COMPONENTS componentsArray=', componentsArray);
            if (componentsArray.length > 0)
                state.components = JSON.parse(componentsArray);
        },
        [types.UNSET_ORDER_ITEMS_COMPONENTS] (state, payload) {
            console.log('/store/modules/order-items.js-types.UNSET_ORDER_ITEMS_COMPONENTS payload=', payload);
            console.log('/store/modules/order-items.js-types.UNSET_ORDER_ITEMS_COMPONENTS state=', state);
            state.components = [];
        },
    },
    actions: {
        getOrderItems: ({commit, dispatch}, payload) => {
            console.log('/store/modules/order-items.js-order-items getOrderItems');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentOrderItem, {params: payload} )
                    .then(response => {
                        console.log('/store/modules/order-items.js-getOrderItems get http success response.body=', response.body);
                        commit({
                            type: types.SET_ORDER_ITEMS,
                            items: response.body.items
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/store/modules/order-items.js-getOrderItems error =', error);
                        reject(error);
                    });
            });
        },
        setOrderItemsComponents: ({commit, dispatch}) => {
            console.log('/store/modules/order-items.js-order-items setOrderItemsComponents');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentComponents, {params: {pageName: PAGE_ORDER_ITEMS}} )
                    .then(response => {
                        console.log('/store/modules/order-items.js-setOrderItemsComponents gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_ORDER_ITEMS_COMPONENTS,
                            components: response.body.components
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/store/modules/order-items.js-setOrderItemsComponents error =', error);
                        reject(error);
                    });
            });
        },
        unsetOrderItemsComponents: ({commit}) => {
            commit({
                type: types.UNSET_ORDER_ITEMS_COMPONENTS
            });
        },
    }
}
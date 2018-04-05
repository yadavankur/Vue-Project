
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

const PAGE_ORDER_DETAILS = 'orderdetails';

export default {
    state: {   components: [],    details: null,  },
    getters: {    //allPODComponents: state => state.components,     //allPODProcesses: state => state.processes,
             },
    mutations: 
    {
        [types.SET_ORDER_DETAILS] (state, payload) 
        {   console.log('/store/modules/order-details.js-types.SET_ORDER_DETAILS payload=', payload);
            console.log('/store/modules/order-details.js-types.SET_ORDER_DETAILS state=', state);
            state.details = payload.details;
        },
        [types.UNSET_ORDER_DETAILS] (state, payload) 
        {   console.log('/store/modules/order-details.js-types.UNSET_ORDER_DETAILS payload=', payload);
            console.log('/modules/order-details.js-types.UNSET_ORDER_DETAILS state=', state);
            state.details = null;
        },
        [types.SET_ORDER_DETAILS_COMPONENTS] (state, payload) 
        {   console.log('/store/modules/order-details.js-types.SET_ORDER_DETAILS_COMPONENTS payload=', payload.components);
            console.log('/store/modules/order-details.js-types.SET_ORDER_DETAILS_COMPONENTS state=', state);

            let componentsArray = payload.components;
            console.log('/store/modules/order-details.js-types.SET_COMPONENTS componentsArray=', componentsArray);
            if (componentsArray.length > 0)
                state.components = JSON.parse(componentsArray);
        },
        [types.UNSET_ORDER_DETAILS_COMPONENTS] (state, payload) 
        {
            console.log('/modules/order-details.js-types.UNSET_ORDER_DETAILS_COMPONENTS payload=', payload);
            console.log('/modules/order-details.js-types.UNSET_ORDER_DETAILS_COMPONENTS state=', state);
            state.components = [];
        },
    },
    actions: 
    {   getOrderDetail: ({commit, dispatch}, payload) => 
        {   console.log('/store/modules/order-details.js-order-details getOrderDetail');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentOrderDetail, {params: payload} )
                    .then(response => 
                        {  console.log('/store/modules/order-details.js-getOrderDetail get http success response.body=', response.body);
                           commit({ type: types.SET_ORDER_DETAILS, details: response.body.details   });
                           resolve(response);
                        })
                    .catch(error => 
                        {  console.error('/store/modules/order-details.js-getOrderDetail error =', error);
                           reject(error);
                        });
            });
        },
        setOrderDetailComponents: ({commit, dispatch}) => 
        {    console.log('/store/modules/order-details.js-order-details setOrderDetailComponents');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentComponents, {params: {pageName: PAGE_ORDER_DETAILS}} )
                    .then(response => {
                        // console.log('setComponents gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_ORDER_DETAILS_COMPONENTS,
                            components: response.body.components
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/order-details.js-setComponents error =', error);
                        reject(error);
                    });
            });
        },
        unsetOrderDetailsComponents: ({commit}) => { commit({ type: types.UNSET_ORDER_DETAILS_COMPONENTS });        },
        unsetOrderDetails: ({commit}) => { commit({     type: types.UNSET_ORDER_DETAILS   });   },
        getOrderStatusOptions: ({dispatch}) => 
        {      return new Promise((resolve, reject) => 
               {  console.log('/resources/assets/js/store/modules/order-details.js-getOrderStatusOptions');
                  Vue.http.get(api.currentOrderStatusOptions)
                    .then(response => {  resolve(response);   })
                    .catch(response => {  reject(response);   });
               })
        },
    }
}

import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

const PAGE_CONFIRMATION = 'CustomerService';

export default 
{ state: {  components: [], selectedOrder: null, },
  mutations: {  [types.SET_CUSTOMER_SERVICE_COMPONENTS] (state, payload) 
                    { console.log('/resources/assets/js/store/modules/customer-service.js-types.SET_CUSTOMER_SERVICE_COMPONENTS payload=', payload.components);
                      console.log('/resources/assets/js/store/modules/customer-service.js-types.SET_CUSTOMER_SERVICE_COMPONENTS state=', state);
                      let componentsArray = payload.components;
                      console.log('/resources/assets/js/store/modules/customer-service.js-types.SET_CUSTOMER_SERVICE_COMPONENTS componentsArray=', componentsArray);
                      if (componentsArray.length > 0)
                       state.components = JSON.parse(componentsArray);
                     },
                [types.UNSET_CUSTOMER_SERVICE_COMPONENTS] (state, payload) 
                    { console.log('/resources/assets/js/store/modules/customer-service.js-types.UNSET_CUSTOMER_SERVICE_COMPONENTS payload=', payload);
                      console.log('/resources/assets/js/store/modules/customer-service.js-types.UNSET_CUSTOMER_SERVICE_COMPONENTS state=', state);
                      state.components = [];
                    },
                [types.SET_CUSTOMER_SERVICE_SELECTED_ORDER] (state, payload) 
                    { console.log('/resources/assets/js/store/modules/customer-service.js-types.SET_CUSTOMER_SERVICE_SELECTED_ORDER payload=', payload.selectedOrder);
                      console.log('/resources/assets/js/store/modules/customer-service.js-types.SET_CUSTOMER_SERVICE_SELECTED_ORDER state=', state);
                      state.selectedOrder = payload.selectedOrder;
                      console.log('/resources/assets/js/store/modules/customer-service.js-types.SET_CUSTOMER_SERVICE_SELECTED_ORDER state=', payload.selectedOrder);
                     },
                [types.SAVE_ACTIVITY_DOWELL_NOTES_REQUEST_SUCCESS] (state, payload) 
                     { console.log('/resources/assets/js/store/modules/customer-service.js-types.SAVE_ACTIVITY_DOWELL_NOTES_REQUEST_SUCCESS payload=', payload);
                     },
                [types.SAVE_ACTIVITY_DOWELL_NOTES_REQUEST_FAILURE] (state, payload) 
                     { console.log('/resources/assets/js/store/modules/customer-service.js-types.SAVE_ACTIVITY_DOWELL_NOTES_REQUEST_FAILURE payload=', payload);
        },

    },
    actions: { setCustomerServiceComponents: ({commit, dispatch}) => 
                {   console.log('/resources/assets/js/store/modules/customer-service.js-setCustomerServiceComponents');
                    return new Promise((resolve, reject) => 
                       {  Vue.http.get(api.currentComponents, {params: {pageName: PAGE_CONFIRMATION}} ).then(response => 
                           {  console.log('/resources/assets/js/store/modules/customer-service.js-setCustomerServiceComponents get http success response.body=', response.body);
                              commit({ type: types.SET_CUSTOMER_SERVICE_COMPONENTS, components: response.body.components });
                              resolve(response);
                           })
                         .catch(error => 
                            { console.error('/resources/assets/js/store/modules/customer-service.js-setCustomerServiceComponents error =', error);
                              reject(error);
                            });
                       });
                 },
                 unsetCustomerServiceComponents: ({commit}) => {  commit({ type: types.UNSET_CUSTOMER_SERVICE_COMPONENTS });},
                 setCustomerServiceSelectedOrder:({commit}, data) => { commit({  type: types.SET_CUSTOMER_SERVICE_SELECTED_ORDER, selectedOrder: data }); },
                 getOrderActivities: ({dispatch}, payload) => 
                   {  console.log('/resources/assets/js/store/modules/customer-service.js-getOrderActivities');
                      return new Promise((resolve, reject) => 
                         {    Vue.http.get(api.currentOrderActivityList, {params: payload} )
                             .then(response => { console.log('/resources/assets/js/store/modules/customer-service.js-getOrderActivities get http success response.body=', response.body);
                                               resolve(response);
                                              })
                             .catch(error => {   console.error('/resources/assets/js/store/modules/customer-service.js-getOrderActivities error =', error);
                                                 reject(error);
                                             });
                         });
                   },
                 getLatestActivityNotesRequest: ({dispatch}, payload) => 
                   {  console.log('/resources/assets/js/store/modules/customer-service.js-getLatestActivityNotesRequest');
                      return new Promise((resolve, reject) => 
                          {   Vue.http.get(api.currentLatestActivityNotes, {params: payload} )
                              .then(response => {  console.log('/resources/assets/js/store/modules/customer-service.js-getLatestActivityNotesRequest get http success response.body=', response.body);
                                                   resolve(response);
                                                })
                              .catch(error => {    console.error('/resources/assets/js/store/modules/customer-service.js-getLatestActivityNotesRequest error =', error);
                                                   reject(error);
                                              });
                          });
                    },
                 saveActivityDowellNotesRequest: ({dispatch}, formData) => 
                    {   console.log('/resources/assets/js/store/modules/customer-service.js-saveActivityDowellNotesRequest formData=', formData);
                        return new Promise((resolve, reject) => 
                           {    Vue.http.post(api.saveActivityDowellNotesRequest, formData)
                               .then(response => { dispatch('saveActivityDowellNotesRequestSuccess', response.body);
                                                    resolve(response);
                                                 })
                               .catch(response => { console.error('/resources/assets/js/store/modules/customer-service.js-saveActivityDowellNotesRequest response.body=', response.body);
                                                    dispatch('saveActivityDowellNotesRequestFailure', response.body);
                                                    reject(response);
                                                  });
                           })
                    },
                 saveActivityDowellNotesRequestSuccess: ({commit, dispatch}, body) => 
                   {   commit({  type: types.SAVE_ACTIVITY_DOWELL_NOTES_REQUEST_SUCCESS,  });
                       dispatch('showSuccessNotification', 'The activity information has been successfully saved.');
                   },
                 saveActivityDowellNotesRequestFailure: ({commit, dispatch}, body) => 
                   {   commit({   type: types.SAVE_ACTIVITY_DOWELL_NOTES_REQUEST_FAILURE,  errors: body   });
                       if(body.error) {  dispatch('showErrorNotification', body.error); }
                   },
                 getActivitiesOfOrder: ({dispatch}, payload) => 
                   {   console.log('/resources/assets/js/store/modules/customer-service.js-getActivitiesOfOrder');
                       return new Promise((resolve, reject) => 
                         {    Vue.http.get(api.currentActivitiesOfOrder, {params: payload} )
                              .then(response => {  console.log('/resources/assets/js/store/modules/customer-service.js-getActivitiesOfOrder get http success response.body=', response.body);
                                                   resolve(response);
                                                })
                              .catch(error => {    console.error('/resources/assets/js/store/modules/customer-service.js-getActivitiesOfOrder error =', error);
                                                   reject(error);
                                              });
                         });
                    },
                  simulateCPM: ({dispatch}, payload) => 
                    {    console.log('/resources/assets/js/store/modules/customer-service.js-simulateCPM');
                         return new Promise((resolve, reject) => 
                           {   Vue.http.post(api.simulateCPM, payload)
                               .then(response => {  console.log('/resources/assets/js/store/modules/customer-service.js-simulateCPM get http success response.body=', response.body);
                                                    resolve(response);
                                                 })
                               .catch(error => {   console.error('/resources/assets/js/store/modules/customer-service.js-simulateCPM error =', error);
                                                   dispatch('/resources/assets/js/store/modules/customer-service.js-showErrorNotification', error.body);
                                                   reject(error);
                                               });
                           });
                    },
                  reCalculateCPM: ({dispatch}, payload) => 
                    {     console.log('/resources/assets/js/store/modules/customer-service.js-reCalculateCPM');
                          return new Promise((resolve, reject) => 
                           {    Vue.http.post(api.reCalculateCPM, payload)
                                .then(response => 
                                    {  console.log('/resources/assets/js/store/modules/customer-service.js-reCalculateCPM get http success response.body=', response.body);
                                        resolve(response);
                                    })
                                 .catch(error => 
                                    {  console.error('/resources/assets/js/store/modules/customer-service.js-reCalculateCPM error =', error);
                                       dispatch('showErrorNotification', error.body);
                                       reject(error);
                                    });
                           });
                    },
                  updateCPMRequest: ({dispatch}, payload) => 
                    {     console.log('/resources/assets/js/store/modules/customer-service.js-updateCPMRequest payload=', payload);
                          return new Promise((resolve, reject) => 
                            {    Vue.http.post(api.updateCPMRequest, payload)
                                 .then(response => 
                                    {     console.log('/resources/assets/js/store/modules/customer-service.js-updateCPMRequest get http success response.body=', response.body);
                                          dispatch('showSuccessNotification', 'The CPM information has been successfully updated.');
                                          resolve(response);
                                     })
                                 .catch(error => 
                                    {     console.error('/resources/assets/js/store/modules/customer-service.js-updateCPMRequest error =', error);
                                          dispatch('showErrorNotification', error.body);
                                          reject(error);
                                    });
                            });
                    },
                 createCPMRequest: ({dispatch}, payload) => 
                   {   console.log('/resources/assets/js/store/modules/customer-service.js-createCPMRequest payload=', payload);
                       return new Promise((resolve, reject) => 
                         {   Vue.http.post(api.createCPMRequest, payload)
                             .then(response => 
                                {  console.log('/resources/assets/js/store/modules/customer-service.js-createCPMRequest get http success response.body=', response.body);
                                   dispatch('showSuccessNotification', 'The CPM has been successfully created.');
                                   resolve(response);
                                })
                             .catch(error => 
                                {  console.error('/resources/assets/js/store/modules/customer-service.js-createCPMRequest error =', error);
                                   dispatch('showErrorNotification', error.body);
                                   reject(error);
                                });
                         });
                   },
                 getServiceOptionsOfLocation: ({dispatch}, payload) => 
                   { console.log('/resources/assets/js/store/modules/customer-service.js-getServiceOptionsOfLocation');
                     return new Promise((resolve, reject) => 
                        {     Vue.http.get(api.getServiceOptionsOfLocation, {params: payload})
                              .then(response => {  console.log('/resources/assets/js/store/modules/customer-service.js-getServiceOptionsOfLocation get http success response.body=', response.body);
                                                   resolve(response);
                                                 })
                              .catch(error => {   console.error('/resources/assets/js/store/modules/customer-service.js-getServiceOptionsOfLocation error =', error);
                                                  reject(error);
                                              });
                        });
                     },
                 getCascadeServiceGroupOptionsOfLocation({dispatch}, payload) 
                   {  console.log('/resources/assets/js/store/modules/customer-service.js-getCascadeServiceGroupOptionsOfLocation');
                      return new Promise((resolve, reject) => 
                         {    Vue.http.get(api.getCascadeServiceGroupOptionsOfLocation, {params: payload})
                             .then(response => {   console.log('/resources/assets/js/store/modules/customer-service.js-getCascadeServiceGroupOptionsOfLocation get http success response.body=', response.body);
                                                   resolve(response);
                                               })
                             .catch(error => {     console.error('/resources/assets/js/store/modules/customer-service.js-getCascadeServiceGroupOptionsOfLocation error =', error);
                                                   reject(error);
                                             });
                         });
                   },
                 getActivityStatuses: ({dispatch}) => 
                   {  console.log('/resources/assets/js/store/modules/customer-service.js-getActivityStatuses');
                      return new Promise((resolve, reject) => 
                         {   Vue.http.get(api.getactivitystatuses)
                             .then(response => {   console.log('/resources/assets/js/store/modules/customer-service.js-getActivityStatuses get http success response.body=', response.body);
                                                   resolve(response);
                                               })
                             .catch(error => {     console.error('/resources/assets/js/store/modules/customer-service.js-getActivityStatuses error =', error);
                                                   reject(error);
                                             });
                         });
                   },
                 addAdhocActivityRequest: ({dispatch}, payload) => 
                  {   console.log('/resources/assets/js/store/modules/customer-service.js-addAdhocActivityRequest payload=', payload);
                      return new Promise((resolve, reject) =>
                         {    Vue.http.post(api.addAdhocActivityRequest, payload)
                               .then(response => {    console.log('/resources/assets/js/store/modules/customer-service.js-addAdhocActivityRequest get http success response.body=', response.body);
                                                     dispatch('showSuccessNotification', 'The ad hoc activity has been successfully added.');
                                                     resolve(response);
                                                })
                               .catch(error => {   console.error('/resources/assets/js/store/modules/customer-service.js-addAdhocActivityRequest error =', error);
                                                   dispatch('showErrorNotification', error.body);
                                                   reject(error);
                                               });
                         });
                  }
        }//end of actions
}//end of export
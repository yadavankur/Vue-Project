import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{   state: {  cpmServices: [],cpmServiceNodes: null,showModal: false,cpmServiceData: null, modalForm: {cpmService: null, }, },
    getters: { allCpmServices: state => state.cpmServices, allCpmServiceNodes: state => state.cpmServiceNodes },
    mutations: 
       {    [types.ADD_CPMSERVICE_SUCCESS] (state, payload) { console.log('/store/cpm-service--types.ADD_CPMSERVICE_SUCCESS payload=', payload);  },
            [types.ADD_CPMSERVICE_FAILURE] (state, payload) { console.log('/store/cpm-service--types.ADD_CPMSERVICE_FAILURE payload=', payload);  },
            [types.UPDATE_CPMSERVICE_SUCCESS] (state, payload) {console.log('/store/cpm-service--types.UPDATE_CPMSERVICE_SUCCESS payload=', payload); },
            [types.UPDATE_CPMSERVICE_FAILURE] (state, payload) { console.log('/store/cpm-service--types.UPDATE_CPMSERVICE_FAILURE payload=', payload);  },
            [types.DELETE_CPMSERVICE_SUCCESS] (state, payload) {console.log('/store/cpm-service--types.DELETE_CPMSERVICE_SUCCESS payload=', payload);   },
            [types.DELETE_CPMSERVICE_FAILURE] (state, payload) { console.log('/store/cpm-service--types.DELETE_CPMSERVICE_FAILURE payload=', payload);   },
            [types.SET_CPMSERVICES] (state, payload) { console.log('types.SET_CPMSERVICES payload=', payload.cpmServices[0].children);
                                                       console.log('types.SET_CPMSERVICES state=', state);
                                                       state.cpmServices = payload.cpmServices[0].children;
                                                     },
            [types.UNSET_CPMSERVICES] (state, payload) { console.log('/store/cpm-service--types.UNSET_CPMSERVICES payload=', payload);
                                                     console.log('/store/cpm-service--types.UNSET_CPMSERVICES state=', state);
                                                     state.cpmServices = [];
                                                    },
            [types.SET_CPMSERVICE_NODES] (state, payload) { console.log('/store/cpm-service--types.SET_CPMSERVICE_NODES payload=', payload.cpmServiceNodes);
                                                            console.log('/store/cpm-service--types.SET_CPMSERVICE_NODES state=', state);
                                                            state.cpmServiceNodes = payload.cpmServiceNodes;
                                                          },
            [types.UNSET_CPMSERVICE_NODES] (state, payload) { console.log('/store/cpm-service--types.UNSET_CPMSERVICE_NODES payload=', payload);
                                                          console.log('/store/cpm-service--types.UNSET_CPMSERVICE_NODES state=', state);
                                                          state.cpmServiceNodes = null;
                                                        },
            [types.SET_CPMSERVICE_SHOW_MODAL] (state, payload) { console.log('/store/cpm-service--types.SET_CPMSERVICE_SHOW_MODAL payload=', payload.data);
                                                             console.log('/store/cpm-service--types.SET_CPMSERVICE_SHOW_MODAL state=', state);
                                                             state.showModal = payload.data.isShow;
                                                             state.cpmServiceData = payload.data.data;
                                                           },
        },
    actions: 
       {    updateCpmServiceRequest: ({dispatch}, formData) => 
               {  return new Promise((resolve, reject) => 
                   {  console.log('updateCpmServiceRequest formData=',formData);
                      Vue.http.post(api.updateCpmService, formData)
                      .then(response => { dispatch('updateCpmServiceSuccess', response.body); resolve(response);  })
                      .catch(response => {  dispatch('updateCpmServiceFailure', response.body); reject(response); });
                   })
               },
           updateCpmServiceSuccess: ({commit, dispatch}, body) => 
               {   commit({ type: types.UPDATE_CPMSERVICE_SUCCESS, cpmService: body.cpmService            });
                   dispatch('showSuccessNotification', 'CpmService has been updated.');
               },
           updateCpmServiceFailure: ({commit, dispatch}, body) => 
               {   commit({  type: types.UPDATE_CPMSERVICE_FAILURE, errors: body });
                if(body.error) { dispatch('showErrorNotification', body.error);   }
               },
           deleteCpmServiceRequest: ({dispatch}, formData) => 
               {    return new Promise((resolve, reject) => 
                   {    Vue.http.post(api.deleteCpmService, formData)
                        .then(response => { dispatch('deleteCpmServiceSuccess', response.body); resolve(response); })
                        .catch(response => { dispatch('deleteCpmServiceFailure', response.body); reject(response); });
                   })
               },
           deleteCpmServiceSuccess: ({commit, dispatch}, body) => 
               { commit({type: types.DELETE_CPMSERVICE_SUCCESS,cpmService: body.cpmService });
                 dispatch('showSuccessNotification', 'CpmService has been deleted.');
               },
           deleteCpmServiceFailure: ({commit, dispatch}, body) => 
                { commit({  type: types.DELETE_CPMSERVICE_FAILURE, errors: body  });
                 if(body.error) { dispatch('showErrorNotification', body.error); }
                },
            addCpmServiceRequest: ({dispatch}, formData) => 
                {   return new Promise((resolve, reject) => 
                    {   Vue.http.post(api.addCpmService, formData)
                        .then(response => { dispatch('addCpmServiceSuccess', response.body); resolve(response);   })
                        .catch(response => { console.error('/store/cpm-service--addCpmServiceRequest response.body=', response.body);
                                         dispatch('addCpmServiceFailure', response.body);
                                         reject(response);
                                       });
                    })
                },
            addCpmServiceSuccess: ({commit, dispatch}, body) => 
                {    commit({ type: types.ADD_CPMSERVICE_SUCCESS, cpmService: body.cpmService });
                     dispatch('showSuccessNotification', 'CpmService has been added.');
                },
            addCpmServiceFailure: ({commit, dispatch}, body) => 
               {     commit({ type: types.ADD_CPMSERVICE_FAILURE, errors: body  });
                     console.error('/store/cpm-service--addCpmServiceFailure body.error=', body.error);
                     if(body.error) {  dispatch('showErrorNotification', body.error); }
               },
            setCpmServiceNodes: ({commit, dispatch}) => 
               {     console.log('/store/cpm-service--setCpmServiceNodes');
                     return new Promise((resolve, reject) => 
                       {   Vue.http.get(api.currentCpmServiceNodes)
                           .then(response =>{ console.log('/store/cpm-service--setCpmServiceNodes gethttp success response.body=', response.body);
                                                commit({  type: types.SET_CPMSERVICE_NODES,  cpmServiceNodes: response.body.cpmServiceNodes  });
                                                resolve(response);
                                             })
                           .catch(error =>{console.error('/store/cpm-service--setCpmServiceNodes error =', error); reject(error); });
                        });   
                },
            unsetCpmServiceNodes: ({commit}) => {commit({ type: types.UNSET_CPMSERVICE_NODES  }); },
            setCpmServices: ({commit, dispatch}) => 
              {  console.log('/store/cpm-service--setCpmServices');
                 return new Promise((resolve, reject) => 
                  {    Vue.http.get(api.currentCpmServices)
                       .then(response =>{  console.log('/store/cpm-service--setCpmServices gethttp success response.body=', response.body);
                                        commit({  type: types.SET_CPMSERVICES, cpmServices: response.body.cpmServices });
                                        resolve(response);
                                    })
                      .catch(error => { console.error('/store/cpm-service--setCpmServices error =', error);  reject(error);  });
                  });
              },
            unsetCpmServices: ({commit}) => {  commit({ type: types.UNSET_CPMSERVICES });   },
            setCpmServiceShowModal:({commit}, data) => { commit({ type: types.SET_CPMSERVICE_SHOW_MODAL, data: data }); },
            getCpmServiceOptions: ({dispatch}) => 
                {  return new Promise((resolve, reject) => 
                    {   console.log('/store/cpm-service--getCpmServiceOptions');
                         Vue.http.get(api.cpmServiceOptions).then(response=>{resolve(response);}).catch(response =>{reject(response);  });
                    })
                },
            getCpmCascadeServiceOptions: ({dispatch}) => 
                { return new Promise((resolve, reject) =>{  console.log('getCpmCascadeServiceOptions');
                                                        Vue.http.get(api.cpmCascadeServiceOptions).then(response =>{resolve(response);}).catch(response =>{reject(response);});
                                                   })
                },
    }
}
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

//getter -function in store that takes a state object and returns a value from it using mapgetter in components. 
//In components- accessed through this.$store.getters.property as a computed property, not a function. 
//If a getter needs a parameter, it can return a second function which takes a parameter.

export default 
{ state: {  cpm2Services: [],
            cpm2ServiceNodes: null, 
            showModal: false,
            cpm2ServiceData: null, 
            modalForm: {cpm2Service: null, }, },
  getters: { allCpm2Services: state => state.cpm2Services, 
             allCpm2ServiceNodes: state => state.cpm2ServiceNodes 
            },
  mutations: {
//Direct modification of state in Vuex is done by calling a function called a mutation.
// A mutation is passed the current state and an optional payload. The payload can be any object. 
//Mutations must be synchronous and shouldn’t return a value.
                    [types.DELETE_CPM2SERVICE_SUCCESS] (state, payload) {console.log('/store/cpm2--types.DELETE_CPMSERVICE_SUCCESS payload=', payload);   },
                    [types.DELETE_CPM2SERVICE_FAILURE] (state, payload) { console.log('/store/cpm2-types.DELETE_CPMSERVICE_FAILURE payload=', payload);   },
                    [types.SET_CPM2SERVICE_SHOW_MODAL] (state, payload) //------used to update data ie variables bw vue and store
                     { console.log('/store/cpm2--types.SET_CPM2SERVICE_SHOW_MODAL payload=', payload.data);
                       console.log('/store/cpm2--types.SET_CPM2SERVICE_SHOW_MODAL state=', state);
                       state.showModal = payload.data.isShow;
                       state.cpm2ServiceData = payload.data.data;
                     },
                     //---------------------------delete done -------add start-----------
                     [types.ADD_CPM2SERVICE_SUCCESS] (state, payload) { console.log('/store/cpm2--types.ADD_CPM2SERVICE_SUCCESS payload=', payload);  },
                     [types.ADD_CPM2SERVICE_FAILURE] (state, payload) { console.log('/store/cpm2--types.ADD_CPM2SERVICE_FAILURE payload=', payload);  },
                      //------------add finish----edit start------------
                      [types.UPDATE_CPM2SERVICE_SUCCESS] (state, payload) {console.log('/store/cpm2--types.UPDATE_CPMSERVICE_SUCCESS payload=', payload); },
                      [types.UPDATE_CPM2SERVICE_FAILURE] (state, payload) { console.log('/store/cpm2--types.UPDATE_CPMSERVICE_FAILURE payload=', payload);  },
                     
               },
  actions:{  deleteCpm2ServiceRequest: ({dispatch}, formData) => 
                {    return new Promise((resolve, reject) => 
                     {    Vue.http.post(api.deleteCpm2Service, formData)
                     .then(response => { dispatch('deleteCpm2ServiceSuccess', response.body); resolve(response); })
                     .catch(response => { dispatch('deleteCpm2ServiceFailure', response.body); reject(response); });
                     })
                },
             deleteCpm2ServiceSuccess: ({commit, dispatch}, body) => 
                { commit({type: types.DELETE_CPM2SERVICE_SUCCESS,cpmService: body.cpmService });
                  dispatch('showSuccessNotification', 'Cpm2Service has been deleted.');
                },
             deleteCpm2ServiceFailure: ({commit, dispatch}, body) => 
               { commit({  type: types.DELETE_CPM2SERVICE_FAILURE, errors: body  });
                 if(body.error) { dispatch('showErrorNotification', body.error); }
               },

    //--showmodal----used to update data ie variables bw vue and store----for add/delete/update--via--state.cpm2ServiceData = payload.data.data;
             setCpm2ServiceShowModal:({commit}, data) => { 
               commit({ type: types.SET_CPM2SERVICE_SHOW_MODAL, data: data }); 
              },
    //--------------delete working above-----add below----------------
             addCpm2ServiceRequest: ({dispatch}, formData) => 
             {   return new Promise((resolve, reject) => 
                 {   Vue.http.post(api.addCpm2Service, formData)
                     .then(response => { dispatch('addCpm2ServiceSuccess', response.body); resolve(response);   })
                     .catch(response => { console.error('/store/cpm2--addCpm2ServiceRequest response.body=', response.body);
                                      dispatch('addCpm2ServiceFailure', response.body);
                                      reject(response);
                                    });
                 })
             },
            addCpm2ServiceSuccess: ({commit, dispatch}, body) => 
             {    commit({ type: types.ADD_CPM2SERVICE_SUCCESS, cpmService: body.cpmService });
                  dispatch('showSuccessNotification', 'Cpm2Service has been added.');
             },
            addCpm2ServiceFailure: ({commit, dispatch}, body) => 
            {     commit({ type: types.ADD_CPM2SERVICE_FAILURE, errors: body  });
                  console.error('/store/cpm2-service--addCpm2ServiceFailure body.error=', body.error);
                  if(body.error) {  dispatch('showErrorNotification', body.error); }
            },
    //---------------------add finish-----------edit start--------------
           updateCpm2ServiceRequest: ({dispatch}, formData) => 
               {  return new Promise((resolve, reject) => 
                   {  console.log('updateCpm2ServiceRequest formData=',formData);
                      Vue.http.post(api.updateCpm2Service, formData)
                      .then(response => { dispatch('updateCpm2ServiceSuccess', response.body); resolve(response);  })
                      .catch(response => {  dispatch('updateCpm2ServiceFailure', response.body); reject(response); });
                   })
               },
           updateCpm2ServiceSuccess: ({commit, dispatch}, body) => 
               {   commit({ type: types.UPDATE_CPM2SERVICE_SUCCESS, cpmService: body.cpmService            });
                   dispatch('showSuccessNotification', 'Cpm2Service has been updated.');
               },
           updateCpm2ServiceFailure: ({commit, dispatch}, body) => 
               {   commit({  type: types.UPDATE_CPM2SERVICE_FAILURE, errors: body });
                if(body.error) { dispatch('showErrorNotification', body.error);   }
               },
            //-----------------edit/update done-------------------
           
            }

}
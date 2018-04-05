import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{
    state: {  cpmActivities: [], cpmActivityNodes: null,  showModal: false,  cpmActivityData: null,
              modalForm: {    cpmActivity: null,   },
           },
    getters: 
          {   allCpmActivities: state => state.cpmActivities,
        allCpmActivityNodes: state => state.cpmActivityNodes
         },
    mutations: 
       {    [types.UPDATE_CPMACTIVITY_USERS_SUCCESS] (state, payload) 
               { console.log('/store/cpm-activity.js-types.UPDATE_CPMACTIVITY_USERS_SUCCESS payload=', payload); },
            [types.UPDATE_CPMACTIVITY_USERS_FAILURE] (state, payload) 
               { console.log('/store/cpm-activity.js-types.UPDATE_CPMACTIVITY_USERS_FAILURE payload=', payload); },
            [types.ADD_CPMACTIVITY_SUCCESS] (state, payload) 
               {  console.log('/store/cpm-activity.js-types.ADD_CPMACTIVITY_SUCCESS payload=', payload); },
            [types.ADD_CPMACTIVITY_FAILURE] (state, payload) 
               {  console.log('/store/cpm-activity.js-types.ADD_CPMACTIVITY_FAILURE payload=', payload); },
            [types.UPDATE_CPMACTIVITY_SUCCESS] (state, payload) 
               { console.log('/store/cpm-activity.js-types.UPDATE_CPMACTIVITY_SUCCESS payload=', payload); },
            [types.UPDATE_CPMACTIVITY_FAILURE] (state, payload) 
               { console.log('/store/cpm-activity.js-types.UPDATE_CPMACTIVITY_FAILURE payload=', payload); },
            [types.DELETE_CPMACTIVITY_SUCCESS] (state, payload) 
               { console.log('/store/cpm-activity.js-types.DELETE_CPMACTIVITY_SUCCESS payload=', payload);   },
            [types.DELETE_CPMACTIVITY_FAILURE] (state, payload) 
               { console.log('/store/cpm-activity.js-types.DELETE_CPMACTIVITY_FAILURE payload=', payload);  },
            [types.SET_CPMACTIVITIES] (state, payload) 
               { console.log('/store/cpm-activity.js-types.SET_CPMACTIVITIES payload=', payload.cpmActivitys[0].children);
                 console.log('/store/cpm-activity.js-types.SET_CPMACTIVITIES state=', state);
                 state.cpmActivitys = payload.cpmActivitys[0].children;
               },
            [types.UNSET_CPMACTIVITIES] (state, payload) 
               {  console.log('/store/cpm-activity.js-types.UNSET_CPMACTIVITIES payload=', payload);
                  console.log('/store/cpm-activity.js-types.UNSET_CPMACTIVITIES state=', state);
                  state.cpmActivitys = [];
               },
            [types.SET_CPMACTIVITY_NODES] (state, payload) 
               {   console.log('/store/cpm-activity.js-types.SET_CPMACTIVITY_NODES payload=', payload.cpmActivityNodes);
                   console.log('/store/cpm-activity.js-types.SET_CPMACTIVITY_NODES state=', state);
                   state.cpmActivityNodes = payload.cpmActivityNodes;
               },
            [types.UNSET_CPMACTIVITY_NODES] (state, payload) 
               {   console.log('/resources/assets/js/store/modules/cpm-activity.js-types.UNSET_CPMACTIVITY_NODES payload=', payload);
                   console.log('/resources/assets/js/store/modules/cpm-activity.js-types.UNSET_CPMACTIVITY_NODES state=', state);
                   state.cpmActivityNodes = null;
               },
             [types.SET_CPMACTIVITY_SHOW_MODAL] (state, payload) 
               {   console.log('/store/cpm-activity.js-types.SET_CPMACTIVITY_SHOW_MODAL payload=', payload.data);
                   console.log('/store/cpm-activity.js-types.SET_CPMACTIVITY_SHOW_MODAL state=', state);
                   state.showModal = payload.data.isShow;
                   state.cpmActivityData = payload.data.data;
               },
        },
    actions: 
    {   updateCpmActivityRequest: ({dispatch}, formData) => 
         {  return new Promise((resolve, reject) => 
             {   console.log('/store/cpm-activity.js-updateCpmActivityRequest formData=',formData);
                Vue.http.post(api.updateCpmActivity, formData)
                    .then(response => { dispatch('updateCpmActivitySuccess', response.body);  resolve(response); })
                    .catch(response => { dispatch('updateCpmActivityFailure', response.body); reject(response);  });
            })
         },
        updateCpmActivitySuccess: ({commit, dispatch}, body) => 
         { commit({ type: types.UPDATE_CPMACTIVITY_SUCCESS, cpmActivity: body.cpmActivity });
            dispatch('showSuccessNotification', 'CpmActivity has been updated.');
         },
        updateCpmActivityFailure: ({commit, dispatch}, body) => 
         {  commit({ type: types.UPDATE_CPMACTIVITY_FAILURE,errors: body   });
            if(body.error) { dispatch('showErrorNotification', body.error);   }
        },
        deleteCpmActivityRequest: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  Vue.http.post(api.deleteCpmActivity, formData)
                    .then(response => { dispatch('deleteCpmActivitySuccess', response.body);  resolve(response);  })
                    .catch(response => { dispatch('deleteCpmActivityFailure', response.body);  reject(response);  });
            })
        },
        deleteCpmActivitySuccess: ({commit, dispatch}, body) => 
        {   commit({  type: types.DELETE_CPMACTIVITY_SUCCESS, cpmActivity: body.cpmActivity });
            dispatch('showSuccessNotification', 'Cpm1Activity has been deleted.');
        },
        deleteCpmActivityFailure: ({commit, dispatch}, body) => 
        {
            commit({   type: types.DELETE_CPMACTIVITY_FAILURE, errors: body  });
            if(body.error) { dispatch('showErrorNotification', body.error); }
        },
        addCpmActivityRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => {
                Vue.http.post(api.addCpmActivity, formData)
                    .then(response => {
                        dispatch('addCpmActivitySuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/cpm-activity.js-addCpmActivityRequest response.body=', response.body);
                        dispatch('addCpmActivityFailure', response.body);
                        reject(response);
                    });
            })
        },
        addCpmActivitySuccess: ({commit, dispatch}, body) => 
        {   commit({  type: types.ADD_CPMACTIVITY_SUCCESS, cpmActivity: body.cpmActivity  });
            dispatch('showSuccessNotification', 'CpmActivity has been added.');
        },
        addCpmActivityFailure: ({commit, dispatch}, body) => 
        {   commit({   type: types.ADD_CPMACTIVITY_FAILURE,  errors: body  });
            console.error('/store/cpm-activity.js-addCpmActivityFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error); }
        },
        setCpmActivityNodes: ({commit, dispatch}) => 
        {   console.log('/store/cpm-activity.js-setCpmActivityNodes');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentCpmActivityNodes)
                    .then(response => 
                        { console.log('/store/cpm-activity.js-setCpmActivityNodes gethttp success response.body=', response.body);
                        commit({ type: types.SET_CPMACTIVITY_NODES,cpmActivityNodes: response.body.cpmActivityNodes });
                        resolve(response);
                    })
                    .catch(error => { console.error('/store/cpm-activity.js-setCpmActivityNodes error =', error);  reject(error);  });
            });
        },
        unsetCpmActivityNodes: ({commit}) => {  commit({  type: types.UNSET_CPMACTIVITY_NODES     });      },
        setCpmActivities: ({commit, dispatch}) => 
        {   console.log('/store/cpm-activity.js-setCpmActivities');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentCpmActivities)
                    .then(response => {
                        console.log('/store/cpm-activity.js-setCpmActivities gethttp success response.body=', response.body);
                        commit({ type: types.SET_CPMACTIVITIES,  cpmActivities: response.body.cpmActivities  });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/store/cpm-activity.js-setCpmActivities error =', error);
                        reject(error);
                    });
            });
        },
        unsetCpmActivities: ({commit}) => { commit({  type: types.UNSET_CPMACTIVITIES });   },
        setCpmActivityShowModal:({commit}, data) => { commit({  type: types.SET_CPMACTIVITY_SHOW_MODAL, data: data });  },
        getCpmActivityOptions: ({dispatch}, service_id) => 
        {   return new Promise((resolve, reject) =>
             {  console.log('/store/cpm-activity.js-getCpmActivityOptions');
                Vue.http.get(api.cpmActivityOptions,  {params: {service_id: service_id}})
                    .then(response => { resolve(response);  })
                    .catch(response => { reject(response);  });
            })
        },
        getCpmActivityUserOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/cpm-activity.js-getCpmActivityUserOptions');
                Vue.http.get(api.cpmActivityUserOptions)
                    .then(response => { resolve(response);  })
                    .catch(response => {  reject(response); });
            })
        },
        getCpmActivityGroupOptions: ({dispatch}) => 
        {  return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/cpm-activity.js-getCpmActivityGroupOptions');
                Vue.http.get(api.cpmActivityGroupOptions)
                    .then(response => { resolve(response);  })
                    .catch(response => {reject(response); });
            })
        },
        updateAssociatedUsersRequest: ({dispatch}, payload) => 
        {   return new Promise((resolve, reject) => {
                Vue.http.post(api.updateAssociatedUsers, payload)
                    .then(response => {  dispatch('updateAssociatedUsersSuccess', response.body);  resolve(response);  })
                    .catch(response => 
                        {   console.error('/store/cpm-activity.js-updateAssociatedUsersRequest response.body=', response.body);
                            dispatch('updateAssociatedUsersFailure', response.body);    reject(response);
                        });
                     })
        },
        updateAssociatedUsersSuccess: ({commit, dispatch}, body) => 
        {   commit({  type: types.UPDATE_CPMACTIVITY_USERS_SUCCESS,
                cpmActivityUsers: body.cpmActivityUsers    });
            dispatch('showSuccessNotification', 'The associated users has been updated.');
        },
        updateAssociatedUsersFailure: ({commit, dispatch}, body) =>
         {
            commit({   type: types.UPDATE_CPMACTIVITY_USERS_FAILURE,  errors: body
            });
            console.error('/store/cpm-activity.js-updateAssociatedUsersFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
        getCpmActivityAssociatedUsers: ({dispatch}, activityId) => 
        {    return new Promise((resolve, reject) => {
                console.log('/store/cpm-activity.js-getCpmActivityAssociatedUsers');
                Vue.http.get(api.cpmActivityAssociatedUsers, {params: {activityId: activityId}})
                    .then(response => {  resolve(response);   })
                    .catch(response => {  reject(response);   });
            })
        },
        getCpmActivityAssociatedManagers: ({dispatch}, activityId) => 
        {  return new Promise((resolve, reject) => 
            {   console.log('/store/cpm-activity.js-getCpmActivityAssociatedManagers');
                Vue.http.get(api.cpmActivityAssociatedManagers, {params: {activityId: activityId}})
                    .then(response => { resolve(response);   })
                    .catch(response => {  reject(response); });
            })
        },
    }
}
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{
    state: { stateNodesnew2: null,  showModal: false,  testData: null,  modalForm: {  state: null, }, },
    getters: {  allStateNodesnew2: state => state.stateNodesnew2   },
    mutations: 
     {  //--------for updating
        [types.UPDATE_TEST_SUCCESS] (state, payload) {console.log('new2.js-types.UPDATE_TEST_SUCCESS payload=', payload); },
        [types.UPDATE_TEST_FAILURE] (state, payload) {console.log('new2.js-types.UPDATE_TEST_FAILURE payload=', payload);  },

        //---------------------for deleting data in table---------------
        [types.DELETE_TEST_SUCCESS] (state, payload) {console.log('new2.js-types.DELETE_TEST_SUCCESS payload=', payload);  },
        [types.DELETE_TEST_FAILURE] (state, payload) {console.log('new2.js-types.DELETE_TEST_FAILURE payload=', payload);  },
        //-----------------for adding data in table-------------------------------
        [types.ADD_STATE_SUCCESS_NEW2] (state, payload) {console.log('new2.js-types.ADD_STATE_SUCCESS_NEW2 payload=', payload); },
        [types.ADD_STATE_FAILURE_NEW2] (state, payload) {console.log('new2.js-types.ADD_STATE_FAILURE_NEW2 payload=', payload); },
        //----------------fow first time loading of data in table------------------
        [types.SET_STATE_NODES_NEW2] (state, payload) //-----this is for refresh
        {  console.log('new2.js-types.SET_STATE_NODES_NEW2 payload=', payload.stateNodesnew2);
           console.log('new2.js-types.SET_STATE_NODES_NEW2 state=', state);
           state.stateNodesnew2 = payload.stateNodesnew2;
        },
        //-----------------for updating current table after add/update/delete------and showing back with new data-----
        [types.SET_STATE_SHOW_MODAL_NEW2] (state, payload) //--------update the data of table--with new modifications
        {   console.log('new2.js-types.SET_STATE_SHOW_MODAL_NEW2 payload=', payload.data);
            console.log('new2.js-types.SET_STATE_SHOW_MODAL_NEW2 state=', state);
            state.showModal = payload.data.isShow;  state.testData = payload.data.data;
        }, 
     },
     actions: 
     {  setStateNodesnew2: ({commit, dispatch}) => //---------for displaying all the records
        {  return new Promise((resolve, reject) => 
            {  Vue.http.get(api.currentStateNodesnew2)
                    .then(response => {   console.log('new2.js-setStateNodesnew2 success response.body=', response.body);                      
                                 commit({type: types.SET_STATE_NODES_NEW2, stateNodesnew2: response.body.gett1 });  resolve(response);
                      }) 
                    .catch(error => {  console.error('new2.js-setStateNodes error =', error);  reject(error); });
            });
        },
        //-------------------------------add test---------------------
        addTestRequest: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  console.log('new2.js-addTestRequest before api.addTest--input formData=', formData);
                Vue.http.post(api.addTest, formData)
                    .then(response => { dispatch('addTestSuccess', response.body);  resolve();   })
                    .catch(response => { dispatch('addTestFailure', response.body); reject();   });
                
            })
        },
        addTestSuccess: ({commit, dispatch}, body) => 
        {   console.log('new2.js-addTestSuccess body=', body);
            commit({   type: types.ADD_STATE_SUCCESS_NEW2, state: body.state   });
            dispatch('showSuccessNotification', 'test has been added.');   
            dispatch('setStateNodesnew2');
        },
        addTestFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.ADD_STATE_FAILURE_NEW2, errors: body  });
            console.error('new2.js-addTestFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
        //---------------------this function updates the data of the table----or take current data and feed to edit/add/delete-----
        setTestShowModal: ({commit}, data) => 
        {   commit({ type: types.SET_STATE_SHOW_MODAL_NEW2, data: data  }); //------update the data of table--with new modifications
            console.log('new2.js-setTestShowModal data=', data); //---------------now the data is updated for table show
            //--------------------------this will update the value in mutation---and feed to the popup--blank form--ie null value
        },
        //-----------------------------------delete below--------------
        deleteTestRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {    Vue.http.post(api.deleteTest, formData)
                    .then(response => {  dispatch('deleteTestSuccess', response.body); resolve(); })
                    .catch(response => { dispatch('deleteTestFailure', response.body);  reject(); });
            })
        },
        deleteTestSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.DELETE_TEST_SUCCESS, state: body.state });
            dispatch('showSuccessNotification', 'Test has been deleted.');
            dispatch('setStateNodesnew2');  //----get back new result with modified values
        },
        deleteTestFailure: ({commit, dispatch}, body) => 
        {   commit({   type: types.DELETE_TEST_FAILURE, errors: body  });
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
        //------------------------------delete finish-------edit start---
        updateTestRequest: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {   console.log('new2.js-updateStateRequest formData=',formData);
                Vue.http.post(api.updateTest, formData)
                    .then(response => {  dispatch('updateTestSuccess', response.body);  resolve(); })
                    .catch(response => { dispatch('updateTestFailure', response.body);  reject();  });
            })
        },
        updateTestSuccess: ({commit, dispatch}, body) => 
        {   commit({   type: types.UPDATE_TEST_SUCCESS,  state: body.state  });
            dispatch('showSuccessNotification', 'Test has been updated- new2.');
            dispatch('setStateNodesnew2'); //----get back new result with modified values
        },
        updateTestFailure: ({commit, dispatch}, body) => 
        {    commit({  type: types.UPDATE_TEST_FAILURE,  errors: body    });
            if(body.error) {  dispatch('showErrorNotification', body.error);   }
        },
     }
}
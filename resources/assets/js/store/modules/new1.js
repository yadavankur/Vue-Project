import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

//getter is simply a function in your store that takes a state object and returns a value from it using mapgetter in components. 
//In your components, it can be accessed through this.$store.getters.property as a computed property, not a function. If a getter needs a parameter, it can return a second function which takes a parameter.

export default 
{
    state: { stateNodes1: null,  showModal: false,  stateData: null,  modalForm: {  state: null, }, },
    getters: {  allStateNodes: state => state.stateNodes1   },
    mutations: 
     {
//Direct modification of state in Vuex is done by calling a function called a mutation.
// A mutation is passed the current state and an optional payload. The payload can be any object. 
//Mutations must be synchronous and shouldn’t return a value.
        [types.ADD_STATE_SUCCESS] (state, payload)    {console.log('/resources/assets/js/store/modules/new1.js-types.ADD_STATE_SUCCESS payload=', payload); },
        [types.ADD_STATE_FAILURE] (state, payload)    {console.log('/resources/assets/js/store/modules/new1.js-types.ADD_STATE_FAILURE payload=', payload); },
        [types.UPDATE_STATE_SUCCESS] (state, payload) {console.log('/resources/assets/js/store/modules/new1.js-types.UPDATE_STATE_SUCCESS payload=', payload); },
        [types.UPDATE_STATE_FAILURE] (state, payload) {console.log('/resources/assets/js/store/modules/new1.js-types.UPDATE_STATE_FAILURE payload=', payload);  },
        [types.DELETE_STATE_SUCCESS] (state, payload) {console.log('/resources/assets/js/store/modules/new1.js-types.DELETE_STATE_SUCCESS payload=', payload);  },
        [types.DELETE_STATE_FAILURE] (state, payload) {console.log('/resources/assets/js/store/modules/new1.js-types.DELETE_STATE_FAILURE payload=', payload);  },
        [types.SET_STATE_NODES] (state, payload) 
        {  console.log('/resources/assets/js/store/modules/new1.js-types.SET_STATE_NODES payload=', payload.stateNodes1);
           console.log('/resources/assets/js/store/modules/new1.js-types.SET_STATE_NODES state=', state);
           state.stateNodes1 = payload.stateNodes1;
        },
        [types.UNSET_STATE_NODES] (state, payload) 
        {   console.log('/resources/assets/js/store/modules/new1.js-types.UNSET_STATE_NODES payload=', payload);
            console.log('/resources/assets/js/store/modules/new1.js-types.UNSET_STATE_NODES state=', state);
            state.stateNodes1 = null;
        },
        [types.SET_STATE_SHOW_MODAL] (state, payload) 
        {   console.log('/resources/assets/js/store/modules/new1.js-types.SET_STATE_SHOW_MODAL payload=', payload.data);
            console.log('/resources/assets/js/store/modules/new1.js-types.SET_STATE_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.stateData = payload.data.data;
        },
     },
    //asynchronous actions that modify the state. Vuex handles this with actions. They are defined 
    //on your state object as well, and are passed the entire state context, which allows them to access getters and commit mutations. 
    //Actions are used in components directly with this.$store.dispatch(‘actionName’, payload).then(response => {}).

//To modify state within an action, use context.commit(‘mutationName’, payload).
// Multiple mutations are allowed inside an action.
    actions: 
    {   updateStateRequest: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {   console.log('/resources/assets/js/store/modules/new1.js-updateStateRequest formData=',formData);
                Vue.http.post(api.updateState, formData)
                    .then(response => {  dispatch('updateStateSuccess', response.body);  resolve(); })
                    .catch(response => { dispatch('updateStateFailure', response.body);  reject();  });
            })
        },
        updateStateSuccess: ({commit, dispatch}, body) => 
        {   commit({   type: types.UPDATE_STATE_SUCCESS,  state: body.state  });
            dispatch('showSuccessNotification', 'State has been updated- new1.');
            dispatch('setStateNodes');
        },
        updateStateFailure: ({commit, dispatch}, body) => 
        {    commit({  type: types.UPDATE_STATE_FAILURE,  errors: body    });
            if(body.error) {  dispatch('showErrorNotification', body.error);   }
        },
        deleteStateRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {    Vue.http.post(api.deleteState, formData)
                    .then(response => {  dispatch('deleteStateSuccess', response.body); resolve(); })
                    .catch(response => { dispatch('deleteStateFailure', response.body);  reject(); });
            })
        },
        deleteStateSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.DELETE_STATE_SUCCESS, state: body.state });
            dispatch('showSuccessNotification', 'State has been deleted.');
            dispatch('setStateNodes');
        },
        deleteStateFailure: ({commit, dispatch}, body) => 
        {   commit({   type: types.DELETE_STATE_FAILURE, errors: body  });
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
        addStateRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {       Vue.http.post(api.addState, formData)
                    .then(response => { dispatch('addStateSuccess', response.body);  resolve(); })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/new1.js-addStateRequest response.body=', response.body);
                        dispatch('addStateFailure', response.body);
                        reject();
                    });
            })
        },
        addStateSuccess: ({commit, dispatch}, body) => {
            commit({   type: types.ADD_STATE_SUCCESS, state: body.state   });
            dispatch('showSuccessNotification', 'State has been added.');
            dispatch('setStateNodes');
        },
        addStateFailure: ({commit, dispatch}, body) => {
            commit({  type: types.ADD_STATE_FAILURE, errors: body  });
            console.error('/resources/assets/js/store/modules/new1.js-addStateFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
        setStateNodes: ({commit, dispatch}) =>   //--------------show table of states
        {   console.log('/resources/assets/js/store/modules/new1.js-setStateNodes api'); //----show states
            return new Promise((resolve, reject) => 
            {  Vue.http.get(api.currentStateNodes).then(response =>  
                  {   //states show api fired 
                      console.log('/resources/assets/js/store/modules/new1.js-setStateNodes gethttp success response.body=', response.body);
                      //console.log('/resources/assets/js/store/modules/new1.js-setStateNodes gethttp success response.body=', response.body.stateNodes);
                      commit({  type: types.SET_STATE_NODES,  stateNodes1: response.body.stateNodes });  resolve(response);
                    })
                 .catch(error => {  console.error('store/modules/new1.js-setStateNodes error =', error);  reject(error); });
            });
        },
        unsetStateNodes: ({commit}) => { commit({  type: types.UNSET_STATE_NODES  }); },
        setStateShowModal:({commit}, data) => {commit({ type: types.SET_STATE_SHOW_MODAL, data: data  }); },
        getStateOptions: ({dispatch}) => 
        {  return new Promise((resolve, reject) => 
            {   console.log('/resources/assets/js/store/modules/new1.js-getStateOptions');
                Vue.http.get(api.stateOptions)
                    .then(response => {  resolve(response);   })
                    .catch(response => { reject(response);    });
            })
        },
    }
}
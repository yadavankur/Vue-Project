import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{
    state: { userNodes: null, userTable:null, showModal: false, userData: null,  modalForm: { user: null, },    },
    getters: {  allUserNodes: state => state.userNodes  },
    mutations: {
        [types.ADD_USER_SUCCESS] (state, payload) {
            console.log('/store/modules/user.js-types.ADD_USER_SUCCESS payload=', payload);
        },
        [types.ADD_USER_FAILURE] (state, payload) {
            console.log('/store/modules/user.js-types.ADD_USER_FAILURE payload=', payload);
        },
        [types.UPDATE_USER_SUCCESS] (state, payload) {
            console.log('/store/modules/user.js-types.UPDATE_USER_SUCCESS payload=', payload);
        },
        [types.UPDATE_USER_FAILURE] (state, payload) {
            console.log('/store/modules/user.js-types.UPDATE_USER_FAILURE payload=', payload);
        },
        [types.DELETE_USER_SUCCESS] (state, payload) {
            console.log('/store/modules/user.js-types.DELETE_USER_SUCCESS payload=', payload);
        },
        [types.DELETE_USER_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/user.js-types.DELETE_USER_FAILURE payload=', payload);
        },
        [types.SET_USER_NODES] (state, payload) {
            console.log('/store/modules/user.js-types.SET_USER_NODES payload=', payload.userNodes);
            console.log('/store/modules/user.js-types.SET_USER_NODES state=', state);
            state.userNodes = payload.userNodes;
        },
        [types.UNSET_USER_NODES] (state, payload) {
            console.log('/store/modules/user.js-types.UNSET_USER_NODES payload=', payload);
            console.log('/store/modules/user.js-types.UNSET_USER_NODES state=', state);
            state.userNodes = null;
        },
        [types.SET_USER_SHOW_MODAL] (state, payload) {
            console.log('/store/modules/user.js-types.SET_USER_SHOW_MODAL payload=', payload.data);
            console.log('/store/modules/user.js-types.SET_USER_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.userData = payload.data.data;
        },
        [types.GET_ALL_USER_TABLE] (state, payload) //-----this is for refresh
        {  console.log('types.GET_ALL_USER_TABLE payload=',  state.userTable);
           console.log('types.GET_ALL_USER_TABLE state=', state);
           state.userTable = payload.userTable;
        },
    },
    actions: 
    {
        updateUserRequest: ({dispatch}, formData) => 
        { return new Promise((resolve, reject) => 
            {   console.log('/js/store/modules/user.js-updateUserRequest formData=',formData);
                Vue.http.post(api.updateUser, formData)
                    .then(response => {  dispatch('updateUserSuccess', response.body);   resolve();  })
                    .catch(response => { dispatch('updateUserFailure', response.body);   reject(); });
            })
        },
        updateUserSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.UPDATE_USER_SUCCESS,  user: body.user });
            dispatch('showSuccessNotification', 'User has been updated.');
            // dispatch('setUserNodes');
        },
        updateUserFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.UPDATE_USER_FAILURE, errors: body  });
            if(body.error) {  dispatch('showErrorNotification', body.error); }
        },
        deleteUserRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {   Vue.http.post(api.deleteUser, formData)
                    .then(response => 
                    {  dispatch('deleteUserSuccess', response.body);
                        resolve();
                    })
                    .catch(response => { dispatch('deleteUserFailure', response.body); reject();  });
            })
        },
        deleteUserSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.DELETE_USER_SUCCESS, user: body.user  });
            dispatch('showSuccessNotification', 'User has been deleted.');
        },
        deleteUserFailure: ({commit, dispatch}, body) => 
        {  commit({  type: types.DELETE_USER_FAILURE, errors: body  });
           if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
        addUserRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {  Vue.http.post(api.addUser, formData)
                    .then(response => { dispatch('addUserSuccess', response.body);  resolve(); })
                    .catch(response => 
                        {  console.error('/store/modules/user.js-addUserRequest response.body=', response.body);
                           dispatch('addUserFailure', response.body);
                           reject();
                        });
            })
        },
        addUserSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.ADD_USER_SUCCESS, user: body.user  });
            dispatch('showSuccessNotification', 'User has been added.');
            //dispatch('setUserNodes');
        },
        addUserFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.ADD_USER_FAILURE, errors: body  });
            console.error('/store/modules/user.js-addUserFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
        setUserNodes: ({commit, dispatch}) => 
        {   console.log('/js/store/modules/user.js-setUserNodes');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentUserNodes)
                    .then(response => 
                    {   console.log('/store/modules/user.js-setUserNodes gethttp success response.body=', response.body);
                        commit({  type: types.SET_USER_NODES, userNodes: response.body.userNodes });
                        resolve(response);
                    })
                    .catch(error => 
                        {  console.error('/store/modules/user.js-setUserNodes error =', error);
                           reject(error);
                        });
            });
        },
        unsetUserNodes: ({commit}) => {  commit({    type: types.UNSET_USER_NODES    });   },
        setUserShowModal:({commit}, data) => {  commit({   type: types.SET_USER_SHOW_MODAL,   data: data  });    },
        getRoleOptions: ({dispatch}, selectedRole) => 
        {  return new Promise((resolve, reject) => 
            {   console.log('/store/modules/user.js-getRoleOptions selectedRole=',selectedRole);
                Vue.http.get(api.roleOptions + '?selectedRole=' + selectedRole)
                    .then(response => {  resolve(response); })
                    .catch(response => {  reject(response);  });
            })
        },
        getGroupOptions: ({dispatch}, selectedRole) => 
        {  return new Promise((resolve, reject) => 
            {   console.log('/store/modules/user.js-getGroupOptions selectedRole=',selectedRole);
                Vue.http.get(api.groupOptions + '?selectedRole=' + selectedRole)
                    .then(response => { resolve(response); })
                    .catch(response => { reject(response); });
            })
        },
        getCascadeRolesGroups: ({dispatch}, selectedRole) => 
        {  return new Promise((resolve, reject) => 
            {   console.log('/store/modules/user.js-getCascadeRolesGroups');
                Vue.http.get(api.cascadeRolesGroups, {params: {selectedRole: selectedRole}})
                    .then(response => { resolve(response);  })
                    .catch(response => { reject(response); });
            })
        },


        getuserlist: ({commit, dispatch}, body) => 
         {  return new Promise((resolve, reject) => 
           {  Vue.http.get(api.userlists)
                   .then(response => {   console.log('/store/userlist response.body=', response.body); 
                                         commit({type: types.GET_ALL_USER_TABLE, userTable: response.body});                       
                                          resolve(response);
                                     }) 
                   .catch(error => {  console.error('/store/userlist response.body=', error);  reject(error); });
           });
         },

    }
}
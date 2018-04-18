import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{
    state: 
    {    authenticated: false,    aclTabs: [],    aclMenus: [],    isAdmin: 0,    name: null,
               email: null,    role: null,    id: '',   
     },
    mutations: 
    {  [types.SET_AUTH_USER] (state, payload) 
        {   console.log('/store/auth-user.js-types.SET_AUTH_USER payload=', payload.user);
            let user = payload.user;
            console.log('/store/auth-user.js-types.SET_AUTH_USER user=', user);
            state.authenticated = true;
            state.name = user.name;    state.email = user.email;     state.role = user.role;
            state.id = user.id;        state.isAdmin = user.isAdmin;
            let tabsArray = [];
            for(let tab in user.aclTabs) {  tabsArray.push(user.aclTabs[tab]); }
            state.aclTabs = tabsArray;
            let menusArray = [];
            for(let menu in user.aclMenus) {  menusArray.push(user.aclMenus[menu]);  }
            state.aclMenus = menusArray;
        },
       [types.UNSET_AUTH_USER] (state, payload) 
        {   state.authenticated = false;   state.name = null;   state.email = null;
            state.role = null;       state.id = null;        //state.isAdmin = 0;
        }
    },
    actions: 
    {  setAuthUser: ({commit, dispatch}) => 
        {   console.log('/store/auth-user.js- setAuthUser');
            Vue.http.get(api.currentUser)
                .then(response => 
                    {  console.log('/store/auth-user.js-setAuthUser response=', response);
                       commit({   type: types.SET_AUTH_USER,  user: response.body  })
                    })
                .catch(error => { dispatch('logoutRequest'); })
        },
        unsetAuthUser: ({commit}) => {  commit({  type: types.UNSET_AUTH_USER  }); },
        runPhpCode: ({dispatch}, payload) => 
        {
            console.log('/store/auth-user.js-runPhpCode');
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.runPhpCode, payload)
                    .then(response => {
                        console.log('/store/auth-user.js-runPhpCode get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/store/auth-user.js-runPhpCode error =', error);
                        reject(error);
                    });
            });
        },
    }
}
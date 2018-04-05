import Vue from 'vue';
import * as api from './../../config';
import jwtToken from './../../helpers/jwt-token';
import * as types from './../../mutation-types';

export default {
    state: {
        errors: {
            email: null,
            password: null
        },
        aclTabs: [],
        aclMenus: [],
        isAdmin: 0,
    },
    mutations: {
        [types.LOGIN_SUCCESS] (state, payload) {
            console.log('types.LOGIN_SUCCESS payload=', payload);
            state.errors.email = null;
            state.errors.passwor = null;
            let tabsArray = [];
            for(let tab in payload.data.aclTabs) {
                tabsArray.push(payload.data.aclTabs[tab]);
            }
            state.aclTabs = tabsArray;
            let menusArray = [];
            for(let menu in payload.data.aclMenus) {
                menusArray.push(payload.data.aclMenus[menu]);
            }
            state.aclMenus = menusArray;
            state.isAdmin = payload.data.isAdmin;
        },
        [types.LOGIN_FAILURE] (state, payload) {
            state.errors.email = payload.errors.email ? payload.errors.email[0] : null;
            state.errors.password = payload.errors.password ? payload.errors.password[0] : null;
        },
        [types.CLEAR_LOGIN_ERRORS] (state, payload) {
            state.errors.email = null;
            state.errors.password = null;
        }
    },
    actions: {
        loginRequest: ({dispatch}, formData) => {
            console.log('loginRequest');
            return new Promise((resolve, reject) => {
                Vue.http.post(api.login, formData)
                    .then(response => {
                        console.log('loginRequest  response.body=', response.body);
                        dispatch('loginSuccess', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.log('loginRequest error=', response);
                        dispatch('loginFailure', response.body);
                        reject(response);
                    });
            });
        },
        loginSuccess: ({commit, dispatch}, jwtTokenObj) => {
            console.log('loginSuccess jwtTokenObj=', jwtTokenObj);
            jwtToken.setToken(jwtTokenObj.token);
            commit({
                type: types.LOGIN_SUCCESS,
                data: {aclTabs: jwtTokenObj.aclTabs, aclMenus: jwtTokenObj.aclMenus}
            });

            dispatch('setAuthUser');
        },
        loginFailure: ({commit, dispatch}, body) => {
            console.log('loginFailure body=', body);
            commit({
                type: types.LOGIN_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        clearLoginErrors: ({commit}) => {
            commit({
                type: types.CLEAR_LOGIN_ERRORS
            });
        },
        logoutRequest: ({dispatch}) => {
            jwtToken.removeToken();

            return new Promise((resolve, reject) => {
                dispatch('unsetAuthUser');
                dispatch('unsetRoles');
                dispatch('unsetMenus');
                dispatch('unsetTabs');
                resolve();
            });
        },
        retrievePasswordRequest: ({dispatch}, formData) => {
            console.log('retrievePasswordRequest');
            return new Promise((resolve, reject) => {
                Vue.http.post(api.retrievePassword, formData)
                    .then(response => {
                        console.log('retrievePasswordRequest  response.body=', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.log('retrievePasswordRequest error=', response);
                        reject(response);
                    });
            });
        },
        resetPasswordRequest: ({dispatch}, formData) => {
            console.log('resetPasswordRequest');
            return new Promise((resolve, reject) => {
                Vue.http.post(api.resetPassword, formData)
                    .then(response => {
                        console.log('resetPasswordRequest  response.body=', response.body);
                        resolve(response);
                    })
                    .catch(response => {
                        console.log('resetPasswordRequest error=', response);
                        reject(response);
                    });
            });
        },
    }
}
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{  state: {  menus: [],  menuNodes: null,  showModal: false,  menuData: null,  modalForm: {  menu: null, }, },
    getters: {  allMenus: state => state.menus,  allMenuNodes: state => state.menuNodes  },
    mutations: {
        [types.ADD_MENU_SUCCESS] (state, payload) {  console.log('/store/modules/menu.js-types.ADD_MENU_SUCCESS payload=', payload);
        },
        [types.ADD_MENU_FAILURE] (state, payload) { console.log('/store/modules/menu.js-types.ADD_MENU_FAILURE payload=', payload);
        },
        [types.UPDATE_MENU_SUCCESS] (state, payload) { console.log('/store/modules/menu.js-types.UPDATE_MENU_SUCCESS payload=', payload);
        },
        [types.UPDATE_MENU_FAILURE] (state, payload) {console.log('/store/modules/menu.js-types.UPDATE_MENU_FAILURE payload=', payload);
        },
        [types.DELETE_MENU_SUCCESS] (state, payload) {  console.log('/store/modules/menu.js-types.DELETE_MENU_SUCCESS payload=', payload);
        },
        [types.DELETE_MENU_FAILURE] (state, payload) { console.log('/store/modules/menu.js-types.DELETE_MENU_FAILURE payload=', payload);
        },
        [types.SET_MENUS] (state, payload) {
            console.log('/store/modules/menu.js-types.SET_MENUS payload=', payload.menus[0].children);
            console.log('/store/modules/menu.js-types.SET_MENUS state=', state);
            state.menus = payload.menus[0].children;
        },
        [types.UNSET_MENUS] (state, payload) {
            console.log('/store/modules/menu.js-types.UNSET_MENUS payload=', payload);
            console.log('/store/modules/menu.js-types.UNSET_MENUS state=', state);            
            state.menus = [];
        },
        [types.SET_MENU_NODES] (state, payload) {
            console.log('/store/modules/menu.js-types.SET_MENU_NODES payload=', payload.menuNodes);
            console.log('/store/modules/menu.js-types.SET_MENU_NODES state=', state);
            state.menuNodes = payload.menuNodes;
        },
        [types.UNSET_MENU_NODES] (state, payload) {
            console.log('/store/modules/menu.js-types.UNSET_MENU_NODES payload=', payload);
            console.log('/store/modules/menu.js-types.UNSET_MENU_NODES state=', state);
            state.menuNodes = null;
        },
        [types.SET_MENU_SHOW_MODAL] (state, payload) 
        {   console.log('/store/modules/menu.js-types.SET_MENU_SHOW_MODAL payload=', payload.data);
            console.log('/store/modules/menu.js-types.SET_MENU_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.menuData = payload.data.data;
        },
    },


    actions: 
    {
        updateMenuRequest: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  console.log('/store/modules/menu.js-updateMenuRequest formData=',formData);
                Vue.http.post(api.updateMenu, formData)
                    .then(response => { dispatch('/store/modules/menu.js-updateMenuSuccess', response.body); resolve();  })
                    .catch(response => { dispatch('/store/modules/menu.js-updateMenuFailure', response.body); reject(); });
            })
        },
        updateMenuSuccess: ({commit, dispatch}, body) => 
        {   commit({  type: types.UPDATE_MENU_SUCCESS,  menu: body.menu  });
            dispatch('/store/modules/menu.js-showSuccessNotification', 'Menu has been updated.');
            dispatch('/store/modules/menu.js-setMenus');
            // dispatch('setMenuNodes');
        },
        updateMenuFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.UPDATE_MENU_FAILURE, errors: body});
            if(body.error) { dispatch('/store/modules/menu.js-showErrorNotification', body.error); }
        },
        deleteMenuRequest: ({dispatch}, formData) => 
        { return new Promise((resolve, reject) => 
            {   Vue.http.post(api.deleteMenu, formData)
                    .then(response => { dispatch('/store/modules/menu.js-deleteMenuSuccess', response.body);   resolve(); })
                    .catch(response => { dispatch('/store/modules/menu.js-deleteMenuFailure', response.body);  reject();  });
            })
        },
        deleteMenuSuccess: ({commit, dispatch}, body) => 
        {   commit({  type: types.DELETE_MENU_SUCCESS,  menu: body.menu  });
            dispatch('/store/modules/menu.js-showSuccessNotification', 'Menu has been deleted.');
            dispatch('/store/modules/menu.js-setMenus');
            // dispatch('setMenuNodes');
        },
        deleteMenuFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.DELETE_MENU_FAILURE, errors: body  });
            if(body.error) { dispatch('/store/modules/menu.js-showErrorNotification', body.error);}
        },
        addMenuRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {    Vue.http.post(api.addMenu, formData)
                    .then(response => { dispatch('/store/modules/menu.js-addMenuSuccess', response.body);  resolve(); })
                    .catch(response => {  console.error('/store/modules/menu.js-addMenuRequest response.body=', response.body);
                                            dispatch('/store/modules/menu.js-addMenuFailure', response.body);
                                         reject();
                                        });
            })
        },
        addMenuSuccess: ({commit, dispatch}, body) => 
        { commit({  type: types.ADD_MENU_SUCCESS, menu: body.menu  });
            dispatch('/store/modules/menu.js-showSuccessNotification', 'Menu has been added.');
            dispatch('/store/modules/menu.js-setMenus');
            // dispatch('setMenuNodes');
        },
        addMenuFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.ADD_MENU_FAILURE, errors: body  });
            console.error('/store/modules/menu.js-addMenuFailure body.error=', body.error);
            if(body.error) { dispatch('/store/modules/menu.js-showErrorNotification', body.error);  }
        },
        setMenuNodes: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/menu.js-setMenuNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentMenuNodes)
                    .then(response => {
                        console.log('/store/modules/menu.js-setMenuNodes gethttp success response.body=', response.body);
                        commit({  type: types.SET_MENU_NODES,   menuNodes: response.body.menuNodes  });
                        resolve(response);
                    })
                    .catch(error => {   console.error('/store/modules/menu.js-setMenuNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetMenuNodes: ({commit}) => { commit({  type: types.UNSET_MENU_NODES  }); },
        setMenus: ({commit, dispatch}) => 
            {   console.log('/store/modules/menu.js-setMenus');
                 return new Promise((resolve, reject) => 
                    {   Vue.http.get(api.currentMenus)
                         .then(response => 
                        {  console.log('/store/modules/menu.js-setMenus gethttp success response.body=', response.body);
                           commit({  type: types.SET_MENUS, menus: response.body.menus  });
                           resolve(response);
                        })
                        .catch(error => { console.error('/store/modules/menu.js-setMenus error =', error);  reject(error); });
                    });
            },
        unsetMenus: ({commit}) => {  commit({   type: types.UNSET_MENUS   });   },
        setMenuShowModal:({commit}, data) => { commit({ type: types.SET_MENU_SHOW_MODAL, data: data   }); },
        getBadgeCount({commit}) 
        {  // console.log('/store/modules/menu.js-getBadgeCount'); //----manoj4
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.getBadgeCount , { showLoadingBar: false })
                    .then(response => { 
                        //console.log('/store/modules/menu.js-getBadgeCount get http success response.body=', response.body); --manoj2
                        resolve(response);
                    })
                    .catch(error => { console.error('/store/modules/menu.js-getBadgeCount error =', error);  reject(error); });
            });
        }
    }
}
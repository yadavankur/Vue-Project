import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        pages: [],
        pageNodes: null,
        showModal: false,
        pageData: null,
        modalForm: {
            page: null,
        },
    },
    getters: {
        allPages: state => state.pages,
        allPageNodes: state => state.pageNodes
    },
    mutations: {
        [types.ADD_PAGE_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.ADD_PAGE_SUCCESS payload=', payload);
        },
        [types.ADD_PAGE_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.ADD_PAGE_FAILURE payload=', payload);
        },
        [types.UPDATE_PAGE_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.UPDATE_PAGE_SUCCESS payload=', payload);
        },
        [types.UPDATE_PAGE_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.UPDATE_PAGE_FAILURE payload=', payload);
        },
        [types.DELETE_PAGE_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.DELETE_PAGE_SUCCESS payload=', payload);
        },
        [types.DELETE_PAGE_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.DELETE_PAGE_FAILURE payload=', payload);
        },
        [types.SET_PAGES] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.SET_PAGES payload=', payload.pages[0].children);
            console.log('/resources/assets/js/store/modules/page.js-types.SET_PAGES state=', state);
            state.pages = payload.pages[0].children;
        },
        [types.UNSET_PAGES] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.UNSET_PAGES payload=', payload);
            console.log('/resources/assets/js/store/modules/page.js-types.UNSET_PAGES state=', state);
            state.pages = [];
        },
        [types.SET_PAGE_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.SET_PAGE_NODES payload=', payload.pageNodes);
            console.log('/resources/assets/js/store/modules/page.js-types.SET_PAGE_NODES state=', state);
            state.pageNodes = payload.pageNodes;
        },
        [types.UNSET_PAGE_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.UNSET_PAGE_NODES payload=', payload);
            console.log('/resources/assets/js/store/modules/page.js-types.UNSET_PAGE_NODES state=', state);
            state.pageNodes = null;
        },
        [types.SET_PAGE_SHOW_MODAL] (state, payload) {
            console.log('/resources/assets/js/store/modules/page.js-types.SET_PAGE_SHOW_MODAL payload=', payload.data);
            console.log('/resources/assets/js/store/modules/page.js-types.SET_PAGE_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.pageData = payload.data.data;
        },
    },
    actions: {
        updatePageRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/page.js-updatePageRequest formData=',formData);
                Vue.http.post(api.updatePage, formData)
                    .then(response => {
                        dispatch('updatePageSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        dispatch('updatePageFailure', response.body);
                        reject();
                    });
            })
        },
        updatePageSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_PAGE_SUCCESS,
                page: body.page
            });
            dispatch('showSuccessNotification', 'Page has been updated.');
        },
        updatePageFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_PAGE_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deletePageRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deletePage, formData)
                    .then(response => {
                        dispatch('deletePageSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        dispatch('deletePageFailure', response.body);
                        reject();
                    });
            })
        },
        deletePageSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_PAGE_SUCCESS,
                page: body.page
            });
            dispatch('showSuccessNotification', 'Page has been deleted.');
        },
        deletePageFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_PAGE_FAILURE,
                errors: body
            });
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addPageRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addPage, formData)
                    .then(response => {
                        dispatch('addPageSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/page.js-addPageRequest response.body=', response.body);
                        dispatch('addPageFailure', response.body);
                        reject();
                    });
            })
        },
        addPageSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_PAGE_SUCCESS,
                page: body.page
            });
            dispatch('showSuccessNotification', 'Page has been added.');
        },
        addPageFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_PAGE_FAILURE,
                errors: body
            });
            console.error('/resources/assets/js/store/modules/page.js-addPageFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setPageNodes: ({commit, dispatch}) => 
        {   console.log('/resources/assets/js/store/modules/page.js-setPageNodes');
            return new Promise((resolve, reject) => 
            {  Vue.http.get(api.currentPageNodes)
                    .then(response => 
                        { console.log('/resources/assets/js/store/modules/page.js-setPageNodes gethttp success response.body=', response.body);
                          commit({ type: types.SET_PAGE_NODES, pageNodes: response.body.pageNodes });
                          resolve(response);
                        })
                    .catch(error => { console.error('/resources/assets/js/store/modules/page.js-setPageNodes error =', error);
                                      reject(error);
                                    });
            });
        },
        unsetPageNodes: ({commit}) => { commit({ type: types.UNSET_PAGE_NODES  });  },
        setPages: ({commit, dispatch}) => 
        {   console.log('/resources/assets/js/store/modules/page.js-setPages');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentPages)
                    .then(response => 
                        {   console.log('/resources/assets/js/store/modules/page.js-setPages gethttp success response.body=', response.body);
                        commit({  type: types.SET_PAGES,pages: response.body.pages });
                        resolve(response);
                        })
                    .catch(error => {  console.error('/resources/assets/js/store/modules/page.js-setPages error =', error);
                                     reject(error);
                                    });
            });
        },
        unsetPages: ({commit}) => { commit({  type: types.UNSET_PAGES  }); },
        setPageShowModal:({commit}, data) => { commit({ type: types.SET_PAGE_SHOW_MODAL, data: data   }); },
        getPageOptions: ({dispatch}) => 
        {   return new Promise((resolve, reject) => 
            {   console.log('/resources/assets/js/store/modules/page.js-getPageOptions');
                Vue.http.get(api.pageOptions)
                    .then(response => { resolve(response); })
                    .catch(response => { reject(response);  });
            })
        },
    }
}
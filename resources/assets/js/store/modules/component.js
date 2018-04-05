
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default {
    state: {
        components: [],
        componentNodes: null,
        showModal: false,
        componentData: null,

    },
    getters: {
        allComponents: state => state.components
    },
    mutations: {
        [types.SET_COMPONENTS] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.SET_COMPONENTS payload=', payload.components);
            console.log('/resources/assets/js/store/modules/component.js-types.SET_COMPONENTS state=', state);
            let componentsArray = [];
            for(let component in payload.components) {
                componentsArray.push(payload.components[component]);
            }
            console.log('/resources/assets/js/store/modules/component.js-types.SET_COMPONENTS componentsArray=', componentsArray);
            state.components = componentsArray;
        },
        [types.UNSET_COMPONENTS] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.UNSET_COMPONENTS payload=', payload);
            console.log('/resources/assets/js/store/modules/component.js-types.UNSET_COMPONENTS state=', state);
            state.components = [];
        },
        [types.ADD_COMPONENT_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.ADD_COMPONENT_SUCCESS payload=', payload);
        },
        [types.ADD_COMPONENT_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.ADD_COMPONENT_FAILURE payload=', payload);
        },
        [types.UPDATE_COMPONENT_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.UPDATE_COMPONENT_SUCCESS payload=', payload);
        },
        [types.UPDATE_COMPONENT_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.UPDATE_COMPONENT_FAILURE payload=', payload);
        },
        [types.DELETE_COMPONENT_SUCCESS] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.DELETE_COMPONENT_SUCCESS payload=', payload);
        },
        [types.DELETE_COMPONENT_FAILURE] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.DELETE_COMPONENT_FAILURE payload=', payload);
        },
        [types.SET_COMPONENT_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.SET_COMPONENT_NODES payload=', payload.componentNodes);
            console.log('/resources/assets/js/store/modules/component.js-types.SET_COMPONENT_NODES state=', state);
            state.componentNodes = payload.componentNodes;
        },
        [types.UNSET_COMPONENT_NODES] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.UNSET_COMPONENT_NODES payload=', payload);
            console.log('/resources/assets/js/store/modules/component.js-types.UNSET_COMPONENT_NODES state=', state);
            state.componentNodes = null;
        },
        [types.SET_COMPONENT_SHOW_MODAL] (state, payload) {
            console.log('/resources/assets/js/store/modules/component.js-types.SET_COMPONENT_SHOW_MODAL payload=', payload.data);
            console.log('/resources/assets/js/store/modules/component.js-types.SET_COMPONENT_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.componentData = payload.data.data;
        },

    },
    actions: {
        setComponents: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/component.js-setComponents');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentComponents)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/component.js-setComponents gethttp success response.body=', response.body);
                        commit({
                            type: types.SET_COMPONENTS,
                            components: response.body.components
                        })
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/component.js-setComponents error =', error);
                        reject(error);
                    });
            });
        },
        unsetComponents: ({commit}) => {
            commit({
                type: types.UNSET_COMPONENTS
            });
        },
        updateComponentRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/component.js-updateComponentRequest formData=',formData);
                Vue.http.post(api.updateComponent, formData)
                    .then(response => {
                        dispatch('updateComponentSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        dispatch('updateComponentFailure', response.body);
                        reject();
                    });
            })
        },
        updateComponentSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_COMPONENT_SUCCESS,
                component: body.component
            });
            dispatch('showSuccessNotification', 'Component has been updated.');
            //dispatch('setComponentNodes');
        },
        updateComponentFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.UPDATE_COMPONENT_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        deleteComponentRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.deleteComponent, formData)
                    .then(response => {
                        dispatch('deleteComponentSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        dispatch('deleteComponentFailure', response.body);
                        reject();
                    });
            })
        },
        deleteComponentSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_COMPONENT_SUCCESS,
                component: body.component
            });
            dispatch('showSuccessNotification', 'Component has been deleted.');
            //dispatch('setComponentNodes');
        },
        deleteComponentFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.DELETE_COMPONENT_FAILURE,
                errors: body
            });

            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        addComponentRequest: ({dispatch}, formData) => {
            return new Promise((resolve, reject) => {
                Vue.http.post(api.addComponent, formData)
                    .then(response => {
                        dispatch('addComponentSuccess', response.body);
                        resolve();
                    })
                    .catch(response => {
                        console.error('/resources/assets/js/store/modules/component.js-addComponentRequest response.body=', response.body);
                        dispatch('addComponentFailure', response.body);
                        reject();
                    });
            })
        },
        addComponentSuccess: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_COMPONENT_SUCCESS,
                component: body.component
            });
            dispatch('showSuccessNotification', 'Component has been added.');
            //dispatch('setComponentNodes');
        },
        addComponentFailure: ({commit, dispatch}, body) => {
            commit({
                type: types.ADD_COMPONENT_FAILURE,
                errors: body
            });
            console.error('/resources/assets/js/store/modules/component.js-addComponentFailure body.error=', body.error);
            if(body.error) {
                dispatch('showErrorNotification', body.error);
            }
        },
        setComponentNodes: ({commit, dispatch}) => {
            console.log('/resources/assets/js/store/modules/component.js-setComponentNodes');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentComponentNodes)
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/component.js-setComponentNodes get http success response.body=', response.body);
                        commit({
                            type: types.SET_COMPONENT_NODES,
                            componentNodes: response.body.componentNodes
                        });
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/component.js-setComponentNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetComponentNodes: ({commit}) => {
            commit({
                type: types.UNSET_COMPONENT_NODES
            });
        },
        setComponentShowModal:({commit}, data) => {
            commit({
                type: types.SET_COMPONENT_SHOW_MODAL,
                data: data
            });
        },
        getComponentOptions: ({dispatch}, selectedPage) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/component.js-getComponentOptions');
                Vue.http.get(api.componentOptions + '?selectedPage=' + selectedPage)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        getAllComponentOptions: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/component.js-getAllComponentOptions');
                Vue.http.get(api.allcomponentOptions)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },
        getCascadeComponents: ({dispatch}) => {
            return new Promise((resolve, reject) => {
                console.log('/resources/assets/js/store/modules/component.js-getCascadeComponents');
                Vue.http.get(api.cascadeComponents)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(response => {
                        reject(response);
                    });
            })
        },

    }
}
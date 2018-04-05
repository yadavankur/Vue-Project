import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{  state: {   locations: [],
                locationNodes: null,
                showModal: false,
                locationData: null,
                modalForm: {  location: null,  },
        },
    getters: {  allLocations: state => state.locations, allLocationNodes: state => state.locationNodes },
    mutations: 
    {   [types.ADD_LOCATION_SUCCESS] (state, payload) { console.log('/store/location.js-types.ADD_LOCATION_SUCCESS payload=', payload); },
        [types.ADD_LOCATION_FAILURE] (state, payload)  { console.log('/store/location.js--types.ADD_LOCATION_FAILURE payload=', payload);},
        [types.UPDATE_LOCATION_SUCCESS] (state, payload) {console.log('/store/location.js--types.UPDATE_LOCATION_SUCCESS payload=', payload); },
        [types.UPDATE_LOCATION_FAILURE] (state, payload) { console.log('/store/location.js--types.UPDATE_LOCATION_FAILURE payload=', payload);},
        [types.DELETE_LOCATION_SUCCESS] (state, payload) {console.log('/store/location.js--types.DELETE_LOCATION_SUCCESS payload=', payload);},
        [types.DELETE_LOCATION_FAILURE] (state, payload) {console.log('/store/location.js--types.DELETE_LOCATION_FAILURE payload=', payload); },
        [types.SET_LOCATIONS] (state, payload) 
        {   console.log('/store/location.js--types.SET_LOCATIONS payload=', payload.locations[0].children);
            console.log('/store/location.js--types.SET_LOCATIONS state=', state);
            state.locations = payload.locations[0].children;
        },
        [types.UNSET_LOCATIONS] (state, payload) 
        {   console.log('/store/location.js--types.UNSET_LOCATIONS payload=', payload);
            console.log('/store/location.js--types.UNSET_LOCATIONS state=', state);
            state.locations = [];
        },
        [types.SET_LOCATION_NODES] (state, payload) 
        {   console.log('/store/location.js--types.SET_LOCATION_NODES payload=', payload.locationNodes);
            console.log('/store/location.js--types.SET_LOCATION_NODES state=', state);
            state.locationNodes = payload.locationNodes;
        },
        [types.UNSET_LOCATION_NODES] (state, payload) 
        {   console.log('/store/location.js--types.UNSET_LOCATION_NODES payload=', payload);
            console.log('/store/location.js--types.UNSET_LOCATION_NODES state=', state);
            state.locationNodes = null;
        },
        [types.SET_LOCATION_SHOW_MODAL] (state, payload) 
        {   console.log('/store/location.js--types.SET_LOCATION_SHOW_MODAL payload=', payload.data);
            console.log('/store/location.js--types.SET_LOCATION_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.locationData = payload.data.data;
        },
    },
    actions: 
    {   updateLocationRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {   console.log('/store/location.js-updateLocationRequest formData=',formData);
                Vue.http.post(api.updateLocation, formData)
                    .then(response => { dispatch('/store/location.js--updateLocationSuccess', response.body);
                                        resolve(response);
                                      })
                    .catch(response => { dispatch('/store/location.js--updateLocationFailure', response.body);
                                         reject(response);
                                       });
            })
        },
        updateLocationSuccess: ({commit, dispatch}, body) => 
        {   commit({  type: types.UPDATE_LOCATION_SUCCESS, location: body.location   });
            dispatch('showSuccessNotification', 'Location has been updated.');
        },
        updateLocationFailure: ({commit, dispatch}, body) => 
        { commit({  type: types.UPDATE_LOCATION_FAILURE,  errors: body });
            if(body.error) {  dispatch('showErrorNotification', body.error); }
        },
        deleteLocationRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {  Vue.http.post(api.deleteLocation, formData)
                    .then(response => { dispatch('deleteLocationSuccess', response.body); resolve(response); })
                    .catch(response => { dispatch('deleteLocationFailure', response.body); reject(response); });
            })
        },
        deleteLocationSuccess: ({commit, dispatch}, body) => 
        {   commit({  type: types.DELETE_LOCATION_SUCCESS, location: body.location });
            dispatch('showSuccessNotification', 'Location has been deleted.');
        },
        deleteLocationFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.DELETE_LOCATION_FAILURE, errors: body });
            if(body.error) { dispatch('showErrorNotification', body.error); }
        },
        addLocationRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {   Vue.http.post(api.addLocation, formData)
                    .then(response => { dispatch('addLocationSuccess', response.body);  resolve(response); })
                    .catch(response => { console.error('/store/location.js-addLocationRequest response.body=', response.body);
                                         dispatch('addLocationFailure', response.body);
                                         reject(response);
                                       });
            })
        },
        addLocationSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.ADD_LOCATION_SUCCESS, location: body.location });
            dispatch('showSuccessNotification', 'Location has been added.');
        },
        addLocationFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.ADD_LOCATION_FAILURE,  errors: body   });
            console.error('/store/location.js--addLocationFailure body.error=', body.error);
            if(body.error) { dispatch('showErrorNotification', body.error);  }
        },
        setLocationNodes: ({commit, dispatch}) => 
        {   console.log('/store/location.js--setLocationNodes');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentLocationNodes)
                    .then(response => 
                        {  console.log('/store/location.js--setLocationNodes gethttp success response.body=', response.body);
                        commit({  type: types.SET_LOCATION_NODES,locationNodes: response.body.locationNodes   });
                        resolve(response);
                    })
                    .catch(error => { console.error('/store/location.js-setLocationNodes error =', error);
                        reject(error);
                    });
            });
        },
        unsetLocationNodes: ({commit}) => {commit({ type: types.UNSET_LOCATION_NODES }); },
        setLocations: ({commit, dispatch}) => 
        { console.log('/store/modules/location.js-setLocations');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentLocations)
                    .then(response => {  console.log('/store/modules/location.js-setLocations gethttp success response.body=', response.body);
                                         commit({type: types.SET_LOCATIONS,locations: response.body.locations });
                                         resolve(response);
                                    })
                    .catch(error => { console.error('/store/modules/location.js-setLocations error =', error);
                                      reject(error);
                                   });
            });
        },
        unsetLocations: ({commit}) => { commit({  type: types.UNSET_LOCATIONS  });   },
        setLocationShowModal:({commit}, data) => {  commit({  type: types.SET_LOCATION_SHOW_MODAL,   data: data  });  },
        getLocationOptions: ({dispatch}) => 
        {      return new Promise
            ((resolve, reject) => 
            {   console.log('/store/location.js-getLocationOptions');
                Vue.http.get(api.locationOptions)
                    .then(response => { resolve(response);   })
                    .catch(response => {  reject(response);  });
            })
        },
        getAssignedLocationOptions: ({dispatch}) => 
        {   return new Promise
            ((resolve, reject) =>
             {  console.log('/store/location.js-getAssignedLocationOptions');
                Vue.http.get(api.assignedlocationOptions)
                    .then(response => {   resolve(response);    })
                    .catch(response => {  reject(response);     });
            })
        },
        getCascadeLocationOptions: ({dispatch}) => 
        {   return new Promise((resolve, reject) => 
            {   console.log('/store/location.js-getCascadeLocationOptions');
                Vue.http.get(api.cascadeLocationOptions)
                    .then(response => { resolve(response); })
                    .catch(response => { reject(response); });
            })
        },
        getAssignedCascadeLocationOptions: ({dispatch}) => 
        {   return new Promise((resolve, reject) => 
            {  console.log('/store/location.js-getAssignedCascadeLocationOptions');
                Vue.http.get(api.assignedCascadelocationOptions)
                    .then(response => { resolve(response); })
                    .catch(response => { reject(response);  });
            })
        },

    }
}
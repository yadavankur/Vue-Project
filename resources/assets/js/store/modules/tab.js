
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{    state: {  tabs: [], tabNodes: null,  showModal: false,  tabData: null, selectedOrder: null, },
    getters: { allTabs: state => state.tabs, selectedOrder: state => state.selectedOrder  },
    mutations: 
    {  [types.SET_TABS] (state, payload) 
        {   console.log('/store/modules/tab.js-types.SET_TABS payload=', payload.tabs);
            console.log('/store/modules/tab.js-types.SET_TABS state=', state);
            let tabsArray = [];
            for(let tab in payload.tabs) { tabsArray.push(payload.tabs[tab]); }
            console.log('/store/modules/tab.js-types.SET_TABS tabsArray=', tabsArray);
            state.tabs = tabsArray;
        },
        [types.UNSET_TABS] (state, payload) 
        {   console.log('/store/modules/tab.js-types.UNSET_TABS payload=', payload);
            console.log('/store/modules/tab.js-types.UNSET_TABS state=', state);
            state.tabs = [];   state. selectedOrder =null;
        },
        [types.SET_ACTIVE_TAB] (state, payload) 
        {   console.log('/store/modules/tab.js-types.SET_ACTIVE_TAB payload=', payload);
            console.log('/store/modules/tab.js-types.SET_ACTIVE_TAB state=', state);
            if (payload.path == "/") payload.path = api.homeUrlPath;
            state.tabs.forEach(tab => { tab.isActive = (tab.link == payload.path);  });
        },
        [types.SET_SELECTED_TAB] (state, payload) 
        {   console.log('/store/modules/tab.js-types.SET_SELECTED_TAB payload=', payload);
            console.log('/store/modules/tab.js-types.SET_SELECTED_TAB state=', state);
            state.tabs.forEach(tab => { tab.isActive = (tab.name == payload.selectedTab.name);  });
        },
        [types.ADD_TAB_SUCCESS] (state, payload) 
        {  console.log('/store/modules/tab.js-types.ADD_TAB_SUCCESS payload=', payload);  },
        [types.ADD_TAB_FAILURE] (state, payload) 
        {  console.log('/store/modules/tab.js-types.ADD_TAB_FAILURE payload=', payload);  },
        [types.UPDATE_TAB_SUCCESS] (state, payload) 
        {  console.log('/store/modules/tab.js-types.UPDATE_TAB_SUCCESS payload=', payload); },
        [types.UPDATE_TAB_FAILURE] (state, payload) 
        { console.log('/store/modules/tab.js-types.UPDATE_TAB_FAILURE payload=', payload);  },
        [types.DELETE_TAB_SUCCESS] (state, payload) 
        { console.log('/store/modules/tab.js-types.DELETE_TAB_SUCCESS payload=', payload);  },
        [types.DELETE_TAB_FAILURE] (state, payload) 
        { console.log('/store/modules/tab.js-types.DELETE_TAB_FAILURE payload=', payload);  },
        [types.SET_TAB_NODES] (state, payload) 
        {   console.log('/store/modules/tab.js-types.SET_TAB_NODES payload=', payload.tabNodes);
            console.log('/store/modules/tab.js-types.SET_TAB_NODES state=', state);
            state.tabNodes = payload.tabNodes;
        },
        [types.UNSET_TAB_NODES] (state, payload) 
        {   console.log('/store/modules/tab.js-types.UNSET_TAB_NODES payload=', payload);
            console.log('/store/modules/tab.js-types.UNSET_TAB_NODES state=', state);
            state.tabNodes = null;
        },
        [types.SET_TAB_SHOW_MODAL] (state, payload) 
        {   console.log('/store/modules/tab.js-types.SET_TAB_SHOW_MODAL payload=', payload.data);
            console.log('/store/modules/tab.js-types.SET_TAB_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.tabData = payload.data.data;
        },
        [types.SET_TAB_SELECTED_ORDER] (state, payload) 
        {   console.log('/store/modules/tab.js-types.SET_TAB_SELECTED_ORDER payload=', payload.selectedOrder);
            console.log('/store/modules/tab.js-types.SET_TAB_SELECTED_ORDER state=', state);
            state.selectedOrder = payload.selectedOrder;
        },
        [types.GET_CSTICKET_TABLE1] (state, payload) //-----this is for refresh
        {  console.log('tab.js-types.GET_CSTICKET_TABLE1 payload=', payload);
           console.log('tab.js-types.GET_CSTICKET_TABLE1 state=', state);
          // state.csTicketperQuote = payload.csTicketperQuote;  //---state takes it from here--gives to getter
                                                        //then getter takes it and gives it back to vue file
        },

    },
    //setTabs called in AppTabs
    actions: 
    { setTabs: ({commit, dispatch}) => 
       {  console.log('/store/modules/tab.js-setTabs');
          return new Promise((resolve, reject) => 
            { Vue.http.get(api.currentTabs)
                .then(response => 
                {  console.log('/store/modules/tab.js-setTabs gethttp success response.body=', response.body);
                    commit({   type: types.SET_TABS,  tabs: response.body.tabs   })
                    resolve(response);
                })
                .catch(error => 
               {   console.error('/store/modules/tab.js-setTabs error =', error);  reject(error);  });
            });
        },
        unsetTabs: ({commit}) => {  commit({  type: types.UNSET_TABS  });  },
        setActiveTab: ({commit}, path) => {  commit({ path: path, type: types.SET_ACTIVE_TAB }); },
        selectTab: ({commit}, selectedTab) => {commit({ selectedTab: selectedTab, type: types.SET_SELECTED_TAB     });  },
        updateTabRequest: ({dispatch}, formData) => 
           {  return new Promise((resolve, reject) => 
                {   console.log('/store/modules/tab.js-updateTabRequest formData=',formData);
                    Vue.http.post(api.updateTab, formData)
                    .then(response => {  dispatch('updateTabSuccess', response.body);  resolve();  })
                    .catch(response => {dispatch('updateTabFailure', response.body);  reject();  });
                })
            },
        updateTabSuccess: ({commit, dispatch}, body) =>
            {   commit({ type: types.UPDATE_TAB_SUCCESS,  tab: body.tab  });
                dispatch('showSuccessNotification', 'Tab has been updated.');
                //dispatch('setTabNodes');
            },
        updateTabFailure: ({commit, dispatch}, body) =>
            {    commit({type: types.UPDATE_TAB_FAILURE, errors: body   });
                 if(body.error) {  dispatch('showErrorNotification', body.error);   }
            },
        deleteTabRequest: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
           { Vue.http.post(api.deleteTab, formData)
                    .then(response => { dispatch('deleteTabSuccess', response.body);  resolve(); })
                    .catch(response => {dispatch('deleteTabFailure', response.body);  reject();  });
            })
        },
        deleteTabSuccess: ({commit, dispatch}, body) => 
            { commit({   type: types.DELETE_TAB_SUCCESS, tab: body.tab  });
            dispatch('showSuccessNotification', 'Tab has been deleted.');     //dispatch('setTabNodes');
            },
        deleteTabFailure: ({commit, dispatch}, body) =>
            { commit({  type: types.DELETE_TAB_FAILURE, errors: body      });
               if(body.error) {    dispatch('showErrorNotification', body.error);    }
            },
        addTabRequest: ({dispatch}, formData) => 
            { return new Promise((resolve, reject) => 
                {   Vue.http.post(api.addTab, formData)
                 .then(response => {  dispatch('addTabSuccess', response.body); resolve();  })
                 .catch(response => {   console.error('/store/modules/tab.js-addTabRequest response.body=', response.body);
                                        dispatch('addTabFailure', response.body);     reject();
                                    });
               })
            },
        addTabSuccess: ({commit, dispatch}, body) => { commit({    type: types.ADD_TAB_SUCCESS, tab: body.tab  });
                                                       dispatch('showSuccessNotification', 'Tab has been added.'); //dispatch('setTabNodes');
                                                     },
        addTabFailure: ({commit, dispatch}, body) => 
           {    commit({  type: types.ADD_TAB_FAILURE, errors: body });
                console.error('/store/modules/tab.js-addTabFailure body.error=', body.error);
                if(body.error) { dispatch('showErrorNotification', body.error); }
           },
        setTabNodes: ({commit, dispatch}) => {   console.log('/store/modules/tab.js-setTabNodes');  },
        unsetTabNodes: ({commit}) => { commit({  type: types.UNSET_TAB_NODES  }); },
        setTabShowModal:({commit}, data) => { commit({ type: types.SET_TAB_SHOW_MODAL, data: data });     },
        setSelectedOrder:({commit}, data) => { commit({ type: types.SET_TAB_SELECTED_ORDER, selectedOrder: data  }); },
        //setSelectedOrder--takes data from one tab and distributes it to all other

    }
}
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{ state: 
    {   cpm1Activities: [],
        cpm1ActivityNodes:null,
        showModal:false,   
        cpm1ActivityData:null,
        modalForm:{cpm1Activity:null,},
    },
  getters: 
        {   allCpm1Activities: state => state.cpm1Activities,
            allCpm1ActivityNodes: state => state.cpm1ActivityNodes
        },
    mutations: 
        {   [types.DELETE_CPM1ACTIVITY_SUCCESS] (state, payload) 
              { console.log('/store/cpm1.js-types.DELETE_CPM1ACTIVITY_SUCCESS payload=', payload);   },
            [types.DELETE_CPM1ACTIVITY_FAILURE] (state, payload) 
              { console.log('/store/cpm1.js-types.DELETE_CPM1ACTIVITY_FAILURE payload=', payload);  },
//-------------DELETE FINIISH--------ADD START(showmodal is required for popup when add is clicked)--
//and for passsing all the data---------------------
            [types.SET_CPM1ACTIVITY_SHOW_MODAL] (state, payload) 
                {   console.log('/store/cpm1.js-types.SET_CPM1ACTIVITY_SHOW_MODAL payload=', payload.data);
                console.log('/store/cpm1.js-types.SET_CPM1ACTIVITY_SHOW_MODAL state=', state);
                state.showModal = payload.data.isShow;
                state.cpm1ActivityData = payload.data.data;
                },
//-------------------------------------------------------------
            [types.ADD_CPM1ACTIVITY_SUCCESS] (state, payload) 
              {  console.log('/store/cpm1-types.ADD_CPM1ACTIVITY_SUCCESS payload=', payload); },
            [types.ADD_CPM1ACTIVITY_FAILURE] (state, payload) 
               {  console.log('/store/cpm1-types.ADD_CPM1ACTIVITY_FAILURE payload=', payload); },


            
        }, //mutations finished
    actions: 
        {  
             
            deleteCpm1ActivityRequest: ({dispatch}, formData) => 
            {  return new Promise((resolve, reject) => 
                {  Vue.http.post(api.deleteCpm1Activity, formData)
                        .then(response => { dispatch('deleteCpm1ActivitySuccess', response.body);  resolve(response);  })
                        .catch(response => { dispatch('deleteCpm1ActivityFailure', response.body);  reject(response);  });
                })
            },
            deleteCpm1ActivitySuccess: ({commit, dispatch}, body) => 
            {   commit({  type: types.DELETE_CPM1ACTIVITY_SUCCESS, cpm1Activity: body.cpm1Activity });
                dispatch('showSuccessNotification', 'Cpm1Activity has been deleted.'); 
                //cpm1Activity: body.cpm1Activity----coming from controller----to show back
            },
            deleteCpm1ActivityFailure: ({commit, dispatch}, body) => 
            {
                commit({   type: types.DELETE_CPM1ACTIVITY_FAILURE, errors: body  });
                if(body.error) { dispatch('showErrorNotification', body.error); }
            },
            //-------------------------DELETE FINISH----------ADD START-------------
            //showmodal is reqd when --clikc on add-------popup hs to come-----
            //onlick new----
            setCpm1ActivityShowModal:({commit}, data) => 
            { commit({  type: types.SET_CPM1ACTIVITY_SHOW_MODAL, data: data });  },
            //----------------------------------
            addCpm1ActivityRequest: ({dispatch}, formData) => 
            {   return new Promise((resolve, reject) => {
                    Vue.http.post(api.addCpm1Activity, formData)
                        .then(response => {
                            dispatch('addCpm1ActivitySuccess', response.body);
                            resolve(response);
                        })
                        .catch(response => {
                            console.error('/store/cpm1-addCpm1ActivityRequest response.body=', response.body);
                            dispatch('addCpmActivityFailure', response.body);
                            reject(response);
                        });
                })
            },
            addCpm1ActivitySuccess: ({commit, dispatch}, body) => 
            {   commit({  type: types.ADD_CPM1ACTIVITY_SUCCESS, cpm1Activity: body.cpm1Activity  });
                dispatch('showSuccessNotification', 'Cpm1Activity has been added.');
            },
            addCpm1ActivityFailure: ({commit, dispatch}, body) => 
            {   commit({   type: types.ADD_CPM1ACTIVITY_FAILURE,  errors: body  });
                console.error('/store/cpm1--addCpm1ActivityFailure body.error=', body.error);
                if(body.error) {  dispatch('showErrorNotification', body.error); }
            },

      
           

        }//actions finished
}

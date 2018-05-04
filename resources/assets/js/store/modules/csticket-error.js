import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{ state: { ticketcnstatustable: null,  //--------for display---GET_TICKET_STATUS_TABLE]
           showPopup: false, //--add 4---goes to CsStatusCrudModal.vue---form field on top--<custom-modal :value="showPopup" @cancel="onClose" effect="fade">
           //modalForm: {  state: null, }, //---not used
           cnstatusData: null, //-------add-5-//--edit6----goes to CsStatusCrudModal.vue--in computed--mapstate
         }, 
            
getters: {    ticketcnstatustablegetter: state => state.ticketcnstatustable //--------for display---GET_TICKET_STATUS_TABLE]
         },  
mutations: 
 {

    [types.GET_TICKET_CNSTATUS_TABLE] (state, payload) //-----this is for refresh
    {  console.log('types.GET_TICKET_CNSTATUS_TABLE payload=', payload.ticketcnstatustable);
       console.log('types.GET_TICKET_CNSTATUS_TABLE state=', state);
       state.ticketcnstatustable = payload.ticketcnstatustable;
    },
    //---------------diplay table working above-----------now add below
    [types.SET_CNSTATUS_SHOW_POPUP] (state, payload) //--------add----3--//----edit--5
    {   console.log('types.SET_CNSTATUS_SHOW_POPUP payload=', payload.data);
        console.log('types.SET_CNSTATUS_SHOW_POPUP state=', state);
        state.showPopup = payload.data.isShow;  state.cnstatusData = payload.data.data; //data could be add or edit
    }, 
   
    //------not required but for getting values on cosole below ttwo lines required in add
    [types.ADD_TICKET_CNSTATUS_SUCCESS] (state, payload) {console.log('types.ADD_TICKET_CNSTATUS_SUCCESS payload=', payload); },
    [types.ADD_TICKET_CNSTATUS_FAILURE] (state, payload) {console.log('types.ADD_TICKET_CNSTATUS_FAILURE payload=', payload); },
    [types.UPDATE_TICKET_CNSTATUS_SUCCESS] (state, payload) {console.log('types.UPDATE_TICKET_CNSTATUS_SUCCESS payload=', payload); },
    [types.UPDATE_TICKET_CNSTATUS_FAILURE] (state, payload) {console.log('types.UPDATE_TICKET_CNSTATUS_FAILURE payload=', payload); },
    [types.DELETE_TICKET_CNSTATUS_SUCCESS] (state, payload) {console.log('types.DELETE_TICKET_CNSTATUS_SUCCESS payload=', payload); },
    [types.DELETE_TICKET_CNSTATUS_FAILURE] (state, payload) {console.log('types.DELETE_TICKET_CNSTATUS_FAILURE payload=', payload); },

},

    actions: 
    {  getticketcnstatustable: ({commit, dispatch}) => //---------for displaying all the records
       {  return new Promise((resolve, reject) => 
           {  Vue.http.get(api.getticketcnstatustableapi)
                   .then(response => {   console.log('getticketcnstatustable response.body=', response.body);                      
                                        commit({type: types.GET_TICKET_CNSTATUS_TABLE, ticketcnstatustable: response.body});  
                                        resolve(response);
                                    }) 
                   .catch(error => {  console.error('getticketcnstatustable  error =', error);  reject(error); });
           });
       },

       cscnstatusshowpopup: ({commit}, data) => 
       {   commit({ type: types.SET_CNSTATUS_SHOW_POPUP, data: data  }); 
           console.log('csticket-status.js--cscnstatusshowpopup data=', data); 

       },
       //-------add 10---      
       cscnstatusadd: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  console.log(' cscnstatusadd-- formData=', formData);
                Vue.http.post(api.addcscnstatus, formData)
                    .then(response => { dispatch('addCnStatusSuccess', response.body);  resolve();   })
                    .catch(response => { dispatch('addCnStatusFailure', response.body); reject();   });
                
            })
        },
        addCnStatusSuccess: ({commit, dispatch}, body) => 
        {   console.log('csticket-status.js---addStatusSuccess body=', body);
            commit({   type: types.ADD_TICKET_CNSTATUS_SUCCESS, state: body.state   });
            dispatch('showSuccessNotification', 'CNSTATUS has been added.');   
            dispatch('getticketcnstatustable');
        },
        addCnStatusFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.ADD_TICKET_CNSTATUS_FAILURE, errors: body  });
            console.error('addCnStatusFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
     //---------------------------add finish---------------update start-----------
     
     updatecnstatus: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  console.log('cscnstatusadd-- formData=', formData);
                Vue.http.post(api.updatecnstatus, formData)
                    .then(response => { dispatch('updateCnStatusSuccess', response.body);  resolve();   })
                    .catch(response => { dispatch('updateCnStatusFailure', response.body); reject();   });
                
            })
        },
        updateCnStatusSuccess: ({commit, dispatch}, body) => 
        {   console.log('addCnStatusSuccess body=', body);
            commit({   type: types.UPDATE_TICKET_CNSTATUS_SUCCESS, state: body.state   });
            dispatch('showSuccessNotification', 'CNSTATUS has been updated.');   
            dispatch('getticketcnstatustable');
        },
        updateCnStatusFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.UPDATE_TICKET_CNSTATUS_FAILURE, errors: body  });
            console.error('csticket-status.js---updateCnStatusFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
 //--------------------update done-------------delete start----------------
 deletecnstatus: ({dispatch}, formData) => 
 {   return new Promise((resolve, reject) => 
     {    Vue.http.post(api.deletecnstatus, formData)
             .then(response => {  dispatch('deleteCnstatusSuccess', response.body); resolve(); })
             .catch(response => { dispatch('deleteCnstatusFailure', response.body);  reject(); });
     })
 },
 deleteCnstatusSuccess: ({commit, dispatch}, body) => 
 {   commit({ type: types.DELETE_TICKET_CNSTATUS_SUCCESS, state: body.state });
     dispatch('showSuccessNotification', 'STATUS has been deleted.');
     dispatch('getticketcnstatustable');  //----get back new result with modified values
 },
 deleteCnstatusFailure: ({commit, dispatch}, body) => 
 {   commit({   type: types.DELETE_TICKET_CNSTATUS_FAILURE, errors: body  });
     if(body.error) {  dispatch('showErrorNotification', body.error);  }
 },

    }

}
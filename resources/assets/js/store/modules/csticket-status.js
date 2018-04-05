import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{ state: { ticketstatustable: null,  //--------for display---GET_TICKET_STATUS_TABLE]
           showPopup: false, //--add 4---goes to CsStatusCrudModal.vue---form field on top--<custom-modal :value="showPopup" @cancel="onClose" effect="fade">
           //modalForm: {  state: null, }, //---not used
           statusData: null, //-------add-5-//--edit6----goes to CsStatusCrudModal.vue--in computed--mapstate
         }, 
            
getters: {    ticketstatustablegetter: state => state.ticketstatustable //--------for display---GET_TICKET_STATUS_TABLE]
         },  
mutations: 
 {

    [types.GET_TICKET_STATUS_TABLE] (state, payload) //-----this is for refresh
    {  console.log('csticket-status.js---types.GET_TICKET_STATUS_TABLE payload=', payload.ticketstatustable);
       console.log('csticket-status.js---types.GET_TICKET_STATUS_TABLE state=', state);
       state.ticketstatustable = payload.ticketstatustable;
    },
    //---------------diplay table working above-----------now add below
    [types.SET_STATUS_SHOW_POPUP] (state, payload) //--------add----3--//----edit--5
    {   console.log('csticket-status.js---types.SET_STATUS_SHOW_POPUP payload=', payload.data);
        console.log('csticket-status.js---types.SET_STATUS_SHOW_POPUP state=', state);
        state.showPopup = payload.data.isShow;  state.statusData = payload.data.data; //data could be add or edit
    }, 
   
    //------not required but for getting values on cosole below ttwo lines required in add
    [types.ADD_TICKET_STATUS_SUCCESS] (state, payload) {console.log('csticketstatus.js-types.ADD_TICKET_STATUS_SUCCESS payload=', payload); },
    [types.ADD_TICKET_STATUS_FAILURE] (state, payload) {console.log('csticketstatus.js-types.ADD_TICKET_STATUS_FAILURE payload=', payload); },
    [types.UPDATE_TICKET_STATUS_SUCCESS] (state, payload) {console.log('csticketstatus.js-types.UPDATE_TICKET_STATUS_SUCCESS payload=', payload); },
    [types.UPDATE_TICKET_STATUS_FAILURE] (state, payload) {console.log('csticketstatus.js-types.UPDATE_TICKET_STATUS_FAILURE payload=', payload); },
    [types.DELETE_TICKET_STATUS_SUCCESS] (state, payload) {console.log('csticketstatus.js-types.DELETE_TICKET_STATUS_SUCCESS payload=', payload); },
    [types.DELETE_TICKET_STATUS_FAILURE] (state, payload) {console.log('csticketstatus.js-types.DELETE_TICKET_STATUS_FAILURE payload=', payload); },

},

    actions: 
    {  getticketstatustable: ({commit, dispatch}) => //---------for displaying all the records
       {  return new Promise((resolve, reject) => 
           {  Vue.http.get(api.getticketstatustableapi)
                   .then(response => {   console.log('csticket-status.js-getticketstatustable response.body=', response.body);                      
                               // commit({type: types.GET_TICKET_STATUS_TABLE, ticketstatustable: response.body.gett1 });  
                                        commit({type: types.GET_TICKET_STATUS_TABLE, ticketstatustable: response.body});  
                                        resolve(response);
                                    }) 
                   .catch(error => {  console.error('csticket-status.js--getticketstatustable  error =', error);  reject(error); });
           });
       },

       //---------------diplay table working above-----------now add below

       //----this one is only for popup first then data transaction bw vue file and store
       csstatusshowpopup: ({commit}, data) => //-for add/edit---//----add----2 //--edit 4
       {   commit({ type: types.SET_STATUS_SHOW_POPUP, data: data  }); //------update the data of table--with new modifications
           console.log('csticket-status.js--csstatusshowpopup data=', data); //---------------now the data is updated for table show
           //--------------------------this will update the value in mutation---and feed to the popup--blank form--ie null value
       },
       //-------add 10---      
       csstatusadd: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  console.log('csticket-status.js-- csstatusadd-- formData=', formData);
                Vue.http.post(api.addcsstatus, formData)
                    .then(response => { dispatch('addStatusSuccess', response.body);  resolve();   })
                    .catch(response => { dispatch('addStatusFailure', response.body); reject();   });
                
            })
        },
        addStatusSuccess: ({commit, dispatch}, body) => 
        {   console.log('csticket-status.js---addStatusSuccess body=', body);
            commit({   type: types.ADD_TICKET_STATUS_SUCCESS, state: body.state   });
            dispatch('showSuccessNotification', 'STATUS has been added.');   
            dispatch('getticketstatustable');
        },
        addStatusFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.ADD_TICKET_STATUS_FAILURE, errors: body  });
            console.error('csticket-status.js---addStatusFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
     //---------------------------add finish---------------update start-----------
     
     updatestatus: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  console.log('csticket-status.js-- csstatusadd-- formData=', formData);
                Vue.http.post(api.updatestatus, formData)
                    .then(response => { dispatch('updateStatusSuccess', response.body);  resolve();   })
                    .catch(response => { dispatch('updateStatusFailure', response.body); reject();   });
                
            })
        },
        updateStatusSuccess: ({commit, dispatch}, body) => 
        {   console.log('csticket-status.js---addStatusSuccess body=', body);
            commit({   type: types.UPDATE_TICKET_STATUS_SUCCESS, state: body.state   });
            dispatch('showSuccessNotification', 'STATUS has been updated.');   
            dispatch('getticketstatustable');
        },
        updateStatusFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.UPDATE_TICKET_STATUS_FAILURE, errors: body  });
            console.error('csticket-status.js---updateStatusFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
 //--------------------update done-------------delete start----------------
 deletestatus: ({dispatch}, formData) => 
 {   return new Promise((resolve, reject) => 
     {    Vue.http.post(api.deletestatus, formData)
             .then(response => {  dispatch('deletestatusSuccess', response.body); resolve(); })
             .catch(response => { dispatch('deletestatusFailure', response.body);  reject(); });
     })
 },
 deletestatusSuccess: ({commit, dispatch}, body) => 
 {   commit({ type: types.DELETE_TICKET_STATUS_SUCCESS, state: body.state });
     dispatch('showSuccessNotification', 'STATUS has been deleted.');
     dispatch('getticketstatustable');  //----get back new result with modified values
 },
 deletestatusFailure: ({commit, dispatch}, body) => 
 {   commit({   type: types.DELETE_TICKET_STATUS_FAILURE, errors: body  });
     if(body.error) {  dispatch('showErrorNotification', body.error);  }
 },

    }

}
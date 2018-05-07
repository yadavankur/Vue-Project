import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{ state: { ticketerrortypetable: null,  //--------for display---GET_TICKET_STATUS_TABLE]
           showPopup: false, //--add 4---goes to CsStatusCrudModal.vue---form field on top--<custom-modal :value="showPopup" @cancel="onClose" effect="fade">
           //modalForm: {  state: null, }, //---not used
           errortypeData: null, //-------add-5-//--edit6----goes to CsStatusCrudModal.vue--in computed--mapstate
         }, 
            
getters: {    ticketerrortypetablegetter: state => state.ticketerrortypetable //--------for display---GET_TICKET_STATUS_TABLE]
         },  
mutations: 
 {

    [types.GET_TICKET_ERRORTYPE_TABLE] (state, payload) //-----this is for refresh
    {  console.log('types.GET_TICKET_ERRORTYPE_TABLE payload=',  state.ticketerrortypetable);
       console.log('types.GET_TICKET_ERRORTYPE_TABLE state=', state);
       state.ticketerrortypetable = payload.ticketerrortypetable;
    },
    //---------------diplay table working above-----------now add below
    [types.SET_ERRORTYPE_SHOW_POPUP] (state, payload) //--------add----3--//----edit--5
    {   console.log('types.SET_ERRORTYPE_SHOW_POPUP payload=', payload.data);
        console.log('types.SET_ERRORTYPE_SHOW_POPUP state=', state);
        state.showPopup = payload.data.isShow;  state.errortypeData = payload.data.data; //data could be add or edit
    }, 
   
    //------not required but for getting values on cosole below ttwo lines required in add
    [types.ADD_TICKET_ERRORTYPE_SUCCESS] (state, payload) {console.log('types.ADD_TICKET_ERRORTYPE_SUCCESS payload=', payload); },
    [types.ADD_TICKET_ERRORTYPE_FAILURE] (state, payload) {console.log('types.ADD_TICKET_ERRORTYPE_FAILURE payload=', payload); },
    [types.UPDATE_TICKET_ERRORTYPE_SUCCESS] (state, payload) {console.log('types.UPDATE_TICKET_ERRORTYPE_SUCCESS payload=', payload); },
    [types.UPDATE_TICKET_ERRORTYPE_FAILURE] (state, payload) {console.log('types.UPDATE_TICKET_ERRORTYPE_FAILURE payload=', payload); },
    [types.DELETE_TICKET_ERRORTYPE_SUCCESS] (state, payload) {console.log('types.DELETE_TICKET_ERRORTYPE_SUCCESS payload=', payload); },
    [types.DELETE_TICKET_ERRORTYPE_FAILURE] (state, payload) {console.log('types.DELETE_TICKET_ERRORTYPE_FAILURE payload=', payload); },

},

    actions: 
    {  getticketerrortypetable: ({commit, dispatch}) => //---------for displaying all the records
       {  return new Promise((resolve, reject) => 
           {  Vue.http.get(api.geterrortypeapi)
                   .then(response => {   console.log('getticketerrortypetable response.body=', response.body);                      
                                        commit({type: types.GET_TICKET_ERRORTYPE_TABLE, ticketerrortypetable: response.body});  
                                        resolve(response);
                                    }) 
                   .catch(error => {  console.error('getticketerrortypetable  error =', error);  reject(error); });
           });
       },

       cserrortypeshowpopup: ({commit}, data) => 
       {   commit({ type: types.SET_ERRORTYPE_SHOW_POPUP, data: data  }); 
           console.log('csticket-status.js--cserrortypeshowpopup data=', data); 

       },
       //-------add 10---      
       cserrortypeadd: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  console.log(' cserrortypeadd-- formData=', formData);
                Vue.http.post(api.adderrortype, formData)
                    .then(response => { dispatch('addErrorTypeSuccess', response.body);  resolve();   })
                    .catch(response => { dispatch('addErrorTypeFailure', response.body); reject();   });
                
            })
        },
        addErrorTypeSuccess: ({commit, dispatch}, body) => 
        {   console.log('csticket-status.js---addStatusSuccess body=', body);
            commit({   type: types.ADD_TICKET_ERRORTYPE_SUCCESS, state: body.state   });
            dispatch('showSuccessNotification', 'ERRORTYPE has been added.');   
            dispatch('getticketerrortypetable');
        },
        addErrorTypeFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.ADD_TICKET_ERRORTYPE_FAILURE, errors: body  });
            console.error('adderrortypeFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
     //---------------------------add finish---------------update start-----------
     
     cserrortypeupdate: ({dispatch}, formData) => 
        {  return new Promise((resolve, reject) => 
            {  console.log('cserrortypeadd-- formData=', formData);
                Vue.http.post(api.updateerrortype, formData)
                    .then(response => { dispatch('updateErrorTypeSuccess', response.body);  resolve();   })
                    .catch(response => { dispatch('updateErrorTypeFailure', response.body); reject();   });
                
            })
        },
        updateErrorTypeSuccess: ({commit, dispatch}, body) => 
        {   console.log('addErrorTypeSuccess body=', body);
            commit({   type: types.UPDATE_TICKET_ERRORTYPE_SUCCESS, state: body.state   });
            dispatch('showSuccessNotification', 'ERRORTYPE has been updated.');   
            dispatch('getticketerrortypetable');
        },
        updateErrorTypeFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.UPDATE_TICKET_ERRORTYPE_FAILURE, errors: body  });
            console.error('csticket-status.js---updateCnStatusFailure body.error=', body.error);
            if(body.error) {  dispatch('showErrorNotification', body.error);  }
        },
 //--------------------update done-------------delete start----------------
 deleteerrortype: ({dispatch}, formData) => 
 {   return new Promise((resolve, reject) => 
     {    Vue.http.post(api.deleteerrortype, formData)
             .then(response => {  dispatch('deleteErrorTypeSuccess', response.body); resolve(); })
             .catch(response => { dispatch('deleteErrorTypeFailure', response.body);  reject(); });
     })
 },
 deleteErrorTypeSuccess: ({commit, dispatch}, body) => 
 {   commit({ type: types.DELETE_TICKET_ERRORTYPE_SUCCESS, state: body.state });
     dispatch('showSuccessNotification', 'ERROR TYPE has been deleted.');
     dispatch('getticketerrortypetable');  //----get back new result with modified values
 },
 deleteErrorTypeFailure: ({commit, dispatch}, body) => 
 {   commit({   type: types.DELETE_TICKET_ERRORTYPE_FAILURE, errors: body  });
     if(body.error) {  dispatch('showErrorNotification', body.error);  }
 },

    }

}
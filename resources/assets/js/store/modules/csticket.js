import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{

   state: 
    {   showModal:false,  csticketActivityData:null,
       //till above add is working
        csTicketdata: null , csTicketperQuote : null, csTicketlast: null,//this is for refresh
        //---it[state], gets value from mutations[REFRESH_CSTICKET_TABLE]---then gives it to getter[allcsTicketdata]
        //--then getter is imported in vue file and --then table is displayed
    },
   getters: 
    {  csTicketdata: csticket => csticket.csTicketdata,//this is for refresh
        csTicketperQuote: csticket => csticket.csTicketperQuote, 
        csTicketlast: csticket=> csticket.csTicketlast,
        
    },
   mutations: 
    {   //--------this one is to exchange data bw vue file and store file
        [types.SET_CSTICKETACTIVITY_SHOW_MODAL] (state, payload) 
        {   console.log('/store/csticket.js-types.CSTICKETACTIVITY_SHOW_MODAL payload=', payload.data);
            console.log('/store/csticket.js-types.CSTICKETACTIVITY_SHOW_MODAL state=', state);
            state.showModal = payload.data.isShow;
            state.csticketActivityData = payload.data.data;
        },
        //---------------------
        [types.ADD_CSTICKET_SUCCESS] (state, payload) { console.log('/store/csticket--types.ADD_CSTICKET_SUCCESS payload=', payload);  },
        [types.ADD_CSTICKET_FAILURE] (state, payload) { console.log('/store/csticket--types.ADD_CSTICKET_FAILURE payload=', payload);  },
        //---------------------
        [types.REFRESH_CSTICKET_TABLE] (state, payload) //-----this is for refresh
        {  console.log('csticket.js-types.REFRESH_CSTICKET_TABLE payload=', payload.csTicketdata);
           console.log('csticket.js-types.REFRESH_CSTICKET_TABLE state=', state);
           state.csTicketdata = payload.csTicketdata;  //---state takes it from here--gives to getter
                                                        //then getter takes it and gives it back to vue file
        },
        //----------------------------------------------
        [types.GET_CSTICKET_TABLE] (state, payload) //-----this is for refresh
        {  console.log('csticket.js-types.GET_CSTICKET_TABLE payload=', payload.csTicketperQuote);
           console.log('csticket.js-types.GET_CSTICKET_TABLE state=', state);
           state.csTicketperQuote = payload.csTicketperQuote;  //---state takes it from here--gives to getter
                                                        //then getter takes it and gives it back to vue file
        },
        [types.GET_LAST_CSTICKET] (state, payload) //-----this is for refresh
        {  console.log('csticket.js-types.GET_LAST_CSTICKET payload=', payload.csTicketlast);
           console.log('csticket.js-types.GET_LAST_CSTICKET state=', state);
           state.csTicketlast = payload.csTicketlast;  //---state takes it from here--gives to getter
                                                        //then getter takes it and gives it back to vue file
        },
        
    },
   actions: 
    {     //-----------this is just to pass data between vue file and store every time something happens
        //setCsTicketShowModal:({commit}, data) => 
        setCsTicketShowModal:({commit,dispatch}, data) => 
        { commit({ type: types.SET_CSTICKETACTIVITY_SHOW_MODAL, data: data });    
         // dispatch('getLastTicket');
        },
           //--------------delete working above-----add below----------------
        addCsTicketRequest: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {   Vue.http.post(api.addcsticket, formData)
                .then(response => { dispatch('addCsTicketSuccess', response.body); resolve(response);   })
                .catch(response => { console.error('/store/csticket--add response.body=', response.body);
                                 dispatch('addCsTicketFailure', response.body);
                                 reject(response);
                               });
            })
        },
       addCsTicketSuccess: ({commit, dispatch}, body) => 
        {    //console.error('/store/csticket--add success response body=', response.body);
            // console.error('/store/csticket--add success body=', body);
            commit({ type: types.ADD_CSTICKET_SUCCESS, csticket: body.gett1 });
             dispatch('showSuccessNotification', 'csticket has been added.');
             dispatch('getTicketsperQuote',body.gett1);
             dispatch('getLastTicket');
           //  dispatch('displayCsTable');  //---this one when get all data
        },
       addCsTicketFailure: ({commit, dispatch}, body) => 
       {     commit({ type: types.ADD_CSTICKET_FAILURE, errors: body  });
             console.error('/store/csticket-service--add failed body.error=', body.error);
             if(body.error) {  dispatch('showErrorNotification', body.error); }
       },
       //-------------------------------
       displayCsTable: ({commit, dispatch},payload) => //---------for displaying all tkts of a quote--not used
       {  return new Promise((resolve, reject) => 
           {  //Vue.http.get(api.displaycs,formData)
                Vue.http.post(api.displaycs,{params:payload})
                   .then(response => {   console.log('csticket.js- displayCsTable success response.body=', response.body);                      
                                commit({type: types.REFRESH_CSTICKET_TABLE, csTicketdata: response.body.gett2 });  resolve(response);
                     }) 
                   .catch(error => {  console.error('csticket.js- displayCsTable error =', error);  reject(error); });
           });
       },

       getTicketsperQuote: ({commit, dispatch},dataItem) =>// 
       {  return new Promise((resolve, reject) => 
           {  //Vue.http.get(api.displaycs,formData)
                Vue.http.post(api.displaycs,dataItem)
                   .then(response => {   console.log('csticket.js- getTicketsperQuote formData=', dataItem);                      
                                         commit({type: types.GET_CSTICKET_TABLE, csTicketperQuote: response.body.gett1 });  
                                        resolve(response);
                                    }) 
                   .catch(error => {  console.error('csticket.js- getTicketsperQuote error =', error);  reject(error); });
           });
       },

       getLastTicket: ({commit, dispatch}) =>// ----for displaying all the records
       {  return new Promise((resolve, reject) => 
           {  //Vue.http.get(api.displaycs,formData)
                Vue.http.get(api.getlastcsticket)
                   .then(response => {   console.log('csticket.js- getLastTicket response.body=', response.body);                      
                                commit({type: types.GET_LAST_CSTICKET, csTicketlast: response.body.gett2 });  resolve(response);
                     }) 
                   .catch(error => {  console.error('csticket.js- getLastTicket error =', error);  reject(error); });
           });
       },
    },

}
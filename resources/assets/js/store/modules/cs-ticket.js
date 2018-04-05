import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{

   state: 
    {   showcstPopup:false, showcstEditPopup:false,  csticketActivityData:null, csticketinbooking:null,
        selectedTicket: null,selectedTicketType:null,
       //till above add is working
        csTicketdata: null , csTicketperQuote : null, csTicketlast: null,//this is for refresh
        //---it[state], gets value from mutations[REFRESH_CSTICKET_TABLE]---then gives it to getter[allcsTicketdata]
        //--then getter is imported in vue file and --then table is displayed
    },
    getters: 
    {},
    mutations: 
    {   //--------this one is to exchange data bw vue file and store file
        [types.ADD_CSTKT_SUCCESS] (state, payload) {console.log('/store/cstkt--types.ADD_CSTKT_SUCCESS payload=', payload); },
        [types.ADD_CSTKT_FAILURE] (state, payload) { console.log('/store/cstkt--types.ADD_CSTKT_FAILURE payload=', payload);  },
        [types.EDIT_CSTKT_SUCCESS] (state, payload) {console.log('/store/cstkt--types.EDIT_CSTKT_SUCCESS payload=', payload); },
        [types.EDIT_CSTKT_FAILURE] (state, payload) { console.log('/store/cstkt--types.EDIT_CSTKT_FAILURE payload=', payload);  },
        [types.DELETE_CSTKT_SUCCESS] (state, payload) {console.log('/store/cstkt--types.EDIT_CSTKT_SUCCESS payload=', payload); },
        [types.DELETE_CSTKT_FAILURE] (state, payload) { console.log('/store/cstkt--types.EDIT_CSTKT_FAILURE payload=', payload);  },
        [types.SET_CS_TICKET_SHOW_MODAL] (state, payload) 
        {   console.log('/store/cstkt.js--SET_CS_TICKET_SHOW_MODAL payload=', payload.data);
            console.log('/store/cstkt.js--SET_CS_TICKET_SHOW_MODAL isShow=', payload.data.isShow);
            console.log('/store/cstkt.js--SET_CS_TICKET_SHOW_MODAL action=', payload.data.data.action);
            console.log('/store/cstkt.js--SET_CS_TICKET_SHOW_MODAL state=', state);
            if(payload.data.data.action=="Add") 
                { state.showcstPopup = payload.data.isShow;
                    console.log('/store/cstkt.js--showcstPopup', state.showcstPopup);
                }
            else if(payload.data.data.action=='Edit')
            { state.showcstEditPopup=payload.data.isShow;
                console.log('/store/cstkt.js--showcstEditPopup', state.showcstEditPopup);
            }
            else //when no add or edit---ie on close or save etc--assing all model to false
            {    state.showcstPopup = payload.data.isShow; //assign them to false on close--else no popup
                      state.showcstEditPopup=payload.data.isShow;
                 }
            state.csticketActivityData = payload.data.data;
        },
        [types.SET_CS_TICKET_IN_BOOKING] (state, payload) 
        {   console.log('/store/cstkt.js--SET_CS_TICKET_IN_BOOKING payload=', payload);
            console.log('/store/cstkt.js--SET_CS_TICKET_IN_BOOKING state=', state);
           // state.showcstPopup = payload.data.isShow;
            state.csticketinbooking = payload.csticketinbooking;
            state.csticketActivityData = payload.csticketinbooking;
            state.csticketActivityData.action ="Add";
        },

        [types.SET_SELECTED_TICKET] (state, payload) 
        {   console.log('/store/cs-tcket.js--types.SET_SELECTED_TICKET payload=', payload.selectedTicket);
            console.log('/store/cs-tcket.js--types.SET_SELECTED_TICKET state=', state);
            state.selectedTicket = payload.selectedTicket;
            state.selectedTicketType = payload.selectedTicketType;
        },
    },
    actions: 
    {     //-----------this is just to pass data between vue file and store every time something happens
        //setCsTicketShowModal:({commit}, data) => 
        setCsTicketShowPopup:({commit,dispatch}, data) => 
        { commit({ type: types.SET_CS_TICKET_SHOW_MODAL, data: data });    
         // dispatch('getLastTicket');
        },

        getOrderOnSearchTkt: ({commit,dispatch}, payload) => 
        {   console.log('/store/cstkt.js-getOrderOnSearchTkt');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.getOrderDetailOnSearch, {params: payload}, /* {responseType: 'arraybuffer'} */ )
                    .then(response => {   console.log('/store/cstkt.js-getOrderOnSearchTkt get http success response.body=', response.body);
                                          commit({type: types.SET_CS_TICKET_IN_BOOKING, csticketinbooking: response.body });
                                          resolve(response);
                                         
                                      })
                    .catch(error => {    console.error('/store/cstkt.js-getOrderOnSearchTkt error =', error);
                                          reject(error);
                                    });
            });
        },

        //----------------------------------
        addCsTicket: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {   Vue.http.post(api.addcsticket, formData)
                .then(response => { dispatch('addCsTicketSuccess1', response.body); resolve(response);   })
                .catch(response => { console.error('/store/cstkt--add response.body=', response.body);
                                 dispatch('addCsTicketFailure1', response.body);
                                 reject(response);
                               });
            })
        },
       addCsTicketSuccess1: ({commit, dispatch}, body) => 
        {    //console.error('/store/csticket--add success response body=', response.body);
            // console.error('/store/csticket--add success body=', body);
            commit({ type: types.ADD_CSTKT_SUCCESS, csticket: body.gett1 });
             dispatch('showSuccessNotification', 'CSTICKET has been added.');
            // dispatch('getTicketsperQuote',body.gett1);
             dispatch('getLastTicket');
           //  dispatch('displayCsTable');  //---this one when get all data
        },
       addCsTicketFailure1: ({commit, dispatch}, body) => 
       {     commit({ type: types.ADD_CSTKT_FAILURE, errors: body  });
             console.error('/store/cstkt-service--add failed body.error=', body.error);
             if(body.error) {  dispatch('showErrorNotification', body.error); }
       },
       //---------------add finish----delete start--------------------
       deletecstkt: ({dispatch}, formData) => 
                {    return new Promise((resolve, reject) => 
                     {    Vue.http.post(api.deletecstkt, formData)
                     .then(response => { dispatch('deletecstktSuccess', response.body); resolve(response); })
                     .catch(response => { dispatch('deletecstktFailure', response.body); reject(response); });
                     })
                },
        deletecstktSuccess: ({commit, dispatch}, body) => 
                { commit({type: types.DELETE_CSTKT_SUCCESS,cpmService: body.cpmService });
                  dispatch('showSuccessNotification', 'CSTICKET has been deleted.');
                },
        deletecstktFailure: ({commit, dispatch}, body) => 
               { commit({  type: types.DELETE_CSTKT_FAILURE, errors: body  });
                 if(body.error) { dispatch('showErrorNotification', body.error); }
               },
          //--------------edit------------
        editCsTicket: ({dispatch}, formData) => 
        {   return new Promise((resolve, reject) => 
            {   Vue.http.post(api.editcsticket, formData)
                .then(response => { dispatch('editCsTicketSuccess1', response.body); resolve(response);   })
                .catch(response => { console.error('/store/cstkt--add response.body=', response.body);
                                 dispatch('editCsTicketFailure1', response.body);
                                 reject(response);
                               });
            })
        },
       editCsTicketSuccess1: ({commit, dispatch}, body) => 
        {    //console.error('/store/csticket--add success response body=', response.body);
            // console.error('/store/csticket--add success body=', body);
            commit({ type: types.EDIT_CSTKT_SUCCESS, csticket: body.gett1 });
             dispatch('showSuccessNotification', 'Ticket has been Updated.');
            // dispatch('getTicketsperQuote',body.gett1);
             dispatch('getLastTicket');
           //  dispatch('displayCsTable');  //---this one when get all data
        },
       editCsTicketFailure1: ({commit, dispatch}, body) => 
       {     commit({ type: types.EDIT_CSTKT_FAILURE, errors: body  });
             console.error('/store/cstkt-service--add failed body.error=', body.error);
             if(body.error) {  dispatch('showErrorNotification', body.error); }
       },
        //-----------------cascade----users
        useraspergroupscascade: ({dispatch}) => 
        {   return new Promise((resolve, reject) => 
            {   console.log('/store/cs-ticket.js-useraspergroupscascade');
                Vue.http.get(api.useraspergroups)
                    .then(response => { resolve(response); })
                    .catch(response => { reject(response); });
            })
        },

        setSelectedTicket:({commit}, data) => 
        { commit({ type: types.SET_SELECTED_TICKET, selectedTicket: data , 
                                                    selectedTicketType:data.ttype.ticket_type 
        
        }); },


          //-------------file------------
           //--------------edit------------
           addFile: ({dispatch}, formData) => 
           {   return new Promise((resolve, reject) => 
               {   Vue.http.post(api.addFile, formData, { headers: {'Content-Type': 'multipart/form-data'  } })
                   .then(response => { console.error('/store/cstkt--upload file success response.body=', response.body);
                                    resolve(response);   
                                })
                   .catch(response => { console.error('/store/cstkt--upload file fail response.body=', response.body);
                                    reject(response);
                                  });
               })
           },


    },




}
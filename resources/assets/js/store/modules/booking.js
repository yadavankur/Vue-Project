
import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

const PAGE_BOOKING = 'booking';

export default 
{   state: {  components: [],csticketinbooking:null,  },
    getters: {  allBookingComponents: state => state.components,    },
    mutations: 
    {   [types.NEW_SUPERVISOR_REQUEST_SUCCESS] (state, payload) { console.log('/store/booking.js-types.NEW_SUPERVISOR_REQUEST_SUCCESS payload=', payload);  },
        [types.NEW_SUPERVISOR_REQUEST_FAILURE] (state, payload) { console.log('/store/booking.js-types.NEW_SUPERVISOR_REQUEST_FAILURE payload=', payload);  },
        [types.CHANGE_SUPERVISOR_REQUEST_SUCCESS] (state, payload) { console.log('/store/booking.js-types.CHANGE_SUPERVISOR_REQUEST_SUCCESS payload=', payload);        },
        [types.CHANGE_SUPERVISOR_REQUEST_FAILURE] (state, payload) { console.log('/store/booking.js-types.CHANGE_SUPERVISOR_REQUEST_FAILURE payload=', payload);        },
        [types.UPDATE_DELIVERY_DATE_REQUEST_SUCCESS] (state, payload) { console.log('/store/booking.js-types.UPDATE_DELIVERY_DATE_REQUEST_SUCCESS payload=', payload);        },
        [types.UPDATE_DELIVERY_DATE_REQUEST_FAILURE] (state, payload) { console.log('/store/booking.js-types.UPDATE_DELIVERY_DATE_REQUEST_FAILURE payload=', payload);        },
        [types.SAVE_DOWELL_NOTES_REQUEST_SUCCESS] (state, payload) { console.log('/store/booking.js-types.SAVE_DOWELL_NOTES_REQUEST_SUCCESS payload=', payload);        },
        [types.SAVE_DOWELL_NOTES_REQUEST_FAILURE] (state, payload) { console.log('/store/booking.js-types.SAVE_DOWELL_NOTES_REQUEST_FAILURE payload=', payload);        },
        [types.SAVE_CUSTOMER_NOTES_REQUEST_SUCCESS] (state, payload) {console.log('/store/booking.js-types.SAVE_CUSTOMER_NOTES_REQUEST_SUCCESS payload=', payload);        },
        [types.SAVE_CUSTOMER_NOTES_REQUEST_FAILURE] (state, payload) {console.log('/store/booking.js-types.SAVE_CUSTOMER_NOTES_REQUEST_FAILURE payload=', payload);        },
        [types.SEND_EMAIL_REQUEST_SUCCESS] (state, payload) {console.log('/store/booking.js-types.SEND_EMAIL_REQUEST_SUCCESS payload=', payload);        },
        [types.SEND_EMAIL_REQUEST_FAILURE] (state, payload) { console.log('/store/booking.js-types.SEND_EMAIL_REQUEST_FAILURE payload=', payload);        },
        [types.ADD_ASSOCIATED_JOB_SUCCESS] (state, payload) { console.log('/store/booking.js-types.ADD_ASSOCIATED_JOB_SUCCESS payload=', payload);        },
        [types.ADD_ASSOCIATED_JOB_FAILURE] (state, payload) { console.log('/store/booking.js-types.ADD_ASSOCIATED_JOB_FAILURE payload=', payload);        },
        [types.UPDATE_ASSOCIATED_JOB_SUCCESS] (state, payload) { console.log('/store/booking.js-types.UPDATE_ASSOCIATED_JOB_SUCCESS payload=', payload);        },
        [types.UPDATE_ASSOCIATED_JOB_FAILURE] (state, payload) { console.log('/store/booking.js-types.UPDATE_ASSOCIATED_JOB_FAILURE payload=', payload);        },
        [types.DELETE_ASSOCIATED_JOB_SUCCESS] (state, payload) { console.log('/store/booking.js-types.DELETE_ASSOCIATED_JOB_SUCCESS payload=', payload);        },
        [types.DELETE_ASSOCIATED_JOB_FAILURE] (state, payload) { console.log('/store/booking.js-types.DELETE_ASSOCIATED_JOB_FAILURE payload=', payload);        },
        [types.SET_BOOKING] (state, payload) 
        {   console.log('/store/modules/booking.js-types.SET_BOOKING payload=', payload);
            console.log('/store/modules/booking.js-types.SET_BOOKING state=', state);
            state.items = payload.items;
        },
        [types.UNSET_BOOKING] (state, payload) 
        {   console.log('/store/modules/booking.js-types.UNSET_BOOKING payload=', payload);
            console.log('/store/modules/booking.js-types.UNSET_BOOKING state=', state);
            state.items = [];
        },
        [types.SET_BOOKING_COMPONENTS] (state, payload) 
        {   console.log('/store/modules/booking.js-types.SET_BOOKING_COMPONENTS payload=', payload.components);
            console.log('/store/modules/booking.js-types.SET_BOOKING_COMPONENTS state=', state);

            let componentsArray = payload.components;
            console.log('/store/modules/booking.js-types.SET_BOOKING_COMPONENTS componentsArray=', componentsArray);
            if (componentsArray.length > 0)
                state.components = JSON.parse(componentsArray);
        },
        [types.UNSET_BOOKING_COMPONENTS] (state, payload) 
        {   console.log('/store/modules/booking.js-types.UNSET_BOOKING_COMPONENTS payload=', payload);
            console.log('/store/modules/booking.js-types.UNSET_BOOKING_COMPONENTS state=', state);
            state.components = [];
        },
        [types.SET_CS_TICKET_IN_BOOKING] (state, payload) 
        {   console.log('/store/booking.js--SET_CS_TICKET_IN_BOOKING payload=', payload);
            console.log('/store/booking.js--SET_CS_TICKET_IN_BOOKING state=', state);
           // state.showcstPopup = payload.data.isShow;
            state.csticketinbooking = payload.csticketinbooking;
        },
    },
    actions: 
    {   getBookingData: ({commit, dispatch}, payload) => 
        {   console.log('/store/modules/booking.js-getBookingData');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentBookingData, {params: payload} )
                    .then(response => 
                        { console.log('/store/modules/booking.js-getBookingData get http success response.body=', response.body);
                        commit({ type: types.SET_BOOKING, data: response.body.data });
                        resolve(response);
                        })
                    .catch(error => { console.error('/store/modules/booking.js-getBooking error =', error);
                                    reject(error);
                                    });
            });
        },
        setBookingComponents: ({commit, dispatch}) => 
        {   console.log('/js/store/modules/booking.js-setBookingComponents');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentComponents, {params: {pageName: PAGE_BOOKING}} )
                    .then(response => 
                        { console.log('/store/modules/booking.js-setBookingComponents get http success response.body=', response.body);
                        commit({ type: types.SET_BOOKING_COMPONENTS, components: response.body.components  });
                        resolve(response);
                       })
                    .catch(error => { console.error('/store/modules/booking.js-setBookingComponents error =', error);
                                      reject(error);
                                    });
            });
        },
        unsetBookingComponents: ({commit}) => { commit({ type: types.UNSET_BOOKING_COMPONENTS }); },
        addAssociatedJobRequest: ({dispatch}, formData) => 
        {   console.log('/js/store/modules/booking.js-addAssociatedJobRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.addAssociatedJob, formData)
                    .then(response => { dispatch('addAssociatedJobSuccess', response.body);  resolve(response); })
                    .catch(response => 
                        {  console.error('/js/store/modules/booking.js-addAssociatedJobRequest response.body=', response.body);
                           dispatch('addAssociatedJobFailure', response.body);
                           reject(response);
                        });
            })
        },
        addAssociatedJobSuccess: ({commit, dispatch}, body) => 
        {   commit({    type: types.ADD_ASSOCIATED_JOB_SUCCESS, associatedJob: body.associatedJob  });
            dispatch('showSuccessNotification', 'AssociatedJob has been added.');
        },
        addAssociatedJobFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.ADD_ASSOCIATED_JOB_FAILURE,  errors: body  });
            console.error('/store/modules/booking.js-addAssociatedJobFailure body.error=', body.error);
            if(body.error) { dispatch('showErrorNotification', body.error); }
        },

        updateAssociatedJobRequest: ({dispatch}, formData) => 
        {   console.log('/store/modules/booking.js-updateAssociatedJobRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.updateAssociatedJob, formData)
                    .then(response => { dispatch('updateAssociatedJobSuccess', response.body); resolve(response);   })
                    .catch(response => { dispatch('updateAssociatedJobFailure', response.body); reject(response);   });
            })
        },
        updateAssociatedJobSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.UPDATE_ASSOCIATED_JOB_SUCCESS, associatedJob: body.associatedJob });
            dispatch('showSuccessNotification', 'The order has been confirmed.');
        },
        updateAssociatedJobFailure: ({commit, dispatch}, body) => 
        {  commit({ type: types.UPDATE_ASSOCIATED_JOB_FAILURE, errors: body });
           if(body.error) {  dispatch('showErrorNotification', body.error);}
        },

        deleteAssociatedJobRequest: ({dispatch}, formData) => 
        {  console.log('/store/modules/booking.js-deleteAssociatedJobRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {  Vue.http.post(api.deleteAssociatedJob, formData)
                    .then(response => { dispatch('deleteAssociatedJobSuccess', response.body); resolve(response); })
                    .catch(response => { dispatch('deleteAssociatedJobFailure', response.body); reject(response);         });
            })
        },
        deleteAssociatedJobSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.DELETE_ASSOCIATED_JOB_SUCCESS,associatedJob: body.associatedJob });
            dispatch('showSuccessNotification', 'AssociatedJob has been deleted.');
        },
        deleteAssociatedJobFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.DELETE_ASSOCIATED_JOB_FAILURE,errors: body });
            if(body.error) {dispatch('showErrorNotification', body.error); }
        },
        sendEmailRequest: ({dispatch}, formData) => 
        {   console.log('/store/booking.js-sendEmailRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.sendEmailRequest, formData)
                    .then(response => { dispatch('sendEmailRequestSuccess', response.body); resolve(response); })
                    .catch(response => 
                        { console.error('/store/booking.js-sendEmailRequest response.body=', response.body);
                        dispatch('sendEmailRequestFailure', response.body);
                        reject(response);
                        });
            })
        },
        sendEmailRequestSuccess: ({commit, dispatch}, body) => 
        {  commit({type: types.SEND_EMAIL_REQUEST_SUCCESS, });
            dispatch('showSuccessNotification', 'The email has been successfully queued.');
        },
        sendEmailRequestFailure: ({commit, dispatch}, body) =>
        {  commit({ type: types.SEND_EMAIL_REQUEST_FAILURE, errors: body
            });
            if(body.error) { dispatch('showErrorNotification', body.error);    }
        },
        saveCustomerNotesRequest: ({dispatch}, formData) => 
        {   console.log('/store/booking.js-saveCustomerNotesRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.saveCustomerNotesRequest, formData)
                    .then(response => { dispatch('saveCustomerNotesRequestSuccess', response.body); resolve(response);  })
                    .catch(response => 
                        {  console.error('/store/modules/booking.js-saveCustomerNotesRequest response.body=', response.body);
                        dispatch('saveCustomerNotesRequestFailure', response.body);
                        reject(response);
                      });
            })
        },
        saveCustomerNotesRequestSuccess: ({commit, dispatch}, body) => 
        {  commit({ type: types.SAVE_CUSTOMER_NOTES_REQUEST_SUCCESS,  });
            dispatch('showSuccessNotification', 'The customer notes has been successfully saved.');
        },
        saveCustomerNotesRequestFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.SAVE_CUSTOMER_NOTES_REQUEST_FAILURE,  errors: body });
            if(body.error) { dispatch('showErrorNotification', body.error); }
        },

        saveDowellNotesRequest: ({dispatch}, formData) => 
        {   console.log('/store/modules/booking.js-saveDowellNotesRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.saveDowellNotesRequest, formData)
                    .then(response => { dispatch('saveDowellNotesRequestSuccess', response.body); resolve(response); })
                    .catch(response => 
                        { console.error('/store/modules/booking.js-saveDowellNotesRequest response.body=', response.body);
                          dispatch('saveDowellNotesRequestFailure', response.body);
                         reject(response);
                       });
            })
        },
        saveDowellNotesRequestSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.SAVE_DOWELL_NOTES_REQUEST_SUCCESS,   });
            dispatch('showSuccessNotification', 'The dowell notes has been successfully saved.');
        },
        saveDowellNotesRequestFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.SAVE_DOWELL_NOTES_REQUEST_FAILURE, errors: body  });
            if(body.error) { dispatch('showErrorNotification', body.error); }
        },
        getLatestNotesRequest: ({dispatch}, payload) => 
        {   console.log('/store/modules/booking.js-getLatestNotesRequest');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentLatestNotes, {params: payload} )
                    .then(response => 
                        { console.log('/store/modules/booking.js-getLatestNotesRequest get http success response.body=', response.body);
                        resolve(response);
                        })
                    .catch(error => {console.error('/store/modules/booking.js-getLatestNotesRequest error =', error);
                                     reject(error);
                                    });
            });
        },

        updateDeliveryDateRequest: ({dispatch}, formData) => 
        {   console.log('/store/modules/booking.js-updateDeliveryDateRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {  Vue.http.post(api.updateDeliveryDateRequest, formData)
                    .then(response => {dispatch('updateDeliveryDateRequestSuccess', response.body); resolve(response); })
                    .catch(response => {
                        console.error('/store/modules/booking.js-updateDeliveryDateRequestSuccess response.body=', response.body);
                        dispatch('updateDeliveryDateRequestFailure', response.body);
                        reject(response);
                    });
            })
        },
        updateDeliveryDateRequestSuccess: ({commit, dispatch}, body) => 
        {  commit({ type: types.UPDATE_DELIVERY_DATE_REQUEST_SUCCESS,  });
            dispatch('showSuccessNotification', 'The requested date has been successfully updated.');
        },
        updateDeliveryDateRequestFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.UPDATE_DELIVERY_DATE_REQUEST_FAILURE, errors: body });
            if(body.error) { dispatch('showErrorNotification', body.error); }
        },
        getBookingInformation: ({dispatch}, payload) => 
        {  console.log('/store/modules/booking.js-getBookingInformation');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.currentBookingInfo, {params: payload} )
                    .then(response => 
                        {  console.log('/store/modules/booking.js-getBookingInformation get http success response.body=', response.body);
                           resolve(response);
                        })
                    .catch(error => { console.error('/store/modules/booking.js-getBookingInformation error =', error);
                                      reject(error);
                                    });
            });
        },
        getOrderConfirmationEmailTemplate: ({dispatch}, payload) => 
        {   console.log('/store/modules/booking.js-getOrderConfirmationEmailTemplate');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.getOrderConfirmationEmailTemplate, {params: payload} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/booking.js-getOrderConfirmationEmailTemplate get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/booking.js-getOrderConfirmationEmailTemplate error =', error);
                        reject(error);
                    });
            });
        },
        getSingleConfirmationEmailTemplate: ({dispatch}, payload) => {
            console.log('getSingleConfirmationEmailTemplate');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.getSingleConfirmationEmailTemplate, {params: payload} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/booking.js-getSingleConfirmationEmailTemplate get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/booking.js-getSingleConfirmationEmailTemplate error =', error);
                        reject(error);
                    });
            });
        },
        getConfirmationEmailTemplate: ({dispatch}, payload) => {
            console.log('/resources/assets/js/store/modules/booking.js-getConfirmationEmailTemplate');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.getConfirmationEmailTemplate, {params: payload} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/booking.js-getConfirmationEmailTemplate get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/booking.js-getConfirmationEmailTemplate error =', error);
                        reject(error);
                    });
            });
        },
        getSingleConfirmationTextTemplate: ({dispatch}, payload) => {
            console.log('/resources/assets/js/store/modules/booking.js-getSingleConfirmationTextTemplate');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.getSingleConfirmationTextTemplate, {params: payload} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/booking.js-getSingleConfirmationTextTemplate get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/booking.js-getSingleConfirmationTextTemplate error =', error);
                        reject(error);
                    });
            });
        },
        getExpediteEnquiryTemplate: ({dispatch}, payload) => {
            console.log('/resources/assets/js/store/modules/booking.js-getExpediteEnquiryTemplate');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.getExpediteEnquiryTemplate, {params: payload} )
                    .then(response => {
                        console.log('/resources/assets/js/store/modules/booking.js-getExpediteEnquiryTemplate get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('/resources/assets/js/store/modules/booking.js-getExpediteEnquiryTemplate error =', error);
                        reject(error);
                    });
            });
        },
        getOrderDetailOnSearch: ({commit,dispatch}, payload) => 
        {   console.log('/store/modules/booking.js-getOrderDetailOnSearch');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.getOrderDetailOnSearch, {params: payload}, /* {responseType: 'arraybuffer'} */ )
                    .then(response => {   console.log('/store/modules/booking.js-getOrderDetailOnSearch get http success response.body=', response.body);

                                          commit({type: types.SET_CS_TICKET_IN_BOOKING, csticketinbooking: response.body });
                                          resolve(response);
                                         
                                      })
                    .catch(error => {    console.error('/store/modules/booking.js-getOrderDetailOnSearch error =', error);
                                          reject(error);
                                    });
            });
        },
        getOrderAttachments: ({dispatch}, payload) => 
        {   console.log('/store/modules/booking.js-getOrderAttachments');
            return new Promise((resolve, reject) => 
            {  Vue.http.get(api.getOrderAttachments, {params: payload} )
                    .then(response => { console.log('/store/modules/booking.js-getOrderAttachments get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {  console.error('/store/modules/booking.js-getOrderAttachments error =', error);
                        reject(error);
                    });
            });
        },
        downloadAttachment: ({dispatch}, payload) => 
        {   console.log('/resources/assets/js/store/modules/booking.js-downloadAttachment');
            return new Promise((resolve, reject) => 
            {   Vue.http.get(api.downloadAttachment, {params: payload}, {responseType: 'arraybuffer'}  )
                    .then(response => { console.log('/store/modules/booking.js-downloadAttachment get http success');
                        resolve(response);
                    })
                    .catch(error => {console.error('/store/modules/booking.js-downloadAttachment error =', error);
                        reject(error);
                    });
            });
        },
        getSystemGeneratedDeliveryDate: ({dispatch}, payload) => 
        {   console.log('/js/store/modules/booking.js-getSystemGeneratedDeliveryDate');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.getSystemGeneratedDeliveryDate, {params: payload, showLoadingBar: false })
                    .then(response => {  console.log('/js/store/modules/booking.js-getSystemGeneratedDeliveryDate get http success');
                                           resolve(response);
                                    })
                    .catch(error => { console.error('/store/modules/booking.js-getSystemGeneratedDeliveryDate error =', error);
                                      reject(error);
                                    });
            });
        },
        changeSupervisorRequest: ({dispatch}, formData) => 
        {   console.log('/store/modules/booking.js-changeSupervisorRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.changeSupervisorRequest, formData)
                    .then(response => {  dispatch('changeSupervisorRequestSuccess', response.body);
                                         resolve(response);
                                      })
                    .catch(response => { console.error('/store/modules/booking.js-changeSupervisorRequest response.body=', response.body);
                                         dispatch('changeSupervisorRequestFailure', response.body);
                                         reject(response);
                                       });
            })
        },
        changeSupervisorRequestSuccess: ({commit, dispatch}, body) => 
        {   commit({ type: types.CHANGE_SUPERVISOR_REQUEST_SUCCESS,});
            dispatch('showSuccessNotification', 'The supervisor has been successfully changed.');
        },
        changeSupervisorRequestFailure: ({commit, dispatch}, body) => 
        {   commit({ type: types.CHANGE_SUPERVISOR_REQUEST_FAILURE, errors: body });
            if(body.error) { dispatch('showErrorNotification', body.error); }
        },
        newSupervisorRequest: ({dispatch}, formData) => 
        {   console.log('/store/modules/booking.js-newSupervisorRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.newSupervisorRequest, formData)
                    .then(response => { dispatch('newSupervisorRequestSuccess', response.body);  resolve(response);  })
                    .catch(response => { console.error('/store/modules/booking.js-newSupervisorRequest response.body=', response.body);
                                         dispatch('newSupervisorRequestFailure', response.body);
                                         reject(response);
                                       });
            })
        },
        newSupervisorRequestSuccess: ({commit, dispatch}, body) => 
        {  commit({ type: types.NEW_SUPERVISOR_REQUEST_SUCCESS, });
            dispatch('showSuccessNotification', 'The supervisor has been successfully created.');
        },
        newSupervisorRequestFailure: ({commit, dispatch}, body) => 
        {   commit({  type: types.NEW_SUPERVISOR_REQUEST_FAILURE, errors: body   });
            if(body.error) { dispatch('showErrorNotification', body.error);}
        },
        changeAddressRequest: ({dispatch}, formData) => 
        {   console.log('/store/modules/booking.js-changeAddressRequest formData=', formData);
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.changeAddressRequest, formData)
                    .then(response => {  dispatch('showSuccessNotification', 'The delivery address has been successfully changed.');
                                         resolve(response);
                                     })
                    .catch(response => { console.error('/store/modules/booking.js-changeAddressRequest response.body=', response.body);
                                         dispatch('showErrorNotification', response.body.error);
                                        reject(response);
                                      });
            })
        },
    }
}
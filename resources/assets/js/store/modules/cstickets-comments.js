import Vue from 'vue';
import * as api from './../../config';
import * as types from './../../mutation-types';

export default 
{ state: { ticketstatustable: null,  //--------for display---GET_TICKET_STATUS_TABLE]
           showPopup: false, //--add 4---goes to CsStatusCrudModal.vue---form field on top--<custom-modal :value="showPopup" @cancel="onClose" effect="fade">
           //modalForm: {  state: null, }, //---not used
           statusData: null, //-------add-5-//--edit6----goes to CsStatusCrudModal.vue--in computed--mapstate
         }, 
            
getters: {    //--------for display---GET_TICKET_STATUS_TABLE]
         },  
mutations: 
     {

    
    },

    actions: 
    {  
        getLatestcsComments: ({dispatch}, payload) => 
        {   console.log('/store/cstktcomments.js-getLatestcscomments');
            return new Promise((resolve, reject) => 
            {   Vue.http.post(api.latestcscomments, {params: payload} )
                    .then(response => 
                        { console.log('/store/cstktcomments.js-getLatestcscomments response.body=', response.body);
                        resolve(response);
                        })
                    .catch(error => {console.error('/store/cstktcomments.js-getLatestcscomments error =', error);
                                     reject(error);
                                    });
            });
        },
    }

}
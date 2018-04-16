<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#csticketinfoo"> Ticket No: {{ selectedTicket1.ticket_no  }} [ Type 2A - Rectification Report ] </a>
              <span class="pull-right"> <button class="btn btn-success btn-sm" @click.prevent="onClickNew">NEW</button> </span>
            </div>
            <div id="csticketinfoo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <thead>
                  <tr> <th>CN No</th><th>Price </th><th>comments</th> <th>Updated_by </th><th>Updated_at </th><th>Actions</th></tr>
                </thead>
                <tbody>
                      <tr><td>ID</td><td colspan="2"> {{csticket? csticket.id : ''}}</td></tr>
                       <tr><td>ticket no</td><td colspan="2"> {{ selectedTicket1 ? selectedTicket1.ticket_no  : '' }}</td></tr>
                 
                    
                      <tr><td>Customer: NAME&CODE</td>
                        <td>{{ csticket  ? csticket.comment : '' }}</td>
                        <td>{{ selectedTicket1 ? selectedTicket1.ticket_no  : '' }}</td>
                      </tr>
                    
                 
                  
                    <td class="center">

                </td>

                  </tr>
                </tbody>
              </table>
            </div>          
        </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import { mapGetters, mapState} from 'vuex'
import PermissionCustomActions from './Type2A/CsTicketType2ACustomActions.vue'



export default 
{    computed: 
        {  ...mapGetters({    }),
           ...mapState({  
               user: state => state.authUser,
                            selectedTicketttype1: state => state.cstkt.selectedTicket.ttype2a,
                            csType1perTicket: state => state.cstickettype.csType2AperTicket,
                            selectedTicket: state => state.cstkt.selectedTicket,
                          
                            
                 }),
                 selectedTicket1() { return this.selectedTicket; },
          csticket() {   if (this.csType1perTicket )  
                          { console.log('type1-add this.csType1perTicket=',this.csType1perTicket); 
                            console.log('type1-add this.selectedTicketttype1=',this.selectedTicketttype1); 
                            console.log('type1-add this.csType1perTicket[0].ttype1=',this.csType1perTicket[0].ttype2a);
                           // console.log('type1-add this.csType1perTicket[Object.keys(this.csType1perTicket)[0]].ticket_no=', this.csType1perTicket[Object.keys(this.csType1perTicket)[0]].ticket_no); 
                         //   console.log('type1-add this.selectedTicket.ticket_no=',this.selectedTicket.ticket_no);
                         //   console.log('type1-add this.csType1perTicket=',this.csType1perTicket.ttype1); 
                         //   console.log('type1-Object.keys(this.csType1perTicket.ttype1)=',Object.keys(this.csType1perTicket.ttype1)); 
                         //   Object.keys(this.csType1perTicket)
                           // console.log('type1-add this.csType1perTicket[0].ticket_no=',this.csType1perTicket[0].ticket_no);
                          //  if(this.csType1perTicket[Object.keys(this.csType1perTicket)[0]].ticket_no == this.selectedTicket.ticket_no)
                           if(this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no)
                             {  // var csType1perTicket1 =  Object.keys(this.csType1perTicket).map(function(key){ return [Number(key), this.csType1perTicket[key]];  });
                                  console.log('new type1 data present- this.csType1perTicket[0].ttype1', this.csType1perTicket[0].ttype2a);
                                  
                                 // return this.csType1perTicket;
                                   return this.csType1perTicket[0].ttype2a;
                              }
                              else  return this.selectedTicketttype1;

                            
                              
                          } 
                      else if (this.selectedTicketttype1)  
                        { 
                            console.log('type1-fromdb=',this.selectedTicketttype1); 
                              return this.selectedTicketttype1;
                         } else return null; 
           
               },


        },

    created() {   console.log('cs/cstickettype1.vue-in created-.this.csticket', this.selectedTicket); 
             //   this.$store.dispatch('setCsTicketType1ShowModal', payload)
              //   .then((response) => {  console.log(' response');    })
                   //     .catch((error) => {console.log('save error');});
              },
    data () {  return {    }   },
    components: {'csticket-type1-actions': PermissionCustomActions, },
    updated() {   console.log('cs/cstickettype1.vue-in updated-.this.selectedorder', this.selectedTicketttype1); 
                  
              },
    methods: 
          { onClickNew() 
              { console.log('cs/cstickettype1.vue-onClickNew');
               // this.$store.dispatch('getLastTicket');
                let formData = {   ticket_no: '',ticket_type_id: '',QUOTE_ID: '',ORDER_ID: '',
                                   location_id: '',
                                   name: '',  title: '',  id: ''
                               };
                let payload = { isShow: true, data: {action: 'Add', data: formData,index: 0} };
                this.$store.dispatch('setCsTicketType2AShowModal', payload)  //----triggers this in store--with empty data and opens new popup for adding
              },
              onActions(data)
              { console.log('cs/cstickettype1.vue--onActions data=', data);
                let payload = { isShow: true, data: data   };
                if (data.action === 'Delete')
                {
                    //
                    let swal = this.$swal;
                    let me = this;
                    this.$swal({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this credit note!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'cancel',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        allowOutsideClick: false
                    }).then(function() {
                        me.$store.dispatch('deletetype1', data.data)
                            .then((response) => {})
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
               this.$store.dispatch('setCsTicketType1ShowModal', payload) 
            }
          },

          watch: 
          {   
          }
}
</script>

<style scoped>
td.center {  text-align: center !important; }
</style>
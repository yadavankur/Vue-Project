<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#csticketinfoo">TYPE9: Rectification required by Dowell</a>
              <span class="pull-right"> <button class="btn btn-success btn-sm" @click.prevent="onClickNew">NEW</button> </span>
            </div>
            <div id="csticketinfoo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <thead>
                  <tr> <th>QTE_POS</th><th>QUANTITY </th> <th>FRA_CODE</th> <th>FINCOL_CODE</th><th>Glass </th></tr>
                </thead>
                <tbody>
                <tr v-for="csticket in csticket">
                <td> {{ csticket.QTE_POS }} </td>
                <td> {{ csticket.QUANTITY }} </td>
                <td> {{ csticket.FRA_CODE }} </td>
                <td> {{ csticket.FINCOL_CODE }} </td>
                <td> {{ csticket.Glass }} </td>
                <td> {{ csticket.PRESSURE_CODE }} </td>
                <td> {{ csticket.POM }} </td>
                <td> {{ csticket.BAG_CODE }} </td>

                <td> {{ csticket.HEIGHT }} </td>
                <td> {{ csticket.WIDTH }} </td>
                <td> {{ csticket.CUST_REF }} </td>
                
               
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


export default 
{   computed: 
       {  ...mapGetters({    }),
          ...mapState({   user: state => state.authUser,
                         // selectedOrder: state => state.tab.selectedOrder,
                          selectedOrder: state => state.cstkt.selectedTicket.v6items,
                          //csTicketdata: state => state.csticket.csTicketdata,
                          csTicketperQuote: state => state.csticket.csTicketperQuote,
                      }),
          order()   {  if (this.selectedOrder)  return this.selectedOrder;  else return null;    }, 
          csticket()   {  if (this.selectedOrder)  return this.selectedOrder;  else return null;    }, 
         //  csticket()   {  if (this.csTicketdata)  return this.csTicketdata;  else return null;    }, 

       },

    created() {   },
    data () {  return {    }   },
    components: {    },
    updated() {    //console.log('Booking/OrderList/CsTicektInfo.vue-updated.this.selectedorder', this.selectedOrder.cs_ticketss); 
                   //console.log('Booking/OrderList/CsTicektInfo.vue-updated.this.selectedorder', this.selectedOrder.cs_ticketss[0].QUOTE_ID); 
                  //console.log('Booking/OrderList/CsTicektInfo.vue-csTicketdata.', this.csTicketdata);
                 // console.log('Booking/OrderList/CsTicektInfo.vue-csTicketdata.', this.csTicketdata[1].QUOTE_ID);
                 //console.log('Booking/OrderList/CsTicektInfo.vue-csticketdata1.',csticketdata1); 
              },
    methods: 
          { onClickNew() 
              { console.log('Booking/OrderList/CsTicektInfo.vue-onClickNew');
               // this.$store.dispatch('getLastTicket');
                let formData = {   ticket_no: '',ticket_type_id: '',QUOTE_ID: '',ORDER_ID: '',
                                   location_id: '',
                                   name: '',  title: '',  id: ''
                               };
                let payload = { isShow: true, data: {action: 'Add', data: formData} };
                this.$store.dispatch('setCsTicketShowModal', payload)  //----triggers this in store--with empty data and opens new popup for adding
              },
          }
}
</script>

<style scoped>
td.center {  text-align: center !important; }
</style>
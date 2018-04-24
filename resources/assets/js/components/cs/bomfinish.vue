<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#bomfinish">
                  BoM Finish
              </a>              
            </div>
            <div id="bomfinish" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <thead>
                  <tr> <th>ItemNo</th> <th>Finish</th> <th>Qty</th><th>Len</th><th>Descr</th><th>Col-Len</th></tr>
                </thead>
                <tbody>
                  <tr v-for="orderHisTran in orderHistoryTrans">
                   <td> {{ orderHisTran.QTE_POS }} </td>
                     <td> {{ orderHisTran.FINCOL_CODE }} </td>
                     <td> {{ orderHisTran.PIECE_COUNT }} </td>
                     <td> {{ orderHisTran.PIECE_LENGTH }} </td>
                   
                    <td> {{ orderHisTran.DESCR }} </td>
                      <td> {{ orderHisTran.EXTN_REF }} </td>
                   
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
        {   ...mapGetters({    }),
            ...mapState({   user: state => state.authUser,
              selectedOrder: state => state.cstkt.selectedTicket.bomfinish,
                            details: state => state.orderDetails.details,
                       }),
            order() {  if (this.selectedOrder)  return this.selectedOrder;  else return null;    }, 
            //order() {   if (this.selectedOrder)   return this.selectedOrder;   else return null;    },
            orderHistoryTrans() 
                {   if (this.selectedOrder) return this.selectedOrder; else return null;
                    
                    //if (this.details && this.details.order && this.details.order.activity_logs)
                    //return this.details.order.activity_logs;
                    //else return null;
                }
        },
    created() {   },
    data () {  return {    }   },
    components: {    }
}
</script>

<style scoped>

td.center {
  text-align: center !important;
}
</style>
<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#bomcomponents">
                  BoM Components
              </a>              
            </div>
            <div id="bomcomponents" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <thead>
                  <tr> <th>ItemNo</th> <th>DESCR</th> <th>Part Code</th><th>Qty</th><th>Unit</th><th>FinCol Code</th></tr>
                </thead>
                <tbody>
                  <tr v-for="orderHisTran in orderHistoryTrans">
                   <td> {{ orderHisTran.QTE_POS }} </td>
                   <td> {{ orderHisTran.DESCR }} </td>
                     <td> {{ orderHisTran.PART_CODE }} </td>
                     <td> {{ Number(Math.round(orderHisTran.COMP_QTY + 'e2')+'e-2') }}</td>
                     
                     <td> {{ orderHisTran.UNIT }} </td>
                     <td> {{ orderHisTran.FINCOL_CODE }} </td>
                    
                   
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
              selectedOrder: state => state.cstkt.selectedTicket.bomcomponent,
                            details: state => state.orderDetails.details,
                       }),
            order() {  if (this.selectedOrder)  return this.selectedOrder;  else return null;    }, 
         
            orderHistoryTrans() 
                {   if (this.selectedOrder) return this.selectedOrder; else return null;
                    
       
                }
        },
    created() {   },
    data () {  return {    }   },
    components: {    },
        methods: 
    {
       round(value, decimals) { return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);  }
    }
}
</script>

<style scoped>

td.center {
  text-align: center !important;
}
</style>
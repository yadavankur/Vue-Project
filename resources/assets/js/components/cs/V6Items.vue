<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#v6items">
                  Items
              </a>              
            </div>
            <div id="v6items" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <thead>
                  <tr> <th>ItemNo</th> <th>Qty</th> <th>Pdct</th><th>Fincol</th><th>Glass</th><th>KPA</th>
                       <th>PoM</th> <th>Reveal</th><th>Ht.</th><th>Wid.</th><th>Descr</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="orderHisTran in orderHistoryTrans">
                   <td> {{ orderHisTran.QTE_POS }} </td>
                     <td> {{ Number(Math.round(orderHisTran.QUANTITY + 'e2')+'e-2') }} </td>
                     <td> {{ orderHisTran.FRA_CODE }} </td>
                     <td> {{ orderHisTran.FINCOL_CODE }} </td>
                    <td> {{ orderHisTran.Glass }} </td>
                     <td> {{ orderHisTran.PRESSURE_CODE }} </td>
                     <td> {{ orderHisTran.POM }} </td>
                      <td> {{ orderHisTran.BAG_CODE }} </td>
                       <td> {{ orderHisTran.HEIGHT }} </td>
                        <td> {{ orderHisTran.WIDTH }} </td>
                         <td> {{ orderHisTran.CUST_REF }} </td>
                                   
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
              selectedOrder: state => state.cstkt.selectedTicket.v6items,
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
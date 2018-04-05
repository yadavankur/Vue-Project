<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#orderhistoryinfo">
                  Order History Summary
              </a>              
            </div>
            <div id="orderhistoryinfo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <thead>
                  <tr> <th>Date Time Stamp</th> <th>Activity</th> <th>Comments</th><th>User</th></tr>
                </thead>
                <tbody>
                  <tr v-for="orderHisTran in orderHistoryTrans">
                    <td> {{ formatDatetime(orderHisTran.updated_at) }}</td>
                    <td> {{ orderHisTran.activity }} </td>
                    <td> {{ orderHisTran.comment }} </td>
                    <td> {{ orderHisTran.updated_by? orderHisTran.updated_by.name: '' }}
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

export default 
{   computed: 
        {   ...mapGetters({    }),
            ...mapState({   user: state => state.authUser,
              selectedOrder: state => state.cstkt.selectedTicket.v6quotee,
                            details: state => state.orderDetails.details,
                       }),
            order() {  if (this.selectedOrder)  return this.selectedOrder;  else return null;    }, 
            //order() {   if (this.selectedOrder)   return this.selectedOrder;   else return null;    },
            orderHistoryTrans() 
                {   if (this.selectedOrder) return this.selectedOrder.activity_logs; else return null;
                    
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
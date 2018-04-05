<template>
  <div id="root-order-detail">
    <div class="main">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <order-info v-if="orderinfo.permission !== 'D' && orderinfo.permission !== 'H'"
                        :user="user"
                        :selectedOrder="selectedOrder"
                        @refreshOrderDetail="refreshOrderDetail"
                        :details="details"
            >
            </order-info>
            <credit-info v-if="creditinfo.permission !== 'D' && creditinfo.permission !== 'H'"></credit-info>
            <cash-sale-details v-if="cashsaledetails.permission !== 'D' && cashsaledetails.permission !== 'H'"></cash-sale-details>
            <quote-info v-if="quoteinfo.permission !== 'D' && quoteinfo.permission !== 'H'"></quote-info>
          </div>
          <div aside class="col-md-6">
            <order-history-summary v-if="orderhistorysummary.permission !== 'D' && orderhistorysummary.permission !== 'H'"></order-history-summary>
          </div>
        </div>
      </div>  
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import { mapGetters, mapState, mapActions} from 'vuex'
import OrderInfo from './OrderInfo.vue'
import OrderHistorySummary from './OrderHistorySummary.vue'
import CreditInfo from './CreditInfo.vue'
import CashSaleDetails from './CashSaleDetails.vue'
import QuoteInfo from './QuoteInfo.vue'

export default 
{  computed: 
        { ...mapGetters({   }),
          ...mapState(
              {    user: state => state.authUser,
                   selectedOrder: state => state.tab.selectedOrder,
                   details: state => state.orderDetails.details,
                   OrderInfo: state => state.orderDetails.components.OrderInfo,
                   CreditInfo: state => state.orderDetails.components.CreditInfo,
                   QuoteInfo: state => state.orderDetails.components.QuoteInfo,
                   CashSaleDetails: state => state.orderDetails.components.CashSaleDetails,
                   OrderHistorySummary: state => state.orderDetails.components.OrderHistorySummary,
             }),
          creditinfo() {  console.log('/OrderDetails.vue--creInfo = CreditInfo=', this.CreditInfo);
                         return this.parseJsObject(this.CreditInfo);
                      },
          orderinfo() {  console.log('/OrderDetails.vue--orderinfo = orderinfo=', this.OrderInfo);
                        return this.parseJsObject(this.OrderInfo);
                     },
           cashsaledetails() { console.log('/OrderDetails.vue--cashsaledetails = cashsaledetails=', this.CashSaleDetails);
                          return this.parseJsObject(this.CashSaleDetails);
                        },
            quoteinfo() {  console.log('/OrderDetails.vue--quoteinfo = quoteinfo=', this.QuoteInfo);
                     return this.parseJsObject(this.QuoteInfo);
                  },
            orderhistorysummary() {  console.log('/OrderDetails.vue--orderhistorysummary = orderhistorysummary=', this.OrderHistorySummary);
                              return this.parseJsObject(this.OrderHistorySummary);
                            },
        }, //compted finish
     data () { return {   } },
     created() 
       {  console.log('/OrderDetails.vue--vue created!');
          if (!this.selectedOrder)
               {this.$router.push({name: 'notallowed'});  return;  }
           this.$store.dispatch('setOrderDetailComponents')
           .then((response) => 
              { console.log('/OrderDetails.vue--created response=', response);
                 let payload = {
                  orderNo: this.selectedOrder.UDF1,
                  quoteId: this.selectedOrder.QUOTE_ID,
                  location: this.selectedOrder.QUOTE_NUM_PREF,
                  };
                 this.$store.dispatch('getOrderDetail', payload)
                  .then((response) => {  console.log('/OrderDetails.vue--response=', response);  })
                  .catch((error) => {     });
              })
          .catch((error) => { console.log('/OrderDetails.vue--created error=', error);    });
        },
      mounted() {  console.log('/OrderDetails.vue-- mounted components=', this.components);},
  components: {     'order-info': OrderInfo,
                    'order-history-summary': OrderHistorySummary,
                    'credit-info': CreditInfo,
                    'cash-sale-details': CashSaleDetails,
                    'quote-info': QuoteInfo,
              },
  methods: {   refreshOrderDetail(payload)
                 {  console.log('/OrderDetails.vue-- refreshOrderDetail payload=', payload);
                    this.onSearch(payload);
                 },
              onSearch(payload) 
                {  console.log('/OrderDetails.vue--onSearch payload=', payload);
                   this.$store.dispatch('getOrderDetailOnSearch', payload)
                   .then((response) => 
                   {  console.log('/OrderDetails.vue-- getOrderDetailOnSearch response=', response);
                        if (this.isEmpty(response.body))
                         {   this.$store.dispatch('setSelectedOrder', null);      }
                         else
                         {   this.$store.dispatch('setSelectedOrder', response.body);
                          let payload = {
                            orderNo: this.selectedOrder.UDF1,
                            quoteId: this.selectedOrder.QUOTE_ID,
                            location: this.selectedOrder.QUOTE_NUM_PREF,
                            };
                            this.$store.dispatch('getOrderDetail', payload)
                            }
                    })
              .catch((error) => {
                      console.log('/OrderDetails.vue-- getOrderDetailOnSearch error=', error);
                      this.$store.dispatch('setSelectedOrder', null);
              })
      }
  }
}
</script>

<style scoped>
</style>

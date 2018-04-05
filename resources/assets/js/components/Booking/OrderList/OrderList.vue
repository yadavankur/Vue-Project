<template>
    <div id="root-order-list">
        <div class="main"> <div class="container">
             <csticketcrud></csticketcrud>
            <div class="row">
                    <div class="col-md-12"> <order-list-search></order-list-search> </div>
                    <div class="col-md-12"> <order-list-result></order-list-result> </div>
                    <div class="col-md-6" v-if="selectedOrder">
                       <order-info></order-info>
                       <credit-info></credit-info>
                       <cash-sale-details ></cash-sale-details>
                       <quote-info></quote-info>
                       <order-history-summary></order-history-summary>
                    </div>
                    <div class="col-md-6" v-if="selectedOrder">
                        <csticketinfo></csticketinfo>
                    </div>
                </div></div></div></div>
</template>
<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import OrderListSearch from './OrderListSearchSection.vue'
    import OrderListResult from './OrderListResultSection.vue'
    import Orderinfo from './OrderInfo.vue'
    import OrderHistorySummary from './OrderHistorySummary.vue'
    import CreditInfo from './CreditInfo.vue'
    import CashSaleDetails from './CashSaleDetails.vue'
    import QuoteInfo from './QuoteInfo.vue'
    import CSticketinfo from './CsTicketInfo.vue'
    import CsTicketCrud from './CsTicketCrud.vue'

 //Vue.component('csticket-actions', CsTicketCustomActions);
    export default 
    {   beforeCreate() { console.log('/components/booking/orderlist/OrderList.vue-beforecreate!')  },
        computed:  {   ...mapGetters({   }),  
                       ...mapState({ user: state => state.authUser, 
                                     selectedOrder: state => state.tab.selectedOrder,
                                     }),
                    },
        data () {  return {     } },
       created() 
             { console.log('/booking/orderlist/OrderList.vue- created!');
               this.$store.dispatch('setSelectedOrder', null); 
             },
        mounted() { console.log('/booking/orderlist/OrderList.vue- mounted'); },
        components: { 'order-list-search': OrderListSearch, 
                      'order-list-result': OrderListResult,
                      'order-info': Orderinfo,   
                      'order-history-summary': OrderHistorySummary,
                      'credit-info': CreditInfo,
                      'cash-sale-details': CashSaleDetails,
                      'quote-info': QuoteInfo,
                      'csticketinfo': CSticketinfo,
                      'csticketcrud': CsTicketCrud,
                    },
        methods: {  }
    }
</script>
<style scoped>
   
</style>

<template>
  <div id="root-cash-sale-details">
    <div class="app-row">
        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#cashsaleinfo">
                  {{ title }}
              </a>              
            </div>
            <div id="cashsaleinfo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <tbody>
                  <tr>
                    <td>Total Amount
                    </td>
                    <td> {{ order? formatPrice(order.TOTALAMOUNT) : '' }}
                    </td>
                  </tr>
                  <tr v-if="order && order.customer && order.customer.ACCOUNT_TYPE == 'CS'">
                    <td>Amount Paid
                    </td>
                    <td>{{ order && order.cash_rec? formatPrice(order.cash_rec.PAY_AMT) : formatPrice(0) }}
                    </td>
                  </tr>
                  <tr v-if="order && order.customer && order.customer.ACCOUNT_TYPE == 'CS'">
                    <td>Amount Owing
                    </td>
                    <td>{{ order && order.cash_rec ? formatPrice(order.TOTALAMOUNT - order.cash_rec.PAY_AMT) : (order? formatPrice(order.TOTALAMOUNT): '') }}
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
import { mapGetters, mapState, mapActions} from 'vuex'

export default {
    computed: {
        ...mapGetters({
            processes: 'allProcessesOfCashSaleDetails',
        }),
        ...mapState({
            user: state => state.authUser,
            selectedOrder: state => state.tab.selectedOrder,
        }),
        order() {
            if (this.selectedOrder)
                return this.selectedOrder;
            else return null;
        },
        btnEditAmtOwing() {
            console.log('btnEditAmtOwing = ', this.processes.btnEditAmtOwing);
            return this.parseJsObject(this.processes.btnEditAmtOwing);
        },
        btnEditAmtPaid() {
            console.log('btnEditAmtPaid = ', this.processes.btnEditAmtPaid);
            return this.parseJsObject(this.processes.btnEditAmtPaid);
        },
        btnEditTotalAmt() {
            console.log('btnEditTotalAmt = ', this.processes.btnEditTotalAmt);
            return this.parseJsObject(this.processes.btnEditTotalAmt);
        },
    },
    created() {
        console.log('CashSaleDetails vue created!');
        this.$store.dispatch('setProcessesOfCashSaleDetails')
            .then((response) => {
                console.log('CashSaleDetails vue created response=', response);
            })
            .catch((error) => {
                console.log('CashSaleDetails vue created error=', error);
            });
        if (this.order && this.order.customer && this.order.customer.ACCOUNT_TYPE == 'CS')
        {
            this.title = 'Cash Sale Customer Details';
        }
    },
    data () {
        return {
            title: 'Account Customer Details',
        }
    },
    components: {
    },
    methods: {
    }
}
</script>

<style scoped>
/*.panel-primary > .panel-heading {*/
    /*color: white;*/
    /*background-color: #0a5b9e;*/
    /*border-color: #0a5b9e;*/
/*}*/
/*.panel-heading a {*/
  /*color: white;*/
/*}*/
/*.panel-heading .accordion-toggle:after {*/
    /*!* symbol for "opening" panels *!*/
    /*font-family: 'Glyphicons Halflings';*/
    /*content: "\e114";*/
    /*float: right;*/
    /*color: white;*/
/*}*/
/*.panel-heading .accordion-toggle.collapsed:after {*/
    /*!* symbol for "collapsed" panels *!*/
    /*content: "\e080";*/
/*}*/
td.center {
  text-align: center !important;
}
</style>

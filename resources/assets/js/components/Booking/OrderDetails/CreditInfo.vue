<template>
  <div id="root-credit-info">
    <div class="app-row" >
        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#creditinfo">
                  Credit Information
              </a>              
            </div>
            <div id="creditinfo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <tbody>
                  <tr>
                    <td>Credit Status
                    </td>
                    <td class="center">
                        <!--<bs-checkbox :value="order && order.customer && order.customer.dbp_cust? order.customer.dbp_cust.CSHLDF != 'N': false" type="primary" disabled></bs-checkbox>-->
                        <div v-if="order && order.customer && order.customer.dbp_cust && order.customer.dbp_cust.CSHLDF != 'N'">
                            <span class="label label-danger">ACCOUNT HOLDING</span>
                            <span>Please call supervisor for further information!</span>
                        </div>
                        <div v-else>
                            <span class="label label-info">NORMAL</span>
                        </div>
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
import checkbox from 'vue-strap/src/Checkbox'

export default {
    computed: {
        ...mapGetters({
            processes: 'allProcessesOfCreditInfo',
        }),
        ...mapState({
            user: state => state.authUser,
            selectedOrder: state => state.tab.selectedOrder,
            details: state => state.orderDetails.details,
        }),
        order() {
            if (this.selectedOrder)
                return this.selectedOrder;
            else return null;
        },
        btnEditCredit() {
            console.log('btnEditCredit = ', this.processes.btnEditCredit);
            return this.parseJsObject(this.processes.btnEditCredit);
        },
    },
    created() {
        console.log('CreditInfo vue created! creditInfo');
        this.$store.dispatch('setProcessesOfCreditInfo')
            .then((response) => {
                console.log('CreditInfo vue created response=', response);
            })
            .catch((error) => {
                console.log('CreditInfo vue created error=', error);
            });
    },
    data () {
        return {}
    },
    components: {
        'bs-checkbox': checkbox
    },
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
/*td.center {*/
  /*text-align: center !important;*/
/*}*/
.checkbox {
    margin-top: 0px !important;
    margin-bottom: 0px !important;
}
</style>
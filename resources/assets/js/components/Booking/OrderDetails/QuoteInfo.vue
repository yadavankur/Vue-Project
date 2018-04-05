<template>
  <div id="root-quote-info">
    <div class="app-row">
        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#quoteinfo">
                  Quote Information
              </a>              
            </div>
            <div id="quoteinfo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <tbody>
                  <tr>
                    <td>
                        Quote Number
                    </td>
                    <td>
                      {{ order? (order.QUOTE_NUM_PREF + order.QUOTE_NUM + '_' + order.QUOTE_NUM_SUFF + '_' + order.QUOTE_VERS) : '' }}
                    </td>                    
                  </tr>
                  <tr>
                      <td>
                          Entered By
                      </td>
                      <td>
                          {{ order? (order.UDF7) : '' }}
                      </td>
                  </tr>
                  <tr>
                      <td>
                          Entered Date
                      </td>
                      <td>
                          {{ order? formatDate(order.QUOTE_DATE, 'DD/MMM/YYYY') : '' }}
                      </td>
                  </tr>
                  <tr>
                      <td>
                          Downloaded By
                      </td>
                      <td>
                          {{ order? order.UDF8 : '' }}
                      </td>
                  </tr>
                  <tr>
                      <td>
                          Downloaded Date
                      </td>
                      <td>
                          {{ order? order.UDF9 : '' }}
                      </td>
                  </tr>
                  <tr>
                      <td>
                          Customer Tier
                      </td>
                      <td>
                          {{ order && order.tier? (order.tier.name) : '' }}
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

export default {
    computed: {
        ...mapGetters({
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
    },
    created() {

    },
    data () {
        return {
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
.key-account {
  background-color: #00d1b2;
}
</style>
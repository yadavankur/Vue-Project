<template>
    <div class="cs-order-activity-summary-root table-responsive">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <a class="accordion-toggle" data-toggle="collapse"  href="#orderactivitysummary">
                    Order Tracking Summary
                </a>
            </div>
            <div id="orderactivitysummary" class="panel-collapse collapse in table-responsive">
                <cs-order-activity-list-table></cs-order-activity-list-table>
            </div>
        </div>
    </div>
</template>

<script>
import CSOrderActivityListTable from './OrderActivity/OrderActivityListTable.vue'
    //import CSOrderActivityListTable from './OrderActivity/OrderActivityTable.vue'
export default 
{   data () {  return {    }   },
    created() {console.log('/components/booking/customerservice/OrderActivitySummary.vue-CSOrderActivitySummary Component created.')    },
    components: {'cs-order-activity-list-table' : CSOrderActivityListTable,  },
    mounted() {  console.log('/components/booking/customerservice/OrderActivitySummary.vue-CSOrderActivitySummary Component mounted.')    },
    methods: {   }
}
</script>

<style scoped>
    /*.panel-primary > .panel-heading {*/
        /*color: white;*/
        /*background-color: #0a5b9e;*/
        /*border-color: #0a5b9e;*/
        /*height: 40px;*/
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

</style>
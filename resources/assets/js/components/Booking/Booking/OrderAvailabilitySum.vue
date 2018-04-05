<template>
    <div id="root-order-availability-sum-info">
        <div class="app-row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#orderavailabilitysum">
                        Order Availability Summary
                    </a>
                </div>
                <div id="orderavailabilitysum" class="panel-collapse collapse in table-responsive">
                    <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                        <tbody>
                        <tr>
                            <td> </td><td> Metal</td><td> Glass</td><td> Timber</td><td> components</td>
                            <td> GBI </td><td> Gallery</td><td> Factory Capacity</td><td> Service Capacity</td>
                            <td> Timeline</td>
                        </tr>
                        <tr> <td>Manufactured Standard Product</td><td></td><td></td> <td></td><td></td><td></td> <td></td><td></td><td></td><td></td>
                        </tr>
                        <tr><td>Manufactured Non Standard Product</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                        <tr> <td>Service</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                        <tr><td>GBI</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {}
        },
        created() {
            console.log('/resources/assets/js/components/booking/booking/OrderAvailabilitySum.vue-App Component created.')
        },
        components: {
        },
        mounted() {
            console.log('/resources/assets/js/components/booking/booking/OrderAvailabilitySum.vue-App Component mounted.')
        },
        methods:
            {

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
</style>

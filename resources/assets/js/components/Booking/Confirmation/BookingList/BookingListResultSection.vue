<template>
    <div id="root-booking-list-result">
        <div class="app-row" >
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#result-section">
                        Search Result Section
                    </a>
                </div>
                <div id="result-section" class="panel-collapse collapse in table-responsive">
                    <div class="row-content">
                        <booking-list-table></booking-list-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import BookingListTable from './BookingListTable.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('BookingListResultSection vue created!');
        },
        data () {
            return {}
        },
        components: {
            'booking-list-table': BookingListTable,
        },
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
    /*.row-content {*/
        /*padding: 10px 10px 30px 10px;*/
        /*!*padding-top: 10px;*!*/
        /*!*padding-right: 10px;*!*/
        /*!*padding-bottom: 10px;*!*/
        /*!*padding-left: 30px;*!*/
    /*}*/
</style>
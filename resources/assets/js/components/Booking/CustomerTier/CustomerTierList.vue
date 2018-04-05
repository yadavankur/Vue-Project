<template>
    <div id="customer-tier-list-root">
        <div class="main">
            <div class="container">
                <customer-tier-crud-modal></customer-tier-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="customer-tier-title">
                                    <span class="pull-left">Customer Tier Setting
                                    </span>
                                </div>
                            </div>
                            <div id="customer-tier-list" class="panel-body table-responsive">
                                <customer-tier-list-table ></customer-tier-list-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import CustomerTierListTable from './CustomerTierListTable.vue'
    import CustomerTierCrudModal from './CustomerTierCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                customerTierNodes: 'allCustomerTierNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('CustomerTierList vue Component created.');
        },
        components: {
            'customer-tier-crud-modal': CustomerTierCrudModal,
            'customer-tier-list-table': CustomerTierListTable,
        },
        mounted() {
            console.log('CustomerTierList vue Component mounted.');
        },
        methods: {
        },
        watch: {
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
    .customer-tier-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
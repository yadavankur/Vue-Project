<template>
    <div id="cpmservice-list">
        <div class="main">
            <div class="container">
                <cpmservice-crud-modal></cpmservice-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="cpmservice-title">
                                    <span class="pull-left">Services
                                    </span>
                                </div>
                            </div>
                            <div id="cpmservicelist" class="panel-body table-responsive">
                                <cpmservice-list-table></cpmservice-list-table>
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
    import CpmServiceListTable from './CpmServiceListTable.vue'
    import CpmServiceCrudModal from './CpmServiceCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                cpmserviceNodes: 'allCpmServiceNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('CpmServiceList vue Component created.');
        },
        components: {
            'cpmservice-crud-modal': CpmServiceCrudModal,
            'cpmservice-list-table': CpmServiceListTable,
        },
        mounted() {
            console.log('CpmServiceList vue Component mounted.');
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
    .cpmservice-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
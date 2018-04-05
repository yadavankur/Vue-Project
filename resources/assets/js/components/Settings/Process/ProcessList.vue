<template>
    <div id="process-list">
        <div class="main">
            <div class="container">
                <process-crud-modal></process-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="process-title">
                                    <span class="pull-left">Process
                                    </span>
                                </div>
                            </div>
                            <div id="processlist" class="panel-body table-responsive">
                                <process-list-table ></process-list-table>
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
    import ProcessListTable from './ProcessListTable.vue'
    import ProcessCrudModal from './ProcessCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                processNodes: 'allProcessNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('ProcessList vue Component created.');
        },
        components: {
            'process-crud-modal': ProcessCrudModal,
            'process-list-table': ProcessListTable,
        },
        mounted() {
            console.log('ProcessList vue Component mounted.');
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
    .process-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
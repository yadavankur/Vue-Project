<template>
    <div id="page-list">
        <div class="main">
            <div class="container">
                <page-crud-modal></page-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="page-title">
                                    <span class="pull-left">Page
                                    </span>
                                </div>
                            </div>
                            <div id="pagelist" class="panel-body table-responsive">
                                <page-list-table ></page-list-table>
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
    import PageListTable from './PageListTable.vue'
    import PageCrudModal from './PageCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                pageNodes: 'allPageNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('PageList vue Component created.');
        },
        components: {
            'page-crud-modal': PageCrudModal,
            'page-list-table': PageListTable,
        },
        mounted() {
            console.log('PageList vue Component mounted.');
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
    .page-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
<template>
    <div id="tab-list">
        <div class="main">
            <div class="container">
                <tab-crud-modal></tab-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="tab-title">
                                    <span class="pull-left">Tab </span>
                                </div>
                            </div>
                            <div id="tablist" class="panel-body table-responsive">
                                <tab-list-table ></tab-list-table>
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
    import TabListTable from './TabListTable.vue'
    import TabCrudModal from './TabCrudModal.vue'

    export default 
    {   data () {  return {     }   },
        computed: {    ...mapGetters({   tabNodes: 'allTabNodes', }),
                       ...mapState({ user: state => state.authUser,  }),
                   },
        created() { console.log('/components/settings/tab/TabList.vue--TabList vue Component created.'); },
        components: { 'tab-crud-modal': TabCrudModal, 'tab-list-table': TabListTable, },
        mounted() { console.log('/components/settings/tab/TabList.vue--TabList vue Component mounted.'); },
        methods: {     },
        watch: {    }
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
    .tab-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
<template>
    <div id="resourceType-list">
        <div class="main">
            <div class="container">
                <resourceType-crud-modal></resourceType-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="resourceType-title">
                                    <span class="pull-left">ResourceType
                                    </span>
                                </div>
                            </div>
                            <div id="resourceTypelist" class="panel-body table-responsive">
                                <resourceType-list-table ></resourceType-list-table>
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
    import ResourceTypeListTable from './ResourceTypeListTable.vue'
    import ResourceTypeCrudModal from './ResourceTypeCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                resourceTypeNodes: 'allResourceTypeNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('ResourceTypeList vue Component created.');
        },
        components: {
            'resourceType-crud-modal': ResourceTypeCrudModal,
            'resourceType-list-table': ResourceTypeListTable,
        },
        mounted() {
            console.log('ResourceTypeList vue Component mounted.');
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
    .resourceType-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
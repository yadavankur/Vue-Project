<template>
    <div id="permission-list">
        <div class="main">
            <div class="container">
                <permission-crud-modal></permission-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="permission-title">
                                    <span class="pull-left">Permission
                                    </span>
                                </div>
                            </div>
                            <div id="permissionlist" class="panel-body table-responsive">
                                <permission-list-table ></permission-list-table>
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
    import PermissionListTable from './PermissionListTable.vue'
    import PermissionCrudModal from './PermissionCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                permissionNodes: 'allPermissionNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('PermissionList vue Component created.');
        },
        components: {
            'permission-crud-modal': PermissionCrudModal,
            'permission-list-table': PermissionListTable,
        },
        mounted() {
            console.log('PermissionList vue Component mounted.');
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
    .permission-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
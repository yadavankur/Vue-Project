<template>
    <div id="group-list">
        <div class="main">
            <div class="container">
                <group-crud-modal></group-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="group-title">
                                    <span class="pull-left">Group
                                    </span>
                                </div>
                            </div>
                            <div id="grouplist" class="panel-body table-responsive">
                                <group-list-table ></group-list-table>
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
    import GroupListTable from './GroupListTable.vue'
    import GroupCrudModal from './GroupCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                groupNodes: 'allGroupNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('GroupList vue Component created.');
        },
        components: {
            'group-crud-modal': GroupCrudModal,
            'group-list-table': GroupListTable,
        },
        mounted() {
            console.log('GroupList vue Component mounted.');
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
    .group-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
<template>
    <div id="menu-list">
        <div class="main">
            <div class="container">
                <menu-crud-modal></menu-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="menu-title">
                                    Menu list
                                </div>
                            </div>
                            <div id="menulist" class="panel-body table-responsive">
                                <menu-list-table ></menu-list-table>
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
    import MenuCrudModal from './MenuCrudModal.vue'
    import MenuListTable from './MenuListTable.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                menuNodes: 'allMenuNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('MenuList vue Component created.');
        },
        components: {
            'menu-crud-modal': MenuCrudModal,
            'menu-list-table': MenuListTable,
        },
        mounted() {
            console.log('MenuList vue Component mounted.');
        },
        methods: {
        },
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
    .role-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
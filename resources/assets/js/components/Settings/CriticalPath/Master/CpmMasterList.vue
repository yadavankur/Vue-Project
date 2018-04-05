<template>
    <div id="cpmmaster-list">
        <div class="main">
            <div class="container">
                <cpmmaster-crud-modal :cascadeServiceOptions="cascadeServiceOptions"></cpmmaster-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="cpmmaster-title">
                                    <span class="pull-left">Services
                                    </span>
                                </div>
                            </div>
                            <div id="cpmmasterlist" class="panel-body table-responsive">
                                <cpmmaster-list-table :cascadeServiceOptions="cascadeServiceOptions"></cpmmaster-list-table>
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
    import CpmMasterListTable from './CpmMasterListTable.vue'
    import CpmMasterCrudModal from './CpmMasterCrudModal.vue'

    export default {
        data () {
            return {
                cascadeServiceOptions: [],
            }
        },
        computed: {
            ...mapGetters({
                cpmmasterNodes: 'allCpmMasterNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('CpmMasterList vue Component created.');
            // get cascade location service options here
            this.$store.dispatch('getCpmCascadeServiceOptions')
                .then((response) => {
                    console.log('created getCpmCascadeServiceOptions success=', response);
                    this.cascadeServiceOptions = response.data.locationServices;
                })
                .catch((error) => {
                    console.error('getCpmCascadeServiceOptions error=', error);
                });
        },
        components: {
            'cpmmaster-crud-modal': CpmMasterCrudModal,
            'cpmmaster-list-table': CpmMasterListTable,
        },
        mounted() {
            console.log('CpmMasterList vue Component mounted.');
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
    .cpmmaster-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
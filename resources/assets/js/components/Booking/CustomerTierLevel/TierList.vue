<template>
    <div id="tier-list">
        <div class="main">
            <div class="container">
                <tier-crud-modal></tier-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="tier-title">
                                    <span class="pull-left">Tab
                                    </span>
                                </div>
                            </div>
                            <div id="tierlist" class="panel-body table-responsive">
                                <tier-list-table ></tier-list-table>
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
    import TierListTable from './TierListTable.vue'
    import TierCrudModal from './TierCrudModal.vue'

    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                tierNodes: 'allTierNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('TierList vue Component created.');
        },
        components: {
            'tier-crud-modal': TierCrudModal,
            'tier-list-table': TierListTable,
        },
        mounted() {
            console.log('TierList vue Component mounted.');
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
    .tier-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
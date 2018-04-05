<template>
    <div id="state-list">
        <div class="main">
            <div class="container">
                <state-crud-modal></state-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="state-title">
                                    State list
                                </div>
                            </div>
                            <div id="statelist" class="panel-collapse collapse in table-responsive">
                                <ul class="list-group">
                                    <li id="states-list" class="list-group-item">
                                        <state-list-view :stateNodes="stateNodes"></state-list-view>
                                    </li>
                                </ul>
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
    import StateListView from './StateListView.vue'
    import StateCrudModal from './StateCrudModal.vue'
    export default {
        data () {
            return {
            }
        },
        computed: {
            ...mapGetters({
                stateNodes: 'allStateNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('StateList vue Component created.');
            this.$store.dispatch('setStateNodes')
                .then((response) => {
                    console.log('StateList vue created response=', response);
                })
                .catch((error) => {
                    console.error('StateList vue created error=', error);
                });
        },
        components: {
            'state-crud-modal': StateCrudModal,
            'state-list-view': StateListView,
        },
        mounted() {
            console.log('StateList vue Component mounted.');
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
<template>
    <div id="component-list">
        <div class="main">
            <div class="container">
                <component-crud-modal :options="cascadeComponents"></component-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="component-title">
                                    <span class="pull-left">Component
                                    </span>
                                </div>
                            </div>
                            <div id="componentlist" class="panel-body table-responsive">
                                <component-list-table ></component-list-table>
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
    import ComponentListTable from './ComponentListTable.vue'
    import ComponentCrudModal from './ComponentCrudModal.vue'

    export default {
        data () {
            return {
                cascadeComponents: [],
            }
        },
        computed: {
            ...mapGetters({
                componentNodes: 'allComponentNodes',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('ComponentList vue Component created.');
            // get cascade components
            this.$store.dispatch('getCascadeComponents')
                .then((response) => {
                    console.log('created getCascadeComponents success=', response);
                    this.cascadeComponents = response.data.cascadeComponents;
                })
                .catch((error) => {
                    console.error('getCascadeComponents error=', error);
                });
        },
        components: {
            'component-crud-modal': ComponentCrudModal,
            'component-list-table': ComponentListTable,
        },
        mounted() {
            console.log('ComponentList vue Component mounted.');
        },
        methods: {
        },
        watch: {
        },
        events: {
            'refreshCascadeComponents' () {
                this.$store.dispatch('getCascadeComponents')
                    .then((response) => {
                        console.log('created getCascadeComponents success=', response);
                        this.cascadeComponents = response.data.cascadeComponents;
                    })
                    .catch((error) => {
                        console.error('getCascadeComponents error=', error);
                    });
            },
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
    .component-title {
        margin-left: 6px;
        height: 30px;
    }
</style>
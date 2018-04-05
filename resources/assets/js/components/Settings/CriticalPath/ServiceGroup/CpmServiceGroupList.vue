<template>
    <div class="container">
        <div class="row">
            <ibox title="Service Groups" :closeable="false">
                <div slot="ibox-content" class="ibox-content">
                    <div class="row-content">
                        <div id="business-calendar-list" class="panel-body table-responsive">
                            <cpmservice-group-list-table></cpmservice-group-list-table>
                        </div>
                    </div>
                </div>
            </ibox>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import IBox from '../../../IBox/IBox.vue'
    import CpmServiceGroupListTable from './CpmServiceGroupListTable.vue'

    export default {
        data () {
            return {

            }
        },
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('CpmServiceGroupList vue Component created.');
        },
        components: {
            'ibox': IBox,
            'cpmservice-group-list-table': CpmServiceGroupListTable,
        },
        mounted() {
            console.log('CpmServiceGroupList vue Component mounted.');
        },
        methods: {
        },
        watch: {
        }
    }
</script>

<style scoped>
</style>
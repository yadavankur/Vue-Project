<template>
    <div id="root-order-activity-list">
        <ibox title="Order Activity List" :closeable="false">
            <div slot="ibox-content" class="ibox-content">
                <div class="row-content">
                    <order-activity-list-table
                            :selectedOrder="selectedOrder"
                    ></order-activity-list-table>
                </div>
            </div>
        </ibox>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import IBox from '../../IBox/IBox.vue'
    import OrderActivityListTable from './OrderActivityList/OrderActivityListTable.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        props: {
            selectedOrder: {
                type: Object,
                default: null,
            }
        },
        data () {
            return {
            }
        },
        created() {
            console.log('OrderActivityList vue created!');
        },
        mounted() {
            console.log('OrderActivityList vue mounted');
        },
        components: {
            'ibox': IBox,
            'order-activity-list-table': OrderActivityListTable,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>

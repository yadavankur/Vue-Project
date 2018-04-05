<template>
    <div id="root-expedite-enquiry-list">
        <ibox title="Expedite Booking Information" :closeable="false">
            <div slot="ibox-content" class="ibox-content">
                <div>
                    <expedite-enquiry-list-table :selectedOrder="selectedOrder"></expedite-enquiry-list-table>
                </div>
            </div>
        </ibox>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import IBox from '../../IBox/IBox.vue'
    import ExpediteEnquiryListTable from './ExpediteEnquiryList/ExpediteEnquiryListTable.vue'

    export default {
        props: {
            selectedOrder: {
                type: Object,
                default: null,
            }
        },
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
            }
        },
        created() {
            console.log('ExpediteEnquiryList vue created!');
        },
        mounted() {
            console.log('ExpediteEnquiryList vue mounted');
        },
        components: {
            'ibox': IBox,
            'expedite-enquiry-list-table': ExpediteEnquiryListTable,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>

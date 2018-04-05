<template>
    <div id="root-booking-list">
        <div class="main">
            <div class="main-body">
                <div class="row">
                    <div class="col-md-12">
                        <booking-list-search></booking-list-search>
                    </div>
                    <div class="col-md-12">
                        <booking-list-result></booking-list-result>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import BookingListSearch from './BookingList/BookingListSearchSection.vue'
    import BookingListResult from './BookingList/BookingListResultSection.vue'

    export default {
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
            console.log('BookingList vue created!');
            //this.$store.dispatch('setSelectedOrder', null);
        },
        mounted() {
            console.log('BookingList vue mounted');
        },
        components: {
            'booking-list-search': BookingListSearch,
            'booking-list-result': BookingListResult,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>

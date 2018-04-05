<template>
    <div id="root-cs-booking-list"> <div class="main"> <div class="main-body"> <div class="row">
                    <div class="col-md-12"> <cs-booking-list-search></cs-booking-list-search></div>
                    <div class="col-md-12"><cs-booking-list-result></cs-booking-list-result></div>
     </div>  </div>   </div> </div>
</template>
<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import CSBookingListSearch from './BookingList/BookingListSearchSection.vue'
    import CSBookingListResult from './BookingList/BookingListResultSection.vue'
    export default 
    { computed: {  ...mapGetters({   }),  ...mapState({ user: state => state.authUser, }), },
      data () {  return {    }  },
      created() { console.log('/components/booking/customerservice/BookingList.vue-CSBookingList vue created!');   },
      mounted() { console.log('/components/booking/customerservice/BookingList.vue-CSBookingList vue mounted');    },
      components: {'cs-booking-list-search': CSBookingListSearch, 'cs-booking-list-result': CSBookingListResult, },
      methods: {    }
    }
</script>
<style scoped>
</style>

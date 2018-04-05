<template>
    <div id="root-order-list">
        <div class="main"> <div class="container"><div class="row">
                    <div class="col-md-12"> <order-list-search></order-list-search> </div>
                    <div class="col-md-12"> <order-list-result></order-list-result> </div>
                </div></div></div></div>
</template>
<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import OrderListSearch from './OListSearchSection.vue'
    import OrderListResult from './OListResultSection.vue'
    export default 
    {   beforeCreate() { console.log('/Ol2/OList.vue--beforecreate!')  },
        computed:      {   ...mapGetters({   }),  ...mapState({ user: state => state.authUser, }),      },
        data ()        {  return {     }       },
       created()       { console.log('/Ol2/OList.vue--OrderList vue created!');
                         this.$store.dispatch('setSelectedOrder', null);
                       },
        mounted()      { console.log('/Ol2/OList.vue--OrderList vue mounted');        },
        components:    {'order-list-search': OrderListSearch, 'order-list-result': OrderListResult,   },
        methods:       {    }
    }
</script>
<style scoped>
 
</style>

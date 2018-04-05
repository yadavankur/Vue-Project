<template>
    <div id="root-order-list-result">
        <div class="app-row" >
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#result-section">
                        Search Result Section
                    </a>
                </div>
                <div id="result-section" class="panel-collapse collapse in ">
                    <div class="row-content">
                        <order-list-table></order-list-table>
                    </div> </div>  </div>  </div> </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import OrderListTable from './OrderListTable.vue'
    export default 
    {  computed: {     ...mapGetters({  }),
                       ...mapState({  user: state => state.authUser,   }),
                  },
        created() { console.log('/booking/orderlist/OrderListResultSection.vue-OrderListResultSection vue created!');
                  },
        data () { return {}  },
        components: { 'order-list-table': OrderListTable, },
    }
</script>

<style scoped>
</style>
<template>
  <div id="root-order-items">

    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <order-item-order-info
                            v-if="orderinfo.permission !== 'D' && orderinfo.permission !== 'H'"
                            :user="user"
                            :selectedOrder="selectedOrder"
                            @refreshOrderDetail="refreshOrderDetail"
                            :details="details"
                    >
                    </order-item-order-info>
                </div>
                <div class="col-lg-12">
                    <order-item-lists v-if="orderitems.permission !== 'D' && orderitems.permission !== 'H'">
                    </order-item-lists>
                </div>
                <div class="col-lg-12">
                    <order-item-finishes v-if="orderfinishes.permission !== 'D' && orderfinishes.permission !== 'H'">
                    </order-item-finishes>
                </div>
                <div class="col-lg-12">
                    <order-item-bom-fills v-if="orderbomfills.permission !== 'D' && orderbomfills.permission !== 'H'">
                    </order-item-bom-fills>
                </div>
                <div class="col-lg-12">
                    <order-item-components v-if="ordercomponents.permission !== 'D' && ordercomponents.permission !== 'H'">
                    </order-item-components>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import { mapGetters, mapState, mapActions} from 'vuex'
import OrderInfo from '../OrderDetails/OrderInfo.vue'
import OrderItemLists from './OrderItemLists.vue'
import OrderItemBomFill from './OrderItemBomFill.vue'
import OrderItemFinish from './OrderItemFinish.vue'
import OrderItemComponent from './OrderItemComponent.vue'

export default {
    computed: {
        ...mapGetters({
            //processes: 'allPODProcesses',
        }),
        ...mapState({
            user: state => state.authUser,
            selectedOrder: state => state.tab.selectedOrder,
            items: state => state.orderItems.items,
            details: state => state.orderDetails.details,
            OrderInfo: state => state.orderItems.components.OrderInfo,
            OrderItems: state => state.orderItems.components.OrderItems,
            OrderFinishes: state => state.orderItems.components.OrderFinishes,
            OrderBomFills: state => state.orderItems.components.OrderBomFills,
            OrderBomComps: state => state.orderItems.components.OrderBomComps,
        }),
        orderinfo() {
            console.log('orderinfo = orderinfo=', this.OrderInfo);
            return this.parseJsObject(this.OrderInfo);
        },
        orderitems() {
            console.log('orderinfo = orderitems=', this.OrderItems);
            return this.parseJsObject(this.OrderItems);
        },
        orderfinishes() {
            console.log('orderfinishes = orderfinishes=', this.OrderFinishes);
            return this.parseJsObject(this.OrderFinishes);
        },
        orderbomfills() {
            console.log('orderbomfills = orderbomfills=', this.OrderBomFills);
            return this.parseJsObject(this.OrderBomFills);
        },
        ordercomponents() {
            console.log('ordercomponents = ordercomponents=', this.OrderBomComps);
            return this.parseJsObject(this.OrderBomComps);
        },
    },
    data () {
        return {}
    },
    components: {
        'order-item-order-info': OrderInfo,
        'order-item-lists': OrderItemLists,
        'order-item-finishes': OrderItemFinish,
        'order-item-bom-fills': OrderItemBomFill,
        'order-item-components': OrderItemComponent,

    },
    created() {
        console.log('OrderItems vue created!');
        // if the order isn't selected
        if (!this.selectedOrder)
        {
            this.$router.push({name: 'notallowed'});
            return;
        }
        // get order details
        // get accessible components
        this.$store.dispatch('setOrderItemsComponents')
            .then((response) => {
                console.log('OrderItems vue created setOrderItemsComponents response=', response);
                if (this.orderinfo.permission !== 'D' && this.orderinfo.permission !== 'H')
                {
                    if (!this.selectedOrder || !this.selectedOrder.UDF1) return;
                    let payload = {
                        orderNo: this.selectedOrder.UDF1,
                        quoteId: this.selectedOrder.QUOTE_ID,
                        location: this.selectedOrder.QUOTE_NUM_PREF,
                    };
                    this.$store.dispatch('getOrderDetail', payload)
                        .then((response) => {
                            console.log('OrderItems getOrderDetail response=', response);
                        })
                        .catch((error) => {
                        });
                }
            })
            .catch((error) => {
                console.log('OrderItems vue created error=', error);
            });
    },
    mounted() {
        console.log('OrderItems vue mounted components=', this.components);
    },
    methods: {
        refreshOrderDetail(payload) {
            console.log('OrderItems refreshOrderDetail payload=', payload);
            this.onSearch(payload);
        },
        onSearch(payload) {
            console.log('onSearch payload=', payload);
            this.$store.dispatch('getOrderDetailOnSearch', payload)
                .then((response) => {
                console.log('OrderItems getOrderDetailOnSearch response=', response);
                if (this.isEmpty(response.body))
                {
                    this.$store.dispatch('setSelectedOrder', null);
                }
                else
                {
                    this.$store.dispatch('setSelectedOrder', response.body);
                    let payload = {
                        orderNo: this.selectedOrder.UDF1,
                        quoteId: this.selectedOrder.QUOTE_ID,
                        location: this.selectedOrder.QUOTE_NUM_PREF,
                    };
                    this.$store.dispatch('getOrderDetail', payload)
                }
            })
        .catch((error) => {
                console.log('OrderItems getOrderDetailOnSearch error=', error);
                this.$store.dispatch('setSelectedOrder', null);
            })
        }
    }
}
</script>

<style scoped>
</style>

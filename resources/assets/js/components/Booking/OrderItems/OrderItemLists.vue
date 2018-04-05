<template>
  <div id="root-order-item-lists">
      <div class="app-row" >
          <div class="panel panel-primary">
              <!-- Default panel contents -->
              <div class="panel-heading">
                  <a class="accordion-toggle" data-toggle="collapse"  href="#item-section">
                      Order Items
                  </a>
              </div>
              <div id="item-section" class="panel-collapse collapse in ">
                  <div class="row-content">
                      <order-product-list-table></order-product-list-table>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import OrderItemRow from './OrderItemRow.vue'
import OrderProductListTable from './OrderProductListTable.vue'

export default {
    data () {
        return {}
    },
    components: {
        'order-item-row': OrderItemRow,
        'order-product-list-table': OrderProductListTable,
    },
    created() {
        // got item data
    }
}
</script>

<style scoped>
</style>

<template>
    <div id="root-order-item-component">
        <div class="app-row" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#bomComp-section">
                        BoM Component
                    </a>
                </div>
                <div id="bomComp-section" class="panel-collapse collapse in ">
                    <div class="row-content">
                        <order-component-list-table></order-component-list-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import OrderItemComponentListTable from './OrderItemComponentListTable.vue'

    export default {
        data () {
            return {}
        },
        components: {
            'order-component-list-table': OrderItemComponentListTable,
        },
        created() {
        }
    }
</script>

<style scoped>
</style>

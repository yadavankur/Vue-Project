<template>
    <div id="root-order-item-bom-fill">
        <div class="app-row" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#bomFill-section">
                        BoM Fill
                    </a>
                </div>
                <div id="bomFill-section" class="panel-collapse collapse in ">
                    <div class="row-content">
                        <order-bom-fill-list-table></order-bom-fill-list-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import OrderItemBomFillListTable from './OrderItemBomFillListTable.vue'

    export default {
        data () {
            return {}
        },
        components: {
            'order-bom-fill-list-table': OrderItemBomFillListTable,
        },
        created() {
         }
    }
</script>

<style scoped>
    /*.panel-primary > .panel-heading {*/
        /*color: white;*/
        /*background-color: #0a5b9e;*/
        /*border-color: #0a5b9e;*/
        /*!*height: 20px;*!*/
    /*}*/
    /*.panel-heading a {*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle:after {*/
        /*!* symbol for "opening" panels *!*/
        /*font-family: 'Glyphicons Halflings';*/
        /*content: "\e114";*/
        /*float: right;*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle.collapsed:after {*/
        /*!* symbol for "collapsed" panels *!*/
        /*content: "\e080";*/
    /*}*/
    /*.row-content {*/
        /*padding: 10px 10px 30px 10px;*/
    /*}*/
</style>

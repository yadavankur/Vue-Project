<template>
    <div>
        <div class="order-activity-custom-actions text-center">
            <button v-if="actDelay" class="btn btn-warning btn-sm" @click="itemAction('Confirm', rowData, rowIndex)" title="title">{{title}}</button>
         </div>
    </div>
</template>
<script>
    export default {
        props: {
            title: {
                type: String,
                default: '',
            },
            item: {
                type: Object,
            },
            rowData: {
                type: Object,
                required: true
            },
            rowIndex: {
                type: Number,
            },
            actDelay: {
                type: Boolean,
                default: false,
            },
        },
        data () {
            return {
                itemData: null,
            }
        },
        mounted() {
        },
        updated() {
            console.log('OrderActivityCustomActions Component updated.');
        },
        created() {
            console.log('OrderActivityCustomActions created: ', this.rowData, this.rowIndex);
        },
        methods: {
            itemAction (action, data, index) {
                console.log('itemAction: CustomAction:action-item :' + action, data, index);
                this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
            },
        },
    }
</script>

<style scoped>

</style>
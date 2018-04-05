<template>
    <div>
        <div class="associated-jobs-all-custom-actions text-center">
            <button v-if="actAdd" class="btn btn-primary btn-sm" @click="itemAction('Add', rowData, rowIndex)" title="Add"><i class="glyphicon glyphicon-plus"></i></button>
        </div>
    </div>
</template>
<script>
    export default {
        components: {
        },
        props: {
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
        },
        data () {
            return {
                actAdd: {
                    type: Boolean,
                    default: true
                },
            }
        },
        mounted() {
        },
        updated() {
            console.log('AssociatedJobsAllCustomActions Component updated.');
        },
        created() {
            console.log('AssociatedJobsAllCustomActions created: ', this.rowData, this.rowIndex);
        },
        methods: {
            itemAction (action, data, index) {
                console.log('itemAction: CustomAction:action-item :' + action, data, index);
                // emit event to let parent do things
                this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
            },
        },
        watch: {
        }
    }
</script>

<style scoped>
    .custom-actions button.ui.button {
        padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
        margin: auto !important;
    }
</style>
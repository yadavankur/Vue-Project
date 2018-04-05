<template>
    <div>
        <div class="state-custom-actions text-center">
            <button v-if="actAdd" class="btn btn-primary btn-sm" @click="itemAction('Add', rowData, rowIndex)" title="Add"><i class="glyphicon glyphicon-plus"></i></button>
            <button v-if="actEdit" class="btn btn-warning btn-sm" @click="itemAction('Edit', rowData, rowIndex)" title="Edit"><i class="glyphicon glyphicon-pencil"></i></button>
            <button v-if="actDelete" class="btn btn-danger btn-sm" @click="itemAction('Delete', rowData, rowIndex)" title="Delete"><i class="glyphicon glyphicon-remove"></i></button>
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
                type: String,
            },
        },
        data () {
            return {
                actAdd: false,
                actEdit: {
                    type: Boolean,
                    default: true
                },
                actDelete: {
                    type: Boolean,
                    default: true
                },
            }
        },
        mounted() {
        },
        updated() {
            console.log('StateCustomActions Component updated.');
        },
        created() {
            console.log('StateCustomActions created: ', this.rowData, this.rowIndex);
        },
        methods: {
            itemAction (action, data, index) {
                console.log('itemAction: ' + action, data, index);
                // emit event to let parent do things
                this.$emit('onActions', {action: action, data: data, index: index});
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
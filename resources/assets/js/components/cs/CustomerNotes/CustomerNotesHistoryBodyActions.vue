<template>
    <div>
        <div class="customer-notes-history-body-actions text-center">
            <button v-if="actView" class="btn btn-primary btn-sm" @click="itemAction('Add', rowData, rowIndex)" title="View"><i class="glyphicon glyphicon-zoom-in"></i></button>
        </div>
    </div>
</template>
<script>
    export default 
    {   components: {    },
        props: {    item: {   type: Object,   },
                    rowData: { type: Object,  required: true },
                    rowIndex: { type: Number, },
                },
        data () {   return { actView: { type: Boolean,  default: true },  }  },
        mounted() {    },
        updated() { console.log('CustomerNotesHistoryBodyActions Component updated.');  },
        created() { console.log('CustomerNotesHistoryBodyActions created: ', this.rowData, this.rowIndex);  },
        methods: {  itemAction (action, data, index) 
                    {    console.log('itemAction: CustomAction:action-item :' + action, data, index);
                        // emit event to let parent do things
                        this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
                    },
                },
        watch: {      }
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
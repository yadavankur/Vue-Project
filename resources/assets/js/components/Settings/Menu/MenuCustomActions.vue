<template>
    <div id="menu-custom-actions">
        <div class="menu-custom-actions text-center">
            <button v-if="actAdd" class="btn btn-primary btn-sm" @click="itemAction('Add', rowData, rowIndex)" title="Add"><i class="glyphicon glyphicon-plus"></i></button>
            <button v-if="actEdit" class="btn btn-warning btn-sm" @click="itemAction('Edit', rowData, rowIndex)" title="Edit"><i class="glyphicon glyphicon-pencil"></i></button>
            <button v-if="actDelete" class="btn btn-danger btn-sm" @click="itemAction('Delete', rowData, rowIndex)" title="Delete"><i class="glyphicon glyphicon-remove"></i></button>
        </div>
    </div>
</template>
<script>
    export default 
    {   components: {    },
        props: {    item: {  type: Object,  },
                    rowData: {  type: Object,   required: true },
                    rowIndex: { type: Number, },
               },
        data () {  return  {   actAdd: {  type: Boolean, default: true },
                              actEdit: { type: Boolean, default: true },
                              actDelete: { type: Boolean, default: true },
                           }
                },
        mounted() {     },
        created() { console.log('/settings/menu/MenuCustomActions.vue--created: ', this.rowData, this.rowIndex);
                    if (this.rowData.id === 1) {this.actDelete = false; } // root menu cannot be deleted or edit
                    else if (parseInt(this.rowData.level) >= 3) { this.actAdd = false; }
                 },
        methods: { itemAction (action, data, index) 
                      {  console.log('/settings/menu/MenuCustomActions.vue--created:itemAction: ' + action, data, index);
                         // emit event to let parent do things
                         this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
                      },
                 },
        watch: {  rowData(newVal, oldVal) 
                    {   console.log('/settings/menu/MenuCustomActions.vue--created:******** rowData changed newVal, oldVal', newVal, oldVal);
                        this.actAdd = true;   this.actEdit = true;
                        this.actDelete = true;
                        if (this.rowData.id === 1) { this.actDelete = false; }// root menu cannot be deleted or edit
                        else if (parseInt(this.rowData.level) >= 3) { this.actAdd = false; }
                    }
               }
    }
</script>

<style scoped>
    .custom-actions button.ui.button {   padding: 8px 8px;    }
    .custom-actions button.ui.button > i.icon {    margin: auto !important;  }
</style>
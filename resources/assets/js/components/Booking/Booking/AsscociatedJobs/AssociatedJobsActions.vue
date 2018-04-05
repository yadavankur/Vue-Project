<template>
    <div>
        <div class="associated-jobs-custom-actions text-center">
            <button v-if="actView" class="btn btn-success btn-sm" @click="itemAction('View', rowData, rowIndex)" title="View"><i class="glyphicon glyphicon-zoom-in"></i></button>
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
                type: Number,
            },
        },
        data () {
            return {
                actDelete: {
                    type: Boolean,
                    default: true
                },
                actView: {
                    type: Boolean,
                    default: true
                },
            }
        },
        mounted() {
        },
        updated() {
            console.log('AssociatedJobsCustomActions Component updated.');
        },
        created() {
            console.log('AssociatedJobsCustomActions created: ', this.rowData, this.rowIndex);
        },
        methods: {
            itemAction (action, data, index) {
                console.log('itemAction: CustomAction:action-item :' + action, data, index);
                if (action == 'Delete')
                {
                    let swal = this.$swal;
                    let me = this;
                    this.$swal({
                        title: 'Are you sure?',
                        text: 'This associated job will be deleted!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'cancel',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        allowOutsideClick: false
                    }).then(function() {
                        // emit event to let parent do things
                        me.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
                    }, function (dismiss) {
                    });
                }
                else
                {
                    // view detail
                    this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
                }
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
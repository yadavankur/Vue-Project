<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Adding ad hoc activity  </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <cs-adhoc-activity-body v-if="isShowModal"
                                            ref="csAdhocactivityBody"
                                            :itemData="itemData"
                                            :order="order"
                                            :currentActivityName="itemData.activity.name"
                    ></cs-adhoc-activity-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="onClickSave">Save</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import CSAdhocActivityBody from './AdhocActivityBody.vue'

    export default {
        props: {
            isShowAdhocActivity: false,
            order: {
                type: Object,
            },
            itemData: {
                type: Object,
            }
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowAdhocActivity;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('AdhocActivityModal Component created.')
        },
        components: {
            'cs-adhoc-activity-body': CSAdhocActivityBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('AdhocActivityModal Component mounted.')
        },
        methods:
        {
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseAdhocActivityModal');
            },
            onClickSave() {
                console.log('onClickSave');
                // check validation
                if (this.isEmpty(this.$refs.csAdhocactivityBody.formData.position))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the position!');
                    return;
                }
                if (this.isEmpty(this.$refs.csAdhocactivityBody.formData.name))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the activity name!');
                    return;
                }
                if (this.$refs.csAdhocactivityBody.formData.duration == 0)
                {
                    this.$store.dispatch('showErrorNotification', 'Please input a valid duration!');
                    return;
                }
                // send insert request
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You are about to insert an ad hoc activity',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'cancel',
                    confirmButtonClass: 'btn btn-success btn-sm',
                    cancelButtonClass: 'btn btn-danger btn-sm',
                    allowOutsideClick: false
                })
                .then((success) => {
                    this.addAdhocActivityRequest();
                })
                .catch((dismiss) => {});
            },
            addAdhocActivityRequest() {
                console.log('addAdhocActivityRequest');
                let formData = {
                    quoteId: this.itemData.quote_id,
                    locationId: this.itemData.location_id,
                    orderId: this.itemData.order_id,
                    activityId: this.itemData.activity_id,
                    serviceId: this.itemData.service_id,
                    orderMatrixId: this.itemData.id,
                    position: this.$refs.csAdhocactivityBody.formData.position,
                    adhocName: this.$refs.csAdhocactivityBody.formData.name,
                    duration: this.$refs.csAdhocactivityBody.formData.duration,
                    sqlStatement: this.$refs.csAdhocactivityBody.formData.sql_statement,
                    comment: this.$refs.csAdhocactivityBody.formData.comment,
                };
                console.log('onClickSave formData=', formData);
                // SEND EMAIL REQUEST
                this.$store.dispatch('addAdhocActivityRequest', formData)
                    .then((response) => {
                        console.log('addAdhocActivityRequest response=', response);
                        this.$emit('onCloseAdhocActivityModal');
                        // fire refresh event
                        this.$emit('onRefreshOrderMatrixTable');
                    })
                    .catch((error) => {
                        console.log('addAdhocActivityRequest error=', error);
                    });
            }
        }
    }
</script>

<style scoped>
    /*.modal-header {*/
        /*padding: 15px;*/
        /*border-bottom: 1px solid #e5e5e5;*/
        /*color: white !important;*/
        /*background-color: #0a5b9e !important;*/
        /*border-color: #0a5b9e;*/
        /*border-top-right-radius: 3px;*/
        /*border-top-left-radius: 3px;*/
    /*}*/
</style>
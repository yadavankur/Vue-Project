<template>
    <div>
        <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <business-calendar-body v-if="isShowModal"
                                        :dataItem="dataItem"
                                        ref="businessCalendarBody">
                </business-calendar-body>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                <button type="button" class="btn btn-success" @click="onClickSave">Save</button>
            </div>
        </custom-modal>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import BusinessCalendarBody from './BusinessCalendarBody.vue'

    export default {
        props: {
            isShow: {
                type: Boolean,
                default: false,
            },
            dataItem: {
                type: Object,
                default: null,
            },
            title: {
                type: String,
                default: '',
            }
        },
        data () {
            return {
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
            isShowModal() {
                return this.isShow;
            }
        },
        created() {
            console.log('BusinessCalendarModal created.')
        },
        components: {
            'business-calendar-body': BusinessCalendarBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('BusinessCalendarModal mounted.')
        },
        methods:
        {
            onClickSave() {
                console.log('onClickSave');
                // validation check
                if (this.$refs.businessCalendarBody.formData.location == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the location!');
                    return;
                }
                if (this.isEmpty(this.$refs.businessCalendarBody.formData.type))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the date type!');
                    return;
                }
                if (this.isEmpty(this.$refs.businessCalendarBody.formData.businessDate))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the date!');
                    return;
                }
                if (this.isEmpty(this.$refs.businessCalendarBody.formData.description))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the date description!');
                    return;
                }
                let formData = this.$refs.businessCalendarBody.formData;
                if (this.dataItem.action == 'Edit')
                {
                    // edit
                    this.$store.dispatch('updateBusinessCalendarRequest', formData)
                        .then((response) => {
                            console.log('saveBusinessCalendarRequest response=', response);
                            this.$emit('onCloseModal');
                            // fire refresh event
                            this.$events.fire('refreshBusinessCalendarTable');
                        })
                        .catch((error) => {
                            console.log('saveBusinessCalendarRequest error=', error);
                        });
                }
                else if (this.dataItem.action == 'Add')
                {
                    // new
                    this.$store.dispatch('saveBusinessCalendarRequest', formData)
                        .then((response) => {
                            console.log('saveBusinessCalendarRequest response=', response);
                            this.$emit('onCloseModal');
                            // fire refresh event
                            this.$events.fire('refreshBusinessCalendarTable');
                        })
                        .catch((error) => {
                            console.log('saveBusinessCalendarRequest error=', error);
                        });
                }
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseModal');
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

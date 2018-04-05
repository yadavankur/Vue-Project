<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Dowell Notes Confirmation </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <dowell-notes-preparation-body v-if="isShowModal" ref="dowellNotes"></dowell-notes-preparation-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickSave">Save</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import DowellNotesPreparationBody from './DowellNotesPreparationBody.vue'
    export default {
        props: {
            isShowDowellNotes: false,
            orderId: '',
            quoteId: '',
            location: '',
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowDowellNotes;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
                //selectedOrder: state => state.tab.selectedOrder,
            }),
        },
        created() {
            console.log('DowellNotesPreparationModal Component created.')
        },
        components: {
            'dowell-notes-preparation-body': DowellNotesPreparationBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('DowellNotesPreparationModal Component mounted.')
        },
        methods:
        {
            onClickSave() {
                console.log('onClickSave');
                if (this.isEmpty(this.$refs.dowellNotes.formData.noteTypeId))
                {
                    this.$store.dispatch('showErrorNotification', 'Please select a note type!');
                    return;
                }
                if (this.isEmpty(this.$refs.dowellNotes.formData.dowellNotesContent))
                {
                    // cannot check whether there are only spaces if user input space deliberately
                    // because it will include <p> tag in the content
                    this.$store.dispatch('showErrorNotification', 'Please input the notes!');
                    return;
                }
                this.$emit('onCloseDowellNotesModal');
                // send save request
                let formData = {
                    //orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                    quoteId: this.quoteId,
                    location: this.location,
                    orderId: this.orderId,
                    noteTypeId: this.$refs.dowellNotes.formData.noteTypeId,
                    notes: this.$refs.dowellNotes.formData.dowellNotesContent
                };
                console.log('onClickSave formData=', formData);
                // SEND EMAIL REQUEST
                this.$store.dispatch('saveDowellNotesRequest', formData)
                    .then((response) => {
                        console.log('saveDowellNotesRequest response=', response);
                        // refresh the dowell notes
                        this.$emit('onRefreshNotes');
                    })
                    .catch((error) => {
                            console.log('saveDowellNotesRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseDowellNotesModal');
            }
        }
    }
</script>

<style scoped>
</style>



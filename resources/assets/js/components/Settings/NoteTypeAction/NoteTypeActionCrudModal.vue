<template>
    <div>
        <div>
            <custom-modal :value="isShowNoteTypeAction" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> {{ title }}</h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <note-type-action-body v-if="isShowNoteTypeAction"
                                           :itemData="itemData"
                                           :locationOptions="locationOptions"
                                           :noteTypeOptions="noteTypeOptions"
                                            ref="noteTypeBody">
                    </note-type-action-body>
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
    import NoteTypeActionBody from './NoteTypeActionBody.vue'

    export default {
        props: {
            isShowNoteTypeAction: false,
            title: '',
            actionType: '',
            itemData: {
                type: Object,
                default: null,
            },
            locationOptions: {
                type: Array,
                default: [],
            },
            noteTypeOptions: {
                type: Array,
                default: [],
            },
        },
        data () {
            return {
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('NoteTypeActionCrud Component created.')
        },
        components: {
            'note-type-action-body': NoteTypeActionBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('NoteTypeActionCrud Component mounted.')
        },
        methods:
        {
            onClickSave() {
                console.log('onClickSave');
                if (this.$refs.noteTypeBody.formData.location_id == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the location!');
                    return;
                }
                if (this.$refs.noteTypeBody.formData.note_type_id == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the note type!');
                    return;
                }
                let formData = this.$refs.noteTypeBody.formData;
                formData.type = this.actionType;
                console.log('onClickSave formData=', formData);
                // SEND UPDATE REQUEST
                this.$store.dispatch('updateNoteTypeActionRequest', formData)
                    .then((response) => {
                        console.log('updateNoteTypeActionRequest response=', response);
                        this.$emit('onCloseNoteTypeActionModal');
                        this.$events.fire('refreshNoteTypeActionListTable')
                    })
                    .catch((error) => {
                        console.log('updateNoteTypeActionRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseNoteTypeActionModal');
            }
        }
    }
</script>

<style scoped>
</style>



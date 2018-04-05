<template>
    <div>
        <div>
            <custom-modal :value="isShowNoteType" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> {{ title }}</h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <note-type-body v-if="isShowNoteType"
                                    :itemData="itemData"
                                    ref="noteTypeBody">
                    </note-type-body>
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
    import NoteTypeBody from './NoteTypeBody.vue'

    export default {
        props: {
            isShowNoteType: false,
            title: '',
            actionType: '',
            itemData: {
                type: Object,
                default: null,
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
        },
        created() {
            console.log('NoteTypeCrud Component created.')
        },
        components: {
            'note-type-body': NoteTypeBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('NoteTypeCrud Component mounted.')
        },
        methods: {
            onClickSave() {
                console.log('onClickSave');
                if (this.isEmpty(this.$refs.noteTypeBody.formData.name))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the note type name!');
                    return;
                }
                let formData = this.$refs.noteTypeBody.formData;
                formData.type = this.actionType;
                console.log('onClickSave formData=', formData);
                // SEND UPDATE REQUEST
                this.$store.dispatch('updateNoteTypeRequest', formData)
                    .then((response) => {
                        console.log('updateNoteTypeRequest response=', response);
                        this.$emit('onCloseNoteTypeModal');
                        this.$events.fire('refreshNoteTypeListTable')
                    })
                    .catch((error) => {
                        console.log('updateNoteTypeRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseNoteTypeModal');
            }
        }
    }
</script>

<style scoped>
</style>
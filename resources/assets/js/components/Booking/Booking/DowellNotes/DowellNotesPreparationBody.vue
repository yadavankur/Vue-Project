<template>
    <div>
        <div>
            <div class="dowellNotes-label">
                <div><label for="noteType">Note Type</label></div>
                    <Select  v-model="formData.noteTypeId"
                        @on-change="onChangeNoteType"
                        placeholder="Please select a note type...">
                        <Option v-for="item in noteTypeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                    </Select>
            </div>
            <div class="dowellNotes-content">
                <div><label for="dowellNotesContent">Notes</label></div>
                <dowell-notes-editor v-model="formData.dowellNotesContent"
                                       ref="dowellNotesQuillEditor"
                                       :options="editorOption">
                </dowell-notes-editor>
            </div>
        </div>
    </div>
</template>

<script>
    import { quillEditor } from 'vue-quill-editor'
    export default {
        data () {
            return {
                editorOption: {
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'font': [] }],
                            [{ 'align': [] }],
                            ['link', 'image']
                        ],
                    },
                    placeholder: 'Please input notes...',
                    theme: 'snow'
                },
                formData: {
                    dowellNotesContent: '',
                    noteTypeId: '',
                },
                noteTypeOptions: '',
            }
        },
        created() {
            console.log('DowellNotesPreparationBody Component created.')
            this.getDowellNoteTypeOptions();
        },
        components: {
            'dowell-notes-editor': quillEditor,
        },
        mounted() {
            console.log('DowellNotesPreparationBody Component mounted.')
        },
        methods:
        {
            onChangeNoteType(val) {
                console.log('onChangeNoteType val=', val);
            },
            updateData: function (data) {
                console.log('updateData data=', data);
                // sync content to component
                this.formData.dowellNotesContent = data;
            },
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>

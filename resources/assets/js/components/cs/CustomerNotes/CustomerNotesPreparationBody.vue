<template>
    <div>
        <div>
            <div class="customerNotes-content">
                <div><label for="customerNotesContent">Notes</label></div>
                <customer-notes-editor v-model="customerNotesContent"
                                     ref="customerNotesQuillEditor"
                                     :options="editorOption">
                </customer-notes-editor>
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
                    //readOnly: true,
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
                customerNotesContent: '',
            }
        },
        created() {
            console.log('CustomerNotesPreparationBody Component created.')
        },
        components: {
            'customer-notes-editor': quillEditor,
        },
        mounted() {
            console.log('CustomerNotesPreparationBody Component mounted.')
        },
        methods:
        {
            updateData: function (data) {
                console.log('updateData data=', data);
                // sync content to component
                this.customerNotesContent = data;
            },
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>

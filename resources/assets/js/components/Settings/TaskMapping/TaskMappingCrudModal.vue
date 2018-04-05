<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> {{ title }}</h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <task-mapping-body v-if="isShowModal"
                                    :itemData="itemData"
                                    ref="taskMappingBody">
                    </task-mapping-body>
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
    import TaskMappingBody from './TaskMappingBody.vue'

    export default {
        props: {
            isShowModal: false,
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
            console.log('TaskMappingCrud Component created.')
        },
        components: {
            'task-mapping-body': TaskMappingBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('TaskMappingCrud Component mounted.')
        },
        methods: {
            onClickSave() {
                console.log('onClickSave');
                if (this.$refs.taskMappingBody.formData.location.id == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the location and service!');
                    return;
                }
                if (this.$refs.taskMappingBody.formData.activity.id == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the activity!');
                    return;
                }
                if (this.$refs.taskMappingBody.formData.task_name == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the task name!');
                    return;
                }
                let formData = this.$refs.taskMappingBody.formData;
                formData.type = this.actionType;
                console.log('onClickSave formData=', formData);
                // SEND UPDATE REQUEST
                this.$store.dispatch('updateTaskMappingRequest', formData)
                    .then((response) => {
                        console.log('updateTaskMappingRequest response=', response);
                        this.$emit('onCloseTaskMappingModal');
                        this.$events.fire('refreshTaskMappingListTable')
                    })
                    .catch((error) => {
                        console.log('updateTaskMappingRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseTaskMappingModal');
            }
        }
    }
</script>

<style scoped>
</style>
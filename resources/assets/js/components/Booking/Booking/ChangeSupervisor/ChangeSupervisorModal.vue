<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" large effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Changing Supervisor </h4>
                </div>
                <div slot="modal-body" class="modal-body table-responsive">
                    <change-supervisor-body
                            v-if="isShowModal"
                            :order="order"
                            @newSupervisor="newSupervisor"
                            @selectSupervisor="selectSupervisor"
                            ref="changeSupervisor"></change-supervisor-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import ChangeSupervisorBody from './ChangeSupervisorBody.vue'

    export default {
        props: {
            isShowChangeSupervisor: false,
            order: null,
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowChangeSupervisor;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('ChangeSupervisorModal Component created.')
        },
        components: {
            'change-supervisor-body': ChangeSupervisorBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('ChangeSupervisorModal Component mounted.')
        },
        methods:
        {
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseChangeSupervisorModal');
            },
            newSupervisor() {
                console.log('ChangeSupervisorModal -> newSupervisor');
                this.$emit('newSupervisor');
            },
            selectSupervisor(dataItem) {
                console.log('ChangeSupervisorModal -> selectSupervisor dataItem=', dataItem);
                this.$emit('selectSupervisor', dataItem);
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



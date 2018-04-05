<template>
    Not used
    <button
            @click="onClick"
            :disabled="isLoading"
            :status="status"
            class="btn-loader">
        <transition name="fade" mode="out-in">
            <i v-if="isLoading" class="fa fa-spinner fa-spin"></i>
        </transition>
        <slot>Submit</slot>
    </button>

</template>

<script>
    export default {
        name: 'loading-button',
        props: {
            id:{
                type: String,
                default: ''
            },
            isLoading: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: "button"
            },
            disabled: {
                type: Boolean,
                default: false
            },
            status: {
                type: String,
                default: ''
            },
        },
        methods: {
            onClick() {
                this.$emit('onClick')
            }
        }
    };
</script>

<style lang="css" scoped>

</style>
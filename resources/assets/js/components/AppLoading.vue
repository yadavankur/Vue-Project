<template>
    <div id="apploading">
        <transition name="fade" mode="in-out">
            <bs-loading ref="spinner" global v-model="isLoading" size="lg" fixed text="loading..."></bs-loading>
        </transition>
    </div>
</template>

<script>

    import Vue from 'vue'
    import { mapState } from 'vuex'
    import spinner from 'vue-strap/src/Spinner'

    export default {
        data () {
            return {}
        },
        computed: {
            ...mapState({
                isLoading : state => state.loading.isLoading,
            }),
        },
        created() {
            console.log('/resources/js/components/AppLoadin.vue-Apploading Component created.')
        },
        components: {
            'bs-loading': spinner,
        },
        mounted() {
            console.log('/resources/js/components/AppLoading.vue-Apploading Component mounted.')
        },
        watch: {
        }
    }
</script>


<style>
</style>


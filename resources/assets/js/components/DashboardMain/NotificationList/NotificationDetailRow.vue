<template>
    <div class="notification-detail-row" @click="onClick">
        <div class="inline field">
            <label>Message: </label>
            <span v-html="rowData.message"></span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            rowData: {
                type: Object,
                required: true
            },
            rowIndex: {
                type: Number
            }
        },
        methods: {
            onClick (event) {
                console.log('notification-detail-row: on-click', event.target)
            }
        },
    }
</script>
<style scoped>
</style>

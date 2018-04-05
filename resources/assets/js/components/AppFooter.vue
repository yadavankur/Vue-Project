<template>
    <div class="app-footer">
            <footer class="layout-footer">
                {{ siteVersion }} &copy; all rights reserved by <span v-once>{{ companyName }}</span> <span v-once>{{ currentYear }}</span>
            </footer>
    </div>
</template>
<script>
import {companyName, siteVersion} from './../config';
    export default {
        props: {

        },
        data() {
            return {
                companyName: companyName,
                siteVersion: siteVersion,
                currentYear: (new Date).getFullYear()
            }
        },
        methods: {
        },
        mounted() {
            console.log('mounted Footer Component ready.');
        }
    }
</script>
<style scoped>
.layout-footer {
	padding: 13px 0 10px 0;
	background: #0a5b9e;
	color: #fff;
	text-align: center;
	font-size: 14px;
	-webkit-box-shadow: 0px 3px 5px 0 rgba(0,0,0,0.5) inset;
	box-shadow: 0px 3px 5px 0 rgba(0,0,0,0.3) inset;
}
</style>
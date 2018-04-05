<template>
    <div id="php-play-ground">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <Input v-model="phpCode" type="textarea" :rows="20" placeholder="Input php code here...."></Input>
                    </div>
                </div>
                <hr>
                <Button type="success" long @click="runCode">Click to run php code</Button>
                <hr>
                <div class="row">
                    <label for="result">Result</label>
                    <div class="col-md-12" v-if="result">
                        <div v-if="htmlFlag" v-html="result"></div>
                        <div v-else><pre>{{ result }}</pre></div>
                    </div>
                    <div class="col-md-12" v-if="error" v-html="error">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                phpCode: '',
                result: '',
                error: '',
                htmlFlag: false,
            }
        },
        created() {
            console.log('Playground vue created!');
        },
        mounted() {
            console.log('Playground vue mounted');
        },
        components: {
        },
        methods: {

            runCode() {
                this.result = '';
                this.error = '';
                let payload = {
                    code: this.phpCode,
                };
                this.$store.dispatch('runPhpCode', payload)
                    .then((response) => {
                        console.log('Playground vue created response=', response);
                        this.result = response.body;
                        //let res = this.result.match(/<!DOCTYPE html/g);
                        let n = this.result.search("DOCTYPE html");
                        if (n > 0)
                        {
                            this.htmlFlag = true;
                        }
                        else
                            this.htmlFlag = false;
                    })
                    .catch((error) => {
                        console.error('Playground vue created error=', error);
                        this.error = error.body;
                    });
            }
        }
    }
</script>

<style scoped>
    /* main */
    .main {
        padding: 20px 0;
        background-color: #f7efd3;
    }
    .result {
        height: 400px;
        overflow-y: scroll;
    }
    pre {
        height: 400px;
    }
</style>

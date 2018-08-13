<template>
    <div slot="section" class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Quick Form</h3>
            </div>
            <div class="panel-body table-responsive" style="font-size: 15px;">
                <div class="tab-pane">
                    <form class="form-horizontal" autocomplete="off">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Term: {{ $route.params.term }}</label>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-2">
                                <input type="radio" id="anatomical" v-model="quickForm.structure" value="anatomical" name="structure" />
                                <label for="anatomical">Anatomical Structure</label>
                            </div>
                            <div class="col-md-6 col-md-offset-2">
                                <input type="radio" id="character" v-model="quickForm.structure" value="character" name="structure" />
                                <label for="character">Character or character state</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Definition for the term: </label>

                            <div class="col-md-6">
                                <input type="text" v-model="quickForm.definition" class="form-control" name="definition">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Sentences using the term: </label>

                            <div class="col-md-6">
                                <input type="text" v-model="quickForm.sentences" class="form-control" name="sentences">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Related taxa: </label>

                            <div class="col-md-6">
                                <input type="text" v-model="quickForm.taxa" class="form-control" name="taxa">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <a v-on:click="submit()" class="btn btn-primary">
                                    Done
                                </a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data: function() {
            return {
                username: sessionStorage.getItem('username'),
                quickForm: {
                    structure: null,
                    definition: null,
                    sentences: null,
                    taxa: null
                }
            }
        },
        beforeMount() {
            var app = this;
            app.username = sessionStorage.getItem('username');
        },
        methods: {
            submit() {
                var app = this;
                console.log('quickForm submit', app.quickForm);
                if (app.username == null) {
                    alert('Please insert the username on homepage');
                } else {
                    var jsonRequest = {
                        'user_email': app.username,
                        'action': 'Submit Quick Form',
                        'action_details': app.username + ' submitted for term ',
                        'abnormal_system_response': null,
                        'type': 'Quick Form'
                    };
                    axios.post('/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function (resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function (resp) {
                            console.log('activity-log error resp', resp);
                        });
                }

            }
        }
    }
</script>

<template>
    <div slot="section" class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-3">
                        <h4 class="text-right">User name: </h4>

                    </div>
                    <div class="col-md-5">
                        <input type="text" v-model="username" style="width: 100%; height: 36px; padding: 6px 12px; font-size: 14px; line-height: 1.6; color: #555555; border: 1px solid #ccd0d2; border-radius: 4px;" name="username" v-on:blur="saveUsername()" autofocus>
                    </div>
                </div>

            </div>
            <div class="panel-body table-responsive">
                <div class="tab-pane">
                    <form class="form-horizontal" autocomplete="off">

                        <div class="form-group">
                            <div class="col-md-12 " style="padding-top: 20px;">
                                <h4>Use on of the following ways to add {{ $route.params.term }} to the CAREX ontology</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 text-center">
                                <a :href="'/wizard/' + $route.params.term" class="btn btn-primary text-center" style="width: 200px; height: 140px; color: black; padding-top: 10px;">
                                    <p style="font-size: 25px; padding-top: 20px; font-weight: bold;">Wizard</p>
                                    Guide by Wizard
                                </a>
                            </div>
                            <div class="col-md-3 text-center">
                                <a :href="'/quick-form/' + $route.params.term" class="btn btn-primary text-center" style="width: 200px; height: 140px; color: black; padding-top: 10px;">
                                    <p style="font-size: 25px; padding-top: 20px; font-weight: bold;">Quick Form</p>
                                    Fill a form
                                </a>
                            </div>
                            <div class="col-md-3 text-center">
                                <a :href="'/web-protege/' + $route.params.term" class="btn btn-primary text-center" style="width: 200px; height: 140px; color: black; padding-top: 10px;">
                                    <p style="font-size: 25px; padding-top: 20px; font-weight: bold;">WebProtege</p>
                                    Add term to WP directly
                                </a>
                            </div>
                            <div class="col-md-3 text-center">
                                <a :href="'/terms-wiki/' + $route.params.term" class="btn btn-primary text-center" style="width: 200px; height: 140px; color: black; padding-top: 10px;">
                                    <p style="font-size: 25px; padding-top: 20px; font-weight: bold;">Terms Wiki</p>
                                    Add term to wiki directly
                                </a>
                            </div>
                        </div>
                        <div class="form-group" style="font-size: 15px;">
                            <div class="col-md-3 text-center">
                                <a href="http://www.google.com" class="text-center" style="color: blue; text-decoration: underline;">Video Tutorial</a>
                            </div>
                            <div class="col-md-3 text-center">
                                <a href="http://www.google.com" class="text-center" style="color: blue; text-decoration: underline;">Video Tutorial</a>
                            </div>
                            <div class="col-md-3 text-center">
                                <a href="http://www.google.com" class="text-center" style="color: blue; text-decoration: underline;">Video Tutorial</a>
                            </div>
                            <div class="col-md-3 text-center">
                                <a href="http://www.google.com" class="text-center" style="color: blue; text-decoration: underline;">Video Tutorial</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <!--<a v-on:click="saveUsername()" class="btn btn-primary">-->
                                    <!--Save-->
                                <!--</a>-->

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
                username: ''
            }
        },
        beforeMount() {
            var app = this;
            app.username = sessionStorage.getItem('username');
        },
        methods: {
            saveUsername() {
                var app = this;
                sessionStorage.setItem('username', app.username);
                var jsonRequest = {
                    'user_email': app.username,
                    'action': 'Save Username',
                    'action_details': app.username + ' saved as username for term ',
                    'abnormal_system_response': null,
                    'type': 'Home'
                };
                var term = 'TTBA';
                console.log('term', app.$route.params.term);
                axios.post('/add2ontology/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                    .then(function (resp) {
                        console.log("activity-log resp", resp);
                    })
                    .catch(function (resp) {
                        console.log('activity-log error resp', resp);
                    });
            }
        }
    }
</script>

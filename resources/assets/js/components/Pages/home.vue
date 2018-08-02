<template>
    <div slot="section" class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Home</h3>
            </div>
            <div class="panel-body table-responsive">
                <div class="tab-pane">
                    <form class="form-horizontal" autocomplete="off">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input type="text" v-model="username" class="form-control" name="username" autofocus>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <a v-on:click="saveUsername()" class="btn btn-primary">
                                    Save
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
                username: null
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
                    'type': 1,
                    'detail': 'User saved username',
                    'detail_addition': 'User saved username as ' + app.username
                };
                axios.post('/api/v1/activity-log', jsonRequest)
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

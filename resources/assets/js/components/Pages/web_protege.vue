<template>
    <div slot="section" class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>WebProtege</h3>
            </div>
            <div class="panel-body table-responsive" style="min-height: 500px;">
                <div class="tab-pane" id="" style="font-size: 15px;">
                    <form class="row" autocomplete="off">
                        <div class="col-md-8 col-md-offset-2">
                            WebProtege: <a href="https://webprotege.stanford.edu/#projects/ef6a650d-ca8d-4a20-8231-71b638b5f88b" target="_blank" v-on:click="clickLink()" style="cursor: pointer;">https://webprotege.stanford.edu/#projects/ef6a650d-ca8d-4a20-8231-71b638b5f88b</a>
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            Click on the WebProtege link above and add [selected word/phrase] to CAREX Ontology.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                username: sessionStorage.getItem('username')
            }
        },
        beforeMount() {
            console.log('Component mounted.')
        },
        methods: {
            clickLink: function() {
                var app = this;
                var jsonRequest = {
                    'user_email': app.username,
                    'type': 2,
                    'detail': app.username + ' entered URL',
                    'detail_addition': 'URL: https://webprotege.stanford.edu/#projects/ef6a650d-ca8d-4a20-8231-71b638b5f88b'
                };
                if (app.username == null) {
                    alert('Please insert the username on homepage');
                } else {
                    axios.post('/add2ontology/public/api/v1/activity-log', jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                }
            }
        }
    }
</script>

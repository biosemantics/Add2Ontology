<template>
    <div slot="section" class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Terms Wiki</h3>
            </div>
            <div class="panel-body table-responsive" style="min-height: 500px;">
                <div class="tab-pane" id="" style="font-size: 15px;">
                    <form class="row" autocomplete="off">
                        <div class="col-md-8 col-md-offset-2">
                            Use terms wiki: <a href="https://terms.tdwg.org/wiki/FloraTerms" target="_blank" style="cursor: pointer;">https://terms.tdwg.org/wiki/FloraTerms</a>
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            Click on the FloraTerms link above and add [selected word/phrase] to Flora Terms.
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            Then enter the term IRI here:
                            <input v-model="termsWikiIRI" style="width: 100%;"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <img src="/add2ontology/public/images/terms_wiki.png" style="width: 100%;"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8 text-right" style="margin-top: 20px;">
                            <a class="btn btn-primary" v-on:click="done()">Done</a>
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
                termsWikiIRI: null,
                username: sessionStorage.getItem('username')
            }
        },
        beforeMount() {
            console.log('Component mounted.')
        },
        methods: {
            done: function() {
                var app = this;
                var jsonRequest = {
                    'user_email': app.username,
                    'action': 'Save IRI',
                    'action_details': app.termsWikiIRI + ' saved for term',
                    'abnormal_system_response': null,
                    'type': 'Terms Wiki'
                };
                if (app.username == null) {
                    alert('Please insert the username on homepage');
                } else {
                    axios.post('/add2ontology/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
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

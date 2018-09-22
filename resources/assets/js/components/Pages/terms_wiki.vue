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
        mounted() {
            var app = this;
            var jsonRequest = {
                'user_email': app.username,
                'action': 'Module landed',
                'action_details': 'TermsWiki module was landed for term',
                'abnormal_system_response': null,
                'type': 'Terms Wiki'
            };
            if (app.username == null || app.username == 'null' || app.username == '') {
                alert('Please insert the username on homepage.');
            } else {
                axios.post('/add2ontology/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                    .then(function(resp) {
                        console.log("activity-log resp", resp);
                    })
                    .catch(function(resp) {
                        console.log("activity-log error resp", resp);
                    });
            }
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
                if (app.username == null || app.username == 'null' || app.username == '') {
                    alert('Please insert the username on homepage');
                } else if (app.termsWikiIRI == null) {
                    jsonRequest.action = 'clicked Done';
                    jsonRequest.action_details = app.username + ' clicked Done for term ';
                    jsonRequest.abnormal_system_response = 'without entry';
                    axios.post('/add2ontology/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    alert('You need to enter IRI in the input box.');
                } else if (app.termsWikiIRI.substring(0, 7) != 'http://') {
                    jsonRequest.action_details = app.username + ' input invalid IRI for term ';
                    jsonRequest.action = 'clicked Done';
                    jsonRequest.abnormal_system_response = 'invalid entry';
                    axios.post('/add2ontology/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    alert('The format you input is not valid.');
                } else {
                    axios.post('/add2ontology/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    var jsonClass = {
                        "term": app.$route.params.term + ' ' + app.termsWikiIRI,
                        "superclassIRI": "http://biosemantics.arizona.edu/ontologies/carex#toreview",
                        "definition": "tba",
                        "elucidation": "tba",
                        "createdBy": app.username,
                        "creationDate": new Date(),
                        "definitionSrc": "tba",
                        "examples": "tba",
                        "logicDefinition": "tba",
                    };
                    axios.post('http://shark.sbs.arizona.edu:8080/class', jsonClass)
                        .then(function(resp) {
                            console.log('class resp', resp);
                            axios.post('http://shark.sbs.arizona.edu:8080/save', {})
                                .then(function(resp) {
                                    console.log('save resp', resp);
                                })
                                .catch(function(resp) {
                                    console.log('save error resp', resp);
                                });
                        })
                        .catch(function(resp) {
                            console.log('class error resp', resp);
                        });
                }
            }
        }
    }
</script>

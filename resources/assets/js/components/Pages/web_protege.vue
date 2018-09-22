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
                            WebProtege: <a href="https://webprotege.stanford.edu/#projects/ef6a650d-ca8d-4a20-8231-71b638b5f88b" target="_blank" style="cursor: pointer;">https://webprotege.stanford.edu/#projects/ef6a650d-ca8d-4a20-8231-71b638b5f88b</a>
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            Click on the WebProtege link above and add [selected word/phrase] to CAREX Ontology.
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            Then enter the term IRI here:
                            <input v-model="webProtegeIRI" style="width: 100%;"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <img src="/add2ontology/public/images/web_protege.png" style="width: 100%;"/>
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
                webProtegeIRI: null,
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
                'action': 'Module Landed',
                'action_details': 'WebProtege module was landed for term',
                'abnormal_system_response': null,
                'type': 'WebProtege'
            };
            if (app.username == null) {
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
            },
            done: function() {
                var app = this;
                var jsonRequest = {
                    'user_email': app.username,
                    'action': 'Save IRI',
                    'action_details': app.webProtegeIRI + ' saved for term',
                    'abnormal_system_response': null,
                    'type': 'Web Protege'
                };
                if (app.username == null) {
                    alert('Please insert the username on homepage');
                } else if (app.webProtegeIRI.substring(0, 7) != 'http://') {
                    alert('The input you provide is not valid.')
                } else {
                    axios.post('/add2ontology/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    var jsonClass = {
                        "term": app.$route.params.term + ' ' + app.webProtegeIRI,
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

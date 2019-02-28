<template>
    <div slot="section" class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Wiki Data</h3>
            </div>
            <div class="panel-body table-responsive" style="min-height: 500px;">
                <div class="tab-pane" id="" style="font-size: 15px;">
                    <form class="row" autocomplete="off">
                        <div class="col-md-8 col-md-offset-2">
                            Use Wiki Data: <a href="https://terms.tdwg.org/wiki/FloraTerms" target="_blank" style="cursor: pointer;">https://terms.tdwg.org/wiki/FloraTerms</a>
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            <label>Click on the link above and add <b>"{{ $route.params.term }}"</b> to the ontology. </label>
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            <label>Then enter the term URI here: (See the image below on where to find the URI for the term)</label>
                            <input v-model="termsWikiIRI" style="width: 100%;"/>
                            <div style="width:100%; height:40px;"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <img src="/add2ontologymodular/public/images/terms_wiki.png" style="width: 100%; border-style: solid;"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8 text-right" style="margin-top: 20px;">
                            <a class="btn btn-primary" v-on:click="done()">Done</a>
                            <div v-if="status == 8">
                                <label><i>{{ $route.params.term }}</i> has been added to the ontology.</label> 
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
                'user_email': app.$route.params.user,
                'action': 'Module landed',
                'action_details': 'TermsWiki module was landed for term',
                'abnormal_system_response': null,
                'type': 'Wiki Data'
            };
            //if (app.$route.params.user == null || app.$route.params.user == 'null' || app.$route.params.user == '') {
            //    alert('Please insert the username on homepage.');
            //} else {
                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                    .then(function(resp) {
                        console.log("activity-log resp", resp);
                    })
                    .catch(function(resp) {
                        console.log("activity-log error resp", resp);
                    });
            //}
        },
        methods: {
            done: function() {
                var app = this;
                var jsonRequest = {
                    'user_email': app.$route.params.user,
                    'action': 'Save IRI',
                    'action_details': app.termsWikiIRI + ' saved for term',
                    'abnormal_system_response': null,
                    'type': 'Wiki Data'
                };
                //if (app.$route.params.user == null || app.$route.params.user == 'null' || app.$route.params.user == '') {
                //    alert('Please insert the username on homepage');
                /*} else*/ if (app.termsWikiIRI == null) {
                    jsonRequest.action = 'clicked Done';
                    jsonRequest.action_details = app.$route.params.user + ' clicked Done for term ';
                    jsonRequest.abnormal_system_response = 'without entry';
                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    alert('You need to enter IRI in the input box.');
                } else if (app.termsWikiIRI.substring(0, 7) != 'http://') {
                    jsonRequest.action_details = app.$route.params.user + ' input invalid IRI for term ';
                    jsonRequest.action = 'clicked Done';
                    jsonRequest.abnormal_system_response = 'invalid entry';
                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    alert('The format you input is not valid.');
                } else {
                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    var jsonClass = {
                        "user":"",
                        "ontology": app.$route.params.ontology,
                        "term": app.$route.params.term + ' ' + app.termsWikiIRI,
                        "superclassIRI": "http://biosemantics.arizona.edu/ontologies/carex#toreview",
                        "definition": "tba",
                        "elucidation": "tba",
                        "createdBy": app.$route.params.user +" via add2ontology terms wiki",
                        "creationDate": new Date(),
                        "definitionSrc": "tba",
                        "examples": "tba",
                        "logicDefinition": "tba",
                    };
                    axios.post('http://shark.sbs.arizona.edu:8080/class', jsonClass)
                        .then(function(resp) {
                            console.log('class resp', resp);
                            var jsonSaveRequest = {
                        "user":"",
                        "ontology": app.$route.params.ontology
                        };
                            axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
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
                app.status = 8;
            }
            
        }
    }
</script>

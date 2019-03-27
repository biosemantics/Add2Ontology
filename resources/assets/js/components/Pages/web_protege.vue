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
                            WebProtege: <a href="https://webprotege.stanford.edu/" target="_blank" style="cursor: pointer;" @click="logClick">https://webprotege.stanford.edu/</a>
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            <label>Click on the WebProtege link above and add <span style="color:dodgerBlue"><i>{{ $route.params.term }}</i></span> to CAREX Ontology. </label>
                        </div>
                        <div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
                            <label>Then enter the term IRI here: (See the image below on where to find the URI for the term)</label>
                            <input v-model="webProtegeIRI" style="width: 100%;"/>
                            <div style="width:100%; height:40px;"/>
                        </div>
                     
                        <div class="col-md-offset-2 col-md-8">
                            <img src="/add2ontologymodular/public/images/web_protege.png" style="width: 100%;"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8 text-right" style="margin-top: 20px;">
                            <a class="btn btn-primary" v-on:click="done()">Done</a>
                            <div v-if="status == 1 ">
                                <label><i>{{ $route.params.term }}</i> &nbsp; has been added to the ontology.</label> 
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
                webProtegeIRI: null,
                username: sessionStorage.getItem('username'),
                status: 0,
                clicked: 0,
            }
        },
        beforeMount() {
            console.log('Component mounted.')
        },
        mounted() {
            var app = this;
            var jsonRequest = {
                'user_email': app.$route.params.user,
                'action': 'Module loaded',
                'action_details': 'WebProtege module was loaded for term',
                'abnormal_system_response': null,
                'type': 'WebProtege'
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
            logClick: function(){
                var app = this;
                app.clicked = 1;
                var jsonRequest = {
                    'user_email': app.$route.params.user,
                    'action': 'Clicked WebProtege link',
                    'action_details': 'Clicked WebProtege link for term=' +  app.$route.params.term,
                    'abnormal_system_response': null,
                    'type': 'Web Protege'
                };
                 axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                
            },
            //clickLink: function() {
            //    var app = this;
            //    var jsonRequest = {
            //        'user_email': app.$route.params.user,
            //        'type': 2,
            //        'detail': app.$route.params.user + ' entered URL',
            //        'detail_addition': 'URL: https://webprotege.stanford.edu/'
            //    };
             //   //if (app.$route.params.user == null || app.$route.params.user == 'null' || app.$route.params.user == '') {
             //   //   alert('Please insert the username on homepage');
             //   //} else {
             //       axios.post('/add2ontologymodular/public/api/v1/activity-log', jsonRequest)
             //           .then(function(resp) {
             //               console.log("activity-log resp", resp);
              //          })
             //           .catch(function(resp) {
             //               console.log("activity-log error resp", resp);
             //           });
                //}
            //},
            done: function() {
                var app = this;
                //app.status = 1;
                var jsonRequest = {
                    'user_email': app.$route.params.user,
                    'action': 'Saved IRI',
                    'action_details': app.webProtegeIRI + ' saved for term',
                    'abnormal_system_response': null,
                    'type': 'Web Protege'
                };
               // if (app.$route.params.user == null || app.$route.params.user == 'null' || app.$route.params.user == '') {
               //     alert('Please insert the username on homepage');
               /*} else*/
               if(app.clicked == 0){
                    jsonRequest.action = 'clicked Done';
                    jsonRequest.action_details = app.$route.params.user + ' clicked Done for term ';
                    jsonRequest.abnormal_system_response = 'did not click open WebProtege link';
                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    alert('You need to click on the WebProtege link and add the term in WebProtege.');
               }
               else if (app.webProtegeIRI == null) {
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
                } else if (app.webProtegeIRI.substring(0, 7) != 'http://' && app.webProtegeIRI.substring(0, 8) != 'https://') {
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
                    alert('Enter the IRI and make sure it is a complete IRI.');
                } else {
                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                    var jsonClass = {
                        "user":app.$route.params.user,
                        "ontology":app.$route.params.ontology,
                        "term": app.$route.params.term + ' ' + app.webProtegeIRI,
                        "superclassIRI": "http://biosemantics.arizona.edu/ontologies/carex#toreview",
                        "definition": "tba",
                        "elucidation": "tba",
                        "createdBy": app.$route.params.user +" via add2ontology webprotege",
                        "creationDate": new Date(),
                        "definitionSrc": "tba",
                        "examples": "tba",
                        "logicDefinition": null,
                    };
                    axios.post('http://shark.sbs.arizona.edu:8080/class', jsonClass)
                        .then(function(resp) {
                            console.log('class resp', resp);
                               var jsonSaveRequest = {
                        "user":app.$route.params.user,
                        "ontology":app.$route.params.ontology
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
                        app.status = 1;
                }
            }
        }
    }
</script>

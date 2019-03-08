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
                            <div class=" col-md-offset-1 col-md-9" style="font-size: 16px;">Fill the form below to add <b> {{ $route.params.term }} </b> to CAREX Ontology. * indicates required fields</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4" style="padding-left: 130px;"><i>{{ $route.params.term }}</i> &nbsp; is a * </label>
                            <div class="col-md-6 col-md-offset-4">
                                <input type="radio" id="anatomical" v-model="quickForm.structure" value="anatomical" name="structure" />
                                <label for="anatomical">anatomical structure</label>
                            </div>
                            <div class="col-md-6 col-md-offset-4">
                                <input type="radio" id="character" v-model="quickForm.structure" value="character" name="structure" />
                                <label for="character">character or character state</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4" style="padding-left: 130px;">Definition for the term: * </label>
                            <div class="col-md-6">
                                <input type="text" v-model="quickForm.definition" class="form-control" name="definition" :placeholder="'what is a '+ $route.params.term +' and how it is related to other structures or characters'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4" style="padding-left: 130px;">Sentences using the term: </label>

                            <div class="col-md-6">
                                <input type="text" v-model="quickForm.sentences" class="form-control" name="sentences" placeholder="provide a sentence or two where this term has been used">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4" style="padding-left: 130px;">Related taxa: </label>

                            <div class="col-md-6">
                                <input type="text" v-model="quickForm.taxa" class="form-control" name="taxa" placeholder="list a few taxa that the term is applicable, separated by semicolons ">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <a v-on:click="submit()" class="btn btn-primary">
                                    Done
                                </a>
                            </div>
                            <div v-if="status == 1" class="col-md-8 col-md-offset-4">
                                <label><i>{{ $route.params.term }}</i>&nbsp; has been added to the ontology.</label> 
                            </div>
                            <div v-if="status == 2" class="col-md-8 col-md-offset-4">
                                <label>You did alright, but the system encountered an error.</label> 
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
            console.log('Component mounted.');
            console.log('Status', app.status);
        },
        data: function() {
            return {
                username: sessionStorage.getItem('username'),
                status: 0,
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
        mounted() {
            var app = this;
            var jsonRequest = {
                'user_email': app.$route.params.user,
                'action': 'Module loaded',
                'action_details': 'QuickForm module was loaded for term',
                'abnormal_system_response': null,
                'type': 'Quick Form'
            };
            //if (app.username == null || app.username == 'null' || app.username == '') {
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
            submit() {
                var app = this;
                //app.status = 1;    
                //console.log("qf_statusFlag", app.status);
                console.log('quickForm submit', app.quickForm);
                //if (app.username == null || app.username == 'null' || app.username == '') {
                //    alert('Please insert the username on homepage');
                /*} else*/ if (app.quickForm.structure == null) {
                    alert('You have to select "Anatomical Structure" or "Character or character state" before clicking "Done" button.')
                    var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Attempt to submit',
                        'action_details': 'clicked Done for term='+ app.$route.params.term,
                        'abnormal_system_response': 'did not select structure vs. character',
                        'type': 'Quick Form'
                    };
                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function (resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function (resp) {
                            console.log('activity-log error resp', resp);
                        });
                } else if (app.quickForm.definition == null) {
                    alert('You have to enter a definition before clicking "Done" button.')
                    var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Attempt to submit',
                        'action_details': 'clicked Done for term='+ app.$route.params.term,
                        'abnormal_system_response':  'did not enter a definition',
                        'type': 'Quick Form'
                    };
                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function (resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function (resp) {
                            console.log('activity-log error resp', resp);
                        });
                }else {
                    var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Submitted Quick Form',
                        'action_details': app.$route.params.user + ' submitted for term ',
                        'abnormal_system_response': null,
                        'type': 'Quick Form'
                    };
                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function (resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function (resp) {
                            console.log('activity-log error resp', resp);
                        });
                    var jsonClass = {
                        "user":app.$route.params.user,
                        "ontology": app.$route.params.ontology,
                        "term": app.$route.params.term,
                        "superclassIRI": "http://biosemantics.arizona.edu/ontologies/carex#toreview",
                        "definition": app.quickForm.definition + '[applicable taxa:' + app.quickForm.taxa + ']',
                        "elucidation": "tba",
                        "createdBy": app.$route.params.user + " via add2ontology quick form",
                        "creationDate": new Date(),
                        "definitionSrc": "tba",
                        "examples": app.quickForm.sentences,
                        "logicDefinition": null,
                    };
                    if (app.quickForm.structure == 'anatomical') {
                        jsonClass.superclassIRI = 'http://purl.obolibrary.org/obo/UBERON_0001062';
                    } else if (app.quickForm.structure == 'character') {
                        jsonClass.superclassIRI = 'http://biosemantics.arizona.edu/ontologies/carex#quality';
                    }
                    axios.post('http://shark.sbs.arizona.edu:8080/class', jsonClass)
                        .then(function(resp) {
                            console.log('class resp', resp);
                            var jsonSaveRequest = {
                        "user":app.$route.params.user,
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
                            app.status = 2;  
                             var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Submitted Quick Form',
                                'action_details': 'submitted for term='+ app.$route.params.term,
                                'abnormal_system_response': "system error",
                                'type': 'Quick Form'
                            };
                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                .then(function (resp) {
                                    console.log("activity-log resp", resp);
                                })
                                .catch(function (resp) {
                                    console.log('activity-log error resp', resp);
                                });
                        });
                        app.status = 1;    
                }

            }
        }
    }
</script>

<template>
<!-- wow -->
    <div slot="section" class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Wizard</h3>
            </div>
            <div class="panel-body table-responsive" style="font-size: 15px;">
                <div class="tab-pane">
                    <form class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-offset-1 col-md-4">
                                <div v-if="status == -1 || status > 0">
                                    <b style="color: green">Questions Answered:</b>
                                    <br>
                                   Please provide a human readable definition for {{ $route.params.term }}:<br/>
                                    <b> {{ term.definition }}</b>
                                </div>
                                
                                <div v-if="status == -1 || status > 0">
                                   Sentences using {{ $route.params.term }}:<br/>
                                    <b> {{ term.sentence }}</b>
                                </div>

                                <div v-if="status == -1 || status > 0">
                                   Related taxa:<br/>
                                    <b> {{ term.taxa }}</b>
                                </div>

                                <div v-if="status == -1 || status > 1">
                                    Does {{ $route.params.term }} represent an anatomical structure or a character? :<br/>
                                    <span v-if="term.represent == 'anatomical'" style="font-weight: bold;">an anatomical structure.</span>
                                    <span v-if="term.represent == 'character'" style="font-weight: bold;">a character.</span>
                                </div>
                                <div v-if="status > 2 && term.represent == 'anatomical'">
                                    Is {{ $route.params.term }} a synonym to any of the existing anatomical structure terms?<br/>
                                    <div v-if="term.synonym == 'yes'" style="font-weight: bold;">Yes, the term is a synonym to:
                                        <span v-for="each in synonyms">
                                             {{ each.text }}
                                        </span> 
                                    </div>

                                    <!--<span v-if="term.synonym == 'yes'" style="font-weight: bold;">Yes, the term is a synonym to {{ another structure term}}. </span> -->
                                    <div v-if="term.synonym == 'no'" style="font-weight: bold;">No, the term is not a synonym. It is a type of: 
                                         <span v-for="each in synonyms">
                                             {{ each.text }}
                                        </span> 
                                    </div>
                                </div>
                                <div v-if="status > 3 && term.represent == 'anatomical' && term.instance != null">
                                    Are all instances of {{ $route.params.term }} part of some larger structures shown in the tree below?<br/>
                                    <div v-if="term.instance == 'yes'" style="font-weight: bold;">Yes, they are a part of: 
                                        <span v-for="each in instances">
                                            {{ each.text }}
                                        </span>
                                    </div>
                                    <span v-if="term.instance == 'no'" style="font-weight: bold;">No, I am not aware of such larger structures.</span>
                                    <div v-if="term.instance == 'no-user'" style="font-weight: bold;">Not in the tree, but here are some larger structures: 
                                        <span v-for="each in userInstances">
                                            {{ each.term }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="status > 4 && term.represent == 'anatomical' && term.hasPart != null">
                                    Do all instances of {{ $route.params.term }} have certain parts that are in the tree below?<br/>
                                    <div v-if="term.hasPart == 'yes'" style="font-weight: bold;">Yes, they all have smaller parts: 
                                        <span v-for="each in hasParts">
                                            {{ each.text }}
                                        </span>
                                    </div>
                                    <span v-if="term.hasPart == 'no'" style="font-weight: bold;">No, I am not aware of such smaller structures.</span>
                                    <div v-if="term.hasPart == 'no-user'" style="font-weight: bold;">Not in the tree, but here are some smaller parts:
                                        <span v-for="each in userHasParts">
                                             {{ each.term}}
                                        </span>
                                        </div>
                                </div>
                                <div v-if="status == 5 && term.represent == 'character' && term.synonym != null">
                                    Is {{ $route.params.term }} a synonym to any of the existing quality terms?<br/>
                                    <div v-if="term.synonym == 'yes'" style="font-weight: bold;">Yes, it is a synonym to: 
                                        <span v-for="each in synonyms"> 
                                            {{ each.text }}
                                        </span>
                                    </div>
                                    <div v-if="term.synonym == 'no'" style="font-weight: bold;">No, it is not a synonym. It is a type of: 
                                        <span v-for="each in synonyms">
                                            {{ each.text }}.
                                            </span>
                                        </div>
                                </div> 

                            </div>
                            <div class="col-md-6">
                                <div v-if="status == 0" class="form-group">
                                    <label class="col-md-12">
                                        Answer the following questions about <span style="color:dodgerBlue"><i>{{ $route.params.term }}</i></span>. Your answer helps the computer understand the meaning of the term. * indicates required fields.
                                    </label>
                                </div>
                                <div v-if="status == 0">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>* Please provide a human readable definition for <i>{{ $route.params.term }}</i>:</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input v-model="term.definition" style="width: 100%;" 
                                            :placeholder="'what is a '+ $route.params.term +' and how it is related to other structures or characters'">
                                        </div>
                                    </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>* Sentences using the term: </label>
                                    </div>

                                    <div class="col-md-12">
                                        <input type="text" v-model="term.sentence" style="width: 100%;" placeholder="provide a sentence or two where this term has been used">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12"> 
                                        <label>* Related taxa: </label>
                                    </div>

                                    <div class="col-md-12">
                                        <input type="text" v-model="term.taxa" style="width: 100%;" placeholder="list a few taxa that the term is applicable, separated by semicolons ">
                                    </div>
                                </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <a class="btn btn-primary" v-on:click="submit(0)">Save</a>
                                        </div>
                                    </div>
                                </div>


                                <div v-if="status == 1">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Does <i>{{ $route.params.term }}</i> represent</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question1-y" v-model="term.represent" value="anatomical" name="structure" />
                                            <label for="question1-y">an anatomical structure? </label>
                                            <div style="padding-left: 10px;">
                                                Select this if the term represents a physical object such as leaf, apex; imaginary anatomical structures such as axis; or confined space such as chamber, pore.
                                                More example anatomical structures are listed below:
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 20px; border: 1px solid grey; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                            <tree
                                                    :data="largeDataEntity"
                                                    :options="treeOption"
                                                    ref="tree"
                                                    @node:selected="onEntitySelected"
                                            />
                                        </div>
                                        <!--<div class="col-md-6" style="margin-top: 20px">-->
                                            <!--<pre>{{ treeDetails.entity }}</pre>-->
                                        <!--</div>-->
                                        <div class="col-md-12" style="margin-top: 20px">
                                            <input type="radio" id="question1-n" v-model="term.represent" value="character" name="structure" />
                                            <label for="question1-n">a character?</label>
                                            <div style="padding-left: 10px;">
                                                Select this if the term represents a property (such as color, shape, size, orientation, position, etc.) of a physical object.
                                                More example character terms are listed below:
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 20px; border: 1px solid grey; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                            <tree
                                                    :data="largeDataQuality"
                                                    :options="treeOption"
                                                    ref="tree"
                                                    @node:selected="onQualitySelected"
                                            />
                                        </div>
                                        <!--<div class="col-md-6" style="margin-top: 20px">-->
                                            <!--<pre>{{ treeDetails.quality }}</pre>-->
                                        <!--</div>-->
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <a class="btn btn-primary" v-on:click="submit(1)">Save</a>
                                        </div>

                                    </div>
                                </div>

                                <div v-if="status == 2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Is <i>{{ $route.params.term }}</i> a synonym to any of the existing anatomical structure terms shown below? </label>
                                        </div>
                                        <div class="col-md-6" style="border: 1px solid grey; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                            <tree
                                                    :data="treeData"
                                                    :options="synonymsOption"
                                                    ref="tree"
                                                    @node:checked="onSynonymSelected"
                                                    @node:unchecked="onSynonymUnselected"
                                            />
                                        </div>
                                        <div class="col-md-6">
                                            <pre>{{ treeDetails.q2 }}</pre>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question2-y" v-model="term.synonym" value="yes" name="structure" />
                                            <label for="question2-y">Yes. Select all the synonyms in the tree above.</label>
                                            <!--<div style="padding-left: 10px;" v-if="term.synonym == 'yes'">
                                              <label style="color:dodgerBlue">In the tree above, select all the nodes that <i>{{ $route.params.term }}</i> is a synonym of, then click Save.</label>
                                            </div>-->
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question2-n" v-model="term.synonym" value="no" name="structure" />
                                            <label for="question2-n">No. Select all the more general terms of which <i>{{ $route.params.term }}</i> &nbsp; is a type </label>
                                            <!--<div style="padding-left: 10px;" v-if="term.synonym == 'no'">
                                               <label style="color:dodgerBlue">In the tree above, select all the more general terms of which <i>{{ $route.params.term }}</i> is a type, then click Save</label>
                                            </div>-->
                                        </div>
                                        <div v-if="status == 2" class="col-md-12">
                                            <a class="btn btn-primary" v-on:click="submit(2)">Save</a>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="status == -1">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Is <i>{{ $route.params.term }}</i> a synonym to any of the existing quality terms? </label>
                                        </div>
                                        <div class="col-md-6" style="border: 1px solid grey; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                            <!--<v-jstree :data="treeData" show-checkbox multiple allow-batch whole-row @item-click="synonymItemClick"></v-jstree>-->
                                            <tree
                                                    :data="treeData"
                                                    :options="synonymsOption"
                                                    ref="tree"
                                                    @node:checked="onCharacterSynonymSelected"
                                                    @node:unchecked="onSynonymUnselected"
                                            />
                                        </div>
                                        <div class="col-md-6">
                                            <pre>{{ treeDetails.qx }}</pre>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="questionx-y" v-model="term.synonym" value="yes" name="structure" />
                                            <label for="questionx-y">Yes. Select all the synonyms in the tree above. </label>
                                            <!--<div style="padding-left: 10px;" v-if="term.synonym =='yes'">
                                               <label style="color:dodgerBlue">In the tree above, select the nodes that <i>{{ $route.params.term }}</i> is a synonym of, then click Save.</label>
                                            </div>-->
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="questionx-n" v-model="term.synonym" value="no" name="structure" />
                                            <label for="questionx-n">No. Select the nodes of which <i>{{ $route.params.term }}</i> &nbsp; is a type </label>
                                            <!--<div style="padding-left: 10px;" v-if="term.synonym == 'no'">
                                               <label style="color:dodgerBlue">In the tree above, select the nodes under which <i>{{ $route.params.term }}</i> should be added, then click Save.</label>
                                            </div>-->
                                        </div>
                                        <div v-if="status == -1" class="col-md-12">
                                            <a class="btn btn-primary" v-on:click="submit(-1)">Save</a>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="status == 3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Are all instances of <i>{{ $route.params.term }}</i> part of some larger structures shown in the tree below? </label>
                                        </div>
                                        <div class="col-md-6" style="border: 1px solid grey; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                            <tree
                                                    :data="treeData"
                                                    :options="synonymsOption"
                                                    ref="tree"
                                                    @node:checked="onPartOfSelected"
                                                    @node:unchecked="onSynonymUnselected"
                                            />
                                        </div>
                                        <div class="col-md-6">
                                            <pre>{{ treeDetails.q3 }}</pre>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question3-y" v-model="term.instance" value="yes" name="structure" />
                                            <label for="question3-y">Yes. Select the most <i>general</i> larger structures in the tree above (if selecting <i>leaf</i>, no need to select <i>basal leaf</i>). </label>
                                            <!--div style="padding-left: 10px;" v-if="term.instance == 'yes'">
                                             <label style="color:dodgerBlue">In the tree above, select the larger structures to which all instances of <i>{{ $route.params.term }}</i> belong, then click Save</label>
                                            </div>-->
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question3-n" v-model="term.instance" value="no" name="structure" />
                                            <label for="question3-n"> No, I am not aware of such larger structures.</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question3-n-user" v-model="term.instance" value="no-user" name="structure" />
                                            <label for="question3-n-user">No, such larger structures exist but are not shown in the tree.</label>
                                        </div>
                                        <div class="col-md-12" v-if="term.instance == 'no-user'">
                                            <label  style="color:dodgerBlue">Please enter these larger structures and their definitions (all required):</label>
                                            <div style="padding-left: 10px;">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label>Parent Structure Term* </label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label>Human Readable Definition* </label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="row" v-for="i in userInstances.length" style="padding-top: 5px;">
                                                    <div class="col-md-5">
                                                        <input v-model="userInstances[i-1].term"/>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input v-model="userInstances[i-1].definition"/>
                                                    </div>
                                                    <div class="col-md-2">
                                                         <a class="btn btn-primary "  v-on:click="removeUserDefinition(i-1)"><span class="glyphicon glyphicon-minus"></span></a>
                                                    </div>    
                                                </div>
                                            </div>
                                            <div style="padding-left: 10px; margin-top: 20px;">
                                                <a class="btn btn-primary "  v-on:click="addUserDefinition()"><span class="glyphicon glyphicon-plus"></span></a>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <a class="btn btn-primary" v-on:click="submit(3)">Save</a>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="status == 4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Do all instances of <i>{{ $route.params.term }}</i> have certain parts that are in the tree below? </label>
                                        </div>
                                        <div class="col-md-6" style="border: 1px solid grey; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                            <tree
                                                    :data="treeData"
                                                    :options="synonymsOption"
                                                    ref="tree"
                                                    @node:checked="onHasPartSelected"
                                                    @node:unchecked="onSynonymUnselected"
                                            />
                                        </div>
                                        <div class="col-md-6">
                                            <pre>{{ treeDetails.q4 }}</pre>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question4-y" v-model="term.hasPart" value="yes" name="structure" />
                                            <label for="question4-y">Yes. Select the most <i>general</i> parts in the tree above (if selecting <i>leaf</i>, no need to select <i>basal leaf</i>). </label>
                                            <!--<div style="padding-left: 10px;" v-if="term.hasPart == 'yes'">
                                              <label style="color:dodgerBlue"> In the tree above, select the parts, then click Save. </label>
                                            </div>-->
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question4-n" v-model="term.hasPart" value="no" name="structure" />
                                            <label for="question4-n"> No, I am not aware of such component structures.</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" id="question4-n-user" v-model="term.hasPart" value="no-user" name="structure" />
                                            <label for="question4-n-user">No, such component structures exist but are not shown in the tree.</label>
                                        </div>
                                        <div class="col-md-12" v-if="term.hasPart == 'no-user'">
                                            <label  style="color:dodgerBlue">Please enter these component structures and their definitions (all required):</label>
                                            <div style="padding-left: 10px;">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label>Component Structure Term*</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label>Human Readable Definition*</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="row" v-for="i in userHasParts.length" style="padding-top: 5px;">
                                                    <div class="col-md-5">
                                                        <input v-model="userHasParts[i-1].term"/>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input v-model="userHasParts[i-1].definition"/>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="btn btn-primary "  v-on:click="removeUserHasPart(i-1)"><span class="glyphicon glyphicon-minus"></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="padding-left: 10px; margin-top: 20px;">
                                                <a class="btn btn-primary "  v-on:click="addUserHasPart()"><span class="glyphicon glyphicon-plus"></span></a>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <a class="btn btn-primary" v-on:click="submit(4)">Save</a>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="status == 5" class="col-md-12">
                                    <label style="color:green"> Summary </label>
                                    <label>Based on your answers, the following have been added to the ontology:</label>
                                    <div v-for="each in summary">
                                        {{ each }}
                                    </div>
                                    <div v-if="summaryPartOf.length > 0">
                                       "{{ $route.params.term }}" is part of <p style="display: inline;" v-for="each in summaryPartOf">"{{ each }}", </p>
                                    </div>
                                    <div v-if="summaryHasPart.length > 0">
                                        "{{ $route.params.term }}" has part <p style="display: inline;" v-for="each in summaryHasPart">"{{ each }}", </p>
                                    </div>
                                    <div class="row" style="margin-top: 20px;">
                                        <div class="col-md-12">
                                            <label style="color: green">You are done!</label>
                                        </div>
                                    </div>
                                    <!--<a class="btn btn-primary" v-on:click="Submit('final')" style="margin-top: 10px;">Submit</a>-->
                                </div>
                            </div>
                        </div>

                        <div v-if="modalShowFlag" @close="modalShowFlag = false">
                            <transition name="modal">
                                <div class="modal-mask">
                                    <div class="modal-wrapper">
                                        <div class="modal-container">

                                            <div class="modal-header">
                                                <h3>Confirmation</h3>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div v-if="status == 2 || status == -1" class="col-md-12">
                                                        <div v-if="term.synonym == 'yes'">
                                                            You are adding <b>{{ $route.params.term }}</b> as a <b>synonym</b> to each of the following terms:
                                                            <div v-for="each in synonyms">
                                                                {{ each.text }}
                                                            </div>
                                                        </div>
                                                        <div v-if="term.synonym == 'no'">
                                                            You are adding <b>{{ $route.params.term }}</b> as a <b>subclass</b> to each of the following terms:
                                                            <div v-for="each in synonyms">
                                                                {{ each.text }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="status == 3" class="col-md-12">
                                                        <div v-if="term.instance == 'yes'">
                                                            Do you want to add <b>part_of</b> relation between <b>{{temp.text}}</b> and the following larger structures:
                                                            <div v-for="each in instances">
                                                                {{ each.text }}
                                                            </div>
                                                        </div>
                                                        <div v-if="term.instance == 'no-user'">
                                                            Do you want to add <b>part_of</b> relation between <b>{{temp.text}}</b> and the following larger structures:
                                                            <div v-for="each in instances">
                                                                {{ each.term }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="status == 4" class="col-md-12">
                                                        <div v-if="term.hasPart == 'yes'">
                                                            Do you want to add <b>has_part</b> relation between <b>{{temp.text}}</b> and the following parts:
                                                            <div v-for="each in hasParts">
                                                                {{ each.text }}
                                                            </div>
                                                        </div>
                                                        <div v-if="term.hasPart == 'no-user'">
                                                            Do you want to add <b>has_part</b> relation between <b>{{temp.text}}</b> and the following parts:
                                                            <div v-for="each in hasParts">
                                                                {{ each.term }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-right" style="margin-top: 15px;">
                                                        <a v-on:click="confirmSave(status)" class="btn btn-primary">Confirm</a>
                                                        <a v-on:click="cancelSave(status)" class="btn btn-danger">Undo</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                        <!--<div id="tooltip" v-show="showTooltip" style="border: solid 1px; position: fixed;">-->
                            <!--Test Tooltip-->
                        <!--</div>-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VJstree from 'vue-jstree';
    import Vue from 'vue';
    import LiquorTree from 'liquor-tree'

    Vue.use(LiquorTree);
    Vue.use(VJstree);
    var shared = true;

    export default {
        data: function() {
            return {
                username: sessionStorage.getItem('username'),
                status: 0,
                error: "",
                list: "", 
                term: {
                    definition: null,
                    sentence:null,
                    taxa:null,
                    represent: null,
                    synonym: null,
                    instance: null,
                    hasPart: null,

                },
                treeDetails: {
                    q2: null,
                    qx: null,
                    q3: null,
                    q4: null,
                    entity: null,
                    quality: null
                },
                arraySynonyms: [],
                treeData: [

                ],
                synonymsOption:{
                    checkbox: true,
                    autoCheckChildren: false,
                    parentSelect: true,
                    checkOnSelect: true
                },
                treeOption:{
                    checkbox: false,
                    autoCheckChildren: false,
                    parentSelect: false,
                    checkOnSelect: false
                },
                synonyms: [],
                instances: [],
                hasParts: [],
                modalShowFlag: false,
                temp : {
                    text: this.$route.params.term,
                    state: {
                        checked: false
                    },
                    data: {
                        details: [
                            {}
                        ]
                    }
                },
                userInstances: [
                    {
                        term: null,
                        definition: null
                    }
                ],
                userHasParts: [
                    {
                        term: null,
                        definition: null
                    }
                ],
                tempIndex: 0,
                tempJ: 0,
                TTBA: [],
                /*largeDataEntity:{
  "data": {
    "details": [
      {
        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#anatomical-structure"
      }
    ]
  },
  "children": [
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#vein",
            "definition": "A portion of vascular tissue (PO:0009015) that is a unit strand of the vascular system (PO:0000034) and has as part xylem (PO:0005352) or phloem (PO:0005417)"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lateral-vein",
                "definition": "A vein on either side of the midvein"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lateral-adaxial-vein"
                  }
                ]
              },
              "text": "lateral adaxial vein"
            }
          ],
          "text": "lateral-vein"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#midvein"
              }
            ]
          },
          "text": "midvein"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial-vein"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lateral-adaxial-vein"
                  }
                ]
              },
              "text": "lateral adaxial vein"
            }
          ],
          "text": "adaxial vein"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#vein-of-pistillate-scale"
              }
            ]
          },
          "text": "vein of pistillate scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#vein-of-staminate-scale"
              }
            ]
          },
          "text": "vein of staminate scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cross-vein"
              }
            ]
          },
          "text": "cross-vein"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#marginal-vein",
                "definition": "The vein closest to the margin of a structure"
              }
            ]
          },
          "text": "marginal vein"
        }
      ],
      "text": "vein"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#collar",
            "definition": "The differentiated apex of a sheath, at the junction with the blade"
          }
        ]
      },
      "text": "collar"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#end",
            "definition": "The portion at which a lengthy object like a stem terminates"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-end"
              }
            ]
          },
          "text": "proximal end"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-end"
              }
            ]
          },
          "text": "distal end"
        }
      ],
      "text": "end"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#chromosome"
          }
        ]
      },
      "text": "chromosome"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tuft",
            "definition": "A cluster of shoots appearing to grow from a common point"
          }
        ]
      },
      "text": "tuft"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#internode",
            "definition": "The portion of a stem between two adjacent nodes"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#middle-internode",
                "definition": "internodes in the medial portion of the culm"
              }
            ]
          },
          "text": "middle internode"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-internode",
                "definition": "The longest distance between two adjacent nodes on a stem"
              }
            ]
          },
          "text": "longest internode"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-proximal-internode",
                "definition": "The longest distance between the lowest nodes on a stem  - probably is reference to the distance between the two lowermost spike in an inflorescence"
              }
            ]
          },
          "text": "longest proximal internode"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-internode",
                "definition": "The internode nearest the point of origin of the axis"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-internode-of-inflorescence-unit"
                  }
                ]
              },
              "text": "proximal internode of inflorescence unit"
            }
          ],
          "text": "proximal internode"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhizome-internode",
                "definition": "The distance between two successive nodes on a rhizome"
              }
            ]
          },
          "text": "rhizome internode"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-internode"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-internode-of-inflorescence"
                  }
                ]
              },
              "text": "distal internode of inflorescence"
            }
          ],
          "text": "distal internode"
        }
      ],
      "text": "internode"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#septum"
          }
        ]
      },
      "text": "septum"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#attachment-%28structure%29"
          }
        ]
      },
      "text": "attachment (structure)"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#constriction"
          }
        ]
      },
      "text": "constriction"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#venation"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pinnate-network"
              }
            ]
          },
          "text": "pinnate network"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nervation-of-perigynium"
              }
            ]
          },
          "text": "nervation of perigynium"
        }
      ],
      "text": "venation"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orifice",
            "definition": "The opening of the perigynium through which the style protrudes"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#beak-orifice"
              }
            ]
          },
          "text": "beak orifice"
        }
      ],
      "text": "orifice"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#groove",
            "definition": "A long narrow indentation in a surface"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#median-groove"
              }
            ]
          },
          "text": "median groove"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#median-adaxial-groove"
              }
            ]
          },
          "text": "median adaxial groove"
        }
      ],
      "text": "groove"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#colony"
          }
        ]
      },
      "text": "colony"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cone"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#slender-terminal-cone"
              }
            ]
          },
          "text": "slender terminal cone"
        }
      ],
      "text": "cone"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex",
            "definition": "the upper surface of a structure"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-inflorescence-unit"
              }
            ]
          },
          "text": "apex of inflorescence unit"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#beak-apex",
                "definition": "The distal point of the beak"
              }
            ]
          },
          "text": "beak apex"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scale-apex"
              }
            ]
          },
          "text": "scale apex"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-sheath-inner-band"
              }
            ]
          },
          "text": "apex of sheath inner band"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ultimate-apex",
                "definition": "The farthest point of the apex"
              }
            ]
          },
          "text": "ultimate apex"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#achene-apex",
                "definition": "The distal portion of the achene"
              }
            ]
          },
          "text": "achene apex"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-bract-node"
              }
            ]
          },
          "text": "apex of bract node"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-rhizome"
              }
            ]
          },
          "text": " apex of rhizome"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-bract"
              }
            ]
          },
          "text": "apex of bract"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-leaf-blade"
              }
            ]
          },
          "text": "apex of leaf blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-inflorescence"
              }
            ]
          },
          "text": " apex of inflorescence"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-ligule"
              }
            ]
          },
          "text": "apex of ligule"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-of-bract-sheath-inner-band"
              }
            ]
          },
          "text": "apex of bract sheath inner band"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath-apex",
                "definition": "the portion of the leaf that does not surrround, and is free from the culm - the photosynetic portion or lamina of the leaf"
              }
            ]
          },
          "text": "sheath apex"
        }
      ],
      "text": "apex"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#seed-coat"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#testa",
                "definition": "That part of the seed coat that develops from the outer integument, or from the single integument when there is only one"
              }
            ]
          },
          "text": "testa"
        }
      ],
      "text": "seed coat"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tissue",
            "definition": "A structural material with a specific function formed of similar cells"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-tissue",
                "definition": "Tissue occupying the base of a structure?"
              }
            ]
          },
          "text": "basal tissue"
        }
      ],
      "text": "tissue"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#septa"
          }
        ]
      },
      "text": "septa"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#midrib",
            "definition": "the central vein of the leaf, often with a prominent keel"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial-midrib"
              }
            ]
          },
          "text": "abaxial midrib"
        }
      ],
      "text": "midrib"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spot"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dot"
              }
            ]
          },
          "text": "dot"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blotch"
              }
            ]
          },
          "text": "blotch"
        }
      ],
      "text": "spot"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#node",
            "definition": "A joint in a stem, bearing a leaf, bract, or scale."
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bract-node"
              }
            ]
          },
          "text": "bract node"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-bract-node"
              }
            ]
          },
          "text": "distal bract node"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#node-of-proximal-bract"
              }
            ]
          },
          "text": "node of proximal bract"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-node"
              }
            ]
          },
          "text": "basal node"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cauline-node",
                "definition": "A node on an above ground elongated stem"
              }
            ]
          },
          "text": "cauline node"
        }
      ],
      "text": "node"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#suture",
            "definition": "A visible seam on the abaxial surface of the beak of some Carex perigynia"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial-suture"
              }
            ]
          },
          "text": "abaxial suture"
        }
      ],
      "text": "suture"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fibril"
          }
        ]
      },
      "text": "fibril"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#plant"
          }
        ]
      },
      "text": "plant"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wing"
          }
        ]
      },
      "text": "wing"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#extension",
            "definition": "a prolongation or lengthening of an organ"
          }
        ]
      },
      "text": "extension"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fiber",
            "definition": "A thread-like structure formed from the decay of a leaf or sheath by persistent sclerenchyma traces"
          }
        ]
      },
      "text": "fiber"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tip"
          }
        ]
      },
      "text": "tip"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scale-tip",
            "definition": "The apex of a scale"
          }
        ]
      },
      "text": "scale tip"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium-beak-tip",
            "definition": "The sometimes differentiated apical portion of the perigynium beak"
          }
        ]
      },
      "text": "perigynium beak tip"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pubescence-%28structure%29"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hair"
              }
            ]
          },
          "text": "hair"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tomentum",
                "definition": "pubescence consisting of longish, soft, entangled hairs pressed close to the surface. Dictionary.com"
              }
            ]
          },
          "text": "tomentum"
        }
      ],
      "text": "pubescence (structure)"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leaf",
            "definition": "A phyllome (PO:0006001) that is not associated with a reproductive structure."
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distalmost-leaf"
              }
            ]
          },
          "text": "distalmost leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-most-leaf"
              }
            ]
          },
          "text": "proximal-most leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-cauline-leaf",
                "definition": "The leaf situated farthest away from the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "distal cauline leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-leaf",
                "definition": "A leaf originating from the lowermost (basal) node of a stem (culm)"
              }
            ]
          },
          "text": "basal leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cataphyll",
                "definition": "The leathery, elongated bud scale, composed of a bladeles sheath"
              }
            ]
          },
          "text": "cataphyll"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#previous-leaf"
              }
            ]
          },
          "text": "previous leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-cauline-leaf",
                "definition": "The lowest (nearest the point of origin of the axis) non-basal leaf on the axis"
              }
            ]
          },
          "text": "proximal cauline leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-leaf",
                "definition": "The maximum width of the blade of the widest leaf"
              }
            ]
          },
          "text": "widest leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#culm-leaf",
                "definition": "A leaf on the shoot axis"
              }
            ]
          },
          "text": "culm leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cauline-leaf",
                "definition": "A leaf on the aerial portion of the stem (culm)"
              }
            ]
          },
          "text": "cauline leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-leaf"
              }
            ]
          },
          "text": "distal leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-leaf",
                "definition": "The leaf situated closest to the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "proximal leaf"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#larger-leaf",
                "definition": "Not the smallest (=longest, widest?) leaf on a culm"
              }
            ]
          },
          "text": "larger leaf"
        }
      ],
      "text": "leaf"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inflorescence-unit",
            "definition": "some inflorescence does not have infl. units"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-inflorescence-unit",
                "definition": "lowermost inflorescence unit found on a stem"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-proximal-inflorescence-unit",
                    "definition": "The longest, lowermost inflorescence unit found on a stem - implies that this is the longest inflorescence unit found on > 1 stems"
                  }
                ]
              },
              "text": "longest proximal inflorescence unit"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximalmost-inflorescence-unit",
                    "definition": "The inflorescence unit situated closest to the point of origin of the axis to which it is attached"
                  }
                ]
              },
              "text": "proximalmost inflorescence unit"
            }
          ],
          "text": "proximal inflorescence unit"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#peduncled-inflorescence-unit",
                "definition": "peduncled inflorescence unit originating from near-basal nodes"
              }
            ]
          },
          "text": "peduncled inflorescence unit"
        }
      ],
      "text": "inflorescence unit"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#neck"
          }
        ]
      },
      "text": "neck"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apiculum",
            "definition": "The persistent style base at the top of the achene"
          }
        ]
      },
      "text": "apiculum"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#band",
            "definition": "In Carex, the differentiated abaxial zone of the sheath (inner band\")"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inner-band"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inner-band-of-bract-sheath"
                  }
                ]
              },
              "text": "inner band of bract sheath"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inner-band-of-sheath"
                  }
                ]
              },
              "text": "inner band of sheath"
            }
          ],
          "text": "inner band"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#submarginal-band"
              }
            ]
          },
          "text": "submarginal band"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subapical-band"
              }
            ]
          },
          "text": "subapical band"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial-band",
                "definition": "Term is unclear but may refer to the inner band of the leaf sheath - used especially in the description of leaf sheaths in sect. Heleoglochin"
              }
            ]
          },
          "text": "adaxial band"
        }
      ],
      "text": "band"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tooth",
            "definition": "In Carex, the narrow prolongations of the two perigynium ribs that extend beyond the tubular portion of the beak"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium-beak-tooth"
              }
            ]
          },
          "text": "perigynium beak tooth"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#beak-tooth",
                "definition": "one of the paired separate narrow, pointed tips that sometimes occur at the apex of the tubular or flattened beak of the perigynium"
              }
            ]
          },
          "text": "beak tooth"
        }
      ],
      "text": "tooth"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spike"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distlmost-lateral-spike"
              }
            ]
          },
          "text": "distlmost lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spike-portion",
                "definition": "part of a spike"
              }
            ]
          },
          "text": "spike portion"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-cauline-spike",
                "definition": "The lowest (nearest the point of origin of the axis) non-basal \"spike\" on the axis"
              }
            ]
          },
          "text": "proximal cauline spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-proximal-spike",
                "definition": "The longest, lowermost spike on a stem - implies that this is the longest spike found on > 1 stems"
              }
            ]
          },
          "text": "longest proximal spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-lateral-spike",
                "definition": "Term is unclear - refers to either a spike originating from a basal node (as in some Acrocystis) or the lowest cauline node in an inflorescence"
              }
            ]
          },
          "text": "basal lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-spike",
                "definition": "The longest spike in an inflorescence"
              }
            ]
          },
          "text": "longest spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximalmost-spike",
                "definition": "The \"spike\" situated closest to the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "proximalmost spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-lateral-spike",
                "definition": "The lateral spike farthest from the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "distal lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-lateral-spike",
                "definition": "The longest spike present in an inflorescence"
              }
            ]
          },
          "text": "longest lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distalmost-lateral-spike",
                "definition": "The lateral spike farthest from the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "distalmost lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#larger-spike",
                "definition": "Not the smallest spike in an inflorescence"
              }
            ]
          },
          "text": "larger spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-spike",
                "definition": "The spike nearest the point of origin of the axis"
              }
            ]
          },
          "text": "proximal spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longer-lateral-spike",
                "definition": "The longest spike present in an inflorescence"
              }
            ]
          },
          "text": "longer lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bract-spike",
                "definition": "The bract associated with a spike?"
              }
            ]
          },
          "text": "bract spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terminal-or-proximal-spike",
                "definition": "The upper and lowermost spikes in an inflorescence"
              }
            ]
          },
          "text": "terminal or proximal spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lateral-spike"
              }
            ]
          },
          "text": "lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terminal-spike",
                "definition": "The distalmost spike, born on the primary axis"
              }
            ]
          },
          "text": "terminal spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terminal-lateral-spike",
                "definition": "Same as distal spike? - the spike farthest from the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "terminal lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terminal-and-distal-lateral-spike",
                "definition": "The upper and lowermost spikes in an inflorescence"
              }
            ]
          },
          "text": "terminal and distal lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spikelet"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terminal-spikelet"
                  }
                ]
              },
              "text": "terminal spikelet"
            }
          ],
          "text": "spikelet"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-terminal-spike",
                "definition": "The longest terminal spike that would be present amongst a larger sampling of culm/inflorescences"
              }
            ]
          },
          "text": "longest terminal spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#portion-spike",
                "definition": "This term does not make sense - should read portion of a spike"
              }
            ]
          },
          "text": "portion spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-lateral-spike"
              }
            ]
          },
          "text": "proximal lateral spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#middle-spike",
                "definition": "A \"spike\" in the medial portion of the inflorescence"
              }
            ]
          },
          "text": "middle spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distalmost-spike",
                "definition": "The\"spike\" in the distalmost position in the inflorescence"
              }
            ]
          },
          "text": "distalmost spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cauline-spike"
              }
            ]
          },
          "text": "cauline spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#individual-spike",
                "definition": "A single spike?"
              }
            ]
          },
          "text": "individual spike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spike-set",
                "definition": "The total number of spikes that comprise an inflorescence"
              }
            ]
          },
          "text": "spike set"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spikes"
              }
            ]
          },
          "text": "spikes"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-spike",
                "definition": "The spike farthest from the point of origin of the axis to which it is attached; a terminal spike"
              }
            ]
          },
          "text": "distal spike"
        }
      ],
      "text": "spike"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#remnant"
          }
        ]
      },
      "text": "remnant"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#setae"
          }
        ]
      },
      "text": "setae"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#anther",
            "definition": "A collective plant organ structure (PO:0025007) that is the pollen-bearing part of a stamen (PO:0009029)"
          }
        ]
      },
      "text": "anther"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#mucro"
          }
        ]
      },
      "text": "mucro"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cell"
          }
        ]
      },
      "text": "cell"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reproductive-structure"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pistil",
                "definition": "The female (ovule-bearing) organ of a flower, consisting of ovary, style, and stigma"
              }
            ]
          },
          "text": "pistil"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fruit",
                "definition": "A multi-tissue plant structure (PO:0025496) that develops from a gynoecium (PO:0009062), or a single carpel (PO:0009030), and at maturity may have as parts one or more seeds (PO:0009010)"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#achene"
                  }
                ]
              },
              "text": "achene"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nutlet"
                  }
                ]
              },
              "text": "nutlet"
            }
          ],
          "text": "fruit"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#embryo"
              }
            ]
          },
          "text": "embryo"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flower",
                "definition": "A determinate reproductive shoot system (PO:0025082) that has as part at least one carpel (PO:0009030) or at least one stamen (PO:0009029) and does not contain any other determinate shoot system (PO:0009006) as a part"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#staminate-flower"
                  }
                ]
              },
              "text": "staminate flower"
            }
          ],
          "text": "flower"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#filament",
                "definition": "A stalk that is part of a stamen"
              }
            ]
          },
          "text": "filament"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#endosperm"
              }
            ]
          },
          "text": "endosperm"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perianth",
                "definition": "A collective phyllome structure (PO:0025023) that includes as parts the corolla (PO:0009059) and/or the calyx (PO:0009060); or one or more tepals (PO:0009033)"
              }
            ]
          },
          "text": "perianth"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#locule"
              }
            ]
          },
          "text": "locule"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stigma",
                "definition": "A cardinal organ part (PO:0025001), usually apical, that is part of a carpel (PO:0009030) or group of fused carpels on which the pollen (PO:0025281) germinates"
              }
            ]
          },
          "text": "stigma"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#style",
                "definition": "A cardinal organ part (PO:0025001) which is an elongated part of a carpel (PO:0009030) or gynoecium (PO:0009062) between the plant ovary (PO:0009072) and the stigma (PO:0009073), and through which the pollen tube (GO:0090406) grows"
              }
            ]
          },
          "text": "style"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pericarp",
                "definition": "A portion of plant tissue (PO:0009007) that is the outer layer (wall) of a fruit (PO:0009001), and develops from a plant ovary (PO:0009072) or a carpel (PO:0009030) wall"
              }
            ]
          },
          "text": "pericarp"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#petal"
              }
            ]
          },
          "text": "petal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stamen",
                "definition": "A microsporophyll bearing one or more microsporangia"
              }
            ]
          },
          "text": "stamen"
        }
      ],
      "text": "reproductive structure"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#clump"
          }
        ]
      },
      "text": "clump"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#portion",
            "definition": "A defined part of any whole"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#middle-portion"
              }
            ]
          },
          "text": "middle portion"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-portion"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terminal-portion-of-inflorescence"
                  }
                ]
              },
              "text": "terminal portion of inflorescence"
            }
          ],
          "text": "distal portion"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-portion"
              }
            ]
          },
          "text": "proximal portion"
        }
      ],
      "text": "portion"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#foliage"
          }
        ]
      },
      "text": "foliage"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#faces",
            "definition": "One of the sides of a multi-sided organ"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-face-of-perigynium"
              }
            ]
          },
          "text": "widest face of perigynium"
        }
      ],
      "text": "face"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inflorescence",
            "definition": "A reproductive shoot system (PO:0025082) that has as parts all of the shoot axes (PO:0025029) and flowers (PO:0009046; must have two or more) distal to the most distal leaf (PO:0009025)"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#raceme",
                "definition": "An inflorescence (PO:0009049), with a monopodial growth habit, which at anthesis is composed of pedicellate (PATO:0001438) flowers (PO:0009046) on an indeterminate first order inflorescence axis (PO:0025104)"
              }
            ]
          },
          "text": "raceme"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#disk"
              }
            ]
          },
          "text": "disk"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cyme"
              }
            ]
          },
          "text": "cyme"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#corymb"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#anthela"
                  }
                ]
              },
              "text": "anthela"
            }
          ],
          "text": "corymb"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-inflorescence"
              }
            ]
          },
          "text": "distal inflorescence"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#primary-inflorescence"
              }
            ]
          },
          "text": "primary inflorescence"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#capitulum"
              }
            ]
          },
          "text": "capitulum"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#secondary-inflorescence"
              }
            ]
          },
          "text": "secondary inflorescence"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#panicle"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#secondary-inflorescences-panicle"
                  }
                ]
              },
              "text": "secondary inflorescences panicle"
            }
          ],
          "text": "panicle"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spike-inflorescence"
              }
            ]
          },
          "text": "spike inflorescence"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#head",
                "definition": "A dense congested, roughly globular inflorescence"
              }
            ]
          },
          "text": "head"
        }
      ],
      "text": "inflorescence"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leaf-ligule",
            "definition": "The thin, membranous outgrowth at the junction of the sheath and the blade, partly fused to the blade"
          }
        ]
      },
      "text": "leaf ligule"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scale-body",
            "definition": "The broadened or expanded portion of a scale"
          }
        ]
      },
      "text": "scale body"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath-front-apex"
          }
        ]
      },
      "text": "sheath front apex"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#area"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial-area"
              }
            ]
          },
          "text": "adaxial area"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#intervenal-area",
                "definition": "The zone between the leaf veins"
              }
            ]
          },
          "text": "intervenal area"
        }
      ],
      "text": "area"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium-body",
            "definition": "The broadened or expanded portion of a perigynium"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#achene-body",
                "definition": "The expanded portion of the achene, between the abrupt narrowing to the base of the style and the abrupt narrowing to the stalk (stipe)"
              }
            ]
          },
          "text": "achene body"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-body"
              }
            ]
          },
          "text": "distal body"
        }
      ],
      "text": "perigynium body"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium-mouth",
            "definition": "The opening of the perigynium, through which the style protrudes"
          }
        ]
      },
      "text": "perigynium mouth"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tussock"
          }
        ]
      },
      "text": "tussock"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flap",
            "definition": "Structure associated with the perigynium body in certain species in sect. Heleoglochin, but not sure"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial-flap"
              }
            ]
          },
          "text": "adaxial flap"
        }
      ],
      "text": "flap"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium-beak"
          }
        ]
      },
      "text": "perigynium beak"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#angle"
          }
        ]
      },
      "text": "angle"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stem-node"
          }
        ]
      },
      "text": "stem node"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#seed",
            "definition": "A multi-tissue plant structure (PO:0025496) that develops from a plant ovule (PO:0020003) and has as parts a plant embryo (PO:0009009) enclosed in a seed coat (PO:0009088)"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#seeds"
              }
            ]
          },
          "text": "seeds"
        }
      ],
      "text": "seed"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#beak"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#beak-tooth",
                "definition": "one of the paired separate narrow, pointed tips that sometimes occur at the apex of the tubular or flattened beak of the perigynium"
              }
            ]
          },
          "text": "beak tooth"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-beak"
              }
            ]
          },
          "text": "distal beak"
        }
      ],
      "text": "beak"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#junction",
            "definition": "The point where two or more organs are joined"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath-blade-junction",
                "definition": "The juncture of the leaf blade with the upper portion of the sheath"
              }
            ]
          },
          "text": "sheath-blade junction"
        }
      ],
      "text": "junction"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#axis",
            "definition": "The principal structure, on which other structures are attached"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#root",
                "definition": "A plant axis (PO:0025004) that lacks shoot axis nodes (PO:0005004) and usually grows indeterminately"
              }
            ]
          },
          "text": "root"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rachis",
                "definition": "the axis to which flowers or short branches are attached."
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rachilla"
                  }
                ]
              },
              "text": "rachilla"
            }
          ],
          "text": "rachis"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stem",
                "definition": "A shoot axis (PO:0025029) that is the primary axis of a plant"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shoot",
                    "definition": "An individual aerial growth from the rhizome"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#arial-shoot"
                      }
                    ]
                  },
                  "text": "arial shoot"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shoots"
                      }
                    ]
                  },
                  "text": "shoots"
                }
              ],
              "text": "shoot"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#culm",
                    "definition": "A shoot axis (PO:0025029) that is the primary axis of a plant"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tallest-culm"
                      }
                    ]
                  },
                  "text": "tallest culm"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#larger-culm",
                        "definition": "This term makes no sense - culms can be short or tall. The terms large and small are not applicable here"
                      }
                    ]
                  },
                  "text": "larger culm"
                }
              ],
              "text": "culm"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flowering-stem"
                  }
                ]
              },
              "text": "flowering-stem"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhizome",
                    "definition": "A swollen shoot axis (PO:0025029) that grows horizontally at or below the substrate surface and produces shoots above and roots or rhizoids below"
                  }
                ]
              },
              "text": "rhizome"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#branch",
                    "definition": "A shoot axis (PO:00025029) that develops from an axillary bud meristem (PO:0000232) or from equal divisions of a meristematic apical cell (PO:0030007)"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-branch",
                        "definition": "a branch that does not branch again and bears flowers. Short branches and also known as perigynia complexes. Short branches may come off long branches, or come off the stem directly."
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#female-flower"
                          }
                        ]
                      },
                      "text": "female flower"
                    }
                  ],
                  "text": "short branch"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-branch",
                        "definition": "A branch originating from one of the basal nodes of the culm"
                      }
                    ]
                  },
                  "text": "basal branch"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-branch"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-lateral-branch"
                          }
                        ]
                      },
                      "text": "proximal lateral branch"
                    }
                  ],
                  "text": "proximal branch"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynial-complex",
                        "definition": "short branch enclosed by a perigynium, with axillary female flowers"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#female-flower"
                          }
                        ]
                      },
                      "text": "female flower"
                    }
                  ],
                  "text": "perigynial complex"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-branch",
                        "definition": "a branch that bears additional short or long branches."
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#1st-order-long-branch"
                          }
                        ]
                      },
                      "text": "1st order long branch"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#2nd-order-long-branch"
                          }
                        ]
                      },
                      "text": "2nd order long branch"
                    }
                  ],
                  "text": "long branch"
                }
              ],
              "text": "branch"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stolon",
                    "definition": "A branch that is slender, prostrate or trailing and above-ground and produces roots and sometimes erect shoots at its nodes"
                  }
                ]
              },
              "text": "stolon"
            }
          ],
          "text": "stem"
        }
      ],
      "text": "axis"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#auricle"
          }
        ]
      },
      "text": "auricle"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blade"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#larger-blade",
                "definition": "Not the smallest (= longest, widest?) blade on a culm"
              }
            ]
          },
          "text": "larger blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leaf-blade",
                "definition": "the expanded and often flattened portion of the leaf"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-leaf-blade"
                  }
                ]
              },
              "text": "distal leaf-blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-leaf-blade",
                    "definition": "The blade of the leaf nearest the point of origin of the axis"
                  }
                ]
              },
              "text": "proximal leaf-blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-leaf-blade"
                  }
                ]
              },
              "text": "widest leaf-blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#foliage-leaf-blade"
                  }
                ]
              },
              "text": "foliage leaf-blade"
            }
          ],
          "text": "leaf blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cauline-blade",
                "definition": "The blade of a leaf on the aerial portion of the stem (culm)"
              }
            ]
          },
          "text": "cauline blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-blade"
              }
            ]
          },
          "text": "proximal blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-blade",
                "definition": "AR"
              }
            ]
          },
          "text": "widest blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bract-blade",
                "definition": "The blade or free portion of the bract"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-bract-blade"
                  }
                ]
              },
              "text": "widest bract blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-bract-blade",
                    "definition": "The blade of the bract farthest from the point of origin of the axis to which it is attached"
                  }
                ]
              },
              "text": "distal bract blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-bract-blade"
                  }
                ]
              },
              "text": "longest bract blade"
            }
          ],
          "text": "bract blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#largest-blade",
                "definition": "The largest (=longest, widest?) leaf on a culm"
              }
            ]
          },
          "text": "largest blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath-blade",
                "definition": "the portion of the leaf that does not surround, and is free from the culm - the photosynetic portion or lamina of the leaf"
              }
            ]
          },
          "text": "sheath blade"
        }
      ],
      "text": "blade"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#speckle"
          }
        ]
      },
      "text": "speckle"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#body",
            "definition": "In a Carex perigynium, the expanded portion associated with the enclosure for the achene"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#achene-body",
                "definition": "The expanded portion of the achene, between the abrupt narrowing to the base of the style and the abrupt narrowing to the stalk (stipe)"
              }
            ]
          },
          "text": "achene body"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-body"
              }
            ]
          },
          "text": "distal body"
        }
      ],
      "text": "body"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nerve"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nerve-of-staminate-scale"
              }
            ]
          },
          "text": "nerve of staminate scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nerve-of-pistillate-scale"
              }
            ]
          },
          "text": "nerve of pistillate scale"
        }
      ],
      "text": "nerve"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#keel"
          }
        ]
      },
      "text": "keel"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bristle-tip",
            "definition": "A short stiff hair-like tip of a structure"
          }
        ]
      },
      "text": "bristle tip"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium-wall"
          }
        ]
      },
      "text": "perigynium wall"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ligule"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-ligule"
              }
            ]
          },
          "text": "distal ligule"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-ligule",
                "definition": "The longest ligule based on a sampling of all the leaves found on culm"
              }
            ]
          },
          "text": "longest ligule"
        }
      ],
      "text": "ligule"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#margin",
            "definition": "The border or edge of a structure"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#margin-of-pistillate-scale"
              }
            ]
          },
          "text": "margin of pistillate scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#margin-of-staminate-scale"
              }
            ]
          },
          "text": "margin of staminate scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial-margin",
                "definition": "The margin facing away from the axis"
              }
            ]
          },
          "text": "abaxial margin"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-margin"
              }
            ]
          },
          "text": "distal margin"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leaf-blade-margin"
              }
            ]
          },
          "text": "leaf blade margin"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#margin-of-perigynium-beak"
              }
            ]
          },
          "text": "margin of perigynium beak"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#enfolding-margin"
              }
            ]
          },
          "text": "enfolding margin"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apical-margin",
                "definition": "The margin at the apex of a structure"
              }
            ]
          },
          "text": "apical margin"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#margin-of-bract-blade"
              }
            ]
          },
          "text": "margin of bract blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-margin"
              }
            ]
          },
          "text": "proximal margin"
        }
      ],
      "text": "margin"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#center",
            "definition": "The middle point of a structure (non-botanical)"
          }
        ]
      },
      "text": "center"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stalk",
            "definition": "A cardinal organ part (PO:0025001) that is elongated and sub-cylindrical to cylindrical and supports another cardinal organ part"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stipe",
                "definition": "Usually referring to the stalk attaching the ovary to the receptacle"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium-stipe"
                  }
                ]
              },
              "text": "perigynium stipe"
            }
          ],
          "text": "stipe"
        }
      ],
      "text": "stalk"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#surface"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial-surface",
                "definition": "The surface or side of an organ oriented towards the axis"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial-bract-blade"
                  }
                ]
              },
              "text": "adaxial bract blade"
            }
          ],
          "text": "adaxial surface"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blade-surface"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial-leaf-blade"
                  }
                ]
              },
              "text": "adaxial leaf blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial-leaf-blade"
                  }
                ]
              },
              "text": "abaxial leaf blade"
            }
          ],
          "text": "blade surface"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#side",
                "definition": "One of the surfaces forming the outside of an organ"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#achene-side"
                  }
                ]
              },
              "text": "achene side"
            }
          ],
          "text": "side"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial-surface",
                "definition": "AR"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial-bract-blade"
                  }
                ]
              },
              "text": "abaxial bract blade"
            }
          ],
          "text": "abaxial surface"
        }
      ],
      "text": "surface"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#peduncle"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-peduncle",
                "definition": "The peduncle situated farthest away from the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "distal peduncle"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-peduncle",
                "definition": "The peduncle nearest the point of origin of the axis which bears it"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-peduncle-of-inflorescence-unit"
                  }
                ]
              },
              "text": "proximal peduncle of inflorescence unit"
            }
          ],
          "text": "proximal peduncle"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spike-peduncle",
                "definition": "the portion of the branch between a node and a scale?"
              }
            ]
          },
          "text": "spike peduncle"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#slender-peduncle"
              }
            ]
          },
          "text": "slender peduncle"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longer-peduncle"
              }
            ]
          },
          "text": "longer peduncle"
        }
      ],
      "text": "peduncle"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#costa"
          }
        ]
      },
      "text": "costa"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bristle",
            "definition": "A short stiff hair-like structure"
          }
        ]
      },
      "text": "bristle"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#edge",
            "definition": "The border at which a thin, flat object like a leaf blade ends"
          }
        ]
      },
      "text": "edge"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fringe"
          }
        ]
      },
      "text": "fringe"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#segment"
          }
        ]
      },
      "text": "segment"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bract",
            "definition": "A phyllome that subtends a reproductive structure"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-bract"
              }
            ]
          },
          "text": "distal bract"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-bract"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-nonbasal-bract",
                    "definition": "The bract nearest the point of origin of the axis to which it is attached, except not on a basal node"
                  }
                ]
              },
              "text": "proximal nonbasal bract"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-cauline-bract"
                  }
                ]
              },
              "text": "proximal cauline bract"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-proximal-bract",
                    "definition": "The longest distance between the lowest nodes on a stem  - probably is reference to the distance between the two lowermost spike in an inflorescence"
                  }
                ]
              },
              "text": "longest proximal bract"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-bract-of-inflorescence"
                  }
                ]
              },
              "text": "proximal bract of inflorescence"
            }
          ],
          "text": "proximal bract"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inflorescence-bract"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#primary-inflorescence-bract",
                    "definition": "The bract subtending the proximal (lowermost) spike in an inflorescence"
                  }
                ]
              },
              "text": "primary inflorescence bract"
            }
          ],
          "text": "inflorescence bract"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distalmost-lateral-bract"
              }
            ]
          },
          "text": "distalmost lateral bract"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest-bract",
                "definition": "The longest leaf or bristle subtending a peduncle"
              }
            ]
          },
          "text": "longest bract"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scale"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-most-scale"
                  }
                ]
              },
              "text": "proximal most scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-scale",
                    "definition": "The scale nearest the point of origin of the axis to which it is attached"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-scale",
                        "definition": "The proximalmost scale in a \"spike\""
                      }
                    ]
                  },
                  "text": "basal scale"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-scale-of-terminal-portion-of-inflorescence"
                      }
                    ]
                  },
                  "text": "proximal scale of terminal portion of inflorescence"
                }
              ],
              "text": "proximal scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#staminate-scale"
                  }
                ]
              },
              "text": "staminate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subtending-scale",
                    "definition": "A scale occurring beneath and associated with a structure"
                  }
                ]
              },
              "text": "subtending scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pistillate-scale"
                  }
                ]
              },
              "text": "pistillate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stamimate-scale",
                    "definition": "The scale subtending a male flower"
                  }
                ]
              },
              "text": "stamimate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-scale",
                    "definition": "The scale situated farthest away from the point of origin of the axis to which it is attached"
                  }
                ]
              },
              "text": "distal scale"
            }
          ],
          "text": "scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bractlet"
              }
            ]
          },
          "text": "bractlet"
        }
      ],
      "text": "bract"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cortex"
          }
        ]
      },
      "text": "cortex"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath",
            "definition": "The tubular portion of the leaf, between the node and the blade"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leaf-sheath",
                "definition": "The tubular portion of the leaf, wrapping the shoot and to which the blade is joined distally"
              }
            ]
          },
          "text": "leaf-sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-sheath",
                "definition": "Sheaths originating from the lowermost (basal) nodes of a stem (culm)"
              }
            ]
          },
          "text": "basal sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-and-proximal-sheath",
                "definition": "The sheath associated with basal and proximal leaves"
              }
            ]
          },
          "text": "basal and proximal sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inflorescence-sheath"
              }
            ]
          },
          "text": "inflorescence sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal-leaf-sheath",
                "definition": "The sheath of the proximal-most leaf"
              }
            ]
          },
          "text": "basal leaf-sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bract-sheath",
                "definition": "The tubular portion of the bract, wrapping the shoot and to which the blade is joined distally"
              }
            ]
          },
          "text": "bract sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-sheath",
                "definition": "The sheath nearest the point of origin of the axis"
              }
            ]
          },
          "text": "proximal sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-sheath"
              }
            ]
          },
          "text": "distal sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath-blade",
                "definition": "the portion of the leaf that does not surround, and is free from the culm - the photosynetic portion or lamina of the leaf"
              }
            ]
          },
          "text": "sheath blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nonbasal-sheath"
              }
            ]
          },
          "text": "nonbasal sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distalmost-sheath",
                "definition": "The sheath farthest from the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "distalmost sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-bract-sheath",
                "definition": "The sheath of the bract closest to the point of origin of the axis to which it is attached"
              }
            ]
          },
          "text": "proximal bract sheath"
        }
      ],
      "text": "sheath"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#projection",
            "definition": "a protruding part (non-botanical)"
          }
        ]
      },
      "text": "projection"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#awn"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apex-awn"
              }
            ]
          },
          "text": "apex awn"
        }
      ],
      "text": "awn"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath-summits-collar"
          }
        ]
      },
      "text": "sheath summits collar"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath-front",
            "definition": "The differentiated abaxial  portion of the sheath"
          }
        ]
      },
      "text": "sheath front"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#point"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#point-of-attachment"
              }
            ]
          },
          "text": "point of attachment"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-achene"
                  }
                ]
              },
              "text": "widest point of achene"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-leaf-blade"
                  }
                ]
              },
              "text": "widest point of leaf blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-bract-blade"
                  }
                ]
              },
              "text": "widest point of bract blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-ligule"
                  }
                ]
              },
              "text": "widest point of ligule"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-pistillate-scale"
                  }
                ]
              },
              "text": "widest point of pistillate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-stem-node"
                  }
                ]
              },
              "text": "widest point of stem node"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-widest-face-of-perigynium"
                  }
                ]
              },
              "text": "widest point of widest face of perigynium"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-terminal-portion-of-inflorescence"
                  }
                ]
              },
              "text": "widest point of terminal portion of inflorescence"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-inflorescence-unit"
                  }
                ]
              },
              "text": "widest point of inflorescence unit"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest-point-of-staminate-scale"
                  }
                ]
              },
              "text": "widest point of staminate scale"
            }
          ],
          "text": "widest point"
        }
      ],
      "text": "point"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rib"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#main-rib"
              }
            ]
          },
          "text": "main rib"
        }
      ],
      "text": "rib"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#free-limb"
          }
        ]
      },
      "text": "free limb"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cross-section"
          }
        ]
      },
      "text": "cross section"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#prophyll"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal-perigynium",
                    "definition": "AR"
                  }
                ]
              },
              "text": "proximal perigynium"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal-perigynium",
                    "definition": "The upppermost perigynium in a spike"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terminal-perigynium",
                        "definition": "The uppermost, or distal-most perigynium in a spike"
                      }
                    ]
                  },
                  "text": "terminal perigynium"
                }
              ],
              "text": "distal perigynium"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#middle-perigynium",
                    "definition": "A perigynium in the middle of a spike (not distal or proximal)"
                  }
                ]
              },
              "text": "middle perigynium"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lateral-perigynium"
                  }
                ]
              },
              "text": "lateral perigynium"
            }
          ],
          "text": "perigynium"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cladoprophyll",
                "definition": "A sheathing prophyll on an elaborated axis"
              }
            ]
          },
          "text": "cladoprophyll"
        }
      ],
      "text": "prophyll"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#base"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#style-base"
              }
            ]
          },
          "text": "style base"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#base-of-the-proximal-bract"
              }
            ]
          },
          "text": "base of proximal bract"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#base-of-proximal-scale-of-terminal-portion-of-inflorescence"
              }
            ]
          },
          "text": "base of proximal scale of terminal portion of inflorescence"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leaf-base"
              }
            ]
          },
          "text": "leaf-base"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#base-of-proximal-scale"
              }
            ]
          },
          "text": "base of proximal scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perigynium-base",
                "definition": "The proximal, often differentiated portion of the perigynium"
              }
            ]
          },
          "text": "perigynium base"
        }
      ],
      "text": "base"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheathing"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-sheathing"
              }
            ]
          },
          "text": "short sheathing"
        }
      ],
      "text": "sheathing"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#mouth"
          }
        ]
      },
      "text": "mouth"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#depression",
            "definition": "a usually small sunken portion of a surface"
          }
        ]
      },
      "text": "depression"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#parenchyma"
          }
        ]
      },
      "text": "parenchyma"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#accessory"
          }
        ]
      },
      "text": "accessory"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheath-summit"
          }
        ]
      },
      "text": "sheath summit"
    }
  ],
  "text": "anatomical structure"
                },*/

                largeDataEntity:{},
                /*largeDataQuality:{
  "data": {
    "details": [
      {
        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#quality"
      }
    ]
  },
  "children": [
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#maturity",
            "definition": "A quality of a single physical entity which is held by a bearer when the latter exhibits complete growth, differentiation, or development"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#mature",
                "definition": "A quality inhering in a bearer by virtue of the bearer's exhibiting complete growth, differentiation, or development"
              }
            ]
          },
          "text": "mature"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#new"
              }
            ]
          },
          "text": "new"
        }
      ],
      "text": "maturity"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orientation",
            "definition": "A spatial quality inhering in a bearer by virtue of the bearer's placement which is defined by the angle between the bearer and an axis, or the angle between the bearer and another object"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#deflexed",
                "definition": "bent abruptly downward"
              }
            ]
          },
          "text": "deflexed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#appressed",
                "definition": "pressed close or flat against another organ"
              }
            ]
          },
          "text": "appressed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reflexed",
                "definition": "bent backward or downward"
              }
            ]
          },
          "text": "reflexed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#erect",
                "definition": "A positional quality inhering in a bearer by virtue of the bearer's being upright in position or posture"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#appressed-erect"
                  }
                ]
              },
              "text": "appressed-erect"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#suberect"
                  }
                ]
              },
              "text": "suberect"
            }
          ],
          "text": "erect"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#exerted"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#projecting"
                  }
                ]
              },
              "text": "projecting"
            }
          ],
          "text": "exerted"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inclined",
                "definition": "rising upwards at a moderate angle"
              }
            ]
          },
          "text": "inclined"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ascending",
                "definition": "growing obliquely upward, usually curved"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#erect-ascending"
                  }
                ]
              },
              "text": "erect-ascending"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#appressed-ascending"
                  }
                ]
              },
              "text": "appressed-ascending"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spreading-ascending"
                  }
                ]
              },
              "text": "spreading-ascending"
            }
          ],
          "text": "ascending"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#arching"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-arching"
                  }
                ]
              },
              "text": "long-arching"
            }
          ],
          "text": "arching"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#curvature"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#recurved",
                    "definition": "A curvature quality inhering in a bearer by virtue of the bearer's being curved backward or inward"
                  }
                ]
              },
              "text": "recurved"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#incurved",
                    "definition": "curved inwards; curved toward the base or apex"
                  }
                ]
              },
              "text": "incurved"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#curled",
                    "definition": "A shape quality inhering in a bearer by virtue of the bearer's having parallel chains in undulate fashion on the border"
                  }
                ]
              },
              "text": "curled"
            }
          ],
          "text": "curvature"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pendent",
                "definition": "hanging or dropping downwards"
              }
            ]
          },
          "text": "pendent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spreading",
                "definition": "extending nearly to the horizontal"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#loosely-spreading"
                  }
                ]
              },
              "text": "loosely spreading"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widely-spreading"
                  }
                ]
              },
              "text": "widely spreading"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#horizontally-spreading"
                  }
                ]
              },
              "text": "horizontally spreading"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#squarrose-spreading"
                  }
                ]
              },
              "text": "squarrose-spreading"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ascending-spreading"
                  }
                ]
              },
              "text": "ascending-spreading"
            }
          ],
          "text": "spreading"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#drooping",
                "definition": "To bent or hang downwards"
              }
            ]
          },
          "text": "drooping"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#horizontal",
                "definition": "A positional quality inhering in a bearer by virtue of the bearer's being in the plane of the horizon"
              }
            ]
          },
          "text": "horizontal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblique",
                "definition": "with unequal sides, especially a leaf base"
              }
            ]
          },
          "text": "oblique"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#recurrent",
                "definition": "A quality of a single process inhering in a bearer by virtue of the bearer's occurring or appearing again or repeatedly"
              }
            ]
          },
          "text": "recurrent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reclining",
                "definition": "bending or curving downward"
              }
            ]
          },
          "text": "reclining"
        }
      ],
      "text": "orientation"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape",
            "definition": "A morphological quality inhering in a bearer by virtue of the bearer's ratios of distances between its features (points, edges, surfaces and also holes etc)"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obdeltoid",
                "definition": "deltoid with an attachment at the pointed end"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obdeltoid-obovoid"
                  }
                ]
              },
              "text": "obdeltoid-obovoid"
            }
          ],
          "text": "obdeltoid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#filiform",
                "definition": "thread like; filamentous"
              }
            ]
          },
          "text": "filiform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being narrow, with the two opposite margins parallel"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-linear"
                  }
                ]
              },
              "text": "oblong-linear"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-oblong"
                  }
                ]
              },
              "text": "linear-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-lanceolate"
                  }
                ]
              },
              "text": "linear-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-elongate"
                  }
                ]
              },
              "text": "linear-elongate"
            }
          ],
          "text": "linear"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#trabeculate",
                "definition": "with crossbar"
              }
            ]
          },
          "text": "trabeculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dilated",
                "definition": "A size quality inhering in a bearer by virtue of the bearer's being made wider or larger in all dimensions"
              }
            ]
          },
          "text": "dilated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblanceolate",
                "definition": "A quality inhering in a bearer by virtue of the bearer's being extended along a straight line, and is shaped like a reversed lance-point, with the tapering point attached to the leafstalk"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-oblanceolate"
                  }
                ]
              },
              "text": "elliptic-oblanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-oblanceloid"
                  }
                ]
              },
              "text": "narrowly oblanceloid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblanceolate-elliptic"
                  }
                ]
              },
              "text": "oblanceolate-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-oblanceolate"
                  }
                ]
              },
              "text": "oblong-oblanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-oblanceolate"
                  }
                ]
              },
              "text": "linear-oblanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovoid-oblanceoloid"
                  }
                ]
              },
              "text": "obovoid-oblanceoloid"
            }
          ],
          "text": "oblanceolate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obtuse",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being roundish, a little inclining to be oblong"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inrolled-obtuse"
                  }
                ]
              },
              "text": "inrolled-obtuse"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-obtuse"
                  }
                ]
              },
              "text": "broadly-obtuse"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subobtuse"
                  }
                ]
              },
              "text": "subobtuse"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-obtuse"
                  }
                ]
              },
              "text": "rounded-obtuse"
            }
          ],
          "text": "obtuse"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pyramidal",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having triangular faces that meet at a common point and containing a polygonal shaped base"
              }
            ]
          },
          "text": "pyramidal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blunt",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's terminating gradually in a rounded end"
              }
            ]
          },
          "text": "blunt"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#caudate",
                "definition": "A quality inhering in a bearer by virtue of the bearer's having a tail or tail-like projection"
              }
            ]
          },
          "text": "caudate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orbicular"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#suborbicular"
                  }
                ]
              },
              "text": "suborbicular"
            }
          ],
          "text": "orbicular"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obvoid"
              }
            ]
          },
          "text": "obvoid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#quadrangular",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having four angles and four sides"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#quadrate",
                    "definition": "square; rectangular"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-quadrate"
                      }
                    ]
                  },
                  "text": "ovate-quadrate"
                }
              ],
              "text": "quadrate"
            }
          ],
          "text": "quadrangular"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having a somewhat elongated form with approximately parallel sides"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-obovate"
                  }
                ]
              },
              "text": "oblong-obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-oblong"
                  }
                ]
              },
              "text": "narrowly oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-oblong"
                  }
                ]
              },
              "text": "elliptic-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elongate-oblong"
                  }
                ]
              },
              "text": "elongate-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-oblong"
                  }
                ]
              },
              "text": "short-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-oblong"
                  }
                ]
              },
              "text": "ovate-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-obovoid"
                  }
                ]
              },
              "text": "oblong-obovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-elliptic"
                  }
                ]
              },
              "text": "oblong-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-ellipsoid"
                  }
                ]
              },
              "text": "oblong-ellipsoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceolate-oblong"
                  }
                ]
              },
              "text": "lanceolate-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid-oblong"
                  }
                ]
              },
              "text": "ovoid-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-lanceolate"
                  }
                ]
              },
              "text": "oblong-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-oblong"
                  }
                ]
              },
              "text": "rhombic-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-oblong"
                  }
                ]
              },
              "text": "linear-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-ovate"
                  }
                ]
              },
              "text": "oblong-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-fusiform"
                  }
                ]
              },
              "text": "oblong-fusiform"
            }
          ],
          "text": "oblong"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#circular",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being such that every part of the surface or the circumference is equidistant from the center"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subcircular",
                    "definition": "A circular shape quality inhering in a bearer by virtue of the bearer's being nearly, but not perfectly, circular"
                  }
                ]
              },
              "text": "subcircular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-circular"
                  }
                ]
              },
              "text": "ovate-circular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-circular"
                  }
                ]
              },
              "text": "elliptic-circular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#circular-obovate"
                  }
                ]
              },
              "text": "circular-obovate"
            }
          ],
          "text": "circular"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pyriform",
                "definition": "pear shaped"
              }
            ]
          },
          "text": "pyriform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#truncate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's terminating abruptly by having or as if having an end or point cut off"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subtruncate"
                  }
                ]
              },
              "text": "subtruncate"
            }
          ],
          "text": "truncate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tubular",
                "definition": "A cylindrical shape that is hollow"
              }
            ]
          },
          "text": "tubular"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#auriculate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having a shape resembling an ear"
              }
            ]
          },
          "text": "auriculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate",
                "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's exhibiting a continuous convex surface with an axis of symmetry and one axis longer than the other; egg-shaped"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orbicular-ovate"
                  }
                ]
              },
              "text": "orbicular-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subovate"
                  }
                ]
              },
              "text": "subovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-ovate"
                  }
                ]
              },
              "text": "rhombic-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-globose"
                  }
                ]
              },
              "text": "ovate-globose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-ovate"
                  }
                ]
              },
              "text": "linear-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#deltate-ovate"
                  }
                ]
              },
              "text": "deltate-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-clavate"
                  }
                ]
              },
              "text": "ovate-clavate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-oblong"
                  }
                ]
              },
              "text": "ovate-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-ovate"
                  }
                ]
              },
              "text": "elliptic-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broad-ovate"
                  }
                ]
              },
              "text": "broad-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-fusiform"
                  }
                ]
              },
              "text": "ovate-fusiform"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceovate"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceovate-planoconvex"
                      }
                    ]
                  },
                  "text": "lanceovate-planoconvex"
                }
              ],
              "text": "lanceovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhomboid-ovate"
                  }
                ]
              },
              "text": "rhomboid-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-cylindric"
                  }
                ]
              },
              "text": "ovate-cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-lanceolate"
                  }
                ]
              },
              "text": "ovate-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-quadrate"
                  }
                ]
              },
              "text": "ovate-quadrate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-circular"
                  }
                ]
              },
              "text": "ovate-circular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-ovate"
                  }
                ]
              },
              "text": "oblong-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-rhomboid"
                  }
                ]
              },
              "text": "ovate-rhomboid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-elliptic"
                  }
                ]
              },
              "text": "ovate-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-triangular"
                  }
                ]
              },
              "text": "ovate-triangular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#trullate-ovate"
                  }
                ]
              },
              "text": "trullate-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-orbiculate"
                  }
                ]
              },
              "text": "ovate-orbiculate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-ovate"
                  }
                ]
              },
              "text": "narrowly ovate"
            }
          ],
          "text": "ovate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cucullate",
                "definition": "hooded or hood-shaped"
              }
            ]
          },
          "text": "cucullate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-in-transverse-section"
              }
            ]
          },
          "text": "shape in transverse section"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#setaceous",
                "definition": "bristle-like; with bristles"
              }
            ]
          },
          "text": "setaceous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#acute",
                "definition": "A quality of a process inhering in a bearer by virtue of the bearer's having a sudden onset, sharp rise, and short course"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-acute"
                  }
                ]
              },
              "text": "short-acute"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#acute-rounded"
                  }
                ]
              },
              "text": "acute-rounded"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subacute"
                  }
                ]
              },
              "text": "subacute"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-acute"
                  }
                ]
              },
              "text": "broadly acute"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-acute"
                  }
                ]
              },
              "text": "long-acute"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-acute"
                  }
                ]
              },
              "text": "ovate-acute"
            }
          ],
          "text": "acute"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovate",
                "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's being egg-shaped and flat, with the narrow end attached to the base"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovate-orbicular"
                  }
                ]
              },
              "text": "obovate-orbicular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#globose-obovate"
                  }
                ]
              },
              "text": "globose-obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-obovate"
                  }
                ]
              },
              "text": "oblong-obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sharply-obovate"
                  }
                ]
              },
              "text": "sharply obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovate-lanceolate"
                  }
                ]
              },
              "text": "obovate-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovate-terete"
                  }
                ]
              },
              "text": "obovate-terete"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-obovate"
                  }
                ]
              },
              "text": "broadly obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subobovate"
                  }
                ]
              },
              "text": "subobovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-obovate"
                  }
                ]
              },
              "text": "elliptic-obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#circular-obovate"
                  }
                ]
              },
              "text": "circular-obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-obovate"
                  }
                ]
              },
              "text": "narrowly obovate"
            }
          ],
          "text": "obovate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#plane"
              }
            ]
          },
          "text": "plane"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hood-shaped"
              }
            ]
          },
          "text": "hood shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rolled"
              }
            ]
          },
          "text": "rolled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#entire",
                "definition": "not toothed, notched, or divided, as the continuous margins on some leaves"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subentire"
                  }
                ]
              },
              "text": "subentire"
            }
          ],
          "text": "entire"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#funnelform",
                "definition": "gradually widening from base to apex; funnel-shaped"
              }
            ]
          },
          "text": "funnelform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sinuate",
                "definition": "with a strongly wavy margin"
              }
            ]
          },
          "text": "sinuate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#concavo-convex",
                "definition": "A shape quality that obtains by virtue of the bearer being concave on one side and convex on the other"
              }
            ]
          },
          "text": "concavo-convex"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elongate"
              }
            ]
          },
          "text": "elongate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#y-shaped"
              }
            ]
          },
          "text": "y-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#triangular",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having three angles"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#triangular-channeled"
                  }
                ]
              },
              "text": "triangular channeled"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#trigonous"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-trigonous"
                      }
                    ]
                  },
                  "text": "rounded-trigonous"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flattened-trigonous"
                      }
                    ]
                  },
                  "text": "flattened-trigonous"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compressed-trigonous"
                      }
                    ]
                  },
                  "text": "compressed-trigonous"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sharply-trigonous"
                      }
                    ]
                  },
                  "text": "sharply trigonous"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#trigonous-obovoid"
                      }
                    ]
                  },
                  "text": "trigonous-obovoid"
                }
              ],
              "text": "trigonous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flattened-triangular"
                  }
                ]
              },
              "text": "flattened-triangular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-triangular"
                  }
                ]
              },
              "text": "ovate-triangular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#triquetrous"
                  }
                ]
              },
              "text": "triquetrous"
            }
          ],
          "text": "triangular"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#aristate",
                "definition": "bearing an awn or bristle at the tip"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-aristate"
                  }
                ]
              },
              "text": "short-aristate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-aristate"
                  }
                ]
              },
              "text": "long-aristate"
            }
          ],
          "text": "aristate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abrupt"
              }
            ]
          },
          "text": "abrupt"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#u-shaped"
              }
            ]
          },
          "text": "u-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-trigonous"
                  }
                ]
              },
              "text": "rounded-trigonous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subrounded"
                  }
                ]
              },
              "text": "subrounded"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#acute-rounded"
                  }
                ]
              },
              "text": "acute-rounded"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-cuneate"
                  }
                ]
              },
              "text": "rounded-cuneate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovoid-round"
                  }
                ]
              },
              "text": "obovoid-round"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-tapering"
                  }
                ]
              },
              "text": "rounded tapering"
            }
          ],
          "text": "rounded"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pleated"
              }
            ]
          },
          "text": "pleated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#w-shaped"
              }
            ]
          },
          "text": "w-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#m-shaped"
              }
            ]
          },
          "text": "m-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subulate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being linear, very narrow, tapering to a very fine point from a narrow base"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lance-subulate"
                  }
                ]
              },
              "text": "lance-subulate"
            }
          ],
          "text": "subulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#capillary"
              }
            ]
          },
          "text": "capillary"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#acuminate",
                "definition": "A tapered shape quality inhering in a bearer by virtue of the bearer's tapering gradually to a sharp point"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceolate-acuminate"
                  }
                ]
              },
              "text": "lanceolate-acuminate"
            }
          ],
          "text": "acuminate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oval",
                "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's exhibiting a continuous convex surface with an axis of symmetry and one axis longer than the other; egg-shaped"
              }
            ]
          },
          "text": "oval"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terete",
                "definition": "round in cross section; cylindrical"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovate-terete"
                  }
                ]
              },
              "text": "obovate-terete"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subterete"
                  }
                ]
              },
              "text": "subterete"
            }
          ],
          "text": "terete"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#revolute",
                "definition": "A curled quality inhering in a bearer by virtue of the bearer's edges of its surface being rolled backwards spirally on each side"
              }
            ]
          },
          "text": "revolute"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#denticulate",
                "definition": "dentate with very small teeth"
              }
            ]
          },
          "text": "denticulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flattened"
              }
            ]
          },
          "text": "flattened"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ridged",
                "definition": "An elongated raised margin or border"
              }
            ]
          },
          "text": "ridged"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceoloid"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-lanceoloid"
                  }
                ]
              },
              "text": "oblong-lanceoloid"
            }
          ],
          "text": "lanceoloid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cylindric",
                "definition": "A convex 3-D shape quality inhering in a bearer by virtue of the bearer's exhibiting a consistently-sized round cross section"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-cylindric"
                  }
                ]
              },
              "text": "ovate-cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-cylindric"
                  }
                ]
              },
              "text": "long-cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-cylindric"
                  }
                ]
              },
              "text": "narrowly cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#clavate-cylindric"
                  }
                ]
              },
              "text": "clavate-cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-cylindric"
                  }
                ]
              },
              "text": "oblong-cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-cylindric"
                  }
                ]
              },
              "text": "linear-cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#involute-cylindric"
                  }
                ]
              },
              "text": "involute-cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subcylindric"
                  }
                ]
              },
              "text": "subcylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-cylindric"
                  }
                ]
              },
              "text": "short-cylindric"
            }
          ],
          "text": "cylindric"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-in-longitudinal-section"
              }
            ]
          },
          "text": "shape in longitudinal section"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scalloped",
                "definition": "A surface feature shape quality inhering in a bearer by virtue of the bearer's having the border, edge, or outline cut into a series of segments of circles resembling a scallop-shell"
              }
            ]
          },
          "text": "scalloped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pointed",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having a point"
              }
            ]
          },
          "text": "pointed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#crinkled",
                "definition": "flattened and somewhat twisted, kinked, or curled"
              }
            ]
          },
          "text": "crinkled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovatelanceolate"
              }
            ]
          },
          "text": "ovatelanceolate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cordlike"
              }
            ]
          },
          "text": "cordlike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovateoblong"
              }
            ]
          },
          "text": "ovateoblong"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#clavate",
                "definition": "A concave 3-D shape quality inhering in a bearer by virtue of the bearer's gradually becoming thicker towards the end"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subclavate"
                  }
                ]
              },
              "text": "subclavate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-clavate"
                  }
                ]
              },
              "text": "long-clavate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#clavate-cylindric"
                  }
                ]
              },
              "text": "clavate-cylindric"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-clavate"
                  }
                ]
              },
              "text": "ovate-clavate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-clavate"
                  }
                ]
              },
              "text": "linear-clavate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#clavate-ovoid"
                  }
                ]
              },
              "text": "clavate-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-clavate"
                  }
                ]
              },
              "text": "oblong-clavate"
            }
          ],
          "text": "clavate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#outcurved"
              }
            ]
          },
          "text": "outcurved"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inrolled",
                "definition": "curled or rolled inward"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inrolled-obtuse"
                  }
                ]
              },
              "text": "inrolled-obtuse"
            }
          ],
          "text": "inrolled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#capitate",
                "definition": "head-like, or in a head-shaped cluster, as the flower in many plant groups but especially those in the dense capitate inflorescences of the compositae (asteraceae)"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subcapitate"
                  }
                ]
              },
              "text": "subcapitate"
            }
          ],
          "text": "capitate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#diamond-shaped"
              }
            ]
          },
          "text": "diamond-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#strap-shaped",
                "definition": "with ligule; strap-shaped"
              }
            ]
          },
          "text": "ligulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#curled",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having parallel chains in undulate fashion on the border"
              }
            ]
          },
          "text": "curled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orbiculate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being perfectly circular"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovate-orbicular"
                  }
                ]
              },
              "text": "obovate-orbicular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-orbicular"
                  }
                ]
              },
              "text": "elliptic-orbicular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orbiculate-flat"
                  }
                ]
              },
              "text": "orbiculate flat"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid-orbicular"
                  }
                ]
              },
              "text": "ovoid-orbicular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-orbiculate"
                  }
                ]
              },
              "text": "rhombic-orbiculate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-orbiculate"
                  }
                ]
              },
              "text": "ovate-orbiculate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#suborbiculate"
                  }
                ]
              },
              "text": "suborbiculate"
            }
          ],
          "text": "orbiculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tapered",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being gradually narrower or thinner toward one end"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-tapered"
                  }
                ]
              },
              "text": "short-tapered"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gradually-tapering"
                  }
                ]
              },
              "text": "gradually tapering"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-tapering"
                  }
                ]
              },
              "text": "long tapering"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-tapering"
                  }
                ]
              },
              "text": "rounded tapering"
            }
          ],
          "text": "tapered"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#deltate",
                "definition": "with the shape of the greek letter delta; shaped like an equilateral triangle"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#deltoid-ovoid"
                  }
                ]
              },
              "text": "deltoid-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#deltate-ovate"
                  }
                ]
              },
              "text": "deltate-ovate"
            }
          ],
          "text": "deltate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#planoconvex"
              }
            ]
          },
          "text": "planoconvex"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obutse"
              }
            ]
          },
          "text": "obutse"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obpyriform"
              }
            ]
          },
          "text": "obpyriform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cuspidate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being tapering gradually into a rigid point"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-cuspidate"
                  }
                ]
              },
              "text": "short-cuspidate"
            }
          ],
          "text": "cuspidate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#retuse",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's terminating in a round end, the centre of which is depressed"
              }
            ]
          },
          "text": "retuse"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#mucronate",
                "definition": "tipped with  short, sharp, abrupt point (mucro)"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-mucronate"
                  }
                ]
              },
              "text": "short-mucronate"
            }
          ],
          "text": "mucronate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compressed"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compressed-obovoid"
                  }
                ]
              },
              "text": "compressed-obovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compressed-trigonous"
                  }
                ]
              },
              "text": "compressed-trigonous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compressed-ovoid"
                  }
                ]
              },
              "text": "compressed-ovoid"
            }
          ],
          "text": "compressed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#knobbed"
              }
            ]
          },
          "text": "knobbed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fibriform",
                "definition": "fiber like"
              }
            ]
          },
          "text": "fibriform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flared"
              }
            ]
          },
          "text": "flared"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#needle-shaped"
              }
            ]
          },
          "text": "needle-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cuspitate"
              }
            ]
          },
          "text": "cuspitate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#v-shaped"
              }
            ]
          },
          "text": "v-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gutter--shaped"
              }
            ]
          },
          "text": "gutter-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceolate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being shaped like a lance-head, considerably longer than wide, tapering towards the tip from below the middle; attached at the broad end"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceolate-oblong"
                  }
                ]
              },
              "text": "lanceolate-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceolate-acuminate"
                  }
                ]
              },
              "text": "lanceolate-acuminate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-lanceolate"
                  }
                ]
              },
              "text": "ovate-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-lanceolate"
                  }
                ]
              },
              "text": "oblong-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovate-lanceolate"
                  }
                ]
              },
              "text": "obovate-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#linear-lanceolate"
                  }
                ]
              },
              "text": "linear-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-lanceolate"
                  }
                ]
              },
              "text": "broadly lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sublanceolate"
                  }
                ]
              },
              "text": "sublanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceolate-elliptic"
                  }
                ]
              },
              "text": "lanceolate-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-lanceolate"
                  }
                ]
              },
              "text": "elliptic-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-lanceolate"
                  }
                ]
              },
              "text": "narrowly lanceolate"
            }
          ],
          "text": "lanceolate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cuneate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being narrowly triangular, wider at the apex and tapering toward the base"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-cuneate"
                  }
                ]
              },
              "text": "broadly cuneate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cuneate-attenuate"
                  }
                ]
              },
              "text": "cuneate-attenuate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-cuneate"
                  }
                ]
              },
              "text": "rounded-cuneate"
            }
          ],
          "text": "cuneate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#trullate"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#trullate-ovate"
                  }
                ]
              },
              "text": "trullate-ovate"
            }
          ],
          "text": "trullate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#toothed",
                "definition": "A surface feature shape quality inhering in a bearer by virtue of the bearer's having toothlike projections in the margin"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#minutely-toothed"
                  }
                ]
              },
              "text": "minutely toothed"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bidentate"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shortly-bidentate"
                      }
                    ]
                  },
                  "text": "shortly bidentate"
                }
              ],
              "text": "bidentate"
            }
          ],
          "text": "toothed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scale-shaped"
              }
            ]
          },
          "text": "scale-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#awn-shaped"
              }
            ]
          },
          "text": "awn-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#awl-shaped"
              }
            ]
          },
          "text": "awl shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sharp",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's terminating in a point or edge"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sharply-obovate"
                  }
                ]
              },
              "text": "sharply obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sharply-trigonous"
                  }
                ]
              },
              "text": "sharply trigonous"
            }
          ],
          "text": "sharp"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#involute",
                "definition": "A curled quality inhering in a bearer by virtue of the bearer's edges of its surface being rolled inwards spirally on each side"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#filiform-involute"
                  }
                ]
              },
              "text": "filiform-involute"
            }
          ],
          "text": "involute"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hemispheric",
                "definition": "shaped like half a sphere"
              }
            ]
          },
          "text": "hemispheric"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obtriangular"
              }
            ]
          },
          "text": "obtriangular"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#crenulate",
                "definition": "A surface feature shape quality inhering in a bearer by virtue of the bearer's having the border, edge, or outline cut into a series of segments of circles resembling a scallop-shell"
              }
            ]
          },
          "text": "crenulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cordate",
                "definition": "A concave 3-D shape quality inhering in a bearer by virtue of the bearer's having a sinus or rounded lobe at the base"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subcordate"
                  }
                ]
              },
              "text": "subcordate"
            }
          ],
          "text": "cordate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceloid"
              }
            ]
          },
          "text": "lanceloid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#circinate",
                "definition": "coiled from the tip downward, as in the young leaves of a fern"
              }
            ]
          },
          "text": "circinate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flat",
                "definition": "A quality inhering in a bearer by virtue of the bearer's having a horizontal surface without a slope, tilt, or curvature"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flattened-trigonous"
                  }
                ]
              },
              "text": "flattened-trigonous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovoid-flat"
                  }
                ]
              },
              "text": "obovoid flat"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orbiculate-flat"
                  }
                ]
              },
              "text": "orbiculate flat"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flattened-triangular"
                  }
                ]
              },
              "text": "flattened-triangular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subflat"
                  }
                ]
              },
              "text": "subflat"
            }
          ],
          "text": "flat"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhomboid",
                "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's being oval and a little angular in the middle"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhomboid-ovoid"
                  }
                ]
              },
              "text": "rhomboid-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-rhomboid"
                  }
                ]
              },
              "text": "ovate-rhomboid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhomboid-ovate"
                  }
                ]
              },
              "text": "rhomboid-ovate"
            }
          ],
          "text": "rhomboid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#convolute",
                "definition": "A curled quality inhering in a bearer by virtue of the bearer's one edge of its surface being wholly rolled up in another"
              }
            ]
          },
          "text": "convolute"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obconic"
              }
            ]
          },
          "text": "obconic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hair-shaped"
              }
            ]
          },
          "text": "hair-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apiculate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having a sharp or tapered end or point"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-apiculate"
                  }
                ]
              },
              "text": "short-apiculate"
            }
          ],
          "text": "apiculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowed"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abruptly-narrowed"
                  }
                ]
              },
              "text": "abruptly narrowed"
            }
          ],
          "text": "narrowed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fusiform",
                "definition": "Having a spindle-like shape that is wide in the middle and tapers at both ends"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-fusiform"
                  }
                ]
              },
              "text": "ovate-fusiform"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-fusiform"
                  }
                ]
              },
              "text": "oblong-fusiform"
            }
          ],
          "text": "fusiform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#undulate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having a sinuate margin and rippled surface"
              }
            ]
          },
          "text": "undulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#symmetric",
                "definition": "A symmetry quality inhering in a bearer by virtue of the bearer's being capable of division by a longitudinal plane into similar halves"
              }
            ]
          },
          "text": "symmetric"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#angled"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sharp-angled"
                  }
                ]
              },
              "text": "sharp-angled"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrous-angled"
                  }
                ]
              },
              "text": "scabrous-angled"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#smooth-angled"
                  }
                ]
              },
              "text": "smooth-angled"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-angled"
                  }
                ]
              },
              "text": "rounded-angled"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wing-angled"
                  }
                ]
              },
              "text": "wing-angled"
            }
          ],
          "text": "angled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#half-terete"
              }
            ]
          },
          "text": "half-terete"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#boatshaped"
              }
            ]
          },
          "text": "boat-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reniform",
                "definition": "A concave 3-D shape quality inhering in a bearer by virtue of the bearer's having the shape of a kidney"
              }
            ]
          },
          "text": "reniform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hood--shaped"
              }
            ]
          },
          "text": "hood--shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#conic",
                "definition": "A convex 3-D shape quality inhering in a bearer by virtue of the bearer's resembling a cone (a 3-D shape with a round cross section that tapers)"
              }
            ]
          },
          "text": "conic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#asymmetric",
                "definition": "A symmetry quality inhering in a bearer by virtue of the bearer's lacking symmetry"
              }
            ]
          },
          "text": "asymmetric"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cut"
              }
            ]
          },
          "text": "cut"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#concave",
                "definition": "A shape quality in a bearer by virtue of the bearer's curving inward"
              }
            ]
          },
          "text": "concave"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic",
                "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's being oval with two axes of symmetry, as produced by a conical section"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-oblanceolate"
                  }
                ]
              },
              "text": "elliptic-oblanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-rhombic"
                  }
                ]
              },
              "text": "elliptic-rhombic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-oblong"
                  }
                ]
              },
              "text": "elliptic-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblanceolate-elliptic"
                  }
                ]
              },
              "text": "oblanceolate-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-ovate"
                  }
                ]
              },
              "text": "elliptic-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceolate-elliptic"
                  }
                ]
              },
              "text": "lanceolate-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-elliptic"
                  }
                ]
              },
              "text": "oblong-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ellipsoid",
                    "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's being oval with two axes of symmetry, as produced by a conical section"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-ellipsoid"
                      }
                    ]
                  },
                  "text": "broadly ellipsoid"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ellipsoid-obovoid"
                      }
                    ]
                  },
                  "text": "ellipsoid-obovoid"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-ellipsoid"
                      }
                    ]
                  },
                  "text": "narrowly ellipsoid"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lance-ellipsoid"
                      }
                    ]
                  },
                  "text": "lance-ellipsoid"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-ellipsoid"
                      }
                    ]
                  },
                  "text": "rhombic-ellipsoid"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-ellipsoid"
                      }
                    ]
                  },
                  "text": "oblong-ellipsoid"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid-ellipsoid"
                      }
                    ]
                  },
                  "text": "ovoid-ellipsoid"
                }
              ],
              "text": "ellipsoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-orbicular"
                  }
                ]
              },
              "text": "elliptic-orbicular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-ovoid"
                  }
                ]
              },
              "text": "elliptic-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-elliptic"
                  }
                ]
              },
              "text": "broadly-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-lanceolate"
                  }
                ]
              },
              "text": "elliptic-lanceolate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-elliptic"
                  }
                ]
              },
              "text": "ovate-elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-circular"
                  }
                ]
              },
              "text": "elliptic-circular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-elliptic"
                  }
                ]
              },
              "text": "narrowly elliptic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-obovate"
                  }
                ]
              },
              "text": "elliptic-obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subelliptic"
                  }
                ]
              },
              "text": "subelliptic"
            }
          ],
          "text": "elliptic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#falcate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having the shape of a scythe or sickle"
              }
            ]
          },
          "text": "falcate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#globose",
                "definition": "globe-shaped; spherical"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid-globose"
                  }
                ]
              },
              "text": "ovoid-globose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#globose-obovate"
                  }
                ]
              },
              "text": "globose-obovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovate-globose"
                  }
                ]
              },
              "text": "ovate-globose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subglobose"
                  }
                ]
              },
              "text": "subglobose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#globular",
                    "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's resembling a ball (a sphere whose equatorial diameter is equal to the polar diameter)"
                  }
                ]
              },
              "text": "globular"
            }
          ],
          "text": "globose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic",
                "definition": "diamond shape"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-rhombic"
                  }
                ]
              },
              "text": "elliptic-rhombic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-ovate"
                  }
                ]
              },
              "text": "rhombic-ovate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-oblong"
                  }
                ]
              },
              "text": "rhombic-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-ovoid"
                  }
                ]
              },
              "text": "rhombic-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subrhombic"
                  }
                ]
              },
              "text": "subrhombic"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-orbiculate"
                  }
                ]
              },
              "text": "rhombic-orbiculate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-ellipsoid"
                  }
                ]
              },
              "text": "rhombic-ellipsoid"
            }
          ],
          "text": "rhombic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#indented"
              }
            ]
          },
          "text": "indented"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distended",
                "definition": "A size quality inhering in a bearer by virtue of the bearer's being abnormally expanded or increased in size"
              }
            ]
          },
          "text": "distended"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spathose-shaped"
              }
            ]
          },
          "text": "spathose-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obpyramidal"
              }
            ]
          },
          "text": "obpyramidal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#quill-shaped"
              }
            ]
          },
          "text": "quill-shaped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblanceoloid"
              }
            ]
          },
          "text": "oblanceoloid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#attenuate",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's gradually tapering to a slender point"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-attenuate"
                  }
                ]
              },
              "text": "short-attenuate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cuneate-attenuate"
                  }
                ]
              },
              "text": "cuneate-attenuate"
            }
          ],
          "text": "attenuate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#utriculiform"
              }
            ]
          },
          "text": "utriculiform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#emarginate",
                "definition": "A notched shape quality inhering in a bearer by virtue of the bearer's having a notched tip or edge"
              }
            ]
          },
          "text": "emarginate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#otuse"
              }
            ]
          },
          "text": "otuse"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid",
                "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's exhibiting a continuous convex surface with an axis of symmetry and one axis longer than the other; egg-shaped"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid-globose"
                  }
                ]
              },
              "text": "ovoid-globose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-ovoid"
                  }
                ]
              },
              "text": "short-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#deltoid-ovoid"
                  }
                ]
              },
              "text": "deltoid-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-ovoid"
                  }
                ]
              },
              "text": "narrowly ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhomboid-ovoid"
                  }
                ]
              },
              "text": "rhomboid-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid-orbicular"
                  }
                ]
              },
              "text": "ovoid-orbicular"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#clavate-ovoid"
                  }
                ]
              },
              "text": "clavate-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid-ellipsoid"
                  }
                ]
              },
              "text": "ovoid-ellipsoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-ovoid"
                  }
                ]
              },
              "text": "broadly ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elliptic-ovoid"
                  }
                ]
              },
              "text": "elliptic-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ovoid-oblong"
                  }
                ]
              },
              "text": "ovoid-oblong"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceovoid"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowly-lanceovoid"
                      }
                    ]
                  },
                  "text": "narrowly lanceovoid"
                }
              ],
              "text": "lanceovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compressed-ovoid"
                  }
                ]
              },
              "text": "compressed-ovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhombic-ovoid"
                  }
                ]
              },
              "text": "rhombic-ovoid"
            }
          ],
          "text": "ovoid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#convex",
                "definition": "A shape quality that obtains by virtue of the bearer having inward facing edges; having a surface or boundary that curves or bulges outward, as the exterior of a sphere"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#biconvex",
                    "definition": "A shape quality inhering in a bearer by virtue of the bearer's being convex on both sides or surface"
                  }
                ]
              },
              "text": "biconvex"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#plano-convex"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subplano-convex"
                      }
                    ]
                  },
                  "text": "subplano-convex"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lanceovate-planoconvex"
                      }
                    ]
                  },
                  "text": "lanceovate-planoconvex"
                }
              ],
              "text": "plano-convex"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-convex"
                  }
                ]
              },
              "text": "short-convex"
            }
          ],
          "text": "convex"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblate"
              }
            ]
          },
          "text": "oblate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spherical",
                "definition": "A spheroid quality inhering in a bearer by virtue of the bearer's resembling a ball (a sphere whose equatorial diameter is equal to the polar diameter)"
              }
            ]
          },
          "text": "spherical"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#curving"
              }
            ]
          },
          "text": "curving"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#serrulate",
                "definition": "toothed along the margin with minute, sharp, forward-pointing teeth"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ciliate-serrulate"
                  }
                ]
              },
              "text": "ciliate-serrulate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#setulose-serrulate"
                  }
                ]
              },
              "text": "setulose-serrulate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subserrulate"
                  }
                ]
              },
              "text": "subserrulate"
            }
          ],
          "text": "serrulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#annulate",
                "definition": "in the form of a ring"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#discoid-annulate"
                  }
                ]
              },
              "text": "discoid-annulate"
            }
          ],
          "text": "annulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#mitrate"
              }
            ]
          },
          "text": "mitrate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovoid",
                "definition": "inversely ovoid with the attachment at the narrow end"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compressed-obovoid"
                  }
                ]
              },
              "text": "compressed-obovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obdeltoid-obovoid"
                  }
                ]
              },
              "text": "obdeltoid-obovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadly-obovoid"
                  }
                ]
              },
              "text": "broadly obovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elongate-obovoid"
                  }
                ]
              },
              "text": "elongate-obovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovoid-flat"
                  }
                ]
              },
              "text": "obovoid flat"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ellipsoid-obovoid"
                  }
                ]
              },
              "text": "ellipsoid-obovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#oblong-obovoid"
                  }
                ]
              },
              "text": "oblong-obovoid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovoid-oblanceoloid"
                  }
                ]
              },
              "text": "obovoid-oblanceoloid"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obovoid-round"
                  }
                ]
              },
              "text": "obovoid-round"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#trigonous-obovoid"
                  }
                ]
              },
              "text": "trigonous-obovoid"
            }
          ],
          "text": "obovoid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#diamond"
              }
            ]
          },
          "text": "diamond"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#round"
              }
            ]
          },
          "text": "round"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bulging"
              }
            ]
          },
          "text": "bulging"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tattered"
              }
            ]
          },
          "text": "tattered"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fid"
              }
            ]
          },
          "text": "fid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#channeled"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#triangular-channeled"
                  }
                ]
              },
              "text": "triangular channeled"
            }
          ],
          "text": "channeled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#notched",
                "definition": "A concave quality inhering in a bearer by virtue of the bearer's shape being a V-shaped cut"
              }
            ]
          },
          "text": "notched"
        }
      ],
      "text": "shape"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#density"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dense"
              }
            ]
          },
          "text": "dense"
        }
      ],
      "text": "density"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture",
            "definition": "A morphologic quality inhering in a bearer by virtue of the bearer's relative size, organization and distribution of its surface elements or the representation or invention of the appearance of its surface; visual and tactile surface characteristics"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rough",
                "definition": "A texture quality inhering in a bearer by virtue of the bearer's irregular surface"
              }
            ]
          },
          "text": "rough"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hard",
                "definition": "A hardness quality of being rigid and resistant to pressure"
              }
            ]
          },
          "text": "hard"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#coriaceous",
                "definition": "with a leathery texture"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subcoriaceous"
                  }
                ]
              },
              "text": "subcoriaceous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#thick-coriaceous"
                  }
                ]
              },
              "text": "thick-coriaceous"
            }
          ],
          "text": "coriaceous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cartilaginous",
                "definition": "A composition quality inhering in an bearer by virtue of the bearer's cartilage quantities"
              }
            ]
          },
          "text": "cartilaginous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#membranous",
                "definition": "thin, soft, flexible, and more or less translucent, like a membrane"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline",
                    "definition": "thin, membranous and translucent or transparent"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gold-hyaline"
                      }
                    ]
                  },
                  "text": "gold-hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#greenish-hyaline"
                      }
                    ]
                  },
                  "text": "greenish hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    }
                  ],
                  "text": "hyaline brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#whitish-hyaline"
                      }
                    ]
                  },
                  "text": "whitish hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline-suffused"
                      }
                    ]
                  },
                  "text": "hyaline suffused"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-hyaline"
                      }
                    ]
                  },
                  "text": "white hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrow-hyaline"
                      }
                    ]
                  },
                  "text": "narrow-hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-hyaline"
                      }
                    ]
                  },
                  "text": "pale hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brownish-hyaline"
                      }
                    ]
                  },
                  "text": "brownish hyaline"
                }
              ],
              "text": "hyaline"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scarious",
                    "definition": "thin, dry, and membranous in texture, not green"
                  }
                ]
              },
              "text": "scarious"
            }
          ],
          "text": "membranous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#papery"
              }
            ]
          },
          "text": "papery"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#coarse",
                "definition": "A texture quality inhering in a bearer by virtue of the bearer's irregular surface"
              }
            ]
          },
          "text": "coarse"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fleshy",
                "definition": "A structural quality inhering in a bearer by virtue of the bearer resembling or containing flesh"
              }
            ]
          },
          "text": "fleshy"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fine-textured"
              }
            ]
          },
          "text": "fine-textured"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#friable",
                "definition": "Easily reduced to powder or easily fragmentable or crumbly"
              }
            ]
          },
          "text": "friable"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#corky"
              }
            ]
          },
          "text": "corky"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fibrillose",
                "definition": "bearing fibrils"
              }
            ]
          },
          "text": "fibrillose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#firm",
                "definition": "A hardness quality of giving significant resistance to pressure"
              }
            ]
          },
          "text": "firm"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dry"
              }
            ]
          },
          "text": "dry"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#coarse-textured"
              }
            ]
          },
          "text": "coarse-textured"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spongy",
                "definition": "A structural quality inhering in a bearer by virtue of the bearer's resembling a sponge in elasticity, absorbency, or porousness"
              }
            ]
          },
          "text": "spongy"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#herbacous"
              }
            ]
          },
          "text": "herbacous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#soft",
                "definition": "A hardness quality of giving little resistance to pressure"
              }
            ]
          },
          "text": "soft"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rigid",
                "definition": "stiff and inflexible"
              }
            ]
          },
          "text": "rigid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leathery"
              }
            ]
          },
          "text": "leathery"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fibrous",
                "definition": "bearing or resembling fibers"
              }
            ]
          },
          "text": "fibrous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#chartaceous",
                "definition": "with a papery texture, usually not green"
              }
            ]
          },
          "text": "chartaceous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#roughened"
              }
            ]
          },
          "text": "roughened"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flaccid",
                "definition": "limp or flabby; not rigid"
              }
            ]
          },
          "text": "flaccid"
        }
      ],
      "text": "texture"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-brightness",
            "definition": "Brightness is the relative lightness or darkness of a particular color, from black (no brightness) to white (full brightness). Brightness is also called Lightness in some contexts. See http://www.georeference.org/doc/colors_as_hue_saturation_and_brightness.htm to differentiate hue (color), saturation, and brightness."
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#low-brightness",
                "definition": "A color brightness which is relatively low"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark",
                    "definition": "A color brightness which is relatively low"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-redbrown"
                          }
                        ]
                      },
                      "text": "dark red brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-chestnut"
                          }
                        ]
                      },
                      "text": "dark chestnut"
                    }
                  ],
                  "text": "dark brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-purple"
                      }
                    ]
                  },
                  "text": "dark purple"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-red"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-redbrown"
                          }
                        ]
                      },
                      "text": "dark red brown"
                    }
                  ],
                  "text": "dark red"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-green"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-olive"
                          }
                        ]
                      },
                      "text": "dark olive"
                    }
                  ],
                  "text": "dark green"
                }
              ],
              "text": "dark"
            }
          ],
          "text": "low brightness"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#darkened"
              }
            ]
          },
          "text": "darkened"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#darker"
              }
            ]
          },
          "text": "darker"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#high-brightness",
                "definition": "A color brightness which is relatively high, has a relative high lightness"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bright",
                    "definition": "A color saturation which is of high purity"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bright-orange"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    }
                  ],
                  "text": "bright orange"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light",
                        "definition": "medium in saturation and high in lightness"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lighter-colored"
                          }
                        ]
                      },
                      "text": "lighter colored"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-brown"
                          }
                        ]
                      },
                      "children": [
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                              }
                            ]
                          },
                          "text": "tan hyaline"
                        },
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-redbrown"
                              }
                            ]
                          },
                          "text": "light red brown"
                        },
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-yellowbrown"
                              }
                            ]
                          },
                          "text": "light yellow brown"
                        }
                      ],
                      "text": "light brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-green"
                          }
                        ]
                      },
                      "children": [
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-olive"
                              }
                            ]
                          },
                          "text": "light olive"
                        }
                      ],
                      "text": "light green"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-colored"
                          }
                        ]
                      },
                      "text": "light colored"
                    }
                  ],
                  "text": "light"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bright-green"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-bright-green"
                          }
                        ]
                      },
                      "text": "pale bright green"
                    }
                  ],
                  "text": "bright green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bright-yellow"
                      }
                    ]
                  },
                  "text": "bright yellow"
                }
              ],
              "text": "bright"
            }
          ],
          "text": "high brightness"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lighter"
              }
            ]
          },
          "text": "lighter"
        }
      ],
      "text": "color brightness"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dehiscence"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#disintegrating"
              }
            ]
          },
          "text": "disintegrating"
        }
      ],
      "text": "dehiscence"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pubescence"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#setose",
                "definition": "A hairy quality inhering in a bearer by virtue of the bearer's being covered with setae"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ciliate-setose"
                  }
                ]
              },
              "text": "ciliate-setose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shortly-setose"
                  }
                ]
              },
              "text": "shortly setose"
            }
          ],
          "text": "setose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#smooth",
                "definition": "A texture quality inhering in a bearer by virtue of the bearer's processing a surface free of roughness or irregularities"
              }
            ]
          },
          "text": "smooth"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glabrescent",
                "definition": "becoming glabrous; almost glabrous"
              }
            ]
          },
          "text": "glabrescent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pubescent",
                "definition": "A maturity quality inhering in a bearer by virtue of the bearer's having arrived at the onset of puberty (the age at which sex glands become functional) but not yet fully mature"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-pubescent"
                  }
                ]
              },
              "text": "short-pubescent"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#appressed-pubescent"
                  }
                ]
              },
              "text": "appressed-pubescent"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-pubescent"
                  }
                ]
              },
              "text": "long-pubescent"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stellate-pubescent"
                  }
                ]
              },
              "text": "stellate-pubescent"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spreading-pubescent"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spreading-white-pubescent"
                      }
                    ]
                  },
                  "text": "spreading white pubescent"
                }
              ],
              "text": "spreading-pubescent"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrous-pubescent"
                  }
                ]
              },
              "text": "scabrous-pubescent"
            }
          ],
          "text": "pubescent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hispidulous",
                "definition": "A quality of being minutely hispid"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rough-hispidulous"
                  }
                ]
              },
              "text": "rough-hispidulous"
            }
          ],
          "text": "hispidulous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ciliate",
                "definition": "with marginal fringe of hairs"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-ciliate"
                  }
                ]
              },
              "text": "long-ciliate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ciliate-serulate"
                  }
                ]
              },
              "text": "ciliate serulate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ciliate-serrulate"
                  }
                ]
              },
              "text": "ciliate-serrulate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#setose-ciliate"
                  }
                ]
              },
              "text": "setose-ciliate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rough-ciliate"
                  }
                ]
              },
              "text": "rough-ciliate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#erose-ciliate"
                  }
                ]
              },
              "text": "erose-ciliate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrous-ciliate"
                  }
                ]
              },
              "text": "scabrous-ciliate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-ciliate"
                  }
                ]
              },
              "text": "short-ciliate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#serrulate-ciliate"
                  }
                ]
              },
              "text": "serrulate-ciliate"
            }
          ],
          "text": "ciliate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hispid",
                "definition": "A quality of being covered with stiff or rough hairs"
              }
            ]
          },
          "text": "hispid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hirsute",
                "definition": "pubescent with coarse, stiff hairs"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hirtellous"
                  }
                ]
              },
              "text": "hirtellous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stellate-hirsute"
                  }
                ]
              },
              "text": "stellate-hirsute"
            }
          ],
          "text": "hirsute"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#strigose",
                "definition": "bearing straight, stiff sharp, appressed hairs"
              }
            ]
          },
          "text": "strigose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bristly",
                "definition": "covered with bristles"
              }
            ]
          },
          "text": "bristly"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#puberulent",
                "definition": "minutely pubescent; with fine, short hairs"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#puberulent-proximally"
                  }
                ]
              },
              "text": "puberulent proximally"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrous-puberulent"
                  }
                ]
              },
              "text": "scabrous-puberulent"
            }
          ],
          "text": "puberulent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ladder-fibrillose"
              }
            ]
          },
          "text": "ladder fibrillose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#puberulous"
              }
            ]
          },
          "text": "puberulous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pilose",
                "definition": "bearing long, soft, straight hairs"
              }
            ]
          },
          "text": "pilose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#felty"
              }
            ]
          },
          "text": "felty"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glaucous",
                "definition": "covered with whitish or bluish waxy coating (bloom), as on the surface of a plum"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glaucous-green"
                  }
                ]
              },
              "text": "glaucous green"
            }
          ],
          "text": "glaucous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glabrous",
                "definition": "A pilosity quality inhering in a bearer by virtue of the bearer's lack of hairs or bristles"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subglabrous"
                  }
                ]
              },
              "text": "subglabrous"
            }
          ],
          "text": "glabrous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bulging"
              }
            ]
          },
          "text": "bulging"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hairy",
                "definition": "A pilosity quality inhering in a bearer by virtue of the bearer's being covered with hair or hairlike projections"
              }
            ]
          },
          "text": "hairy"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ciliolate"
              }
            ]
          },
          "text": "ciliolate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scaly",
                "definition": "A texture quality inhering in a bearer by virtue of the bearer's being covered or partially covered with scales"
              }
            ]
          },
          "text": "scaly"
        }
      ],
      "text": "pubescence"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reflectance"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#translucent",
                "definition": "A optical quality inhering in a bearer by virtue of the bearer's exhibiting low opacity"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline",
                    "definition": "thin, membranous and translucent or transparent"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gold-hyaline"
                      }
                    ]
                  },
                  "text": "gold-hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#greenish-hyaline"
                      }
                    ]
                  },
                  "text": "greenish hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    }
                  ],
                  "text": "hyaline brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#whitish-hyaline"
                      }
                    ]
                  },
                  "text": "whitish hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline-suffused"
                      }
                    ]
                  },
                  "text": "hyaline suffused"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-hyaline"
                      }
                    ]
                  },
                  "text": "white hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrow-hyaline"
                      }
                    ]
                  },
                  "text": "narrow-hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-hyaline"
                      }
                    ]
                  },
                  "text": "pale hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brownish-hyaline"
                      }
                    ]
                  },
                  "text": "brownish hyaline"
                }
              ],
              "text": "hyaline"
            }
          ],
          "text": "translucent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheen"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#satiny-luster"
                  }
                ]
              },
              "text": "satiny luster"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#metallic-sheen"
                  }
                ]
              },
              "text": "metallic sheen"
            }
          ],
          "text": "sheen"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#transparent",
                "definition": "A optical quality inhering in a bearer by virtue of the bearer's lacking opacity"
              }
            ]
          },
          "text": "transparent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#opaque",
                "definition": "A optical quality inhering in a bearer by virtue of the bearer's not being clear; not transmitting or reflecting light or radiant energy"
              }
            ]
          },
          "text": "opaque"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lustrous",
                "definition": "shiny or glossy"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shiny",
                    "definition": "A reflectivity quality inhering in a bearer by virtue of the bearer's reflecting lots of light"
                  }
                ]
              },
              "text": "shiny"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#satiny"
                  }
                ]
              },
              "text": "satiny"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glossy",
                    "definition": "A reflectivity quality inhering in a bearer by virtue of the bearer's reflecting lots of light"
                  }
                ]
              },
              "text": "glossy"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pearlescent"
                  }
                ]
              },
              "text": "pearlescent"
            }
          ],
          "text": "lustrous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dull"
              }
            ]
          },
          "text": "dull"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#metallic"
              }
            ]
          },
          "text": "metallic"
        }
      ],
      "text": "reflectance"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#quantity"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#many"
              }
            ]
          },
          "text": "many"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rare"
              }
            ]
          },
          "text": "rare"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#few"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fewer"
                  }
                ]
              },
              "text": "fewer"
            }
          ],
          "text": "few"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lacking"
              }
            ]
          },
          "text": "lacking"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#single"
              }
            ]
          },
          "text": "single"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#several"
              }
            ]
          },
          "text": "several"
        }
      ],
      "text": "quantity"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#development"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abortive",
                "definition": "not fully or properly developed; rudimentary"
              }
            ]
          },
          "text": "abortive"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#well-developed"
              }
            ]
          },
          "text": "well-developed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rudimentary",
                "definition": "imperfectly developed; vestigial"
              }
            ]
          },
          "text": "rudimentary"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#developed"
              }
            ]
          },
          "text": "developed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#poorly-developed"
              }
            ]
          },
          "text": "poorly developed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#modified"
              }
            ]
          },
          "text": "modified"
        }
      ],
      "text": "development"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#arrangement"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fascicled",
                "definition": "A structural quality inhering in a bearer by virtue of the bearer's forming a bundle of aligned anatomical fibers, as of muscle or nerve"
              }
            ]
          },
          "text": "fascicled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#congregated"
              }
            ]
          },
          "text": "congregated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ternate",
                "definition": "in threes, as a leaf which is divided into three leaflets"
              }
            ]
          },
          "text": "ternate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#confluent",
                "definition": "running together or blending of one part into another"
              }
            ]
          },
          "text": "confluent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#separated"
              }
            ]
          },
          "text": "separated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adjacent"
              }
            ]
          },
          "text": "adjacent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#clustered",
                "definition": "A spatial pattern inhering in a bearer by virtue of the bearer's being gathered or tending to gather into a mass or whole"
              }
            ]
          },
          "text": "clustered"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distant"
              }
            ]
          },
          "text": "distant"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#divergent"
              }
            ]
          },
          "text": "divergent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#aggregated",
                "definition": "A spatial pattern inhering in a bearer by virtue of the bearer's being gathered or tending to gather into a mass or whole"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#loosely-aggregated"
                  }
                ]
              },
              "text": "loosely aggregated"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#densely-aggregated"
                  }
                ]
              },
              "text": "densely aggregated"
            }
          ],
          "text": "aggregated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#alternate",
                "definition": "borne singly at each node, as leaves on a stem"
              }
            ]
          },
          "text": "alternate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distichous",
                "definition": "A spatial quality inhering in a bearer by virtue of the bearer's being arranged in two opposite rows (and hence in the same plane)"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subdistichous"
                  }
                ]
              },
              "text": "subdistichous"
            }
          ],
          "text": "distichous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distributed"
              }
            ]
          },
          "text": "distributed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#radiating"
              }
            ]
          },
          "text": "radiating"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#crowded"
              }
            ]
          },
          "text": "crowded"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#multiseriate"
              }
            ]
          },
          "text": "multiseriate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#interspersed"
              }
            ]
          },
          "text": "interspersed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#corrugate",
                "definition": "wrinkled or folded into alternating furrows and ridges"
              }
            ]
          },
          "text": "corrugate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fasciculate",
                "definition": "A structural quality inhering in a bearer by virtue of the bearer's forming a bundle of aligned anatomical fibers, as of muscle or nerve"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subfasciculate"
                  }
                ]
              },
              "text": "subfasciculate"
            }
          ],
          "text": "fasciculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#paniculate",
                "definition": "having flowers in panicles"
              }
            ]
          },
          "text": "paniculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#imbricate"
              }
            ]
          },
          "text": "imbricate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#parallel"
              }
            ]
          },
          "text": "parallel"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stellate"
              }
            ]
          },
          "text": "stellate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#disposed"
              }
            ]
          },
          "text": "disposed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ranked"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#2-ranked"
                  }
                ]
              },
              "text": "2-ranked"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#3-ranked"
                  }
                ]
              },
              "text": "3-ranked"
            }
          ],
          "text": "ranked"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apart"
              }
            ]
          },
          "text": "apart"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#separate"
              }
            ]
          },
          "text": "separate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cluster"
              }
            ]
          },
          "text": "cluster"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#approximate"
              }
            ]
          },
          "text": "approximate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rosette"
              }
            ]
          },
          "text": "rosette"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spaced"
              }
            ]
          },
          "text": "spaced"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#solitary",
                "definition": "occurring singly and not borne in a cluster or group"
              }
            ]
          },
          "text": "solitary"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scattered",
                "definition": "A spatial pattern inhering in a bearer by virtue of the bearer's being spread out or scattered about or divided up"
              }
            ]
          },
          "text": "scattered"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nonoverlapping"
              }
            ]
          },
          "text": "nonoverlapping"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#racemose"
              }
            ]
          },
          "text": "racemose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fastigiate"
              }
            ]
          },
          "text": "fastigiate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#overlapping",
                "definition": "Overlap with quality inhering in a bearer by virtue of the bearer's being positioned over another entity so as to cover some portion of it"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#loosely-overlapping"
                  }
                ]
              },
              "text": "loosely overlapping"
            }
          ],
          "text": "overlapping"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#corymbose",
                "definition": "having flowers in corymbs. The term is sometimes used in the same sense as corymbiform"
              }
            ]
          },
          "text": "corymbose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#contiguous",
                "definition": "adjoining; touching"
              }
            ]
          },
          "text": "contiguous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#uncrowded"
              }
            ]
          },
          "text": "uncrowded"
        }
      ],
      "text": "arrangement"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#duration",
            "definition": "A process quality inhering in a bearer by virtue of the bearer's magnitude of the temporal extent between the starting and ending point"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#marcescent"
              }
            ]
          },
          "text": "marcescent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#evergreen",
                "definition": "having green leaves through the winter; not deciduous"
              }
            ]
          },
          "text": "evergreen"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#persistent",
                "definition": "remaining attached after similar parts are normally dropped, after the function has been completed"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-persistent"
                  }
                ]
              },
              "text": "short-persistent"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-persistent"
                  }
                ]
              },
              "text": "long-persistent"
            }
          ],
          "text": "persistent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#overwintering"
              }
            ]
          },
          "text": "overwintering"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#annual",
                "definition": "a plant which germinates from seed, flowers, sets seed, and dies in the same year"
              }
            ]
          },
          "text": "annual"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perennial"
              }
            ]
          },
          "text": "perennial"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#deciduous",
                "definition": "falling off as leaves from a tree; not evergreen; not presistent"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#early-deciduous"
                  }
                ]
              },
              "text": "early deciduous"
            }
          ],
          "text": "deciduous"
        }
      ],
      "text": "duration"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-quality"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-shape-in-transverse-section"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-perigynium-in-transverse-section"
                  }
                ]
              },
              "text": "shape of perigynium in transverse section"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-achene-in-transverse-section"
                  }
                ]
              },
              "text": "shape of achene in transverse section"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-stem-in-transverse-section"
                  }
                ]
              },
              "text": "shape of stem in transverse section"
            }
          ],
          "text": "perceived shape in transverse section"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-size"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-length"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-filament"
                      }
                    ]
                  },
                  "text": "length of filament"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-tooth-of-perigynium-beak"
                      }
                    ]
                  },
                  "text": "length of tooth of perigynium beak"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-inflorescence-unit"
                      }
                    ]
                  },
                  "text": "length of inflorescence unit"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-anther"
                      }
                    ]
                  },
                  "text": "length of anther"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-leaf-blade"
                      }
                    ]
                  },
                  "text": "length of leaf blade"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-bract-sheath"
                      }
                    ]
                  },
                  "text": "length of bract sheath"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-stem"
                      }
                    ]
                  },
                  "text": "length of stem"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-terminal-portion-of-inflorescence"
                      }
                    ]
                  },
                  "text": "length of terminal portion of inflorescence"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-inflorescence"
                      }
                    ]
                  },
                  "text": "length of inflorescence"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-perigynium-body"
                      }
                    ]
                  },
                  "text": "length of perigynium body"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-staminate-scale"
                      }
                    ]
                  },
                  "text": "length of staminate scale"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-perigynium-stipe"
                      }
                    ]
                  },
                  "text": "length of perigynium stipe"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-pistillate-scale"
                      }
                    ]
                  },
                  "text": "length of pistillate scale"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-achene"
                      }
                    ]
                  },
                  "text": "length of achene"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-rhizome-internode"
                      }
                    ]
                  },
                  "text": "length of rhizome internode"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-perigynium-beak"
                      }
                    ]
                  },
                  "text": "length of perigynium beak"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-ligule"
                      }
                    ]
                  },
                  "text": "length of ligule"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-sheath"
                      }
                    ]
                  },
                  "text": "length of sheath"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-bract-blade"
                      }
                    ]
                  },
                  "text": "length of bract blade"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-proximal-internode-of-inflorescence-unit"
                      }
                    ]
                  },
                  "text": "length of proximal internode of inflorescence unit"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-perigynium"
                      }
                    ]
                  },
                  "text": "length of perigynium"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-distal-internode-of-inflorescence"
                      }
                    ]
                  },
                  "text": "length of distal internode of inflorescence"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-proximal-peduncle-of-inflorescence-unit"
                      }
                    ]
                  },
                  "text": "length of proximal peduncle of inflorescence unit"
                }
              ],
              "text": "perceived length"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-width"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-inflorescence-unit"
                      }
                    ]
                  },
                  "text": "width of inflorescence unit"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-staminate-scale"
                      }
                    ]
                  },
                  "text": "width of staminate scale"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-terminal-portion-of-inflorescence"
                      }
                    ]
                  },
                  "text": "width of terminal portion of inflorescence"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-leaf-blade"
                      }
                    ]
                  },
                  "text": "width of leaf blade"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-stem"
                      }
                    ]
                  },
                  "text": "width of stem"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-perigynium"
                      }
                    ]
                  },
                  "text": "width of perigynium"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-bract-blade"
                      }
                    ]
                  },
                  "text": "width of bract blade"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-pistillate-scale"
                      }
                    ]
                  },
                  "text": "width of pistillate scale"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width-of-achene"
                      }
                    ]
                  },
                  "text": "width of achene"
                }
              ],
              "text": "perceived width"
            }
          ],
          "text": "perceived size"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-quantity"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-inflorescence-units"
                  }
                ]
              },
              "text": "number of inflorescence units"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-leaf-per-stem"
                  }
                ]
              },
              "text": "number of leaf per stem"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-staminate-flowers"
                  }
                ]
              },
              "text": "number of staminate flowers"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-nerves-in-pistillate-scale"
                  }
                ]
              },
              "text": "number of nerves in pistillate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-nerves-in-staminate-scale"
                  }
                ]
              },
              "text": "number of nerves in staminate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-veins-in-pistillate-scale"
                  }
                ]
              },
              "text": "number of veins in pistillate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-perigynia-per-inforescence-unit"
                  }
                ]
              },
              "text": "number of perigynia per inforescence unit"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-stigmas"
                  }
                ]
              },
              "text": "number of stigmas"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#number-of-veins-in-staminate-scale"
                  }
                ]
              },
              "text": "number of veins in staminate scale"
            }
          ],
          "text": "perceived quantity"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-shape"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-apex-of-bract-sheath-inner-band"
                  }
                ]
              },
              "text": "shape of apex of bract sheath inner band"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-orifice-of-perigynium-beak"
                  }
                ]
              },
              "text": "shape of orifice of perigynium beak"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-ligule"
                  }
                ]
              },
              "text": "shape of ligule"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-staminate-scale"
                  }
                ]
              },
              "text": "shape of staminate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-apex-of-achene"
                  }
                ]
              },
              "text": "shape of apex of achene"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-inflorescence-unit"
                  }
                ]
              },
              "text": "shape of inflorescence unit"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-pistillate-scale"
                  }
                ]
              },
              "text": "shape of pistillate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-apex-of-sheath-inner-band"
                  }
                ]
              },
              "text": "shape of apex of sheath inner band"
            }
          ],
          "text": "perceived shape"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-relative-position"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-position-of-pistillate-scale-to-perigynium"
                  }
                ]
              },
              "text": "relative position of pistillate scale to perigynium"
            }
          ],
          "text": "perceived relative position"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-inflation"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inflation-of-perigynium"
                  }
                ]
              },
              "text": "inflation of perigynium"
            }
          ],
          "text": "perceived inflation"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-shape-in-longitudinal-section"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shape-of-achene-in-longitudinal-section"
                  }
                ]
              },
              "text": "shape of achene in longitudinal section"
            }
          ],
          "text": "perceived shape in longitudinal section"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-architecture"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#architecture-of-vein"
                  }
                ]
              },
              "text": "architecture of vein"
            }
          ],
          "text": "perceived architecture"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-pubescence"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pubescence-of-margin-of-bract-blade"
                  }
                ]
              },
              "text": "pubescence of margin of bract blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pubescence-of-stem"
                  }
                ]
              },
              "text": "pubescence of stem"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pubescence-of-margin-of-leaf-blade"
                  }
                ]
              },
              "text": "pubescence of margin of leaf blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pubescence-of-margin-of-staminate-scale"
                  }
                ]
              },
              "text": "pubescence of margin of staminate scale"
            }
          ],
          "text": "perceived pubescence"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-orientation"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orientation-of-perigynium"
                  }
                ]
              },
              "text": "orientation of perigynium"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orientation-of-perigynium-beak"
                  }
                ]
              },
              "text": "orientation of perigynium beak"
            }
          ],
          "text": "perceived orientation"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-texture"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-pistillate-scale"
                  }
                ]
              },
              "text": "texture of pistillate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-proximal-peduncle-of-inflorescence-unit"
                  }
                ]
              },
              "text": "texture of proximal peduncle of inflorescence unit"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-adaxial-leaf-blade"
                  }
                ]
              },
              "text": "texture of adaxial leaf blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-cataphyll"
                  }
                ]
              },
              "text": "texture of cataphyll"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-margin-of-leaf-blade"
                  }
                ]
              },
              "text": "texture of margin of leaf blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-abaxial-leaf-blade"
                  }
                ]
              },
              "text": "texture of abaxial leaf blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-perigynium-beak"
                  }
                ]
              },
              "text": "texture of perigynium beak"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-distal-internode-of-inflorescence"
                  }
                ]
              },
              "text": "texture of distal internode of inflorescence"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-abaxial-bract-blade"
                  }
                ]
              },
              "text": "texture of abaxial bract blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-perigynium"
                  }
                ]
              },
              "text": "texture of perigynium"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-margin-of-perigynium-beak"
                  }
                ]
              },
              "text": "texture of margin of perigynium beak"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-margin-of-staminate-scale"
                  }
                ]
              },
              "text": "texture of margin of staminate scale"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-margin-of-bract-blade"
                  }
                ]
              },
              "text": "texture of margin of bract blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-stem"
                  }
                ]
              },
              "text": "texture of stem"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-adaxial-bract-blade"
                  }
                ]
              },
              "text": "texture of adaxial bract blade"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#texture-of-staminate-scale"
                  }
                ]
              },
              "text": "texture of staminate scale"
            }
          ],
          "text": "perceived texture"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-duration"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#duration-of-style"
                  }
                ]
              },
              "text": "duration of style"
            }
          ],
          "text": "perceived duration"
        }
      ],
      "text": "perceived quality"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#life-cycle"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flowering"
              }
            ]
          },
          "text": "flowering"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#anthesis"
              }
            ]
          },
          "text": "anthesis"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#young"
              }
            ]
          },
          "text": "young"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#immature"
              }
            ]
          },
          "text": "immature"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#marescent",
                "definition": "withering but persistent, as the sepals and petals in some flowers or the leaves at the base of some plants"
              }
            ]
          },
          "text": "marescent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#withering"
              }
            ]
          },
          "text": "withering"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dead",
                "definition": "A viability quality inhering in a bearer by virtue of the cessation of the bearer's life"
              }
            ]
          },
          "text": "dead"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#youngest"
              }
            ]
          },
          "text": "youngest"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#falling"
              }
            ]
          },
          "text": "falling"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fallen"
              }
            ]
          },
          "text": "fallen"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#maturing"
              }
            ]
          },
          "text": "maturing"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#older"
              }
            ]
          },
          "text": "older"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#overwintered"
              }
            ]
          },
          "text": "overwintered"
        }
      ],
      "text": "life cycle"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#coating"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glaucescent"
              }
            ]
          },
          "text": "glaucescent"
        }
      ],
      "text": "coating"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relief"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#punctate",
                "definition": "A pattern inhering in a surface by virtue of the bearer's being marked by the presence of dots, punctures, points or pits"
              }
            ]
          },
          "text": "punctate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrid"
              }
            ]
          },
          "text": "scabrid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#papillate"
              }
            ]
          },
          "text": "papillate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrous",
                "definition": "rough to the touch, due to the structure of the epidermal cells, or to the presence of short stiff hairs"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scaberulous"
                  }
                ]
              },
              "text": "scaberulous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#retrorse-scabrous"
                  }
                ]
              },
              "text": "retrorse-scabrous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrous-pubescent"
                  }
                ]
              },
              "text": "scabrous-pubescent"
            }
          ],
          "text": "scabrous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#puncticulate",
                "definition": "minutely punctate"
              }
            ]
          },
          "text": "puncticulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#puckered"
              }
            ]
          },
          "text": "puckered"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pustulate",
                "definition": "with smaller blisters or pustules, often at the base of a hair"
              }
            ]
          },
          "text": "pustulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#corrugated",
                "definition": "wrinkled or folded into alternating furrows and ridges"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cross-corrugated"
                  }
                ]
              },
              "text": "cross corrugated"
            }
          ],
          "text": "corrugated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#unwrinkled"
              }
            ]
          },
          "text": "unwrinkled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#striate",
                "definition": "marked with fine, usually parallel lines or grooves"
              }
            ]
          },
          "text": "striate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#canaliculate",
                "definition": "with longitudinal channels or grooves"
              }
            ]
          },
          "text": "canaliculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#papillose",
                "definition": "having minute papillae"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subpapillose"
                  }
                ]
              },
              "text": "subpapillose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-papillose"
                  }
                ]
              },
              "text": "red-papillose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#puberulent-papillose"
                  }
                ]
              },
              "text": "puberulent-papillose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#granular-papillose"
                  }
                ]
              },
              "text": "granular-papillose"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#papillose-abaxially"
                  }
                ]
              },
              "text": "papillose abaxially"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-papillose"
                  }
                ]
              },
              "text": "short-papillose"
            }
          ],
          "text": "papillose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#grooved",
                "definition": "Texture quality inhering in a bearer by virtue of the bearer's being marked with one or more channels"
              }
            ]
          },
          "text": "grooved"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#warty"
              }
            ]
          },
          "text": "warty"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#erose",
                "definition": "A surface feature shape quality inhering in a bearer by virtue of the bearer's having an irregularly toothed edge"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#truncate-erose"
                  }
                ]
              },
              "text": "truncate-erose"
            }
          ],
          "text": "erose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabriduous"
              }
            ]
          },
          "text": "scabriduous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rugose",
                "definition": "A surface feature shape quality inhering in a bearer by virtue of the bearer's having many wrinkles or creases on the surface"
              }
            ]
          },
          "text": "rugose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wrinkled"
              }
            ]
          },
          "text": "wrinkled"
        }
      ],
      "text": "relief"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#course"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flexuous",
                "definition": "with curves or bends; sinuous; somewhat zigzagged"
              }
            ]
          },
          "text": "flexuous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bent",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's having one or more angle(s) in its length"
              }
            ]
          },
          "text": "bent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#curved",
                "definition": "A curvature quality inhering in a bearer by virtue of the bearer's having or being marked by a curve or smoothly rounded bend"
              }
            ]
          },
          "text": "curved"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#straight",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being free of curves, bends, or angles"
              }
            ]
          },
          "text": "straight"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#strict",
                "definition": "very straight and upright, not at all spreading"
              }
            ]
          },
          "text": "strict"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#defexed"
              }
            ]
          },
          "text": "defexed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#zigzag"
              }
            ]
          },
          "text": "zigzag"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#arcuate",
                "definition": "curved into an arch like a bow"
              }
            ]
          },
          "text": "arcuate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sinuous",
                "definition": "A curvature quality inhering in a bearer by virtue of the bearer's having many curves and turns"
              }
            ]
          },
          "text": "sinuous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#excurved",
                "definition": "curving outwards away from the axis"
              }
            ]
          },
          "text": "excurved"
        }
      ],
      "text": "course"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relational-quality",
            "definition": "types of quality that require an additional entity in order to exist"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relational-size"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#equal-to"
                  }
                ]
              },
              "text": "equal to"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subequal-to"
                  }
                ]
              },
              "text": "subequal to"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longer-than"
                  }
                ]
              },
              "text": "longer than"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wider-than"
                  }
                ]
              },
              "text": "wider than"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrower-than"
                  }
                ]
              },
              "text": "narrower than"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shorter-than"
                  }
                ]
              },
              "text": "shorter than"
            }
          ],
          "text": "relational size"
        }
      ],
      "text": "relational quality"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#direction",
            "definition": "The orientation towards which a structure lies or points (not botanical)"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#horizontally",
                "definition": "A positional quality inhering in a bearer by virtue of the bearer's being in the plane of the horizon"
              }
            ]
          },
          "text": "horizontally"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#back",
                "definition": "The adaxial surface of a structure"
              }
            ]
          },
          "text": "back"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#front",
                "definition": "A directional quality inhering in a bearer by virtue of the bearer's direction that is similar to the direction of an object to the south when it faces north"
              }
            ]
          },
          "text": "front"
        }
      ],
      "text": "direction"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#architecture"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glumelike"
              }
            ]
          },
          "text": "glumelike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bladeless"
              }
            ]
          },
          "text": "bladeless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#winged"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#thin-winged"
                  }
                ]
              },
              "text": "thin-winged"
            }
          ],
          "text": "winged"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#unwinged"
              }
            ]
          },
          "text": "unwinged"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gynecadrous"
              }
            ]
          },
          "text": "gynecadrous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#unbranched",
                "definition": "A branchiness quality inhering in a bearer by virtue of the bearer's lacking branches"
              }
            ]
          },
          "text": "unbranched"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pedunculate"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-pendunculate"
                  }
                ]
              },
              "text": "short pendunculate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-pedunclulate"
                  }
                ]
              },
              "text": "short pedunclulate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-pedunculate"
                  }
                ]
              },
              "text": "short pedunculate"
            }
          ],
          "text": "pedunculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glumaceous",
                "definition": "resembling a glume; bearing glumes"
              }
            ]
          },
          "text": "glumaceous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#splitting"
              }
            ]
          },
          "text": "splitting"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flowerless"
              }
            ]
          },
          "text": "flowerless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leafless"
              }
            ]
          },
          "text": "leafless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bladed"
              }
            ]
          },
          "text": "bladed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-pendunculate"
              }
            ]
          },
          "text": "long-pendunculate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pistillate",
                "definition": "bearing a pistil or pistils, but lacking stamens"
              }
            ]
          },
          "text": "pistillate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#septate",
                "definition": "divided by one or more partitions (septa)"
              }
            ]
          },
          "text": "septate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cuplike",
                "definition": "A concave 3-D shape quality inhering in a bearer by virtue of the bearer's shape form resembling the shape of a cup"
              }
            ]
          },
          "text": "cuplike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leaflike"
              }
            ]
          },
          "text": "leaflike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#plicate",
                "definition": "plaited or folded, as a folding fan"
              }
            ]
          },
          "text": "plicate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bidendate",
                "definition": "with two teeth"
              }
            ]
          },
          "text": "bidendate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bidentulate"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#minutely-bidentulate"
                  }
                ]
              },
              "text": "minutely bidentulate"
            }
          ],
          "text": "bidentulate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cellular"
              }
            ]
          },
          "text": "cellular"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#phyllopodic",
                "definition": "with the lowest leaves well developed, not reduced to scales"
              }
            ]
          },
          "text": "phyllopodic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#beaded"
              }
            ]
          },
          "text": "beaded"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#toothless"
              }
            ]
          },
          "text": "toothless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#carpellate"
              }
            ]
          },
          "text": "carpellate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#folded",
                "definition": "A surface feature shape quality inhering in a bearer by virtue of the bearer's one part being layered over another connected part"
              }
            ]
          },
          "text": "folded"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rhizomatous",
                "definition": "rhizome-like; with rhizomes"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stout-rhizomatous"
                  }
                ]
              },
              "text": "stout-rhizomatous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-rhizomatous"
                  }
                ]
              },
              "text": "short-rhizomatous"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-rhizomatous"
                  }
                ]
              },
              "text": "long-rhizomatous"
            }
          ],
          "text": "rhizomatous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lobed",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being partly divided into a determinate number of regions"
              }
            ]
          },
          "text": "lobed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#collapsing"
              }
            ]
          },
          "text": "collapsing"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#aphyllopodic"
              }
            ]
          },
          "text": "aphyllopodic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#beakless"
              }
            ]
          },
          "text": "beakless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#veinless"
              }
            ]
          },
          "text": "veinless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#peduncled",
                "definition": "with a peduncle"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-peduncled"
                  }
                ]
              },
              "text": "short-peduncled"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-peduncled"
                  }
                ]
              },
              "text": "long peduncled"
            }
          ],
          "text": "peduncled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nerved"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#1-nerved"
                  }
                ]
              },
              "text": "1-nerved"
            }
          ],
          "text": "nerved"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#coiled",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being curled or wound (especially in concentric rings or spirals)"
              }
            ]
          },
          "text": "coiled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#contorted",
                "definition": "twisted or bent"
              }
            ]
          },
          "text": "contorted"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#twisted",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being twisted or turned"
              }
            ]
          },
          "text": "twisted"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stalked"
              }
            ]
          },
          "text": "stalked"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lax",
                "definition": "loose; with parts open and spreading, not compact"
              }
            ]
          },
          "text": "lax"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#capitate",
                "definition": "head-like, or in a head-shaped cluster, as the flower in many plant groups but especially those in the dense capitate inflorescences of the compositae (asteraceae)"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subcapitate"
                  }
                ]
              },
              "text": "subcapitate"
            }
          ],
          "text": "capitate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#costate",
                "definition": "ribbed"
              }
            ]
          },
          "text": "costate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bulbous"
              }
            ]
          },
          "text": "bulbous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spicate",
                "definition": "arranged in a spike"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#multispicate"
                  }
                ]
              },
              "text": "multispicate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#unispicate"
                  }
                ]
              },
              "text": "unispicate"
            }
          ],
          "text": "spicate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#irregular",
                "definition": "bilaterally symmetrical; said of a flower in which all parts are not similar in size and arrangement on the receptacle"
              }
            ]
          },
          "text": "irregular"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#condensed",
                "definition": "A structural quality inhering in a bearer by virtue of the bearer's being thicker or more closely packed together; pressed tightly together"
              }
            ]
          },
          "text": "condensed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cleft",
                "definition": "A concave quality inhering in a bearer by virtue of the bearer's resembling a groove or fissure"
              }
            ]
          },
          "text": "cleft"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#clasping",
                "definition": "wholly or partly surrounding the stem"
              }
            ]
          },
          "text": "clasping"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inflation"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inflated",
                    "definition": "A structure quality inhering in a bearer by virtue of the bearer's being filled with air"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subinflated"
                      }
                    ]
                  },
                  "text": "subinflated"
                }
              ],
              "text": "inflated"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tight"
                  }
                ]
              },
              "text": "tight"
            }
          ],
          "text": "inflation"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stipitate",
                "definition": "borne on a stripe or stalk"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-stipitate"
                  }
                ]
              },
              "text": "short-stipitate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#estipitate",
                    "definition": "without a stripe"
                  }
                ]
              },
              "text": "estipitate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#substipitate"
                  }
                ]
              },
              "text": "substipitate"
            }
          ],
          "text": "stipitate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tipped"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#black-tipped"
                  }
                ]
              },
              "text": "black tipped"
            }
          ],
          "text": "tipped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#open"
              }
            ]
          },
          "text": "open"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hypostomic"
              }
            ]
          },
          "text": "hypostomic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sessile",
                "definition": "attached directly, without a supporting stalk, as a leaf without a petiole"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subsessile"
                  }
                ]
              },
              "text": "subsessile"
            }
          ],
          "text": "sessile"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#septate-noduloae"
              }
            ]
          },
          "text": "septate noduloae"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#serrulate-winged"
              }
            ]
          },
          "text": "serrulate-winged"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nipplelike"
              }
            ]
          },
          "text": "nipplelike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#interrupted",
                "definition": "not continuous"
              }
            ]
          },
          "text": "interrupted"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#furrowed",
                "definition": "A grooved texture quality inhering in a bearer by virtue of the bearer's being marked by two or more parallel channels"
              }
            ]
          },
          "text": "furrowed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compound",
                "definition": "with two or more like parts in one organ"
              }
            ]
          },
          "text": "compound"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stoloniferous",
                "definition": "bearing stolons"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-stoloniferous"
                  }
                ]
              },
              "text": "long-stoloniferous"
            }
          ],
          "text": "stoloniferous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#aggregate"
              }
            ]
          },
          "text": "aggregate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wiry"
              }
            ]
          },
          "text": "wiry"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pinnate"
              }
            ]
          },
          "text": "pinnate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bristlelike"
              }
            ]
          },
          "text": "bristlelike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#compact"
              }
            ]
          },
          "text": "compact"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#undivided",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being unseparated into parts or divisions"
              }
            ]
          },
          "text": "undivided"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheathless"
              }
            ]
          },
          "text": "sheathless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flowered"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#many-flowered"
                  }
                ]
              },
              "text": "many-flowered"
            }
          ],
          "text": "flowered"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#winglike"
              }
            ]
          },
          "text": "winglike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spiked"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fewer-spiked"
                  }
                ]
              },
              "text": "fewer-spiked"
            }
          ],
          "text": "spiked"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wingless"
              }
            ]
          },
          "text": "wingless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#margined"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-margined"
                  }
                ]
              },
              "text": "white-margined"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrous-margined"
                  }
                ]
              },
              "text": "scabrous-margined"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#smooth-margined"
                  }
                ]
              },
              "text": "smooth-margined"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ciliate-margined"
                  }
                ]
              },
              "text": "ciliate margined"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#serrulate-margined"
                  }
                ]
              },
              "text": "serrulate-margined"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#thin-margined"
                  }
                ]
              },
              "text": "thin-margined"
            }
          ],
          "text": "margined"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#keeled",
                "definition": "ridged, like the keel of a boat"
              }
            ]
          },
          "text": "keeled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hollow"
              }
            ]
          },
          "text": "hollow"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#branched",
                "definition": "A branchiness quality inhering in a bearer by virtue of the bearer's having branches"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bifid",
                    "definition": "A branched quality inhering in a bearer by virtue of the bearer's being divided into two branches"
                  }
                ]
              },
              "text": "bifid"
            }
          ],
          "text": "branched"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#epistomic"
              }
            ]
          },
          "text": "epistomic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#loose",
                "definition": "A pattern quality inhering in a bearer by virtue of the bearer's not being compact or dense in arrangement"
              }
            ]
          },
          "text": "loose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hairlike"
              }
            ]
          },
          "text": "hairlike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#solid",
                "definition": "A physical quality inhering in a bearer by virtue of the bearer's parts having the arrangement which exhibits characteristics of solids"
              }
            ]
          },
          "text": "solid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ribbed"
              }
            ]
          },
          "text": "ribbed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bractless"
              }
            ]
          },
          "text": "bractless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#congested",
                "definition": "A structural quality inhering in a bearer with some kind of aperture or opening that is blocked or clogged"
              }
            ]
          },
          "text": "congested"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bractlike"
              }
            ]
          },
          "text": "bractlike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#non-sheathed"
              }
            ]
          },
          "text": "non-sheathed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spathelike"
              }
            ]
          },
          "text": "spathelike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#veined"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#green-veined"
                  }
                ]
              },
              "text": "green-veined"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#several-veined"
                  }
                ]
              },
              "text": "several-veined"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#few-veined"
                  }
                ]
              },
              "text": "few-veined"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reticulate",
                    "definition": "in the form of a network"
                  }
                ]
              },
              "text": "reticulate"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#many-veined"
                  }
                ]
              },
              "text": "many-veined"
            }
          ],
          "text": "veined"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#awned",
                "definition": "possessing an awn"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#smooth-awned"
                  }
                ]
              },
              "text": "smooth-awned"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scabrous-awned"
                  }
                ]
              },
              "text": "scabrous-awned"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#acuminate-awned"
                  }
                ]
              },
              "text": "acuminate-awned"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-awned"
                  }
                ]
              },
              "text": "long-awned"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-awned"
                  }
                ]
              },
              "text": "short-awned"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rough-awned"
                  }
                ]
              },
              "text": "rough-awned"
            }
          ],
          "text": "awned"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sheathed",
                "definition": "forming a sheath, as the leaf of the base of a grass forms a sheath as it surrounds a stem. Different from ensheathed, which is used as ... ensheathed by ..."
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#aphyllous-sheathed"
                  }
                ]
              },
              "text": "aphyllous-sheathed"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-sheathed"
                  }
                ]
              },
              "text": "short-sheathed"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-sheathed"
                  }
                ]
              },
              "text": "long-sheathed"
            }
          ],
          "text": "sheathed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#decompound",
                "definition": "More than once-compound, the leaflets again divided"
              }
            ]
          },
          "text": "decompound"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#awnless"
              }
            ]
          },
          "text": "awnless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#staminate",
                "definition": "bearing stamens but not pistils, as a male flower which does not produce fruit or seeds"
              }
            ]
          },
          "text": "staminate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#channelled"
              }
            ]
          },
          "text": "channelled"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#leafy"
              }
            ]
          },
          "text": "leafy"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pseudoumbel"
              }
            ]
          },
          "text": "pseudoumbel"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nerveless"
              }
            ]
          },
          "text": "nerveless"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#threadlike"
              }
            ]
          },
          "text": "threadlike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#simple",
                "definition": "A complexity quality in a bearer by virtue of the bearer's having few parts or being complicated or compound"
              }
            ]
          },
          "text": "simple"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#enveloped"
              }
            ]
          },
          "text": "enveloped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#prophyllate",
                "definition": "with prophyll"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cladoprophyllate"
                  }
                ]
              },
              "text": "cladoprophyllate"
            }
          ],
          "text": "prophyllate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#amphistomic"
              }
            ]
          },
          "text": "amphistomic"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pithlike"
              }
            ]
          },
          "text": "pithlike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#empty"
              }
            ]
          },
          "text": "empty"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spinulose",
                "definition": "bearing spinules"
              }
            ]
          },
          "text": "spinulose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#foliaceous",
                "definition": "leaf-like in color and texture; bearing leaves; of or pertaining to leaves"
              }
            ]
          },
          "text": "foliaceous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#edged"
              }
            ]
          },
          "text": "edged"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blade-bearing"
              }
            ]
          },
          "text": "blade-bearing"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#needlelike",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being linear, very narrow, tapering to a very fine point from a narrow base"
              }
            ]
          },
          "text": "needlelike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#beaked",
                "definition": "bearing a beak"
              }
            ]
          },
          "text": "beaked"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#scalelike"
              }
            ]
          },
          "text": "scalelike"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#headlike"
              }
            ]
          },
          "text": "headlike"
        }
      ],
      "text": "architecture"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-saturation",
            "definition": "A scalar chromatic property that is the degree of purity of perceived light.\nSaturation is the intensity of a hue from gray tone (no saturation) to pure, vivid color (high saturation). See http://www.georeference.org/doc/colors_as_hue_saturation_and_brightness.htm to differentiate hue (color), saturation, and brightness."
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#medium-saturated"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light",
                    "definition": "medium in saturation and high in lightness"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lighter-colored"
                      }
                    ]
                  },
                  "text": "lighter colored"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-redbrown"
                          }
                        ]
                      },
                      "text": "light red brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-yellowbrown"
                          }
                        ]
                      },
                      "text": "light yellow brown"
                    }
                  ],
                  "text": "light brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-green"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-olive"
                          }
                        ]
                      },
                      "text": "light olive"
                    }
                  ],
                  "text": "light green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-colored"
                      }
                    ]
                  },
                  "text": "light colored"
                }
              ],
              "text": "light"
            }
          ],
          "text": "medium saturated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#paler"
              }
            ]
          },
          "text": "paler"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#saturated"
              }
            ]
          },
          "text": "saturated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#desaturated"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale",
                    "definition": "having color of reduced saturation"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-gold"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-gold-tinged"
                          }
                        ]
                      },
                      "text": "pale gold tinged"
                    }
                  ],
                  "text": "pale gold"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-yellow"
                      }
                    ]
                  },
                  "text": "pale yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-silvery-brown"
                          }
                        ]
                      },
                      "text": "pale silvery brown"
                    }
                  ],
                  "text": "pale brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-hyaline"
                      }
                    ]
                  },
                  "text": "pale hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-ferruginous"
                      }
                    ]
                  },
                  "text": "pale ferruginous"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-red-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-red-brown-tinged"
                          }
                        ]
                      },
                      "text": "pale red brown tinged"
                    }
                  ],
                  "text": "pale red brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-green"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-bright-green"
                          }
                        ]
                      },
                      "text": "pale bright green"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-olive"
                          }
                        ]
                      },
                      "text": "pale olive"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-silvery-green"
                          }
                        ]
                      },
                      "text": "pale silvery green"
                    }
                  ],
                  "text": "pale green"
                }
              ],
              "text": "pale"
            }
          ],
          "text": "desaturated"
        }
      ],
      "text": "color saturation"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#perceived-color",
            "definition": "include hue (coloration), color brightness, color saturation, and reflectance, the overall color perceived by an observer"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-sheath"
              }
            ]
          },
          "text": "color of sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-leaf-blade"
              }
            ]
          },
          "text": "color of leaf blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-pistillate-scale"
              }
            ]
          },
          "text": "color of pistillate scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-leaf-blade-margin"
              }
            ]
          },
          "text": "color of leaf blade margin"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-bract-sheath"
              }
            ]
          },
          "text": "color of bract sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-staminate-scale"
              }
            ]
          },
          "text": "color of staminate scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-margin-of-staminate-scale"
              }
            ]
          },
          "text": "color of margin of staminate scale"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-margin-of-leaf-blade"
              }
            ]
          },
          "text": "color of margin of leaf blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-stem"
              }
            ]
          },
          "text": "color of stem"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-achene"
              }
            ]
          },
          "text": "color of achene"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-tooth-of-perigynium-beak"
              }
            ]
          },
          "text": "color of tooth of perigynium beak"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-inner-band-of-bract-sheath"
              }
            ]
          },
          "text": "color of inner band of bract sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-inner-band-of-sheath"
              }
            ]
          },
          "text": "color of inner band of sheath"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-bract-blade"
              }
            ]
          },
          "text": "color of bract blade"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-cataphyll"
              }
            ]
          },
          "text": "color of cataphyll"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#color-of-margin-of-bract-blade"
              }
            ]
          },
          "text": "color of margin of bract blade"
        }
      ],
      "text": "perceived color"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#growth-form"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#herb",
                "definition": "any seed-bearing plant that does not have a woody stem and dies down to the ground after flowering."
              }
            ]
          },
          "text": "herb"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#procumbent",
                "definition": "Growing horizontal to the ground without setting roots"
              }
            ]
          },
          "text": "procumbent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#caespitose",
                "definition": "growing in dense tufts"
              }
            ]
          },
          "text": "caespitose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#decumbent",
                "definition": "A positional quality inhering in a bearer by virtue of the bearer's lying or growing on the ground but with erect or rising tips"
              }
            ]
          },
          "text": "decumbent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cespitose"
              }
            ]
          },
          "text": "cespitose"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#excurrent",
                "definition": "extending beyond the apex, as the midrib in some leaves"
              }
            ]
          },
          "text": "excurrent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#prostrate",
                "definition": "A positional quality inhering in a bearer by virtue of the bearer's being stretched out and lying at full length along the ground"
              }
            ]
          },
          "text": "prostrate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#woody"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subwoody"
                  }
                ]
              },
              "text": "subwoody"
            }
          ],
          "text": "woody"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#herbacous"
              }
            ]
          },
          "text": "herbacous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#creeping",
                "definition": "growing along the surface of the ground, or just beneath the surface, and producing roots, usually at the nodes"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-creeping"
                  }
                ]
              },
              "text": "short-creeping"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-creeping"
                  }
                ]
              },
              "text": "long-creeping"
            }
          ],
          "text": "creeping"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#colonial",
                "definition": "forming colonies; usually refers to groups of plants connected to one another by underground organs"
              }
            ]
          },
          "text": "colonial"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tuft-forming"
              }
            ]
          },
          "text": "tuft-forming"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#mat-forming"
              }
            ]
          },
          "text": "mat-forming"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#herbaceous",
                "definition": "A quality inhering in a bearer by virtue of the bearer's upper parts die back to the rootstock at the end of the growing season"
              }
            ]
          },
          "text": "herbaceous"
        }
      ],
      "text": "growth form"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-position"
          }
        ]
      },
      "text": "relative position"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#coloration",
            "definition": "Human perceived quality of the bearer being colored, including multiple colors and variant color. A color is a composite chromatic quality composed of hue, saturation and intensity parts. See See http://www.georeference.org/doc/colors_as_hue_saturation_and_brightness.htm to differentiate hue (color), saturation, and brightness."
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#colored",
                "definition": "A color quality inhering in a bearer by virtue of the bearer's having color"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pink",
                    "definition": "Red color having medium to high brightness and low to moderate saturation"
                  }
                ]
              },
              "text": "pink"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#colorless",
                    "definition": "A color quality inhering in a bearer by virtue of the bearer's lacking color"
                  }
                ]
              },
              "text": "colorless"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-colored"
                  }
                ]
              },
              "text": "light colored"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gray"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gray-brown"
                      }
                    ]
                  },
                  "text": "gray brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cinereous"
                      }
                    ]
                  },
                  "text": "cinereous"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gray-green"
                      }
                    ]
                  },
                  "text": "gray green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery-white"
                          }
                        ]
                      },
                      "text": "silvery white"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery-green"
                          }
                        ]
                      },
                      "children": [
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-silvery-green"
                              }
                            ]
                          },
                          "text": "pale silvery green"
                        }
                      ],
                      "text": "silvery green"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery-greenish"
                          }
                        ]
                      },
                      "text": "silvery greenish"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery-brown"
                          }
                        ]
                      },
                      "children": [
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-silvery-brown"
                              }
                            ]
                          },
                          "text": "pale silvery brown"
                        }
                      ],
                      "text": "silvery brown"
                    }
                  ],
                  "text": "silvery"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gray-black"
                      }
                    ]
                  },
                  "text": "gray black"
                }
              ],
              "text": "gray"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red",
                    "definition": "A color hue with high wavelength of the long-wave end of the visible spectrum, evoked in the human observer by radiant energy with wavelengths of approximately 630 to 750 nanometers"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-dotted"
                      }
                    ]
                  },
                  "text": "red dotted"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-gold"
                      }
                    ]
                  },
                  "text": "red gold"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-tinged"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#redbrown-tinged"
                          }
                        ]
                      },
                      "text": "red brown tinged"
                    }
                  ],
                  "text": "red tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-red"
                      }
                    ]
                  },
                  "text": "purple red"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-red"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-redbrown"
                          }
                        ]
                      },
                      "text": "dark red brown"
                    }
                  ],
                  "text": "dark red"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous",
                            "definition": "rust-colored"
                          }
                        ]
                      },
                      "children": [
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous-brown"
                              }
                            ]
                          },
                          "text": "ferruginous brown"
                        },
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous-yellow"
                              }
                            ]
                          },
                          "text": "ferruginous yellow"
                        },
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous-green"
                              }
                            ]
                          },
                          "text": "ferruginous green"
                        },
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-ferruginous"
                              }
                            ]
                          },
                          "text": "pale ferruginous"
                        }
                      ],
                      "text": "ferruginous"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-redbrown"
                          }
                        ]
                      },
                      "text": "light red brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-redbrown"
                          }
                        ]
                      },
                      "text": "dark red brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-brown-speckled"
                          }
                        ]
                      },
                      "text": "red brown speckled"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#redbrown-tinged"
                          }
                        ]
                      },
                      "text": "red brown tinged"
                    }
                  ],
                  "text": "red brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wine-red"
                      }
                    ]
                  },
                  "text": "wine-red"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sanguineous",
                        "definition": "blood red"
                      }
                    ]
                  },
                  "text": "sanguineous"
                }
              ],
              "text": "red"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown",
                    "definition": "A color consisting of dark orange, red, of very low intensity"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-tinged"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#redbrown-tinged"
                          }
                        ]
                      },
                      "text": "red brown tinged"
                    }
                  ],
                  "text": "brown tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gray-brown"
                      }
                    ]
                  },
                  "text": "gray brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-redbrown"
                          }
                        ]
                      },
                      "text": "dark red brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-chestnut"
                          }
                        ]
                      },
                      "text": "dark chestnut"
                    }
                  ],
                  "text": "dark brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-green"
                      }
                    ]
                  },
                  "text": "brown green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gold-brown"
                      }
                    ]
                  },
                  "text": "gold brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bronze",
                        "definition": "A medium yellowish brown color with a metallic sheen, resembles the actual alloy bronze"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#coppery-tinged"
                          }
                        ]
                      },
                      "text": "coppery tinged"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#copper-brown"
                          }
                        ]
                      },
                      "text": "copper brown"
                    }
                  ],
                  "text": "bronze"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellow-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-yellowbrown"
                          }
                        ]
                      },
                      "text": "light yellow brown"
                    }
                  ],
                  "text": "yellow brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orangebrown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    }
                  ],
                  "text": "orange brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-purple"
                      }
                    ]
                  },
                  "text": "purple brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#medium-brown"
                      }
                    ]
                  },
                  "text": "medium brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-dotted"
                      }
                    ]
                  },
                  "text": "brown dotted"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#black-brown"
                      }
                    ]
                  },
                  "text": "black brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brownish-yellow"
                      }
                    ]
                  },
                  "text": "brownish yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    }
                  ],
                  "text": "hyaline brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#greenish-brown"
                      }
                    ]
                  },
                  "text": "greenish brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-redbrown"
                          }
                        ]
                      },
                      "text": "light red brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-yellowbrown"
                          }
                        ]
                      },
                      "text": "light yellow brown"
                    }
                  ],
                  "text": "light brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#chestnut",
                        "definition": "a grayish to reddish brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#castaneous"
                          }
                        ]
                      },
                      "text": "castaneous"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-chestnut"
                          }
                        ]
                      },
                      "text": "dark chestnut"
                    }
                  ],
                  "text": "chestnut"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellowbrown-tinged"
                      }
                    ]
                  },
                  "text": "yellow brown tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous",
                            "definition": "rust-colored"
                          }
                        ]
                      },
                      "children": [
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous-brown"
                              }
                            ]
                          },
                          "text": "ferruginous brown"
                        },
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous-yellow"
                              }
                            ]
                          },
                          "text": "ferruginous yellow"
                        },
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous-green"
                              }
                            ]
                          },
                          "text": "ferruginous green"
                        },
                        {
                          "data": {
                            "details": [
                              {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-ferruginous"
                              }
                            ]
                          },
                          "text": "pale ferruginous"
                        }
                      ],
                      "text": "ferruginous"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-redbrown"
                          }
                        ]
                      },
                      "text": "light red brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-redbrown"
                          }
                        ]
                      },
                      "text": "dark red brown"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-brown-speckled"
                          }
                        ]
                      },
                      "text": "red brown speckled"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#redbrown-tinged"
                          }
                        ]
                      },
                      "text": "red brown tinged"
                    }
                  ],
                  "text": "red brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#olive-brown"
                      }
                    ]
                  },
                  "text": "olive brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-stained"
                      }
                    ]
                  },
                  "text": "brown stained"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-silvery-brown"
                          }
                        ]
                      },
                      "text": "pale silvery brown"
                    }
                  ],
                  "text": "silvery brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brownish-black"
                      }
                    ]
                  },
                  "text": "brownish black"
                }
              ],
              "text": "brown"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellow",
                    "definition": "A color hue with medium wavelength of that portion of the visible spectrum lying between orange and green, evoked in the human observer by radiant energy with wavelengths of approximately 570 to 590 nanometers"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#green-yellow"
                      }
                    ]
                  },
                  "text": "green yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-yellow"
                      }
                    ]
                  },
                  "text": "pale yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellowbrown-tinged"
                      }
                    ]
                  },
                  "text": "yellow brown tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#greenish-yellow"
                      }
                    ]
                  },
                  "text": "greenish yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellowish-green"
                      }
                    ]
                  },
                  "text": "yellowish green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous-yellow"
                      }
                    ]
                  },
                  "text": "ferruginous yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellow-brown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-yellowbrown"
                          }
                        ]
                      },
                      "text": "light yellow brown"
                    }
                  ],
                  "text": "yellow brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellow-white"
                      }
                    ]
                  },
                  "text": "yellow white"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bright-yellow"
                      }
                    ]
                  },
                  "text": "bright yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brownish-yellow"
                      }
                    ]
                  },
                  "text": "brownish yellow"
                }
              ],
              "text": "yellow"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gold"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-gold"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-gold-tinged"
                          }
                        ]
                      },
                      "text": "pale gold tinged"
                    }
                  ],
                  "text": "pale gold"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gold-brown"
                      }
                    ]
                  },
                  "text": "gold brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-gold"
                      }
                    ]
                  },
                  "text": "red gold"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#golden-green"
                      }
                    ]
                  },
                  "text": "gold green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-gold"
                      }
                    ]
                  },
                  "text": "dark gold"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline-gold"
                      }
                    ]
                  },
                  "text": "hyaline gold"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gold-tinged"
                      }
                    ]
                  },
                  "text": "gold tinged"
                }
              ],
              "text": "gold"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white",
                    "definition": "An achromatic color of maximum brightness; the color of objects that reflect nearly all light of all visible wavelengths"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-iridescent"
                      }
                    ]
                  },
                  "text": "white iridescent"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery-white"
                      }
                    ]
                  },
                  "text": "silvery white"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-mottled"
                      }
                    ]
                  },
                  "text": "white mottled"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-hyaline"
                      }
                    ]
                  },
                  "text": "white hyaline"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellow-white"
                      }
                    ]
                  },
                  "text": "yellow white"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cream-colored"
                      }
                    ]
                  },
                  "text": "cream-colored"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-green"
                      }
                    ]
                  },
                  "text": "white green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-dotted"
                      }
                    ]
                  },
                  "text": "white dotted"
                }
              ],
              "text": "white"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lighter-colored"
                  }
                ]
              },
              "text": "lighter colored"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orange",
                    "definition": "A color hue with high-medium wavelength that of that portion of the visible spectrum lying between red and yellow, evoked in the human observer by radiant energy with wavelengths of approximately 585 to 620 nanometers"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bright-orange"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    }
                  ],
                  "text": "bright orange"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#orangebrown"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tan-hyaline"
                          }
                        ]
                      },
                      "text": "tan hyaline"
                    }
                  ],
                  "text": "orange brown"
                }
              ],
              "text": "orange"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#black",
                    "definition": "A color that lacks any hues as parts"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#black-tinged"
                      }
                    ]
                  },
                  "text": "black tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blue-black"
                      }
                    ]
                  },
                  "text": "blue black"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#black-mottled"
                      }
                    ]
                  },
                  "text": "black mottled"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#black-brown"
                      }
                    ]
                  },
                  "text": "black brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brownish-black"
                      }
                    ]
                  },
                  "text": "brownish black"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gray-black"
                      }
                    ]
                  },
                  "text": "gray black"
                }
              ],
              "text": "black"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blue",
                    "definition": "A color hue with low wavelength of that portion of the visible spectrum lying between green and indigo, evoked in the human observer by radiant energy with wavelengths of approximately 420 to 490 nanometers"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blue-green"
                      }
                    ]
                  },
                  "text": "blue green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blue-black"
                      }
                    ]
                  },
                  "text": "blue black"
                }
              ],
              "text": "blue"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#green",
                    "definition": "A color hue with medium-low wavelength of that portion of the visible spectrum lying between yellow and blue, evoked in the human observer by radiant energy with wavelengths of approximately 490 to 570 nanometers"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#green-yellow"
                      }
                    ]
                  },
                  "text": "green yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-green"
                      }
                    ]
                  },
                  "text": "brown green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#greenish-yellow"
                      }
                    ]
                  },
                  "text": "greenish yellow"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellowish-green"
                      }
                    ]
                  },
                  "text": "yellowish green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bright-green"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-bright-green"
                          }
                        ]
                      },
                      "text": "pale bright green"
                    }
                  ],
                  "text": "bright green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#golden-green"
                      }
                    ]
                  },
                  "text": "gold green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gray-green"
                      }
                    ]
                  },
                  "text": "gray green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-green"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-olive"
                          }
                        ]
                      },
                      "text": "dark olive"
                    }
                  ],
                  "text": "dark green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#olive-green",
                        "definition": "A color consisting of brown and green hues"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-olive"
                          }
                        ]
                      },
                      "text": "dark olive"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-olive"
                          }
                        ]
                      },
                      "text": "pale olive"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#olive-brown"
                          }
                        ]
                      },
                      "text": "olive brown"
                    }
                  ],
                  "text": "olive green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery-green"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-silvery-green"
                          }
                        ]
                      },
                      "text": "pale silvery green"
                    }
                  ],
                  "text": "silvery green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#glaucous-green"
                      }
                    ]
                  },
                  "text": "glaucous green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#greenish-brown"
                      }
                    ]
                  },
                  "text": "greenish brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blue-green"
                      }
                    ]
                  },
                  "text": "blue green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-green"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#light-olive"
                          }
                        ]
                      },
                      "text": "light olive"
                    }
                  ],
                  "text": "light green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ferruginous-green"
                      }
                    ]
                  },
                  "text": "ferruginous green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-green"
                      }
                    ]
                  },
                  "text": "white green"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#silvery-greenish"
                      }
                    ]
                  },
                  "text": "silvery greenish"
                }
              ],
              "text": "green"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#variant",
                    "definition": "A quality of certain surfaces which appear to change colour as the angle of view changes"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#iridescent",
                        "definition": "displaying many colors, as in a rainbow"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-iridescent"
                          }
                        ]
                      },
                      "text": "white iridescent"
                    }
                  ],
                  "text": "iridescent"
                }
              ],
              "text": "variant color"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple",
                    "definition": "A color that falls about midway between red and blue in hue"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-blotches"
                      }
                    ]
                  },
                  "text": "purple blotches"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-dotted"
                      }
                    ]
                  },
                  "text": "purple dotted"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-blotched"
                      }
                    ]
                  },
                  "text": "purple blotched"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-red"
                      }
                    ]
                  },
                  "text": "purple red"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dark-purple"
                      }
                    ]
                  },
                  "text": "dark purple"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-purple"
                      }
                    ]
                  },
                  "text": "purple brown"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-tinged"
                      }
                    ]
                  },
                  "text": "purple tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-banded"
                      }
                    ]
                  },
                  "text": "purple banded"
                }
              ],
              "text": "purple"
            }
          ],
          "text": "colored"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#multi-colored"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#suffused",
                    "definition": "tinted or tinged"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hyaline-suffused"
                      }
                    ]
                  },
                  "text": "hyaline suffused"
                }
              ],
              "text": "suffused"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stained"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-stained"
                      }
                    ]
                  },
                  "text": "brown stained"
                }
              ],
              "text": "stained"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#speckled"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-brown-speckled"
                      }
                    ]
                  },
                  "text": "red brown speckled"
                }
              ],
              "text": "speckled"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#mottled",
                    "definition": "with colored spots or blotches"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-mottled"
                      }
                    ]
                  },
                  "text": "white mottled"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#green-and-white-mottled"
                      }
                    ]
                  },
                  "text": "green and white mottled"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#black-mottled"
                      }
                    ]
                  },
                  "text": "black mottled"
                }
              ],
              "text": "mottled"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tinged"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-tinged"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#redbrown-tinged"
                          }
                        ]
                      },
                      "text": "red brown tinged"
                    }
                  ],
                  "text": "brown tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#yellowbrown-tinged"
                      }
                    ]
                  },
                  "text": "yellow brown tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#black-tinged"
                      }
                    ]
                  },
                  "text": "black tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-tinged"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#redbrown-tinged"
                          }
                        ]
                      },
                      "text": "red brown tinged"
                    }
                  ],
                  "text": "red tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-tinged"
                      }
                    ]
                  },
                  "text": "purple tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#coppery-tinged"
                      }
                    ]
                  },
                  "text": "coppery tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-red-brown-tinged"
                      }
                    ]
                  },
                  "text": "pale red brown tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pale-gold-tinged"
                      }
                    ]
                  },
                  "text": "pale gold tinged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gold-tinged"
                      }
                    ]
                  },
                  "text": "gold tinged"
                }
              ],
              "text": "tinged"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#banded",
                    "definition": "horizontal ring on a vertical structure."
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-banded"
                      }
                    ]
                  },
                  "text": "purple banded"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#striped",
                        "definition": "a banded pattern which is oriented the same way as the axis of the bearing structure."
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#green-and-white-striped"
                          }
                        ]
                      },
                      "text": "green and white striped"
                    }
                  ],
                  "text": "striped"
                }
              ],
              "text": "banded"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stripe"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#streaked"
                      }
                    ]
                  },
                  "text": "streaked"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#midstripe"
                      }
                    ]
                  },
                  "text": "midstripe"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#streak"
                      }
                    ]
                  },
                  "text": "streak"
                }
              ],
              "text": "stripe"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dotted",
                    "definition": "A color pattern inhering in a bearer by virtue of bearer's being marked with a round area of different hue or degree of saturation"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#red-dotted"
                      }
                    ]
                  },
                  "text": "red dotted"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-dotted"
                      }
                    ]
                  },
                  "text": "purple dotted"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#blotched",
                        "definition": "A color pattern inhering in a bearer by virtue of bearer's being marked with irregularly shaped spots or blots of a different hue or degree of saturation"
                      }
                    ]
                  },
                  "children": [
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-blotches"
                          }
                        ]
                      },
                      "text": "purple blotches"
                    },
                    {
                      "data": {
                        "details": [
                          {
                            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#purple-blotched"
                          }
                        ]
                      },
                      "text": "purple blotched"
                    }
                  ],
                  "text": "blotched"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#brown-dotted"
                      }
                    ]
                  },
                  "text": "brown dotted"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#white-dotted"
                      }
                    ]
                  },
                  "text": "white dotted"
                }
              ],
              "text": "dotted"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#green-and-white"
                  }
                ]
              },
              "text": "green and white"
            }
          ],
          "text": "multi-colored"
        }
      ],
      "text": "coloration"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#habitat"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#alpine"
              }
            ]
          },
          "text": "alpine"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#arctic-alpine"
              }
            ]
          },
          "text": "arctic alpine"
        }
      ],
      "text": "habitat"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#variability",
            "definition": "A quality inhering in a bearer by virtue of the bearer's disposition to varying or changing"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#varying"
              }
            ]
          },
          "text": "varying"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#uniform",
                "definition": "A variability quality inhering in a bearer by virtue of the bearer's lacking or not exhibiting variation"
              }
            ]
          },
          "text": "uniform"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#equal"
              }
            ]
          },
          "text": "equal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#differentiated"
              }
            ]
          },
          "text": "differentiated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subequal"
              }
            ]
          },
          "text": "subequal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#variable"
              }
            ]
          },
          "text": "variable"
        }
      ],
      "text": "variability"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#size",
            "definition": "A morphology quality inhering in a bearer by virtue of the bearer's physical magnitude"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-size"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#smaller"
                  }
                ]
              },
              "text": "smaller"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-height"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tallest"
                      }
                    ]
                  },
                  "text": "tallest"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#taller"
                      }
                    ]
                  },
                  "text": "taller"
                }
              ],
              "text": "relative height"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-width"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest"
                      }
                    ]
                  },
                  "text": "widest"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrower"
                      }
                    ]
                  },
                  "text": "narrower"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wider"
                      }
                    ]
                  },
                  "text": "wider"
                }
              ],
              "text": "relative width"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-length"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longer"
                      }
                    ]
                  },
                  "text": "longer"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shortest"
                      }
                    ]
                  },
                  "text": "shortest"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elongated",
                        "definition": "A quality inhering in a bearer by virtue of the bearer's length being notably higher than its width"
                      }
                    ]
                  },
                  "text": "elongated"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest"
                      }
                    ]
                  },
                  "text": "longest"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shorter"
                      }
                    ]
                  },
                  "text": "shorter"
                }
              ],
              "text": "relative length"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#larger"
                  }
                ]
              },
              "text": "larger"
            }
          ],
          "text": "relative size"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#large"
              }
            ]
          },
          "text": "large"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#contracted",
                "definition": "narrowed; narrow, thick, and dense, as an inflorescence with crowded, short or appressed branches"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#rounded-contracted"
                  }
                ]
              },
              "text": "rounded-contracted"
            }
          ],
          "text": "contracted"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#plump"
              }
            ]
          },
          "text": "plump"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#slender",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being small or narrow in circumference or width in proportion to length or height"
              }
            ]
          },
          "text": "slender"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#depth",
                "definition": "A 1-D extent quality inhering in a bearer by virtue of the bearer's downward or backward or inward dimenision"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#deep",
                    "definition": "A depth quality which is relatively high"
                  }
                ]
              },
              "text": "deep"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flattened"
                  }
                ]
              },
              "text": "flattened"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#thickened",
                    "definition": "A thickness which is relatively high"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#spongy-thickened"
                      }
                    ]
                  },
                  "text": "spongy-thickened"
                }
              ],
              "text": "thickened"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#thin"
                  }
                ]
              },
              "text": "thin"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#thick"
                  }
                ]
              },
              "text": "thick"
            }
          ],
          "text": "depth"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#enlarged"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#swelling"
                  }
                ]
              },
              "text": "swelling"
            }
          ],
          "text": "enlarged"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reduced"
              }
            ]
          },
          "text": "reduced"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#medium"
              }
            ]
          },
          "text": "medium"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length",
                "definition": "A 1-D extent quality which is equal to the distance between two points"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#prolonged"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bristlelike-prolonged"
                      }
                    ]
                  },
                  "text": "bristlelike-prolonged"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short-prolonged"
                      }
                    ]
                  },
                  "text": "short-prolonged"
                }
              ],
              "text": "prolonged"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-length"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longer"
                      }
                    ]
                  },
                  "text": "longer"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shortest"
                      }
                    ]
                  },
                  "text": "shortest"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elongated",
                        "definition": "A quality inhering in a bearer by virtue of the bearer's length being notably higher than its width"
                      }
                    ]
                  },
                  "text": "elongated"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#longest"
                      }
                    ]
                  },
                  "text": "longest"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shorter"
                      }
                    ]
                  },
                  "text": "shorter"
                }
              ],
              "text": "relative length"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#length-of-perigynium"
                  }
                ]
              },
              "text": "length of perigynium"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long",
                    "definition": "A length quality which is relatively large"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-pubescent"
                      }
                    ]
                  },
                  "text": "long-pubescent"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#long-peduncled"
                      }
                    ]
                  },
                  "text": "long peduncled"
                }
              ],
              "text": "long"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#short",
                    "definition": "A length quality which is relatively small"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#shortened",
                        "definition": "A quality inhering in a bearer by virtue of the bearer's length being notably lower than its width"
                      }
                    ]
                  },
                  "text": "shortened"
                }
              ],
              "text": "short"
            }
          ],
          "text": "length"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#constricted",
                "definition": "A structural quality inhering in a bearer by virtue of the bearer's being drawn together, compressed or squeezed physically"
              }
            ]
          },
          "text": "constricted"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#minute"
              }
            ]
          },
          "text": "minute"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#small"
              }
            ]
          },
          "text": "small"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reduced-magnitude"
              }
            ]
          },
          "text": "reduced magnitude"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#medium-sized"
              }
            ]
          },
          "text": "medium sized"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#expanded"
              }
            ]
          },
          "text": "expanded"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#finer"
              }
            ]
          },
          "text": "finer"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#large-sized"
              }
            ]
          },
          "text": "large sized"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#diameter"
              }
            ]
          },
          "text": "diameter"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#uninflated"
              }
            ]
          },
          "text": "uninflated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#width",
                "definition": "A 1-D extent quality which is equal to the distance from one side of an object to another side which is opposite"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wide"
                  }
                ]
              },
              "text": "wide"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrow"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrowing"
                      }
                    ]
                  },
                  "text": "narrowing"
                }
              ],
              "text": "narrow"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fine"
                  }
                ]
              },
              "text": "fine"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-width"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#widest"
                      }
                    ]
                  },
                  "text": "widest"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#narrower"
                      }
                    ]
                  },
                  "text": "narrower"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#wider"
                      }
                    ]
                  },
                  "text": "wider"
                }
              ],
              "text": "relative width"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broad",
                    "definition": "A quality inhering in a bearer by virtue of the bearer's width being notably higher than its length"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broader"
                      }
                    ]
                  },
                  "text": "broader"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#broadened"
                      }
                    ]
                  },
                  "text": "broadened"
                }
              ],
              "text": "broad"
            }
          ],
          "text": "width"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#height",
                "definition": "A 1-D extent quality inhering in a bearer by virtue of the bearer's vertical dimension of extension"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#relative-height"
                  }
                ]
              },
              "children": [
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tallest"
                      }
                    ]
                  },
                  "text": "tallest"
                },
                {
                  "data": {
                    "details": [
                      {
                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#taller"
                      }
                    ]
                  },
                  "text": "taller"
                }
              ],
              "text": "relative height"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dwarf"
                  }
                ]
              },
              "text": "dwarf"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tall"
                  }
                ]
              },
              "text": "tall"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#high"
                  }
                ]
              },
              "text": "high"
            }
          ],
          "text": "height"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fitting"
              }
            ]
          },
          "text": "fitting"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#swollen",
                "definition": "A structure quality inhering in a bearer by virtue of the bearer's exhibiting transient abnormal enlargement, not due to cell proliferation"
              }
            ]
          },
          "text": "swollen"
        }
      ],
      "text": "size"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#derivation"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adventitious"
              }
            ]
          },
          "text": "adventitious"
        }
      ],
      "text": "derivation"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fusion"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adnate",
                "definition": "fusion of unlike parts, as the stamens to the corolla"
              }
            ]
          },
          "text": "adnate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#free",
                "definition": "not attached to other organs"
              }
            ]
          },
          "text": "free"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#connate",
                "definition": "fusion of like parts, as the fusion of staminal filaments into a tube"
              }
            ]
          },
          "text": "connate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fused",
                "definition": "A structural quality inhering in a bearer by virtue of the bearer's being merged with another entity"
              }
            ]
          },
          "text": "fused"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fasciated",
                "definition": "A shape quality inhering in a bearer by virtue of the bearer's being abnormally flattened or coalesced"
              }
            ]
          },
          "text": "fasciated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distinct",
                "definition": "separate; not attached to like parts"
              }
            ]
          },
          "text": "distinct"
        }
      ],
      "text": "fusion"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fixation"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#detatched"
              }
            ]
          },
          "text": "detatched"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pressed"
              }
            ]
          },
          "text": "pressed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basifixed",
                "definition": "attached by base"
              }
            ]
          },
          "text": "basifixed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#attached",
                "definition": "An attachment quality inhering in a bearer by virtue of the bearer's connection or association with another entity"
              }
            ]
          },
          "text": "attached"
        }
      ],
      "text": "fixation"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fragility",
            "definition": "A structural quality inhering in a bearer by virtue of the bearer's disposition to being damaged or destroyed"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#flexible",
                "definition": "A physical quality inhering in a bearer by virtue of the bearer's ability of being turned, bowed, or twisted without breaking"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#pliable"
                  }
                ]
              },
              "text": "pliable"
            }
          ],
          "text": "flexible"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stiff",
                "definition": "A physical quality inhering in a bearer by virtue of the bearer's inability of being turned, bowed, or twisted without breaking"
              }
            ]
          },
          "text": "stiff"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reduced-strength"
              }
            ]
          },
          "text": "reduced strength"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#delicate"
              }
            ]
          },
          "text": "delicate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#stout",
                "definition": "A thickness which is relatively high"
              }
            ]
          },
          "text": "stout"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fragile",
                "definition": "A structural quality inhering in a bearer by virtue of the bearer's disposition to being easily damaged or destroyed"
              }
            ]
          },
          "text": "fragile"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#tough"
              }
            ]
          },
          "text": "tough"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#strong"
              }
            ]
          },
          "text": "strong"
        }
      ],
      "text": "fragility"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#position",
            "definition": "A spatial quality inhering in a bearer by virtue of the bearer's spatial location relative to other objects in the vicinity"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#middle"
              }
            ]
          },
          "text": "middle"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#marginal"
              }
            ]
          },
          "text": "marginal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#exserted",
                "definition": "A quality inhering in a bearer by virtue of the bearer's protruding or projecting from the body"
              }
            ]
          },
          "text": "exserted"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#submarginal"
              }
            ]
          },
          "text": "submarginal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#surrounding",
                "definition": "A spatial quality inhering in a bearer by virtue of the bearer's being extended on all sides of another entity simultaneously"
              }
            ]
          },
          "text": "surrounding"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#terminal",
                "definition": "A position quality inhering in a bearer by virtue of the bearer's forming the end of another entity"
              }
            ]
          },
          "text": "terminal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#low"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lower"
                  }
                ]
              },
              "text": "lower"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lowest"
                  }
                ]
              },
              "text": "lowest"
            }
          ],
          "text": "low"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apical",
                "definition": "located at the apex or tip"
              }
            ]
          },
          "text": "apical"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximate"
              }
            ]
          },
          "text": "proximate"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inner"
              }
            ]
          },
          "text": "inner"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basal",
                "definition": "positioned at or arising from the base, as leaves arising from the base of the stem"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#basalmost"
                  }
                ]
              },
              "text": "basalmost"
            },
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subbasal"
                  }
                ]
              },
              "text": "subbasal"
            }
          ],
          "text": "basal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#central"
              }
            ]
          },
          "text": "central"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reaching"
              }
            ]
          },
          "text": "reaching"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial",
                "definition": "the side toward the axis"
              }
            ]
          },
          "text": "adaxial"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subapical"
              }
            ]
          },
          "text": "subapical"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#midway"
              }
            ]
          },
          "text": "midway"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hypogynous",
                "definition": "with stamens, petals, and sepals attached below the ovary, the ovary superior to the other floral parts"
              }
            ]
          },
          "text": "hypogynous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#subtending"
              }
            ]
          },
          "text": "subtending"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#overtoppped"
              }
            ]
          },
          "text": "overtoppped"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#exceeding"
              }
            ]
          },
          "text": "exceeding"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximal",
                "definition": "toward the base, or the end of the organ by which it is attached"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#proximalmost"
                  }
                ]
              },
              "text": "proximalmost"
            }
          ],
          "text": "proximal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#enclosing",
                "definition": "A spatial quality inhering in a bearer by virtue of the bearer's being extended on all sides of another entity simultaneously"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#enveloping"
                  }
                ]
              },
              "text": "enveloping"
            }
          ],
          "text": "enclosing"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#nonbasal"
              }
            ]
          },
          "text": "nonbasal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#interior"
              }
            ]
          },
          "text": "interior"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#dorsal"
              }
            ]
          },
          "text": "dorsal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#lateral",
                "definition": "A spatial quality inhering in a bearer by virtue of the bearer's being located toward the side relative to another entity"
              }
            ]
          },
          "text": "lateral"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distal",
                "definition": "A spatial quality inhering in a bearer by virtue of the bearer's being located further from a more centrally located entity"
              }
            ]
          },
          "children": [
            {
              "data": {
                "details": [
                  {
                    "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distalmost"
                  }
                ]
              },
              "text": "distalmost"
            }
          ],
          "text": "distal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#mid"
              }
            ]
          },
          "text": "mid"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#intervenal"
              }
            ]
          },
          "text": "intervenal"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#median"
              }
            ]
          },
          "text": "median"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial",
                "definition": "the side away from the axis"
              }
            ]
          },
          "text": "abaxial"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#enfolding"
              }
            ]
          },
          "text": "enfolding"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#cauline",
                "definition": "of, on, or pertaining to the stem, as leaves arising from the stem above the ground level"
              }
            ]
          },
          "text": "cauline"
        }
      ],
      "text": "position"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#condition"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ruptured"
              }
            ]
          },
          "text": "ruptured"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#drying"
              }
            ]
          },
          "text": "drying"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#closed"
              }
            ]
          },
          "text": "closed"
        }
      ],
      "text": "condition"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#reproduction"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gynaecandrous"
              }
            ]
          },
          "text": "gynaecandrous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#unisexual",
                "definition": "a flower with either male or female reproductive parts, but not both. The term is also applied to plants possessing such flowers"
              }
            ]
          },
          "text": "unisexual"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gynecandrous"
              }
            ]
          },
          "text": "gynecandrous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#female",
                "definition": "A biological sex quality inhering in an individual or a population that only produces gametes that can be fertilised by male gametes"
              }
            ]
          },
          "text": "female"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#sterile",
                "definition": "A fertility quality inhering in a bearer by virtue of the bearer's being incapable of initiating, sustaining, or supporting reproduction"
              }
            ]
          },
          "text": "sterile"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#monoecious",
                "definition": "flowers imperfect, the staminate and pistillate flowers borne on the same plant"
              }
            ]
          },
          "text": "monoecious"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#bisexual",
                "definition": "a flower with both male and female reproductive organs"
              }
            ]
          },
          "text": "bisexual"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#gyncecandrous"
              }
            ]
          },
          "text": "gyncecandrous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#vegetative",
                "definition": "of or pertaining to the non-floral parts of a plant"
              }
            ]
          },
          "text": "vegetative"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#fertile",
                "definition": "A fertility quality inhering in a bearer by virtue of the bearer's being capable of initiating, sustaining, or supporting reproduction"
              }
            ]
          },
          "text": "fertile"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#male",
                "definition": "A biological sex quality inhering in an individual or a population whose sex organs contain only male gametes"
              }
            ]
          },
          "text": "male"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#androgynous",
                "definition": "with both staminate and pistilate flowers, the staminate flowers borne aboce the pistilate, as in in the inflorescence of some carex species"
              }
            ]
          },
          "text": "androgynous"
        }
      ],
      "text": "reproduction"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#prominence",
            "definition": "A quality inhering in a bearer by virtue of the degree to which the bearers stands out or projects beyond a surface or line"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#prominent",
                "definition": "A quality inhering in a bearer by virtue of the bearer's standing out or projecting beyond a surface or line"
              }
            ]
          },
          "text": "prominent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#evident"
              }
            ]
          },
          "text": "evident"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#distinguishable"
              }
            ]
          },
          "text": "distinguishable"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#conspicous"
              }
            ]
          },
          "text": "conspicous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#hidden"
              }
            ]
          },
          "text": "hidden"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#impressed",
                "definition": "situated below the surface, as in some leaf veins"
              }
            ]
          },
          "text": "impressed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obscure"
              }
            ]
          },
          "text": "obscure"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#apparent"
              }
            ]
          },
          "text": "apparent"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#elevated"
              }
            ]
          },
          "text": "elevated"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#vestigial",
                "definition": "A quality inhering in a bearer by virtue of the bearer's being a remnant structure from earlier development or evolution"
              }
            ]
          },
          "text": "vestigial"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inconspiculous"
              }
            ]
          },
          "text": "inconspiculous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#conspicuous",
                "definition": "A quality inhering in a bearer by virtue of being clearly visible"
              }
            ]
          },
          "text": "conspicuous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inconspicuous",
                "definition": "A quality inhering in a bearer by virtue of not being clearly visible"
              }
            ]
          },
          "text": "inconspicuous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obvious"
              }
            ]
          },
          "text": "obvious"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#inconspiuous"
              }
            ]
          },
          "text": "inconspiuous"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#obsolete",
                "definition": "an organ or structure which is much reduced na likely nonfunctional, though believed at one time to have been more perfectly formed vestigial"
              }
            ]
          },
          "text": "obsolete"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#exposed",
                "definition": "Open to view or not covered by another entity"
              }
            ]
          },
          "text": "exposed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#raised",
                "definition": "A shape quality inhering in a colony by virtue of the bearer's appearing above the medium surface with terraced edges"
              }
            ]
          },
          "text": "raised"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#indistinct"
              }
            ]
          },
          "text": "indistinct"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#indistinguishable"
              }
            ]
          },
          "text": "indistinguishable"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#visible"
              }
            ]
          },
          "text": "visible"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#concealed",
                "definition": "A positional quality inhering in a bearer by virtue of the bearer being hidden from view"
              }
            ]
          },
          "text": "concealed"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#protruding",
                "definition": "A quality inhering in a bearer by virtue of the bearer's extending out above or beyond a surface or boundary"
              }
            ]
          },
          "text": "protruding"
        },
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#defined"
              }
            ]
          },
          "text": "defined"
        }
      ],
      "text": "prominence"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#ratio"
          }
        ]
      },
      "text": "ratio"
    },
    {
      "data": {
        "details": [
          {
            "IRI": "http://biosemantics.arizona.edu/ontologies/carex#age",
            "definition": "A time quality inhering in a bearer by virtue of how long the bearer has existed"
          }
        ]
      },
      "children": [
        {
          "data": {
            "details": [
              {
                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#old",
                "definition": "An age which is relatively high"
              }
            ]
          },
          "text": "old"
        }
      ],
      "text": "age"
    }
  ],
  "text": "quality"
                },*/
               
                largeDataQuality:{},
                sampleEntityAllFields: {
                    "children": [
                        {
                            "children": [
                                {
                                    "data": {
                                        "details": [
                                            {
                                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#adaxial",
                                                "shared synonyms": "I'm a shared synonym",
                                                "exact synonyms": "adaxially",
                                                "elucidation": "http://biosemantics.arizona.edu/images/adaxial.jpg"
                                            }
                                        ],
                                    },
                                    "text": "adaxial"
                                },
                                {
                                    "children": [
                                        {
                                            "data": {
                                                "details": [
                                                    {
                                                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial-like"
                                                    }
                                                ],
                                            },
                                            "text": "abaxial-like"
                                        }
                                    ],
                                    "data": {
                                        "details": [
                                            {
                                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial",
                                                "exact synonyms": "abaxially"
                                            }
                                        ],
                                    },
                                    "text": "abaxial"
                                }
                            ],
                            "data": {
                                "details": [
                                    {
                                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#position"
                                    }
                                ],
                            },
                            "text": "position"
                        },
                        {
                            "children": [
                                {
                                    "children": [
                                        {
                                            "data": {
                                                "details": [
                                                    {
                                                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial-like"
                                                    }
                                                ],
                                            },
                                            "text": "abaxial-like"
                                        }
                                    ],
                                    "data": {
                                        "details": [
                                            {
                                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#abaxial",
                                                "exact synonyms": "abaxially"
                                            }
                                        ],
                                    },
                                    "text": "abaxial"
                                }
                            ],
                            "data": {
                                "details": [
                                    {
                                        "IRI": "http://biosemantics.arizona.edu/ontologies/carex#location"
                                    }
                                ],
                            },
                            "text": "location"
                        }
                    ],
                    "data": {
                        "details": [
                            {
                                "IRI": "http://biosemantics.arizona.edu/ontologies/carex#quality",
                                "definition": "Attributes or characteristics an object has"
                            }
                        ],
                    },
                    "text": "quality"
                },
                summary: [],
                summaryHasPart: [],
                summaryPartOf: [],
                showTooltip: false,
                mouseX: 0,
                mouseY: 0,
                parentNode: null,
            }
        },
        
        mounted() {
            var app = this;
            console.log('Status', app.status);
            if (sessionStorage.getItem('definition') != null) {
                app.term.definition = sessionStorage.getItem('definition');
            }
            if (sessionStorage.getItem('sentence') != null) {
                app.term.sentence = sessionStorage.getItem('sentence');
            }
            if (sessionStorage.getItem('taxa') != null) {
                app.term.taxa = sessionStorage.getItem('taxa');
            }
            if (sessionStorage.getItem('represent') != null) {
                app.term.represent = sessionStorage.getItem('represent');
            }
            if (sessionStorage.getItem('synonym') != null) {
                app.term.synonym = sessionStorage.getItem('synonym');
            }
            if (sessionStorage.getItem('instance') != null) {
                app.term.instance = sessionStorage.getItem('instance');
            }
            if (sessionStorage.getItem('hasPart') != null) {
                app.term.hasPart = sessionStorage.getItem('hasPart');
            }
            window.addEventListener('click', this.mousedown);
            //app.largeDataEntity = await app.getLargeDataEntity();
            //app.largeDataQuality = await app.getLargeDataQuality();
            //app.sortTreeData(app.largeDataEntity.children);
            //app.sortTreeData(app.largeDataQuality.children);

            //use then, not async/await, works. "Then" waits for the result is returned 
            app.getLargeDataEntity().then((result) => {
                app.largeDataEntity = result.data;
                console.log("result", result.data);
                app.sortTreeData(app.largeDataEntity.children);
            });
            app.getLargeDataQuality().then((result) => {
                app.largeDataQuality = result.data;
                app.sortTreeData(app.largeDataQuality.children);
            })
           
            //console.log('mounted largeDataQuality', app.largeDataQuality);

            var jsonRequest = {
                'user_email': app.$route.params.user,
                'action': 'Module loaded',
                'action_details': 'Wizard module loaded for term='+app.$route.params.term,
                'abnormal_system_response': null,
                'type': 'Wizard'
            };
            //if (app.username == null) {
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
        beforeMount() {
        },

        methods: {
            mousedown: function(e) {
//                console.log("mousedown", e);
                this.mouseX = e.x;
                this.mouseY = e.y;
//                $('#tooltip').css({'top':app.mouseY + 20,'left':app.mouseX + 20});

            },
            submit: function(status) {
                var app = this;
                app.showTooltip = false;
                console.log("term", app.term);
                console.log('status', status);
                switch (status) {
                    case 0: //definition, sentence, taxa
                        if (app.term.definition == null || app.term.sentence == null || app.term.taxa == null) {
                            alert('All fields on this page are required. Please provide definition, sentence, and taxa for the term');
                            app.error = "please provide ";
                            if(app.term.definition == null){
                                app.error += "definition ";                        
                            }
                            if(app.term.sentence == null){
                               app.error += "sentence ";
                            }
                            if(app.term.taxa == null){
                                app.error += "taxa ";
                            }
                            var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Attempt to Save Human Readable Definition',
                                'action_details': 'term='+app.$route.params.term +': definition=' +app.term.definition,
                                'abnormal_system_response': app.error,
                                'type': 'Wizard'
                            };
                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                        } else {
                            sessionStorage.setItem('definition', app.term.definition);
                            var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Clicked Save for Human Readable Definition',
                                'action_details': 'term='+app.$route.params.term +': definition=' +app.term.definition,
                                'abnormal_system_response': null,
                                'type': 'Wizard'
                            };
                            //if (app.username == null) {
                            //    alert('Please insert the username on homepage');
                            //} else {
                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            //}
                            //app.status = 1;
                            app.summary.push('"' + app.temp.text + '"' + ' is defined as ' + '"' +app.term.definition + '".');
                        //}
                        /* sentence */
                        //if (app.term.sentence == null) {
                        //    alert('Please enter an example sentence for the term.');
                        //} else {
                            sessionStorage.setItem('sentence', app.term.sentence);
                            var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Saved Example Sentence',
                                'action_details': 'term='+app.$route.params.term +': sentence=' +app.term.sentence,
                                'abnormal_system_response': null,
                                'type': 'Wizard'
                            };
                            //if (app.username == null) {
                            //    alert('Please insert the username on homepage');
                            //} else {
                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            //}
                            //app.status = 1;
                            app.summary.push('"' + app.temp.text + '"' + ' is used in ' + '"' +app.term.sentence + '".');
                        //}


                        /* taxa */
                        //if (app.term.taxa == null) {
                        //    alert('Please enter applicable taxa for the term.');
                        //} else {
                            sessionStorage.setItem('taxa', app.term.taxa);
                            var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Saved Applicable Taxa',
                                'action_details': 'term='+app.$route.params.term +': taxa=' +app.term.taxa,
                                'abnormal_system_response': null,
                                'type': 'Wizard'
                            };
                            //if (app.username == null) {
                            //    alert('Please insert the username on homepage');
                            //} else {
                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            //}
                            app.status = 1;
                            app.summary.push('"' + app.temp.text + '"' + ' is applicable to ' + '"' +app.term.taxa + '".');
                        }
                        
                        break;
                    case 1: //anatomical vs. character
                        sessionStorage.setItem('represent', app.term.represent);
                        if (app.term.represent == null) {
                            alert("Please select one of the radio buttons before Save.")
                            var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Attempt to Save Term Type (structure vs character)',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': "please select one of the two radio buttons before Save",
                                'type': 'Wizard'
                            };
                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                        } else {
                            if (app.term.represent == 'anatomical') {
                                app.treeData.push(app.largeDataEntity);
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Saved term type',
                                    'action_details': 'term ' +app.$route.params.term+' is an anatomical structure',
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                };
                                //if (app.username == null) {
                                //    alert('Please insert the username on homepage');
                                //} else {
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                        .then(function(resp) {
                                            console.log("activity-log resp", resp);
                                        })
                                        .catch(function(resp) {
                                            console.log("activity-log error resp", resp);
                                        });
                                //}
                                app.status = 2;
                                app.summary.push('"' + app.temp.text + '"' + " is an anatomical structure.");
                            } else {
                                app.treeData.push(app.largeDataQuality);
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Saved term type',
                                    'action_details': 'term '+app.$route.params.term+' is a character',
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                };
                               // if (app.username == null) {
                               //     alert('Please insert the username on homepage');
                               // } else {
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                        .then(function(resp) {
                                            console.log("activity-log resp", resp);
                                        })
                                        .catch(function(resp) {
                                            console.log("activity-log error resp", resp);
                                        });
                                //}
                                app.status = -1;
                                app.summary.push('"' + app.temp.text + '"' + " is a character.");
                            }
                        }
                        break;
                    case 2: //synonym vs. class for structure
                        app.parentNode = app.$refs.tree.find('anatomical structure', true);
                        sessionStorage.setItem('synonym', app.term.synonym);
                        if (app.term.synonym == null) {
                            alert("Please select 'Yes' or 'No' before clicking the Save button");
                            var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Attempt to Save Synonym vs Class Decision for structure term',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': "please select Yes/No before Save",
                                'type': 'Wizard'
                            };
                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                        } else {
                            app.synonyms = app.$refs.tree.find({
                                state: { checked: true }
                                }, true);
                            if (app.synonyms == null) {
                                alert('Please select the relevant nodes in the tree before clicking Save button.');
                                var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Attempt to Save Synonym vs Class Decision for structure term',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': "please select nodes in the tree before Save",
                                'type': 'Wizard'
                                };
                                 axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            } else {
                                app.modalShowFlag = true;

                                 var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Clicked Save for Synonym vs Class Decision for structure term',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': null,
                                'type': 'Wizard'
                                };
                                 axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            }

                        }
                        break;
                    case -1: //synonym vs. class for quality term
                        sessionStorage.setItem('synonym', app.term.synonym);
                        if (app.term.synonym == null) {
                            alert("Please select 'Yes' or 'No' and perform required action before clicking the Save button");
                            var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Attempt to Save Synonym vs Class Decision for character term',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': "please select Yes/No before Save",
                                'type': 'Wizard'
                            };
                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                        } else {
                            app.synonyms = app.$refs.tree.find({
                                state: {
                                    checked: true
                                }
                            }, true);

                            if (app.synonyms == null) {
                                alert('Please select relevant nodes in the tree before clicking Save button.');
                                var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Attempt to Save Synonym vs Class Decision for character term',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': "please select nodes in the tree before Save",
                                'type': 'Wizard'
                                };
                                 axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            } else {
                                app.modalShowFlag = true;
                                 var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Clicked Save for Synonym vs Class Decision for character term',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': null,
                                'type': 'Wizard'
                                };
                                 axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            }
                        }
                        break;
                    case 3: //part of
                        sessionStorage.setItem('instance', app.term.instance);
                        if (app.term.instance == null) {
                            alert("Please select radio button and perform required action before clicking 'Save' button");
                            var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Attempt to Save Part-Of Decision',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': "please select radio button before Save",
                                'type': 'Wizard'
                                };
                                 axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                        } else {
                            if (app.term.instance == 'yes') {
                                app.instances = app.$refs.tree.find({
                                    state: { checked: true }
                                }, true);
                                console.log('selected instances', app.instances);
                                if (app.instances == null) {
                                    alert('Please select relevant nodes in the tree before clicking Save button.');
                                    var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Attempt to Save Part-Of Decision',
                                    'action_details': 'term='+app.$route.params.term,
                                    'abnormal_system_response': "please select nodes in the tree before Save",
                                    'type': 'Wizard'
                                    };
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                                } else {
                                    app.modalShowFlag = true;
                                     var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Clicked Save for Part-Of Decision',
                                    'action_details': 'term='+app.$route.params.term,
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                    };
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                                }
                               
                            } else if (app.term.instance == 'no') {
                                app.status = 4;
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Clicked Save for Part-Of Decision',
                                    'action_details': 'no part-of relations for term='+app.$route.params.term,
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                    };
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            } else if (app.term.instance == 'no-user') {
                                app.instances = app.userInstances;
                                var hasTerms = true;
                                
                                //constract user supplied list
                                app.list = "";
                                for (var i = 0; i < app.userInstances.length; i++) {
                                    if(app.userInstances[i].term!=null && app.userInstances[i].term.trim().length > 0){
                                        app.list += app.userInstances[i].term+" | ";
                                    }else{
                                        hasTerms = false;
                                    }
                                }
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'user supply',
                                    //'action_details': 'User supplied ' + app.userInstances[app.userInstances.length - 1].term + ' for term',
                                    'action_details': 'User supplied parent structure terms:' + app.list + ' for term',
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
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
                                if(hasTerms) app.modalShowFlag = true; //hong 7/16/19
                                else alert("Please fill out parent structure terms and definitions");
                            }

                        }
                        break;
                    case 4://has part
                        sessionStorage.setItem('hasPart', app.term.hasPart);
                        if (app.term.hasPart == null) {
                            alert("Please select radio button and perform required action before clicking 'Save' button");
                              var jsonRequest = {
                                'user_email': app.$route.params.user,
                                'action': 'Attempt to Save Has-Part Decision',
                                'action_details': 'term='+app.$route.params.term,
                                'abnormal_system_response': "please select radio button before Save",
                                'type': 'Wizard'
                                };
                                 axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                        } else {
                            if (app.term.hasPart == 'yes') {
                                app.hasParts = app.$refs.tree.find({
                                    state: { checked: true }
                                }, true);

                                if (app.hasParts == null) {
                                    alert('Please select the nodes before clicking Save button.');
                                    var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Attempt to Save Has-Part Decision',
                                    'action_details': 'term='+app.$route.params.term,
                                    'abnormal_system_response': "please select nodes in the tree before Save",
                                    'type': 'Wizard'
                                    };
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                                } else {
                                    app.modalShowFlag = true;
                                    var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Clicked Save for Has-Part Decision',
                                    'action_details': 'term='+app.$route.params.term,
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                    };
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                                }

                            } else if (app.term.hasPart == 'no') {
                                 var jsonSaveRequest = {
                                  "user": shared? "" : app.$route.params.user,
                                   "ontology": app.$route.params.ontology,
                                    };
                                axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                    .then(function(resp) {
                                        console.log('save resp', resp);
                                    })
                                    .catch(function(resp) {
                                        console.log('save error resp', resp);
                                    });

                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Clicked Save for Has-Part Decision',
                                    'action_details': 'no has-part relations for term='+app.$route.params.term,
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                    };
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });    
                                    
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Summary loaded',
                                    'action_details': 'Summary Page was loaded for term',
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
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
                                app.status = 5;
                            } else if (app.term.hasPart == 'no-user') {
                                app.hasParts = app.userHasParts;
                                var hasTerms = true;
                               
                                app.list = "";
                                for (var i = 0; i < app.userHasParts.length; i++) {
                                    if(app.userHasParts[i].term !=null && app.userHasParts[i].term.trim().length > 0){
                                        app.list += app.userHasParts[i].term + " | ";
                                    }else{
                                        hasTerms = false;
                                    }
                                }
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'user supply',
                                    //'action_details': 'User supplied ' + app.userHasParts[app.userHasParts.length - 1].term + ' for term',
                                    'action_details': 'User supplied component terms: ' + app.list + ' for term',
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                };
                               // if (app.username == null || app.username == 'null' || app.username == '') {
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
                                if(hasTerms) app.modalShowFlag = true; //hong 7/16/19
                                else alert("Please fill out component terms and definitions");
                            }
                        }
                        break;
                    default:
                        break;
                }
            },
            collapseAndUncheck: function() {
                var app = this;
                var temp = app.$refs.tree.findAll({
                    state: { checked: true }
                }, true);
                if(temp!=null) temp.uncheck(); //reset all nodes to uncheck
                console.log('parentNode', app.parentNode);
                app.parentNode.collapse();
            },

            confirmSave: function(status) {
                var app = this;
                app.modalShowFlag = false;

                //alert("status="+status + " app.term.hasPart="+app.term.hasPart);

                switch (status) {
                    case 2: //synonym vs. class on structure term
                        var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Confirmed synonym vs. class',
                        'action_details': 'confirmed synonym vs. class decision on structure term='+app.$route.params.term,
                        'abnormal_system_response': null,
                        'type': 'Wizard'
                            };
                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });

                        if (app.term.synonym == 'yes') { //yes, it's a synonym

                            if (app.synonyms.length == 1) {
                                var jsonRequest = {
                                 "user": shared? "" : app.$route.params.user,
                                     "ontology": app.$route.params.ontology,
                                    "term": app.temp.text,
                                    "classIRI": app.synonyms[0].data.details[0].IRI
                                };
                                axios.post("http://shark.sbs.arizona.edu:8080/esynonym", jsonRequest)
                                    .then(function(resp) {
                                        console.log("synonym resp", resp);
                                        if (resp.data == "SUCCESSFULLY" || resp.data == "NO_OPERATION") {
                                            app.synonyms[0].data.details[0].exact_synonym = app.temp.text;
                                            app.summary.push('"' + app.temp.text + '"' + " is a synonym of " + '"' + app.synonyms[0].text + '".');
                                            app.treeData = app.$refs.tree.model;
                                            console.log("tree after updated", app.$refs.tree);
                                            var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Set Synonym',
                                                'action_details': app.synonyms[0].text  + ' is set as synonym for term',
                                                'abnormal_system_response': null,
                                                'type': 'Wizard'
                                            };
                                            //if (app.username == null || app.username == 'null' || app.username == '') {
                                            //    alert('Please insert the username on homepage');
                                            //} else {
                                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                    .then(function(resp) {
                                                        console.log("activity-log resp", resp);
                                                        //app.modalShowFlag = false;
                                                        //app.collapseAndUncheck();
                                                        //app.status = 3;

                                                    })
                                                    .catch(function(resp) {
                                                        console.log("activity-log error resp", resp);
                                                    });
                                                app.modalShowFlag = false; //hong moved the three lines here
                                                app.collapseAndUncheck();
                                                app.status = 3;
                                                  // }
                                        } else {
                                            app.collapseAndUncheck();
                                            app.status = 3;
                                        }

                                    })
                                    .catch(function(resp) {
                                        console.log("synonym error", resp);
                                        //hong add log for /esynonym error
                                        var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Set Synonym',
                                                'action_details': 'Attempted to make the term esysnonym of '+app.synonyms[0].text,
                                                'abnormal_system_response': "ontology /esynonym error at "+ app.temp.text+ " is esynonym to " +app.synonyms[0].text,
                                                'type': 'Wizard'
                                            };
                                            //if (app.username == null || app.username == 'null' || app.username == '') {
                                            //    alert('Please insert the username on homepage');
                                            //} else {
                                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                            .then(function(resp) {
                                                console.log("activity-log resp", resp);
                                            })
                                            .catch(function(resp) {
                                                console.log("activity-log error resp", resp);
                                            });
                                    });
                            } else {//app.synonyms.length > 1
                                app.callLoopApi(0, 'bsynonym', null, null, null, null, status);
                                app.collapseAndUncheck();

                            }


                        } else if (app.term.synonym == 'no') { //it is class
                            app.callLoopApi(0, 'class', null, null, null, null, status);
                            app.collapseAndUncheck();
                        }
                        break;
                    case -1: //synonym vs. class on character term
                        var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Confirmed synonym vs.class',
                        'action_details': 'confirmed synonym vs. class decision on character term='+app.$route.params.term,
                        'abnormal_system_response': null,
                        'type': 'Wizard'
                            };
                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });


                        if (app.term.synonym == 'yes') { //is a synonym
                            if (app.synonyms.length == 1) {
                                var jsonRequest = {
                                  "user":shared? "" :app.$route.params.user,
                                     "ontology": app.$route.params.ontology,
                                    "term": app.temp.text,
                                    "classIRI": app.synonyms[0].data.details[0].IRI
                                };
                                axios.post("http://shark.sbs.arizona.edu:8080/esynonym", jsonRequest)
                                    .then(function(resp) {
                                        console.log("synonym resp", resp);
                                        if (resp.data == "SUCCESSFULLY" || resp.data == "NO_OPERATION") {
                                            app.synonyms[0].data.details[0].exact_synonym = app.temp.text;
                                            app.summary.push('"' + app.temp.text + '"' + " is a synonym of " + '"' + app.synonyms[0].text + '".');
                                            app.treeData = app.$refs.tree.model;
                                            console.log("tree after updated", app.$refs.tree);
                                            var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Set Synonym',
                                                'action_details': app.synonyms[0].text  + ' is set as synonym for term',
                                                'abnormal_system_response': null,
                                                'type': 'Wizard'
                                            };
                                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                .then(function(resp) {
                                                    console.log("activity-log resp", resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log("activity-log error resp", resp);
                                                });
                                        }
                                        app.modalShowFlag = false;
                                        app.status = 5;
                                    })
                                    .catch(function(resp) {
                                        console.log("synonym error", resp);
                                        //hong: log /esynonym error
                                        var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Set Synonym',
                                                'action_details': 'Attempted to make the term esynonym of '+app.synonyms[0].text,
                                                'abnormal_system_response': "ontology /esynonym error:" + app.temp.text+" esynonym of "+ app.synonyms[0].text,
                                                'type': 'Wizard'
                                            };
                                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                .then(function(resp) {
                                                    console.log("activity-log resp", resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log("activity-log error resp", resp);
                                                });
                                    });
                                    
                            } else {
                                app.callLoopApi(0, 'bsynonym', null, null, null, null, status);
                            }

                        } else if (app.term.synonym == 'no') {
                            //alert("calling callLoopApi to create classes for character term")
                            app.callLoopApi(0, 'class', null, null, null, null, status);

                        }
                        break;
                    case 3: //part of
                        var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Confirmed part-of',
                        'action_details': 'confirmed part-of decision on term='+app.$route.params.term,
                        'abnormal_system_response': null,
                        'type': 'Wizard'
                            };
                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });

                        if (app.term.instance == 'yes') { //parent structures in the tree
                            app.TTBA = app.$refs.tree.find(app.temp.text, true); //text is search criteria; true => multiple true
                            //app.temp.text is the term parameter passed into the app. 
                            console.log("TTBA", app.TTBA); 
                            if (app.TTBA == null) {//term not found in the tree
                                for (var i = 0; i < app.synonyms.length; i++) {
                                    app.tempIndex = i;
                                    if (!app.synonyms[i].data.details[0].partOf) {
                                        //initialize partOf for the term
                                        app.synonyms[i].data.details[0].partOf = [

                                        ];
                                    }

                                    app.callLoopApi(0, 'partOf', 'synonym', i, null, null, status); //loop through optionIndex i, classes TTBA is a bsynonym to.
                                    app.collapseAndUncheck();
                                }

                            } else if (app.TTBA.length > 0) {//term found in the tree
                                for (var i = 0; i < app.TTBA.length; i++) {
                                    app.tempIndex = i;

                                    if (!app.TTBA[i].data.details[0].partOf) {
                                        //initialize partOf for the matched node
                                        app.TTBA[i].data.details[0].partOf = [

                                        ];
                                    }
                                    app.callLoopApi(0, 'partOf', 'TTBA', i, null, null, status); //loop through optionIndex i, class of the TTBA (should be one, but this code treated it as multiple copies of one thing, if there are multiple superclasses)
                                    app.collapseAndUncheck();
                                }

                            }
                        } else if (app.term.instance == 'no-user') { 
                            var superClass = app.$refs.tree.find({
                                text: "anatomical structure"
                            })[0];
                            app.TTBA = app.$refs.tree.find(app.temp.text, true);
                            for (var i = 0; i < app.userInstances.length; i++) {

                                if(app.userInstances[i].term==null || app.userInstances[i].definition==null || app.userInstances[i].term.trim().length <= 0 || app.userInstances[i].definition.trim().length <= 0){
                                    alert("Please enter term and definition, or remove empty rows");
                                } 
                                                                
                                var jsonClassRequest = {
                                "user":shared? "" :app.$route.params.user,
                                    "ontology": app.$route.params.ontology,
                                    "term": app.userInstances[i].term,
                                    "superclassIRI": superClass.data.details[0].IRI,
                                    "definition": app.userInstances[i].definition,
                                    "elucidation": null,
                                    "createdBy": app.$route.params.user +" via add2ontology wizard",
                                    "creationDate": new Date(),
                                    "definitionSrc": app.$route.params.user,
                                    "examples": "tba",
                                    "logicDefinition": null,
                                };
                                //app.tempIndex = app.userInstances[i].term; //hong 3/12, app.tempIndex secretly change value
                                
                                axios.post("http://shark.sbs.arizona.edu:8080/class", jsonClassRequest)
                                    .then(function(resp) {
                                        console.log("class resp", resp);
                                        var userTermIRI = resp.data;
                                        if (resp.data == 'UNSUCCESSFULLY' || resp.data == 'NO_OPERATION') {
                                            //Hong: log /class error
                                            userTermIRI = "http://example.org/terms#"+userTermIRI.split('#')[1]; //must use #
                                        
                                            var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Add Class',
                                                'action_details': 'Attempted to make '+userTermIRI.split('#')[1]  + ' subclass of ' + superClass.text,
                                                'abnormal_system_response': "ontology /class error: "+userTermIRI.split('#')[1]  + ' subclass of ' + superClass.text,
                                                'type': 'Wizard'
                                            };
                                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                .then(function(resp) {
                                                    console.log("activity-log resp", resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log("activity-log error resp", resp);
                                                });
                                        } 
                                        var jsonRequest = {
                                            'user_email': app.$route.params.user,
                                            'action': 'Add Class',
                                            'action_details': userTermIRI.split('#')[1]  + ' is added as subclass of ' + superClass.text +' for term',
                                            'abnormal_system_response': null,
                                            'type': 'Wizard'
                                        };
                                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                            .then(function(resp) {
                                                console.log("activity-log resp", resp);
                                            })
                                            .catch(function(resp) {
                                                console.log("activity-log error resp", resp);
                                            });

                                        if (app.TTBA == null) {
                                            //app.callLoopApi(0, 'partOf', 'no-synonym', null, resp.data, app.tempIndex, app.status);
                                            app.callLoopApi(0, 'partOf', 'no-synonym', null, userTermIRI, userTermIRI.split('#')[1], app.status);
                                            app.collapseAndUncheck();
                                        } else if (app.TTBA.length > 0) {
                                            //app.callLoopApi(0, 'partOf', 'no-TTBA', null, resp.data, app.tempIndex, app.status);
                                            //alert("!!should be the same: userTermIRI="+userTermIRI +" userTermIRI.split('#')[1]="+userTermIRI.split('#')[1]);
                                            app.callLoopApi(0, 'partOf', 'no-TTBA', null, userTermIRI, userTermIRI.split('#')[1], app.status);
                                            app.collapseAndUncheck();
                                        }
                                        
                                    })
                                    .catch(function(resp) {
                                        console.log("class error", resp);
                                    });
                                
                               
                            }
                            //app.status = 4; //hong 3/12
                        }
                        break;

                    case 4: //has_part
                         var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Confirmed has-part',
                        'action_details': 'confirmed has-part decision on term='+app.$route.params.term,
                        'abnormal_system_response': null,
                        'type': 'Wizard'
                            };
                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });

                        if (app.term.hasPart == 'yes') {//component terms in the tree
                            app.TTBA = app.$refs.tree.find(app.temp.text, true);
                            console.log("TTBA", app.TTBA);
                            if (app.TTBA == null) {
                                for (var i = 0; i < app.synonyms.length; i++) {
                                    app.tempIndex = i;
                                    if (!app.synonyms[i].data.details[0].hasPart) {
                                        app.synonyms[i].data.details[0].hasPart = [

                                        ];
                                    }

                                    app.callLoopApi(0, 'hasPart', 'synonym', i, null, null, status);
                                }
                            } else if (app.TTBA.length > 0) {
                                for (var i = 0; i < app.TTBA.length; i++) {
                                    app.tempIndex = i;
                                    if (!app.TTBA[i].data.details[0].hasPart) {
                                        app.TTBA[i].data.details[0].hasPart = [

                                        ];
                                    }

                                    app.callLoopApi(0, 'hasPart', 'TTBA', i, null, null, status);


                                }

                            }
                            //app.status = 5;  //hong 3/12
                        } else if (app.term.hasPart == 'no-user') { //user supplies component terms #######################3
                            var superClass = app.$refs.tree.find({
                                text: "anatomical structure"
                            })[0];
                            app.TTBA = app.$refs.tree.find(app.temp.text, true);
                            for (var i = 0; i < app.userHasParts.length; i++) {
                                  
                                if(app.userHasParts[i].term == null || app.userHasParts[i].definition == null || app.userHasParts[i].term.trim().length <= 0 || app.userHasParts[i].definition.trim().length <= 0){
                                   alert("Please enter term and definition, or remove empty rows.");
                                } 
                              
                                //app.tempIndex = app.userHasParts[i].term; //hong 3/12
                                var jsonClassRequest = {
                                "user":shared? "" :app.$route.params.user,
                                "ontology": app.$route.params.ontology,
                                "term": app.userHasParts[i].term,
                                "superclassIRI": superClass.data.details[0].IRI,
                                "definition": app.userHasParts[i].definition,
                                "elucidation": null,
                                "createdBy": app.$route.params.user +" via add2ontology wizard",
                                "creationDate": new Date(),
                                "definitionSrc": app.$route.params.user,
                                "examples":  "tba",
                                "logicDefinition": null,
                                };
                                axios.post("http://shark.sbs.arizona.edu:8080/class", jsonClassRequest)
                                .then(function(resp) {
                                    console.log("class resp", resp);
                                    var userTermIRI = resp.data;
                                    if (resp.data == 'UNSUCCESSFULLY' || resp.data == 'NO_OPERATION') {
                                        //hong: log unsuccessfully error
                                        userTermIRI = "http://example.org/terms#"+userTermIRI.split('#')[1]; //must use #
                                    
                                        var jsonRequest = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add Class',
                                        'action_details': 'Attempted to make '+ userTermIRI.split('#')[1] + ' subclass of ' + superClass.text,
                                        'abnormal_system_response': "ontology /class error: "+ userTermIRI.split('#')[1] + ' subclass of ' + superClass.text,
                                        'type': 'Wizard'
                                        };
                                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                            .then(function(resp) {
                                                console.log("activity-log resp", resp);
                                            })
                                            .catch(function(resp) {
                                                console.log("activity-log error resp", resp);
                                            });
                                    
                                    } 
                                    var jsonRequest = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add Class',
                                        'action_details': userTermIRI.split('#')[1] + ' is added as subclass of ' + superClass.text +' for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                        .then(function(resp) {
                                            console.log("activity-log resp", resp);
                                        })
                                        .catch(function(resp) {
                                            console.log("activity-log error resp", resp);
                                        });
                                    if (app.TTBA == null) {
                                        //alert("resp.data="+resp.data + " userTermIRI.split('#')[1]="+userTermIRI.split('#')[1]);
                                        //app.callLoopApi(0, 'hasPart', 'no-synonym', null, resp.data, app.tempIndex, app.status);
                                        app.callLoopApi(0, 'hasPart', 'no-synonym', null, userTermIRI, userTermIRI.split('#')[1], app.status);
                                    } else if (app.TTBA.length > 0) {
                                        //alert("!!should be the same: userTermIRI="+userTermIRI +" userTermIRI.split('#')[1]="+userTermIRI.split('#')[1]);
                                        //app.callLoopApi(0, 'hasPart', 'no-TTBA', null, resp.data, app.tempIndex, app.status);
                                        app.callLoopApi(0, 'hasPart', 'no-TTBA', null, userTermIRI, userTermIRI.split('#')[1], app.status);

                                    }
                                    
                                })

                                .catch(function(resp) {
                                    console.log("class error", resp);
                                    //Hong log /class error
                                    var jsonRequest = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add Class',
                                        'action_details': "Attempted to make "+userTermIRI.split('#')[1] + ' subclass of ' + superClass.text,
                                        'abnormal_system_response': "ontology /class error: "+userTermIRI.split('#')[1] + ' subclass of ' + superClass.text,
                                        'type': 'Wizard'
                                    };
                                        //if (app.username == null || app.username == 'null' || app.username == '') {
                                        //    alert('Please insert the username on homepage');
                                        //} else {
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                        .then(function(resp) {
                                            console.log("activity-log resp", resp);
                                        })
                                        .catch(function(resp) {
                                            console.log("activity-log error resp", resp);
                                        });
                                });
                                    
                               
                            }
                            //app.status = 5; //hong 3/12
                        }

                        break;
                    default:
                        break;
                }


            },
            //optionData = IRI, optionData2 = label, both are user supplied terms. Depending on 'key', they may be parent or component terms
            callLoopApi: function(index, key, setting = null, optionIndex = null, optionData = null, optionData2 = null, status = null) {
                var app = this;
                var jsonRequest;


                //alert("index="+index +" key="+key+ " setting="+setting +" optionIndex="+optionIndex +" optionData="+optionData+" optionData2="+optionData2+ " status="+status);
                
                /*if(app.instances[index]!=null){
                    //alert("app.instances[index].text="+app.instances[index].text);
                }
                if(app.synonyms[index]!=null){
                    //alert("app.synonyms[index].text="+app.synonyms[index].text);
                }*/
                
                //create jsonRequest ontology calls
                if (key == 'bsynonym') {
                    jsonRequest = {
                      "user":shared? "" :app.$route.params.user,
                         "ontology": app.$route.params.ontology,
                        "term": app.temp.text,
                        "classIRI": app.synonyms[index].data.details[0].IRI //?index=0, dealing with the first bsynonym only?
                    };
                } else if (key == 'class') {
                    jsonRequest = {
                      "user":shared? "" :app.$route.params.user,
                         "ontology": app.$route.params.ontology,
                        "term": app.temp.text,
                        "superclassIRI": app.synonyms[index].data.details[0].IRI, //? index=0, dealing with the first class only
                        "definition": app.term.definition,
                        "elucidation": null,
                        "createdBy": app.$route.params.user +" via add2ontology wizard",
                        "creationDate": new Date(),
                        "definitionSrc":  app.$route.params.user,
                        "examples":  app.term.sentence + '[' + app.term.taxa + ']',
                        "logicDefinition": null,
                    };
                } else if (key == 'partOf' || key == 'hasPart') {

                    if (setting == 'synonym') {
                        if (key == 'partOf') {
                            jsonRequest = {
                              "user":shared? "" :app.$route.params.user,
                                 "ontology": app.$route.params.ontology,
                                "bearerIRI": app.instances[index].data.details[0].IRI,
                                "partIRI": app.synonyms[optionIndex].data.details[0].IRI 
                                //? check: synonyms hold the equ class of ttba as a syn, or the newly added ttba class
                                //?what is optionIndex?
                            };
                        } else { //hasPart
                            jsonRequest = {
                              "user":shared? "" :app.$route.params.user,
                                 "ontology": app.$route.params.ontology,
                                "bearerIRI": app.synonyms[optionIndex].data.details[0].IRI,
                                "partIRI": app.hasParts[index].data.details[0].IRI
                            };
                        }
                    } else if (setting == 'TTBA') {
                        if (key == 'partOf') {
                            jsonRequest = {
                              "user":shared? "" :app.$route.params.user,
                                 "ontology": app.$route.params.ontology,
                                "bearerIRI": app.instances[index].data.details[0].IRI, 
                                "partIRI": app.TTBA[optionIndex].data.details[0].IRI //?TTBA size = 1
                            };
                        } else {
                            jsonRequest = {
                              "user":shared? "" :app.$route.params.user,
                                 "ontology": app.$route.params.ontology,
                                "bearerIRI": app.TTBA[optionIndex].data.details[0].IRI,
                                "partIRI": app.hasParts[index].data.details[0].IRI
                            };
                        }

                    } else if (setting == 'no-synonym') {

                        if (key == 'partOf') { 
                            jsonRequest = {
                              "user":shared? "" :app.$route.params.user,
                                  "ontology": app.$route.params.ontology,
                                "bearerIRI": optionData, 
                                "partIRI": app.synonyms[index].data.details[0].IRI
                            };
                            if (!app.synonyms[index].data.details[0].partOf) {
                                app.synonyms[index].data.details[0].partOf = []; //?
                            }
                        } else { //hasPart
                            jsonRequest = {
                              "user":shared? "" :app.$route.params.user,
                                 "ontology": app.$route.params.ontology,
                                "bearerIRI": app.synonyms[index].data.details[0].IRI,
                                "partIRI": optionData 
                            };
                            if (!app.synonyms[index].data.details[0].hasPart) {
                                app.synonyms[index].data.details[0].hasPart = [];
                            }
                        }

                    } else if (setting == 'no-TTBA') {

                        if (key == 'partOf') {
                            jsonRequest = {
                              "user":shared? "" :app.$route.params.user,
                                     "ontology": app.$route.params.ontology,
                                "bearerIRI": optionData,
                                "partIRI": app.TTBA[index].data.details[0].IRI
                            };
                            if (!app.TTBA[index].data.details[0].partOf) {
                                app.TTBA[index].data.details[0].partOf = [];
                            }
                        } else {//hasPart
                            jsonRequest = {
                              "user":shared? "" :app.$route.params.user,
                                     "ontology": app.$route.params.ontology,
                                "bearerIRI": app.TTBA[index].data.details[0].IRI,
                                "partIRI": optionData
                            };
                            if (!app.TTBA[index].data.details[0].hasPart) {
                                app.TTBA[index].data.details[0].hasPart = [];
                            }
                        }

                    }

                }

                //call ontology api
                axios.post("http://shark.sbs.arizona.edu:8080/" + key, jsonRequest)
                    .then(function(resp) {
                        //alert("called api key="+key +" resp.data ="+resp.data + " superclass="+app.synonyms[index].data.details[0].IRI);
                        console.log(key + " resp", resp);

                        //if (resp.data == 'NO_OPERATION' || resp.data == 'UNSUCCESSFULLY') { //?why UNSUCCESSFULLY?
                        if (resp.data == 'NO_OPERATION' || resp.data == 'SUCCESSFULLY' || resp.data.indexOf("http")==0) { //?why UNSUCCESSFULLY?
                            if(key !="hasPart" && key != "partOf"){//this throws error for key=hasPart, hong commented it out for the moment
                                app.treeData = app.$refs.tree.model; //??
                                console.log("tree after updated", app.treeData);
                            }
                           /* alert("call api before status="+app.status);
                            if (status == -1) {
                                app.modalShowFlag = false;
                                app.status = 5;
                            } else{ // if(status < 5) { //hong ??
                                app.modalShowFlag = false;
                                app.status = status + 1;
                            }
                            alert("call api after status="+app.status);
                            if (app.status == 5) {
                                 alert("called save api");
                                  var jsonSaveRequest = {
                                  "user":shared? "" :app.$route.params.user,
                                   "ontology": app.$route.params.ontology,
                                    };
                                axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                    .then(function(resp) {
                                        console.log('save resp', resp);
                                    })
                                    .catch(function(resp) {
                                        console.log('save error resp', resp);
                                    });
                                alert("entered summary loaded in log");
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Summary loaded',
                                    'action_details': 'Summary Page was loaded for term',
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                };
                                // if (app.username == null || app.username == 'null' || app.username == '') {
                                 //   alert('Please insert the username on homepage.');
                                // } else {
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                        .then(function(resp) {
                                            console.log("activity-log resp", resp);
                                        })
                                        .catch(function(resp) {
                                            console.log("activity-log error resp", resp);
                                        });
                                // }
                            }*/

                        //} else { //?api call not successful
                            //log activities
                            //alert("log activity for key="+key);
                            if (key == 'bsynonym') {
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Set Synonym',
                                    'action_details': app.synonyms[index].text  + ' is set as synonym for term',
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                };
                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            } else if (key == 'class') {
                                //alert("added "+app.temp.text  + ' is added as subclass of ' + app.synonyms[index].text +'in log');
                                var jsonRequest = {
                                    'user_email': app.$route.params.user,
                                    'action': 'Add Class',
                                    'action_details': app.temp.text  + ' is added as subclass of ' + app.synonyms[index].text +' for term',
                                    'abnormal_system_response': null,
                                    'type': 'Wizard'
                                };
                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            } else if (key == 'partOf') {
                                var jsonLog = {};
                                if (setting == 'synonym') {//setting synonym, different from key bsynonym 
                                    jsonLog = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add part_of',
                                        'action_details': app.synonyms[optionIndex].text+" is part of "+app.instances[index].text  + ' is added for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };

                                } else if (setting == 'TTBA') {
                                    jsonLog = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add part_of',
                                        'action_details': app.TTBA[optionIndex].text +" is part of "+app.instances[index].text  + ' is added for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };
                                } else if (setting == 'no-synonym') {
                                    jsonLog = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add part_of',
                                        'action_details': app.synonyms[index].text + ' is added as part_of of ' + optionData2 +' for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };
                                } else if (setting == 'no-TTBA') {
                                    jsonLog = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add part_of',
                                        'action_details': app.TTBA[index].text + ' is added as part_of of ' + optionData2 +' for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };
                                }
                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonLog)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            } else if (key == 'hasPart') {
                                var jsonLog = {};
                                if (setting == 'synonym') { 
                                    jsonLog = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add has_part',
                                        //'action_details': app.instances[index].text  + ' is added as has_part of ' + app.synonyms[optionIndex].text +' for term',
                                        'action_details': app.synonyms[optionIndex].text+ ' has_part '+ app.hasParts[index].text  +' is added for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };

                                } else if (setting == 'TTBA') {
                                    //alert("log key="+key +" optionIndex="+optionIndex +" index="+index+" : ");
                                    //alert("log key="+key +": "+app.TTBA[optionIndex].text   + ' has_part ' + app.hasParts[index].text)
                                    jsonLog = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add has_part',
                                        //'action_details': app.instances[index].text  + ' is added as has_part of ' + app.TTBA[optionIndex].text +' for term',
                                        'action_details': app.TTBA[optionIndex].text   + ' has_part ' + app.hasParts[index].text +' is added for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };
                                } else if (setting == 'no-synonym') {
                                    jsonLog = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add has_part',
                                        'action_details': app.synonyms[index].text + ' has_part ' + optionData2 +' is added for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };
                                } else if (setting == 'no-TTBA') {
                                    jsonLog = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add has_part',
                                        'action_details': app.TTBA[index].text + ' has_part ' + optionData2 +' is added for term',
                                        'abnormal_system_response': null,
                                        'type': 'Wizard'
                                    };
                                }
                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonLog)
                                    .then(function(resp) {
                                        console.log("activity-log resp", resp);
                                    })
                                    .catch(function(resp) {
                                        console.log("activity-log error resp", resp);
                                    });
                            }

                            //update summary and process further indexes if any
                            //alert("process further indexes");
                            if (key == 'class') {
                                app.temp.data.details[0].IRI = resp.data; //set class IRI for ttba
                                app.synonyms[index].append(app.temp);
                                console.log("app.temp", app.temp);
                                console.log("app.synonyms", app.synonyms);
                                //for(var i = 0; i < app.synonyms.length; i++){
                                //    alert("after class added, app.synonyms[i].text="+app.synonyms[i].text);
                                //}
                                //app.synonyms[index].append("another term"); //tree.model is updated this way, another term included in the model
                                app.summary.push('"' + app.temp.text + '"' + " is a subclass of " + '"' + app.synonyms[index].text + '".');

                                app.treeData = app.$refs.tree.model; 
                                console.log("tree after updated", app.treeData);
                                if (index < app.synonyms.length - 1) { //next index
                                    //alert("callLoopApi with index="+ (index+1));
                                    app.callLoopApi(index + 1, key, setting, optionIndex, optionData, optionData2, status);
                                } else {
                                   
                                    app.modalShowFlag = false;
                                    //alert("end of class list, before status="+app.status)
                                    if (status == -1) {
                                        app.status = 5;
                                        //alert("status=5, log summery and save ontology");
                                        /* hong 3/12
                                        var jsonSaveRequest = {
                                            "user":shared? "" :app.$route.params.user,
                                            "ontology": app.$route.params.ontology,
                                        };
                                         axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                            .then(function(resp) {
                                                console.log('save resp', resp);
                                            })
                                            .catch(function(resp) {
                                                console.log('save error resp', resp);
                                            });
                                        var jsonRequest = {
                                            'user_email': app.$route.params.user,
                                            'action': 'Summary loaded',
                                            'action_details': 'Summary Page was loaded for term',
                                            'abnormal_system_response': null,
                                            'type': 'Wizard'
                                        };
                                       // if (app.username == null || app.username == 'null' || app.username == '') {
                                       //     alert('Please insert the username on homepage.');
                                       // } else {
                                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                .then(function(resp) {
                                                    console.log("activity-log resp", resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log("activity-log error resp", resp);
                                                });*/
                                        //}
                                    } else {
                                        //alert("increase status by 1")
                                        app.status = status + 1;
                                    }
                                    //alert("end of class list, after status="+app.status)
                                    //app.treeData = app.$refs.tree.model;
                                    //hong 3/12
                                    if(app.status == 5){
                                        var jsonSaveRequest = {
                                                    "user":shared? "" :app.$route.params.user,
                                                    "ontology": app.$route.params.ontology,
                                                };
                                                axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                                .then(function(resp) {
                                                    console.log('save resp', resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log('save error resp', resp);
                                                });
                                                
                                                var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Summary loaded',
                                                'action_details': 'Summary Page was loaded for term',
                                                'abnormal_system_response': null,
                                                'type': 'Wizard'
                                                 };
                                                    // if (app.username == null || app.username == 'null' || app.username == '') {
                                                    //     alert('Please insert the username on homepage.');
                                                    // } else {
                                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                    .then(function(resp) {
                                                        console.log("activity-log resp", resp);
                                                    })
                                                    .catch(function(resp) {
                                                        console.log("activity-log error resp", resp);
                                                });
                                    }
                                }
                            } else if (key == 'partOf' || key == 'hasPart') {
                                if (setting == 'synonym') {
                                    if (key == 'partOf') {
                                        app.synonyms[optionIndex].data.details[0][key].push(
                                            app.instances[index].data.details[0].IRI
                                        );
                                        // app.summary.push(app.synonyms[optionIndex].text + "(synonym of " + app.temp.text + ") part_of " + app.instances[index].text + " is added.");
                                        app.summaryPartOf.push(app.instances[index].text);
                                        //alert("synonym:"+app.instances[index].text);
                                        app.treeData = app.$refs.tree.model;
                                        console.log("tree after updated", app.treeData);
                                        if (index < app.instances.length - 1) { //loop through index
                                            app.callLoopApi(index + 1, key, setting, optionIndex, optionData, optionData2, status);
                                        } else {
                                            app.modalShowFlag = false;
                                            if (status == -1) {
                                                app.status = 5;
                                                //hong inserted 
                                                //alert("should not land here: status=5, log summery and save ontology");
                                            
                                                /* hong 3/12
                                                var jsonSaveRequest = {
                                                    "user":shared? "" :app.$route.params.user,
                                                    "ontology": app.$route.params.ontology,
                                                };
                                                axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                                .then(function(resp) {
                                                    console.log('save resp', resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log('save error resp', resp);
                                                });
                                                
                                                var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Summary loaded',
                                                'action_details': 'Summary Page was loaded for term',
                                                'abnormal_system_response': null,
                                                'type': 'Wizard'
                                                 };
                                                    // if (app.username == null || app.username == 'null' || app.username == '') {
                                                    //     alert('Please insert the username on homepage.');
                                                    // } else {
                                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                    .then(function(resp) {
                                                        console.log("activity-log resp", resp);
                                                    })
                                                    .catch(function(resp) {
                                                        console.log("activity-log error resp", resp);
                                                    });*/
                                            } else {
                                                app.status = status + 1;
                                            }
                                            //app.treeData = app.$refs.tree.model;
                                            //Hong 3/12
                                            if(app.status == 5){
                                                var jsonSaveRequest = {
                                                    "user":shared? "" :app.$route.params.user,
                                                    "ontology": app.$route.params.ontology,
                                                };
                                                axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                                .then(function(resp) {
                                                    console.log('save resp', resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log('save error resp', resp);
                                                });
                                                
                                                var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Summary loaded',
                                                'action_details': 'Summary Page was loaded for term',
                                                'abnormal_system_response': null,
                                                'type': 'Wizard'
                                                 };
                                                    // if (app.username == null || app.username == 'null' || app.username == '') {
                                                    //     alert('Please insert the username on homepage.');
                                                    // } else {
                                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                    .then(function(resp) {
                                                        console.log("activity-log resp", resp);
                                                    })
                                                    .catch(function(resp) {
                                                        console.log("activity-log error resp", resp);
                                                    });
                                            }
                                        }
                                    } else { //hasPart
                                        app.synonyms[optionIndex].data.details[0][key].push(
                                            app.hasParts[index].data.details[0].IRI
                                        );
                                        // app.summary.push(app.synonyms[optionIndex].text + "(synonym of " + app.temp.text + ") has_part " + app.hasParts[index].text + " is added.");
                                        app.summaryHasPart.push(app.hasParts[index].text);
                                        app.treeData = app.$refs.tree.model;
                                        console.log("tree after updated", app.treeData);
                                        if (index < app.hasParts.length - 1) {
                                            app.callLoopApi(index + 1, key, setting, optionIndex, optionData, optionData2, status);
                                        } else {
                                            app.modalShowFlag = false;
                                            if (status == -1) {
                                                app.status = 5;
                                            } else {
                                                app.status = status + 1;
                                            }
                                            //alert("hasPart status="+app.status);
                                            if(app.status == 5){ //hong 3/12, added this condition
                                                var jsonSaveRequest = {
                                                    "user":shared? "" :app.$route.params.user,
                                                    "ontology": app.$route.params.ontology,
                                                };
                                                axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                                .then(function(resp) {
                                                       console.log('save resp', resp);
                                                })
                                                .catch(function(resp) {
                                                     console.log('save error resp', resp);
                                                });

                                                var jsonRequest = {
                                                    'user_email': app.$route.params.user,
                                                    'action': 'Summary loaded',
                                                    'action_details': 'Summary Page was loaded for term',
                                                    'abnormal_system_response': null,
                                                    'type': 'Wizard'
                                                };
                                                // if (app.username == null || app.username == 'null' || app.username == '') {
                                                //    alert('Please insert the username on homepage.');
                                                // } else {
                                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                .then(function(resp) {
                                                    console.log("activity-log resp", resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log("activity-log error resp", resp);
                                                });
                                          //  }
                                           }
                                        //app.treeData = app.$refs.tree.model;
                                        }
                                    }

                                } else if (setting == 'TTBA') {
                                    if (key == 'partOf') {
                                        app.TTBA[optionIndex].data.details[0][key].push(
                                            app.instances[index].data.details[0].IRI
                                        );
                                        // app.summary.push(TTBA[optionIndex].text + " part_of " + app.instances[index].text + " is added.");
                                        app.summaryPartOf.push(app.instances[index].text);
                                        //alert("TTBA:"+app.instances[index].text);
                                        app.treeData = app.$refs.tree.model;
                                        console.log("tree after updated", app.treeData);
                                        if (index < app.instances.length - 1) {
                                            app.callLoopApi(index + 1, key, setting, optionIndex, optionData, optionData2, status);
                                        } else {
                                            app.modalShowFlag = false;
                                            if (status == -1) {
                                                app.status = 5;
                                            } else {
                                                app.status = status + 1;
                                            }

                                            if(app.status == 5){ //hong 3/12
                                                var jsonSaveRequest = {
                                                    "user":shared? "" :app.$route.params.user,
                                                    "ontology": app.$route.params.ontology,
                                                };
                                                axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                                .then(function(resp) {
                                                       console.log('save resp', resp);
                                                })
                                                .catch(function(resp) {
                                                     console.log('save error resp', resp);
                                                });

                                                var jsonRequest = {
                                                    'user_email': app.$route.params.user,
                                                    'action': 'Summary loaded',
                                                    'action_details': 'Summary Page was loaded for term',
                                                    'abnormal_system_response': null,
                                                    'type': 'Wizard'
                                                };
                                                // if (app.username == null || app.username == 'null' || app.username == '') {
                                                //    alert('Please insert the username on homepage.');
                                                // } else {
                                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                .then(function(resp) {
                                                    console.log("activity-log resp", resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log("activity-log error resp", resp);
                                                });
                                                //  }
                                           }
                                            // app.treeData = app.$refs.tree.model;
                                        }
                                    } else {//hasPart
                                        app.TTBA[optionIndex].data.details[0][key].push(
                                            app.hasParts[index].data.details[0].IRI
                                        );
                                        // app.summary.push(TTBA[optionIndex].text + " has_part " + app.hasParts[index].text + " is added.");
                                        app.summaryHasPart.push(app.hasParts[index].text);
                                        //  app.treeData = app.$refs.tree.model;
                                        //  console.log("tree after updated", app.treeData);
                                        if (index < app.hasParts.length - 1) {
                                            app.callLoopApi(index + 1, key, setting, optionIndex, optionData, optionData2, status);
                                        } else {
                                            app.modalShowFlag = false;
                                            if (status == -1) {
                                                app.status = 5;
                                            } else {
                                                app.status = status + 1;
                                            }
                                            //alert("hasPart/TTBA app.status="+app.status); 
                                            if(app.status == 5){ //hong 3/12, added this condition
                                                var jsonSaveRequest = {
                                                "user":shared? "" :app.$route.params.user,
                                                "ontology": app.$route.params.ontology,
                                                };
                                                axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                                .then(function(resp) {
                                                    console.log('save resp', resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log('save error resp', resp);
                                                });

                                                var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Summary loaded',
                                                'action_details': 'Summary Page was loaded for term',
                                                'abnormal_system_response': null,
                                                'type': 'Wizard'
                                                };
                                                // if (app.username == null || app.username == 'null' || app.username == '') {
                                                    //    alert('Please insert the username on homepage.');
                                                // } else {
                                                axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                    .then(function(resp) {
                                                        console.log("activity-log resp", resp);
                                                    })
                                                    .catch(function(resp) {
                                                        console.log("activity-log error resp", resp);
                                                    });
                                                // }
                                            }
                                            // app.treeData = app.$refs.tree.model;
                                        }
                                    }

                                } else if (setting == 'no-synonym') {
                                    if (key == "partOf") {
                                        //   app.summary.push(app.synonyms[index].text + "(synonym of " + app.temp.text + ") part_of " + optionData2 + " is added.");
                                        app.summaryPartOf.push(optionData.split('#')[1]);
                                        //alert("no-synonym:"+ optionData.split('#')[1]);
                                    } else {//hasPart
                                        // app.summary.push(app.synonyms[index].text + "(synonym of " + app.temp.text + ") has_part " + optionData2 + " is added.");
                                        app.summaryHasPart.push(optionData.split('#')[1]);
                                    }
                                    app.synonyms[index].data.details[0][key].push(
                                        resp.data
                                    );
                                    app.treeData = app.$refs.tree.model;
                                    console.log("tree after updated", app.treeData);
                                    if (index < app.synonyms.length - 1) {
                                        app.callLoopApi(index + 1, key, setting, optionIndex, optionData, optionData2, status);
                                    } else {
                                        app.modalShowFlag = false;
                                        if (status == -1) {
                                            app.status = 5;
                                        } else {  
                                            //app.status = status + 1;
                                            app.status = status + 1; //hong 3/12
                                        }
                                        //if (key == 'hasPart') {
                                        if(app.status == 5){ //hong 3/12, switched the condition
                                            var jsonSaveRequest = {
                                                "user":shared? "" :app.$route.params.user,
                                                "ontology": app.$route.params.ontology,
                                            };
                                            axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                                .then(function(resp) {
                                                    console.log('save resp', resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log('save error resp', resp);
                                                });
                                            var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Summary loaded',
                                                'action_details': 'Summary Page was loaded for term',
                                                'abnormal_system_response': null,
                                                'type': 'Wizard'
                                            };
                                           // if (app.username == null || app.username == 'null' || app.username == '') {
                                           //     alert('Please insert the username on homepage.');
                                            // } else {
                                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                    .then(function(resp) {
                                                        console.log("activity-log resp", resp);
                                                    })
                                                    .catch(function(resp) {
                                                        console.log("activity-log error resp", resp);
                                            });
                                          //  }
                                        }
                                    }
                                } else if (setting == 'no-TTBA') {
                                    console.log("*******testing******", key + ' *** ' + optionData.split('#')[1]); //take the lable part of the URI

                                    if (key == "partOf") {
                                        //app.summary.push(app.TTBA[index].text + " part_of " + optionData2 + " is added.");
                                        app.summaryPartOf.push(optionData.split('#')[1]);
                                        //alert("no-TTBA:"+ optionData.split('#')[1]);
                                        app.treeData = app.$refs.tree.model;
                                        console.log("tree after updated", app.treeData);
                                    } else {
                                        //app.summary.push(app.TTBA[index].text + " has_part " + optionData2 + " is added.");
                                        app.summaryHasPart.push(optionData.split('#')[1]);
                                    }
                                    app.TTBA[index].data.details[0][key].push(
                                        resp.data
                                    );

                                    if (index < app.TTBA.length - 1) {
                                        app.callLoopApi(index + 1, key, setting, optionIndex, optionData, optionData2, status);
                                    } else {
                                        app.modalShowFlag = false;
                                        if (status == -1) {
                                            app.status = 5;
                                        } else {
                                           // app.status = status + 1;
                                           app.status = status + 1; //hong 3/12
                                        }
                                        //if (key == 'hasPart') {
                                        if(app.status == 5){ //hong 3/12 switched the condition
                                            var jsonSaveRequest = {
                                                "user":shared? "" :app.$route.params.user,
                                                "ontology": app.$route.params.ontology,
                                            };
                                            axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                                .then(function(resp) {
                                                    console.log('save resp', resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log('save error resp', resp);
                                                });
                                            var jsonRequest = {
                                                'user_email': app.$route.params.user,
                                                'action': 'Summary loaded',
                                                'action_details': 'Summary Page was loaded for term',
                                                'abnormal_system_response': null,
                                                'type': 'Wizard'
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
                                          //  }
                                        }
                                    // app.treeData = app.$refs.tree.model;
                                    }
                                }

                            } else if (key == 'bsynonym') {
                                app.synonyms[index].data.details[0].broad_synonym = app.temp.text;
                                app.summary.push('"' + app.temp.text + '"' + ' is a synonym of "' + app.synonyms[index].text + '".');
                                app.treeData = app.$refs.tree.model;
                                console.log("tree after updated", app.treeData);
                                if (index < app.synonyms.length - 1) {
                                    app.callLoopApi(index + 1, key, setting, optionIndex, optionData, optionData2, status);
                                } else {
                                    app.modalShowFlag = false;
                                    if (status == -1) {
                                        app.status = 5;
                                        //alert("status=5, log summery and save ontology");
                                        var jsonSaveRequest = {
                                            "user":shared? "" :app.$route.params.user,
                                            "ontology": app.$route.params.ontology,
                                        };
                                         axios.post('http://shark.sbs.arizona.edu:8080/save', jsonSaveRequest)
                                            .then(function(resp) {
                                                console.log('save resp', resp);
                                            })
                                            .catch(function(resp) {
                                                console.log('save error resp', resp);
                                            });
                                        var jsonRequest = {
                                            'user_email': app.$route.params.user,
                                            'action': 'Summary loaded',
                                            'action_details': 'Summary Page was loaded for term',
                                            'abnormal_system_response': null,
                                            'type': 'Wizard'
                                        };
                                       // if (app.username == null || app.username == 'null' || app.username == '') {
                                       //     alert('Please insert the username on homepage.');
                                       // } else {
                                            axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                                .then(function(resp) {
                                                    console.log("activity-log resp", resp);
                                                })
                                                .catch(function(resp) {
                                                    console.log("activity-log error resp", resp);
                                                });
                                    } else {
                                        app.status = status + 1;
                                    }
                                    //app.treeData = app.$refs.tree.model;
                                }
                            }

                            //console.log("tree", app.$refs.tree.model);
                            //app.treeData = app.$refs.tree.model;

                        }else { //?api call not successful
                                //alert("ontology api call failed");
                                //hong: log error
                                var jsonRequest = {
                                        'user_email': app.$route.params.user,
                                        'action': 'Add Class',
                                        'action_details': 'Attempt /'+key,
                                        'abnormal_system_response': "ontology /"+key+" error. ",
                                        'type': 'Wizard'
                                    };
                                        //if (app.username == null || app.username == 'null' || app.username == '') {
                                        //    alert('Please insert the username on homepage');
                                        //} else {
                                    axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                                        .then(function(resp) {
                                            console.log("activity-log resp", resp);
                                        })
                                        .catch(function(resp) {
                                            console.log("activity-log error resp", resp);
                                        });
                                
                        }
                    })
                    .catch(function(resp) {
                        console.log(key + " error", resp);
                    });
            },
            cancelSave: function(status) {
                var app = this;
                app.modalShowFlag = false;
                switch (status) {
                    case -1:
                         var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Undo synonym vs. class',
                        'action_details': 'undo synonym vs. class decision on character term='+app.$route.params.term,
                        'abnormal_system_response': null,
                        'type': 'Wizard'
                            };
                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                        break;
                    case 2:
                        var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Undo synonym vs. class',
                        'action_details': 'undo synonym vs. class decision on structure term='+app.$route.params.term,
                        'abnormal_system_response': null,
                        'type': 'Wizard'
                            };
                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                        break;
                    case 3:
                        var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Undo part-of',
                        'action_details': 'undo part-of decision on term='+app.$route.params.term,
                        'abnormal_system_response': null,
                        'type': 'Wizard'
                            };
                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });
                        break;
                    case 4:
                        var jsonRequest = {
                        'user_email': app.$route.params.user,
                        'action': 'Undo has-part',
                        'action_details': 'undo has-part decision on term='+app.$route.params.term,
                        'abnormal_system_response': null,
                        'type': 'Wizard'
                            };
                        axios.post('/add2ontologymodular/public/api/v1/activity-log/' + app.$route.params.term, jsonRequest)
                        .then(function(resp) {
                            console.log("activity-log resp", resp);
                        })
                        .catch(function(resp) {
                            console.log("activity-log error resp", resp);
                        });  
                        break;  
                    }

            },
            onSynonymSelected: function(node) {
                var app = this;
                console.log("selected node", node);
//                app.showTooltip = true;
                app.treeDetails.q2 = node.data.details;
//                app.arraySynonyms.push(node);
            },
            onCharacterSynonymSelected: function(node) {
                var app = this;
                app.treeDetails.qx = node.data.details;
            },
            onPartOfSelected: function(node) {
                var app = this;
                app.treeDetails.q3 = node.data.details;
            },
            onHasPartSelected: function(node) {
                var app = this;
                app.treeDetails.q4 = node.data.details;
            },
            onEntitySelected: function(node) {
                var app = this;
                app.treeDetails.entity = node.data.details;
            },
            onQualitySelected: function(node) {
                var app = this;
                app.treeDetails.quality = node.data.details;
            },
            onSynonymUnselected: function(node) {
                var app = this;

            },
            addUserDefinition: function() {
                var app = this;
                app.userInstances.push({
                    "term": null,
                    "definition": null
                });
            },
            removeUserDefinition: function(index) {
                var app = this;
                if(app.userInstances.length == 1) app.term.instance=null; //always keep one row there
                else app.userInstances.splice(index, 1); //hong 7/16/19, index starting from 0
               
            },
            addUserHasPart: function() {
                var app = this;
                app.userHasParts.push({
                    "term": null,
                    "definition": null
                });
            },
            removeUserHasPart: function(index) {
                var app = this;
                if(app.userHasParts.length == 1) term.hasPart =null;
                else app.userHasParts.splice(index, 1);
                
            },
            sorting: function(js_object, key_to_sort_by) {
                function sortByKey(a, b) {
                    var x = a[key_to_sort_by];
                    var y = b[key_to_sort_by];
                    return ((x > y) ? 1 : ((x < y) ? -1 : 0));
                };

                js_object.sort(sortByKey);
            },
            sortTreeData: function(children) {
                var app = this;
                app.sorting(children, 'text');
                for (var i = 0; i < children.length; i++)
                    if (children[i].hasOwnProperty('children'))
                        app.sortTreeData(children[i].children);
            },

            getLargeDataQuality: function(){
                 return axios.get('http://shark.sbs.arizona.edu:8080/carex/getSubclasses?baseIri=http://biosemantics.arizona.edu/ontologies/carex&term=quality')
            },

            getLargeDataEntity: function(){
                 return axios.get('http://shark.sbs.arizona.edu:8080/carex/getSubclasses?baseIri=http://biosemantics.arizona.edu/ontologies/carex&term=anatomical-structure')
            },

            /*getLargeDataQuality: function(){
                 axios.get('http://shark.sbs.arizona.edu:8080/carex/getSubclasses?baseIri=http://biosemantics.arizona.edu/ontologies/carex&term=quality')
                        .then ( (resp) => {
                       //.then(function(resp) {
                            console.log("quality term tree", resp.data);
                            //use retrieved data
                            app.largeDataQuality = resp.data;
                            //console.log("qulity terms", app.largeDataQuality);
                            return resp.data;

                    })
                    .catch((resp) => {
                    //.catch(function(resp) {
                          console.log("activity-log error resp", resp);
                          return app.largeDataQuality;
                    });
            },

            getLargeDataEntity: function(){
                 axios.get('http://shark.sbs.arizona.edu:8080/carex/getSubclasses?baseIri=http://biosemantics.arizona.edu/ontologies/carex&term=anatomical-structure')
                        .then ((resp) => {
                        //.then(function(resp) {
                            console.log('entity term tree', resp.data);
                            //use retrieved data
                            app.largeDataEntity = resp.data;
                            //console.log("entity terms", app.largeDataEntity);
                            return resp.data;

                    })
                    .catch((resp) => {
                    //.catch(function(resp) {
                        //use default empty data
                        console.log("activity-log error resp", resp);
                        return app.largeDataEntity;
                    });
            },*/


        }
    }
</script>

/**
 * Created by ZEUS on 5/22/2018.
 */
import Home from './components/Pages/home.vue';
import Wizard from './components/Pages/wizard.vue';
import QuickForm from './components/Pages/quick_form.vue';
import WebProtege from './components/Pages/web_protege.vue';
import TermsWiki from './components/Pages/terms_wiki.vue';

const routes = [
    //Heng's code
    /*
    {
        path: '/add2ontology/public/:term',
        component: Home,
        name: 'home'
    },
    {
        path: '/add2ontology/public/wizard/:term',
        component: Wizard,
        name: 'wizard'
    },
    {
        path: '/add2ontology/public/quick-form/:term',
        component: QuickForm,
        name: 'quick-form'
    },
    {
        path: '/add2ontology/public/web-protege/:term',
        component: WebProtege,
        name: 'web-protege'
    },
    {
        path: '/add2ontology/public/terms-wiki/:term',
        component: TermsWiki,
        name: 'terms-wiki'
    },*/
    //Hong's attempt
     {
        path: '/add2ontology/public/:user/:ontology/:term',
        component: Home,
        name: 'home'
    },
    {
        path: '/add2ontology/public/wizard/:user/:ontology/:term',
        component: Wizard,
        name: 'wizard'
    },
    {
        path: '/add2ontology/public/quick-form/:user/:ontology/:term',
        component: QuickForm,
        name: 'quick-form'
    },
    {
        path: '/add2ontology/public/web-protege/:user/:ontology/:term',
        component: WebProtege,
        name: 'web-protege'
    },
    {
        path: '/add2ontology/public/terms-wiki/:user/:ontology/:term',
        component: TermsWiki,
        name: 'terms-wiki'
    },
];

export default routes;
/**
 * Created by ZEUS on 5/22/2018.
 */
import Home from './components/Pages/home.vue';
import Wizard from './components/Pages/wizard.vue';
import QuickForm from './components/Pages/quick_form.vue';
import WebProtege from './components/Pages/web_protege.vue';
import TermsWiki from './components/Pages/terms_wiki.vue';

const routes = [
    {
        path: '/add2ontology/public/',
        component: Home,
        name: 'home'
    },
    {
        path: '/add2ontology/public/wizard',
        component: Wizard,
        name: 'wizard'
    },
    {
        path: '/add2ontology/public/quick-form',
        component: QuickForm,
        name: 'quick-form'
    },
    {
        path: '/add2ontology/public/web-protege',
        component: WebProtege,
        name: 'web-protege'
    },
    {
        path: '/add2ontology/public/terms-wiki',
        component: TermsWiki,
        name: 'terms-wiki'
    },
];

export default routes;
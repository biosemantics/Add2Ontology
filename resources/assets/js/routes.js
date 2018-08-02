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
        path: '/',
        component: Home,
        name: 'home'
    },
    {
        path: '/wizard',
        component: Wizard,
        name: 'wizard'
    },
    {
        path: '/quick-form',
        component: QuickForm,
        name: 'quick-form'
    },
    {
        path: '/web-protege',
        component: WebProtege,
        name: 'web-protege'
    },
    {
        path: '/terms-wiki',
        component: TermsWiki,
        name: 'terms-wiki'
    },
];

export default routes;
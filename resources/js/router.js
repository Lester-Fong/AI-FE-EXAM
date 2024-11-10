const ArticleDetail = () => import(/* webpackChunkName: "js/1001" */ "./articles/article_details.vue");
const LoginPage = () => import(/* webpackChunkName: "js/1003" */ "./front/login.vue");
const DashboardPage = () => import(/* webpackChunkName: "js/1004" */ "./portal/dashboard.vue");
const CompanyPage = () => import(/* webpackChunkName: "js/1005" */ "./portal/company.vue");
const ArticlePage = () => import(/* webpackChunkName: "js/1006" */ "./portal/article.vue");
const UserPage = () => import(/* webpackChunkName: "js/1007" */ "./portal/user.vue");

const routes = [
    {
        path: "/:link",
        name: "IndexPage",
        component: ArticleDetail,
    },
    {
        path: "/login",
        name: "LoginPage",
        component: LoginPage,
    },
    {
        path: "/user/dashboard",
        name: "DashboardPage",
        component: DashboardPage,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/companies",
        name: "CompanyPage",
        component: CompanyPage,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/articles",
        name: "ArticlePage",
        component: ArticlePage,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/users",
        name: "UserPage",
        component: UserPage,
        meta: {
            requiresAuth: true,
        },
    },
];

export default routes;

import './editor.scss';
import './style.scss';
import icon from './components/icon.js';
import {Sidebar} from './components/sidebar.js';
import includes from "lodash/includes.js";
import domReady from '@wordpress/dom-ready';
const {registerPlugin} = wp.plugins;
const {__} = wp.i18n;
const {select} = wp.data;
const UlrichsHausTeamSidebarPlugin = () => {
    const postType = select("core/editor").getCurrentPostType();
    if (!includes(["team"], postType)) {
        return null;
    }
    return (
        <Sidebar/>
    );
}

domReady(() => {
    registerPlugin('ulrichshaus-team-members', {
        icon: icon,
        className: 'ulrichshaus-member-sidebar',
        render: UlrichsHausTeamSidebarPlugin,
    });
});

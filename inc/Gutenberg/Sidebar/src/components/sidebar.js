import {TextareaControl} from "@wordpress/components";

const {__} = wp.i18n;
const {Fragment, Component} = wp.element;
const {PluginSidebarMoreMenuItem, PluginSidebar} = wp.editPost;
const {PanelBody, PanelRow, ToggleControl, TextControl, SelectControl} = wp.components;
const {compose} = wp.compose;
const {withDispatch, withSelect, select} = wp.data;

export class Sidebar extends Component {
    constructor() {
        super();
    }

    render() {

        /*===================================================================
        ========================== NAME ======================
        =====================================================================*/
        const TeamName = (props) => {
            return (
                <TextControl
                    label='Name'
                    icon='arrow-right-alt'
                    value={props.teamName}
                    onChange={props.setTeamNameValue}
                    disabled={false}
                />
            );
        }
        const TeamNameMeta = compose([
            withSelect(select => {
                return {teamName: select('core/editor').getEditedPostAttribute('meta')['_team_name']}
            }),
            withDispatch(dispatch => {
                return {
                    setTeamNameValue: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_name: value}});
                    }
                }
            })
        ])(TeamName);

        /*====================================================
        ========================== NAME ======================
        ======================================================*/
        const TeamPosition = (props) => {
            return (
                <TextControl
                    label='Position'
                    icon='arrow-right-alt'
                    value={props.teamPosition}
                    onChange={props.setTeamPositionValue}
                    disabled={false}
                />
            );
        }
        const TeamPositionMeta = compose([
            withSelect(select => {
                return {teamPosition: select('core/editor').getEditedPostAttribute('meta')['_team_position']}
            }),
            withDispatch(dispatch => {
                return {
                    setTeamPositionValue: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_position: value}});
                    }
                }
            })
        ])(TeamPosition);

        /*===============================================================
        ========================== Arbeitsgebiet 1 ======================
        =================================================================*/
        const TeamGebiet1 = (props) => {
            return (
                <TextControl
                    label='Arbeitsgebiet 1'
                    icon='arrow-right-alt'
                    value={props.teamGebiet1}
                    onChange={props.setTeamGebiet1Value}
                    disabled={false}
                />
            );
        }
        const TeamGebiet1Meta = compose([
            withSelect(select => {
                return {teamGebiet1: select('core/editor').getEditedPostAttribute('meta')['_team_arbeitsgebiet1']}
            }),
            withDispatch(dispatch => {
                return {
                    setTeamGebiet1Value: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_arbeitsgebiet1: value}});
                    }
                }
            })
        ])(TeamGebiet1);

        /*===============================================================
        ========================== Arbeitsgebiet 2 ======================
        =================================================================*/
        const TeamGebiet2 = (props) => {
            return (
                <TextControl
                    label='Arbeitsgebiet 2'
                    icon='arrow-right-alt'
                    value={props.teamGebiet2}
                    onChange={props.setTeamGebiet2Value}
                    disabled={false}
                />
            );
        }
        const TeamGebiet2Meta = compose([
            withSelect(select => {
                return {teamGebiet2: select('core/editor').getEditedPostAttribute('meta')['_team_arbeitsgebiet2']}
            }),
            withDispatch(dispatch => {
                return {
                    setTeamGebiet2Value: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_arbeitsgebiet2: value}});
                    }
                }
            })
        ])(TeamGebiet2);

        /*===============================================================
        ========================== Arbeitsgebiet 3 ======================
        =================================================================*/
        const TeamGebiet3 = (props) => {
            return (
                <TextControl
                    label='Arbeitsgebiet 3'
                    icon='arrow-right-alt'
                    value={props.teamGebiet3}
                    onChange={props.setTeamGebiet3Value}
                    disabled={false}
                />
            );
        }
        const TeamGebiet3Meta = compose([
            withSelect(select => {
                return {teamGebiet3: select('core/editor').getEditedPostAttribute('meta')['_team_arbeitsgebiet3']}
            }),
            withDispatch(dispatch => {
                return {
                    setTeamGebiet3Value: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_arbeitsgebiet3: value}});
                    }
                }
            })
        ])(TeamGebiet3);

        /*===============================================================
        ========================== Arbeitsgebiet 4 ======================
        =================================================================*/
        const TeamGebiet4 = (props) => {
            return (
                <TextControl
                    label='Arbeitsgebiet 4'
                    icon='arrow-right-alt'
                    value={props.teamGebiet4}
                    onChange={props.setTeamGebiet4Value}
                    disabled={false}
                />
            );
        }
        const TeamGebiet4Meta = compose([
            withSelect(select => {
                return {teamGebiet4: select('core/editor').getEditedPostAttribute('meta')['_team_arbeitsgebiet4']}
            }),
            withDispatch(dispatch => {
                return {
                    setTeamGebiet4Value: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_arbeitsgebiet4: value}});
                    }
                }
            })
        ])(TeamGebiet4);

        /*===============================================================
        ========================== Arbeitsgebiet 5 ======================
        =================================================================*/
        const TeamGebiet5 = (props) => {
            return (
                <TextControl
                    label='Arbeitsgebiet 5'
                    icon='arrow-right-alt'
                    value={props.teamGebiet5}
                    onChange={props.setTeamGebiet5Value}
                    disabled={false}
                />
            );
        }
        const TeamGebiet5Meta = compose([
            withSelect(select => {
                return {teamGebiet5: select('core/editor').getEditedPostAttribute('meta')['_team_arbeitsgebiet5']}
            }),
            withDispatch(dispatch => {
                return {
                    setTeamGebiet5Value: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_arbeitsgebiet5: value}});
                    }
                }
            })
        ])(TeamGebiet5);

        /*===============================================================
        ========================== LEBENSLAUF Show ======================
        =================================================================*/
        const CheckLebenslauf = (props) => {
            return (

                <ToggleControl
                    label={__('Lebenslauf anzeigen', 'bootscore') + ':'}
                    checked={props.getLebenslaufActive}
                    onChange={props.setLebenslaufActive}
                />
            );
        }

        const CheckLebenslaufMeta = compose([
            withSelect(select => {
                return {getLebenslaufActive: select('core/editor').getEditedPostAttribute('meta')['_team_lebenslauf']}
            }),
            withDispatch(dispatch => {
                return {
                    setLebenslaufActive: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_lebenslauf: value}});
                    }
                }
            })
        ])(CheckLebenslauf);

        /*===============================================================
        ========================== Arbeitsgebiet 6 ======================
        =================================================================*/
        const TeamGebiet6 = (props) => {
            return (
                <TextControl
                    label='Arbeitsgebiet 6'
                    icon='arrow-right-alt'
                    value={props.teamGebiet6}
                    onChange={props.setTeamGebiet6Value}
                    disabled={false}
                />
            );
        }
        const TeamGebiet6Meta = compose([
            withSelect(select => {
                return {teamGebiet6: select('core/editor').getEditedPostAttribute('meta')['_team_arbeitsgebiet6']}
            }),
            withDispatch(dispatch => {
                return {
                    setTeamGebiet6Value: function (value) {
                        dispatch('core/editor').editPost({meta: {_team_arbeitsgebiet6: value}});
                    }
                }
            })
        ])(TeamGebiet6);

        return (
            <Fragment>
                <PluginSidebarMoreMenuItem
                    target='wp-team-editor-sidebar'
                    icon='erIcon'
                >
                    {__('Ulrichshaus Team', 'bootscore')}
                </PluginSidebarMoreMenuItem>
                <PluginSidebar
                    name="wp-team-editor-sidebar"
                    title={__('Ulrichshaus Team', 'bootscore')}
                    className="ulrichshaus-member-sidebar"
                >

                    <PanelBody
                        title={__('Member', 'bootscore')}
                        initialOpen={true}
                        className="ulrichshaus-member"
                    >
                        <TeamNameMeta/>
                        <TeamPositionMeta/>
                        <p className="checkbox">
                            <CheckLebenslaufMeta/>
                        </p>
                        <hr/>
                        <TeamGebiet1Meta/>
                        <TeamGebiet2Meta/>
                        <TeamGebiet3Meta/>
                        <TeamGebiet4Meta/>
                        <TeamGebiet5Meta/>
                        <TeamGebiet6Meta/>
                    </PanelBody>
                </PluginSidebar>
            </Fragment>
        )
    }
}
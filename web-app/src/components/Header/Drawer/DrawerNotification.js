import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import {withStyles} from '@material-ui/core/styles';
import Drawer from '@material-ui/core/Drawer';
import CssBaseline from '@material-ui/core/CssBaseline';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import List from '@material-ui/core/List';
import Typography from '@material-ui/core/Typography';
import Divider from '@material-ui/core/Divider';
import IconButton from '@material-ui/core/IconButton';
import MenuIcon from '@material-ui/icons/Menu';
import ChevronLeftIcon from '@material-ui/icons/ChevronLeft';
import ChevronRightIcon from '@material-ui/icons/ChevronRight';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import InboxIcon from '@material-ui/icons/MoveToInbox';
import MailIcon from '@material-ui/icons/Mail';
import {Link as RouterLink, Link} from "react-router-dom";
import Button from "@material-ui/core/es/Button/Button";
import MenuItem from '@material-ui/core/MenuItem';
import MenuList from '@material-ui/core/MenuList';
import {connect} from "react-redux";
import Avatar from "@material-ui/core/Avatar";
import axios from './../../../axios/axios'
import Grid from "../../Home/PostComponent";
import {toast} from "react-toastify";
import NotificationSwitch from './Notification/NotificationSwitch';

const drawerWidth = 240;

const styles = theme => ({
    root: {
        display: 'flex',
    },
    appBar: {
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
    },
    appBarShift: {
        width: `calc(100% - ${drawerWidth}px)`,
        marginLeft: drawerWidth,
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.easeOut,
            duration: theme.transitions.duration.enteringScreen,
        }),
    },
    menuButton: {
        marginLeft: 12,
        marginRight: 20,
    },
    hide: {
        display: 'none',
    },
    drawer: {
        width: drawerWidth,
        flexShrink: 0,
    },
    drawerPaper: {
        width: drawerWidth,
    },
    drawerHeader: {
        display: 'flex',
        alignItems: 'center',
        padding: '0 8px',
        ...theme.mixins.toolbar,
        justifyContent: 'flex-end',
    },
    content: {
        flexGrow: 1,
        padding: theme.spacing.unit * 3,
        transition: theme.transitions.create('margin', {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
        marginLeft: -drawerWidth,
    },
    contentShift: {
        transition: theme.transitions.create('margin', {
            easing: theme.transitions.easing.easeOut,
            duration: theme.transitions.duration.enteringScreen,
        }),
        marginLeft: 0,
    },
    menu__avatar: {
        display: 'flex',
        justifyContent: "space-between",
        alignItems: "center"
    },
    button: {
        margin: '1em',
    },
    displayed: {
        backgroundColor: "grey",
    }
});

class DrawerNotification extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const {classes, theme} = this.props;
        const {open, handleDrawerClose, notification} = this.props;
        return (
            <div className={classes.root}>
                <CssBaseline/>
                <Drawer
                    className={classes.drawer}
                    variant="persistent"
                    anchor="right"
                    open={open}
                    classes={{
                        paper: classes.drawerPaper,
                    }}
                >
                    <div className={classes.drawerHeader}>
                        <IconButton onClick={handleDrawerClose}>
                            {theme.direction === 'ltr' ? <ChevronLeftIcon/> : <ChevronRightIcon/>}
                        </IconButton>
                    </div>
                    <Divider/>
                    {notification.map((item) => {
                        return (
                            <MenuList>
                                {item.displayed === true ?
                                    <Button variant="outlined" component="span"
                                            className={classes.button} disabled>
                                        <NotificationSwitch notification={item}/>
                                    </Button> :
                                    <Button variant="outlined" component="span"
                                            className={classes.button}>
                                        <NotificationSwitch notification={item}/>
                                    </Button>
                                }

                            </MenuList>
                        )
                    })}
                    <Button
                        variant="outlined"
                        color="primary"
                        className={classes.button}
                        onClick={() => {
                            if (this.props.notification.length === this.props.notificationTotal) {
                                toast.info("Nie ma wiecej powiadomień", {
                                    position: toast.POSITION.BOTTOM_RIGHT
                                });
                            } else {
                                this.props.handleUpLimit();
                            }
                        }}
                    >
                        Wiecej
                    </Button>
                </Drawer>
            </div>
        );
    }
}

Drawer.propTypes = {};


export default withStyles(styles, {withTheme: true})(DrawerNotification);
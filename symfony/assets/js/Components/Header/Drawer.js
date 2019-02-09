import React from 'react';
import {withStyles} from '@material-ui/core/styles';
import Drawer from '@material-ui/core/Drawer';
import List from '@material-ui/core/List';
import Divider from '@material-ui/core/Divider';
import IconButton from '@material-ui/core/IconButton';
import ChevronLeftIcon from '@material-ui/icons/ChevronLeft';
import ChevronRightIcon from '@material-ui/icons/ChevronRight';
import {NavLink} from "react-router-dom";
import Button from "@material-ui/core/Button/Button";
import Toolbar from "@material-ui/core/Toolbar/Toolbar";
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import ListSubheader from '@material-ui/core/ListSubheader';
import PeopleIcon from '@material-ui/icons/People';
import LockIcon from '@material-ui/icons/LockOutlined';
import {connect} from 'react-redux';
import SupervisedUserCircle from '@material-ui/icons/SupervisedUserCircle';

const drawerWidth = 240;

const styles = theme => ({
    root: {
        flexGrow: 1,
    },
    appFrame: {
        height: 430,
        zIndex: 1,
        overflow: 'hidden',
        position: 'relative',
        display: 'flex',
        width: '100%',
    },
    appBar: {
        position: 'absolute',
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
    },
    appBarShift: {
        width: `calc(100% - ${drawerWidth}px)`,
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.easeOut,
            duration: theme.transitions.duration.enteringScreen,
        }),
    },
    'appBarShift-left': {
        marginLeft: drawerWidth,
    },
    'appBarShift-right': {
        marginRight: drawerWidth,
    },
    hide: {
        display: 'none',
    },
    drawerPaper: {
        position: 'absolute',
        width: drawerWidth,
        display: 'flex'
    },
    drawerHeader: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'flex-end',
        padding: '0 8px',
        ...theme.mixins.toolbar,
    },
    content: {
        flexGrow: 1,
        backgroundColor: theme.palette.background.default,
        padding: theme.spacing.unit * 3,
        transition: theme.transitions.create('margin', {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
    },
    'content-left': {
        marginLeft: -drawerWidth,
    },
    'content-right': {
        marginRight: -drawerWidth,
    },
    contentShift: {
        transition: theme.transitions.create('margin', {
            easing: theme.transitions.easing.easeOut,
            duration: theme.transitions.duration.enteringScreen,
        }),
    },
    'contentShift-left': {
        marginLeft: 0,
    },
    'contentShift-right': {
        marginRight: 0,
    },
});


class DrawerComponent extends React.Component {
    constructor(props) {
        super(props);

        this.state = {};
    }

    render() {
        const classes = this.props.classes;
        const theme = this.props.theme;
        let drawer = '';

        if (this.props.user.length === 0) {
            drawer = <React.Fragment>
                <NavLink to="/login" className={classes.menuButton}>
                    <List>
                        <ListItem button>
                            <ListItemIcon>
                                <LockIcon/>
                            </ListItemIcon>
                            <ListItemText primary="Zaloguj sie"/>
                        </ListItem>
                    </List>
                </NavLink>
                <NavLink to="/registration">
                    <List>
                        <ListItem button>
                            <ListItemIcon>
                                <PeopleIcon/>
                            </ListItemIcon>
                            <ListItemText primary="Rejestracja"/>
                        </ListItem>
                    </List>
                </NavLink>
            </React.Fragment>
        } else {
            let admin;

            if (this.props.user.roles[0] === 'ROLE_ADMIN') {
                admin = <React.Fragment>
                    <NavLink to="/panel/kategorie" className={classes.menuButton}>
                        <List>
                            <ListItem button>
                                <ListItemIcon>
                                    <LockIcon/>
                                </ListItemIcon>
                                <ListItemText primary="Zarządzaj kategoriami"/>
                            </ListItem>
                        </List>
                    </NavLink>
                    <NavLink to="/user/ban" className={classes.menuButton}>
                        <List>
                            <ListItem button>
                                <ListItemIcon>
                                    <LockIcon/>
                                </ListItemIcon>
                                <ListItemText primary="Zbanuj"/>
                            </ListItem>
                        </List>
                    </NavLink>
                </React.Fragment>
            }

            drawer = <React.Fragment>
                <NavLink to="/wyloguj" className={classes.menuButton}>
                    <List>
                        <ListItem button>
                            <ListItemIcon>
                                <LockIcon/>
                            </ListItemIcon>
                            <ListItemText primary="Wyloguj sie"/>
                        </ListItem>
                    </List>
                </NavLink>
                <NavLink to="/panel/user" className={classes.menuButton}>
                    <List>
                        <ListItem button>
                            <ListItemIcon>
                                <SupervisedUserCircle/>
                            </ListItemIcon>
                            <ListItemText primary="Panel Użytkownika"/>
                        </ListItem>
                    </List>
                </NavLink>
                {admin}
            </React.Fragment>
        }

        return (
            <React.Fragment>
                <Drawer
                    variant="persistent"
                    anchor="left"
                    open={this.props.open}
                    classes={{
                        paper: classes.drawerPaper,
                    }}
                >
                    <div className={classes.drawerHeader}>
                        <IconButton onClick={this.props.handleMenuClose}>
                            {theme.direction === 'rtl' ? <ChevronRightIcon/> : <ChevronLeftIcon/>}
                        </IconButton>
                    </div>
                    <Divider/>
                    <NavLink to="/category" className={classes.menuButton}>
                        <List>
                            <ListItem button>
                                <ListItemIcon>
                                    <LockIcon/>
                                </ListItemIcon>
                                <ListItemText primary="Wszystkie Kategorie"/>
                            </ListItem>
                        </List>
                    </NavLink>
                    <NavLink to="/user" className={classes.menuButton}>
                        <List>
                            <ListItem button>
                                <ListItemIcon>
                                    <LockIcon/>
                                </ListItemIcon>
                                <ListItemText primary="Użytkownicy"/>
                            </ListItem>
                        </List>
                    </NavLink>
                    {this.props.user.length !==0 &&
                    <NavLink to="/post/dodaj" className={classes.menuButton}>
                        <List>
                            <ListItem button>
                                <ListItemIcon>
                                    <LockIcon/>
                                </ListItemIcon>
                                <ListItemText primary="Dodaj post"/>
                            </ListItem>
                        </List>
                    </NavLink>
                    }
                    {drawer}
                </Drawer>
            </React.Fragment>
        );
    }
}

const mapStateToProps = state => ({
    user: state.user
});

export default connect(mapStateToProps)(withStyles(styles, {withTheme: true})(DrawerComponent));
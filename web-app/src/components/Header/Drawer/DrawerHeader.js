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
import {Link, withRouter} from "react-router-dom";
import Button from "@material-ui/core/es/Button/Button";
import MenuItem from '@material-ui/core/MenuItem';
import MenuList from '@material-ui/core/MenuList';
import {connect} from "react-redux";
import Avatar from "@material-ui/core/Avatar";
import {login, userClear} from "../../../actions/user";
import {getNotification} from "../../../actions/notification";
import {toast} from "react-toastify";

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
    }
});

class DrawerHeader extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const {classes, theme} = this.props;
        const {open, handleDrawerClose} = this.props;
        return (
            <div className={classes.root}>
                <CssBaseline/>
                <Drawer
                    className={classes.drawer}
                    variant="persistent"
                    anchor="left"
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
                    <MenuList>
                        <MenuItem component={Link} to="/">Główna</MenuItem>
                    </MenuList>
                    <MenuList>
                        <MenuItem component={Link} to="/kategorie">Lista Kategori</MenuItem>
                    </MenuList>
                    <MenuList>
                        <MenuItem component={Link} to="/uzytkownicy">Lista Uzytkowników</MenuItem>
                    </MenuList>
                    <MenuList>
                        <MenuItem component={Link} to="/posty">Lista Postów</MenuItem>
                    </MenuList>
                    <Divider/>
                    {this.props.user.length !== 0 &&
                    <React.Fragment>
                        <MenuList>
                            <MenuItem component={Link} to="/panel/uzytkownika" className={classes.menu__avatar}>
                                <Avatar alt="Remy Sharp" src={"http://localhost:9999" + this.props.user[0].avatar}
                                        className={classes.avatar}/>
                                <Typography variant="title" gutterBottom>
                                    {this.props.user[0].name}
                                </Typography>
                            </MenuItem>
                            <MenuItem component={Link} to="/dodaj/post">
                                Dodaj Post
                            </MenuItem>
                        </MenuList>
                    </React.Fragment>
                    }
                    {this.props.user.length === 0 ?
                        <React.Fragment>
                            <Divider/>
                            <MenuList>
                                <MenuItem component={Link} to="/zaloguj">Zaloguj się</MenuItem>
                            </MenuList>
                            <MenuList>
                                <MenuItem component={Link} to="/zarejestruj">Zarejestruj się</MenuItem>
                            </MenuList>
                        </React.Fragment> :
                        <React.Fragment>
                            <Divider/>
                            <MenuList onClick={() => {
                                localStorage.removeItem('token');
                                this.props.userClear();
                                toast.info("Wylogowales sie", {
                                    position: toast.POSITION.BOTTOM_RIGHT
                                });
                                this.props.history.push('/');
                            }}>
                                <MenuItem>Wyloguj</MenuItem>
                            </MenuList>
                        </React.Fragment>
                    }
                </Drawer>
            </div>
        );
    }
}

Drawer.propTypes = {};

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login, userClear};
export default withRouter(connect(mapStateToProps, mapDispatchToProps)(withStyles(styles, {withTheme: true})(DrawerHeader)));
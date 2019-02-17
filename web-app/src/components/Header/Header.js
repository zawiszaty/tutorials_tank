import React, {Component} from 'react';
import AppBar from "@material-ui/core/AppBar";
import withStyles from "@material-ui/core/es/styles/withStyles";
import MenuItem from "@material-ui/core/MenuItem";
import Menu from "@material-ui/core/Menu";
import IconButton from "@material-ui/core/IconButton";
import Typography from "@material-ui/core/Typography";
import Toolbar from "@material-ui/core/Toolbar";
import MenuIcon from '@material-ui/icons/Menu';
import AccountCircle from '@material-ui/icons/AccountCircle';
import DrawerHeader from "./Drawer/DrawerHeader";
import classNames from 'classnames';
import Badge from "@material-ui/core/Badge";
import NotificationsIcon from '@material-ui/icons/Notifications';
import {login} from "../../actions/user";
import {connect} from "react-redux";
import DrawerNotification from "./Drawer/DrawerNotification";
import axios from "../../axios/axios";
import {REMOVE_NOTIFICATION} from "../../actions/notification";

const drawerWidth = 240;

const styles = theme => ({
    root: {
        flexGrow: 1,
    },
    grow: {
        flexGrow: 1,
    },
    menuButton: {
        marginLeft: -12,
        marginRight: 20,
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
});

class Header extends Component {
    constructor(props) {
        super(props);
        this.state = {
            auth: true,
            anchorEl: null,
            open: false,
            openRight: false,
            notification: [],
            notificationTotal: 0,
            limit: 10,
        }
    }

    handleChange = event => {
        this.setState({auth: event.target.checked});
    };

    handleClose = () => {
        this.setState({anchorEl: null});
    };


    handleDrawerOpen = () => {
        this.setState({open: true});
    };

    handleDrawerClose = () => {
        this.setState({open: false});
    };

    handleDrawerRightClose = () => {
        this.setState({openRight: false});
    };

    handleUpLimit = () => {
        this.setState({
            limit: this.state.limit + 10
        }, () => {
            this.getAllNotification();
        })
    };

    getAllNotification = () => {
        if (this.props.user.length !== 0) {
            axios.get(`/api/v1/notification?query=${this.props.user[0].id}&&limit=${this.state.limit}`)
                .then((e) => {
                    this.setState({
                        notification: e.data.data,
                        notificationTotal: e.data.total
                    });
                    let data = [];
                    e.data.data.map((item) => {
                        if (item.displayed !== true) {
                            data.push(item.id)
                        }
                    });

                    if (data.length !== 0) {
                        axios.patch('/api/v1/notifications', {'notifications': data}, {
                            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                        }).then((e) => {
                            this.props.REMOVE_NOTIFICATION(this.props.notification);
                        });
                    }
                })
        }
    };

    render() {
        const {classes} = this.props;
        const open = Boolean(this.state.anchorEl);
        return (
            <div className={classes.root}>
                <AppBar position="static"
                        className={classNames(classes.appBar, {
                            [classes.appBarShift]: this.state.open,
                        })}>
                    <Toolbar>
                        <IconButton
                            color="inherit"
                            aria-label="Open drawer"
                            onClick={this.handleDrawerOpen}
                            className={classNames(classes.menuButton, open && classes.hide)}
                        >
                            <MenuIcon/>
                        </IconButton>
                        <Typography variant="h6" color="inherit" className={classes.grow}>
                            Tutorials Tank
                        </Typography>
                        {this.props.user.length !== 0 &&
                        <IconButton color="inherit" onClick={() => {
                            this.getAllNotification();
                            this.setState({
                                openRight: true
                            })
                        }
                        }>
                            <Badge badgeContent={this.props.notification} color="secondary">
                                <NotificationsIcon/>
                            </Badge>
                        </IconButton>
                        }
                    </Toolbar>
                </AppBar>
                <DrawerHeader open={this.state.open} handleDrawerClose={this.handleDrawerClose}/>
                {this.props.user.length !== 0 &&
                <DrawerNotification open={this.state.openRight} handleDrawerClose={this.handleDrawerRightClose}
                                    user={this.props.user[0]}
                                    notification={this.state.notification}
                                    notificationTotal={this.state.notificationTotal}
                                    handleUpLimit={this.handleUpLimit}
                />
                }
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user,
        notification: state.notification[0],
    }
};
const mapDispatchToProps = {login, REMOVE_NOTIFICATION};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles, {withTheme: true})(Header));
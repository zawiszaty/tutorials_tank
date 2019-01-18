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
                    </Toolbar>
                </AppBar>
                <DrawerHeader open={this.state.open} handleDrawerClose={this.handleDrawerClose}/>
            </div>
        );
    }
}

export default withStyles(styles, {withTheme: true})(Header);
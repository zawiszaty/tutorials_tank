import React from 'react';

import withStyles from '@material-ui/core/styles/withStyles';
import AppBar from "@material-ui/core/AppBar/AppBar";
import Toolbar from "@material-ui/core/Toolbar/Toolbar";
import Typography from "@material-ui/core/Typography/Typography";
import {NavLink} from "react-router-dom";
import Button from "@material-ui/core/Button/Button";
import IconButton from "@material-ui/core/IconButton";
import MenuIcon from '@material-ui/icons/Menu';
import Hidden from '@material-ui/core/Hidden';
import Avatar from '@material-ui/core/Avatar';
import {connect} from 'react-redux';
import Badge from '@material-ui/core/Badge';
import NotificationsIcon from '@material-ui/icons/Notifications';

const styles = {
    root: {
        flexGrow: 1,
    },
    grow: {
        flexGrow: 1,
    },
    menuButton: {
        marginLeft: -12,
        marginRight: 20,
        color: '#fff',
        textdecoration: '0'
    },
};


class Header extends React.Component {
    constructor(props) {
        super(props);
        console.log(props.user);
        this.state = {};
    }


    render() {
        const classes = this.props.classes;
        const handleMenuOpen = this.props.handleMenuOpen;
        let userInfo = '';
        if (this.props.user.length !== 0) {
            userInfo = <React.Fragment>
                <Avatar alt="user avatar"
                        src={this.props.user.avatar}
                 />
                <IconButton color="inherit">
                    <Badge badgeContent={4} color="secondary">
                        <NotificationsIcon />
                    </Badge>
                </IconButton>
            </React.Fragment>
        }
        return (
            <React.Fragment>
                <div className={classes.root}>
                    <AppBar position="static">
                        <Toolbar>
                            <IconButton className={classes.menuButton} color="inherit" aria-label="Menu"
                                        onClick={handleMenuOpen}>
                                <MenuIcon/>
                            </IconButton>
                            <Typography variant="title" color="inherit" className={classes.grow}>
                                Tutorials Tank
                            </Typography>
                            {userInfo}
                        </Toolbar>
                    </AppBar>
                </div>
            </React.Fragment>
        );
    }
}

const mapStateToProps = state => ({
    user: state.user
});

export default connect(mapStateToProps)(withStyles(styles)(Header));
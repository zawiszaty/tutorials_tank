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

        this.state = {};
    }


    render() {
        const classes = this.props.classes;
        const handleMenuOpen = this.props.handleMenuOpen;
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
                            <Hidden mdDown>
                                <Avatar alt="Remy Sharp" src="data:image/jpeg;base64,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" />
                                Test User
                            </Hidden>
                        </Toolbar>
                    </AppBar>
                </div>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(Header);
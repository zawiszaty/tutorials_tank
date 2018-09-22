import React from 'react';

import withStyles from '@material-ui/core/styles/withStyles';
import AppBar from "@material-ui/core/AppBar/AppBar";
import Toolbar from "@material-ui/core/Toolbar/Toolbar";
import Typography from "@material-ui/core/Typography/Typography";
import {NavLink} from "react-router-dom";
import Button from "@material-ui/core/Button/Button";
import IconButton from "@material-ui/core/IconButton";
import MenuIcon from '@material-ui/icons/Menu';

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
        return (
            <React.Fragment>
                <div className={classes.root}>
                    <AppBar position="static">
                        <Toolbar>
                            <IconButton className={classes.menuButton} color="inherit" aria-label="Menu">
                                <MenuIcon/>
                            </IconButton>
                            <Typography variant="title" color="inherit" className={classes.grow}>
                                Tutorials Tank
                            </Typography>
                            <NavLink to="/login" className={classes.menuButton}><Button color="inherit">
                                Zaloguj się
                            </Button>
                            </NavLink>
                            <NavLink to="/registration" className={classes.menuButton}><Button color="inherit">
                                Zarejestruj się
                            </Button>
                            </NavLink>
                        </Toolbar>
                    </AppBar>
                </div>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(Header);
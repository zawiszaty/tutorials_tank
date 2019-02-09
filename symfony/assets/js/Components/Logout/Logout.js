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
import {Route, Redirect} from 'react-router'
import {withSnackbar} from 'notistack';
import {loginUser, logoutUser} from "../../actions/user-action";

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

let onPresentSnackbar;

class Logout extends React.Component {
    constructor(props) {
        super(props);
        console.log(props.user);
        onPresentSnackbar = props.onPresentSnackbar;
        this.state = {};
        this.componentDidMount = this.componentDidMount.bind(this);
    }

    componentDidMount() {
        localStorage.removeItem('token');
        onPresentSnackbar('info', 'Wylogowano');
        this.props.onLogoutUser();
    }

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <Redirect to="/" />
            </React.Fragment>
        );
    }
}

const mapStateToProps = state => ({
    user: state.user
});

const mapActionToProps = {
    onLogoutUser: logoutUser
};

export default connect(mapStateToProps, mapActionToProps)(withStyles(styles)(withSnackbar(Logout)));
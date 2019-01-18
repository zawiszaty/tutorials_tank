import React, {Component} from 'react';
import PropTypes from 'prop-types';
import axios from "../../axios/axios";
import {toast} from "react-toastify";
import CssBaseline from "@material-ui/core/CssBaseline";
import Paper from "@material-ui/core/Paper";
import CircularProgress from "@material-ui/core/CircularProgress";
import {withStyles} from "@material-ui/core";
import Redirect from "react-router-dom/es/Redirect";

const styles = theme => ({
    progress: {
        margin: theme.spacing.unit * 2,
    },
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: 400,
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    avatar: {
        margin: theme.spacing.unit,
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

class ConfirmUser extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: true,
            error: false,
        }
    }

    sendConfirmRequest = () => {
        const token = this.props.match.params.token;
        axios.patch(`/api/v1/user/confirm/${token}`)
            .then((response) => {
                toast.success("Konto pomyślnie potwierdzono", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                this.setState({
                    loading: false
                })
            })
            .catch((e) => {
                toast.error("coś poszło nie tak", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                this.setState({
                    error: true
                })
            })
    };

    componentDidMount = () => {
        this.sendConfirmRequest();
    };

    render() {
        const {classes} = this.props;
        if (this.state.error === true || this.state.loading === false) {
            return (
                <Redirect to="/"/>
            );
        }

        return (
            <div>
                <main className={classes.main}>
                    <CssBaseline/>
                    <Paper className={classes.paper}>
                        <CircularProgress className={classes.progress} color="secondary"/>
                    </Paper>
                </main>
            </div>
        );
    }
}

ConfirmUser.propTypes = {};

export default withStyles(styles)(ConfirmUser);
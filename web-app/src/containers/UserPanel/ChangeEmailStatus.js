import React, {Component} from 'react';
import PropTypes from 'prop-types';
import axios from "../../axios/axios";
import {withRouter} from "react-router-dom";
import {toast} from "react-toastify";
import CircularProgress from "@material-ui/core/CircularProgress";
import {Paper} from "@material-ui/core";
import CssBaseline from "@material-ui/core/CssBaseline";
import green from "@material-ui/core/colors/green";
import withStyles from "@material-ui/core/es/styles/withStyles";

const styles = theme => ({
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    wrapper: {
        margin: theme.spacing.unit,
        position: 'relative',
        display: 'flex',
        alignItems: 'center',
        flexDirection: 'column',
    },
    buttonSuccess: {
        backgroundColor: green[500],
        '&:hover': {
            backgroundColor: green[700],
        },
    },
    buttonProgress: {
        color: green[500],
        position: 'absolute',
        top: '50%',
        left: '50%',
        marginTop: -12,
        marginLeft: -12,
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    facebook: {
        margin: theme.spacing.unit * 2,
        position: 'relative',
    },
});

class ChangeEmailStatus extends Component {
    constructor(props) {
        super(props);
        this.state = {}
    }


    confirmChangeEmail = () => {
        axios.patch(`/api/v1/user/change/email/status?type=confirm&&token=${this.props.match.params.token}`)
            .then((response) => {
                this.move('Pomyslnie potwierdzono zmiane emiala');
            }).catch((e) => {
            this.props.history.push('/');
            toast.error('Coś poszło nie tak', {
                position: toast.POSITION.BOTTOM_RIGHT
            });
        })
    };

    discardChangeEmail = (oldEmail) => {
        axios.patch(`/api/v1/user/change/email/status?type=discard&&token=${this.props.match.params.token}&&oldEmail=${oldEmail}`)
            .then((response) => {
                this.move('Pomyslnie odrzucono zmiane emaila');
            }).catch((e) => {
            this.props.history.push('/');
            toast.error('Coś poszło nie tak', {
                position: toast.POSITION.BOTTOM_RIGHT
            });
        });
    };

    move = (text) => {
        this.props.history.push('/');
        toast.success(text, {
            position: toast.POSITION.BOTTOM_RIGHT
        });
    };

    render() {
        const params = new URLSearchParams(this.props.location.search);
        const {classes} = this.props;
        if (params.get('type') === 'confirm') {
            this.confirmChangeEmail();
        } else {
            this.discardChangeEmail(params.get('oldEmail'));
        }
        return (
            <div className={classes.paper}>
                <CssBaseline/>
                <div className={classes.facebook}>
                    <CircularProgress
                        variant="indeterminate"
                        disableShrink
                        size={50}
                        thickness={4}
                    />
                </div>
            </div>
        );
    }
}

ChangeEmailStatus
    .propTypes = {};

export default withRouter(withStyles(styles)(ChangeEmailStatus));
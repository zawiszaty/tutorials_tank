import React from 'react';
import Button from '@material-ui/core/Button';
import {ValidatorForm, TextValidator} from 'react-material-ui-form-validator';
import FormControl from "../../containers/Registtration/Registration";
import withStyles from "@material-ui/core/es/styles/withStyles";
import CircularProgress from "@material-ui/core/CircularProgress";
import classNames from 'classnames';
import green from '@material-ui/core/colors/green';
import axios from './../../axios/axios';
import Redirect from "react-router-dom/es/Redirect";
import {ToastContainer, toast} from 'react-toastify';
import {client_id, client_secret} from "./../../axios/env";
import {login} from "../../actions/user";
import {connect} from "react-redux";
import {getNotification} from "../../actions/notification";
import {ErrorMessage} from "../Notification/ErrorMessage";
import {Link} from "react-router-dom";
import Paper from "@material-ui/core/Paper";
import CssBaseline from "@material-ui/core/CssBaseline";
import Typography from "@material-ui/core/Typography";

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
});

class PasswordRecovery extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            success: false,
            email: '',
        };
    }


    handleChangeEmail = (event) => {
        const email = event.target.value;
        this.setState({email});
    };

    handleSubmit = () => {
        axios.patch(`/api/v1/user/password/recover/${this.state.email}`).then((response) => {
            toast.success("Email wysłany", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
        });
    };

    render() {
        const {email} = this.state;
        const {classes} = this.props;
        const buttonClassname = classNames({
            [classes.buttonSuccess]: this.state.success,
        });

        if (this.state.success) {
            return (
                <React.Fragment>
                    <Redirect to="/"/>
                </React.Fragment>
            )
        }

        return (
            <main className={classes.main}>
                <CssBaseline/>
                <Paper className={classes.paper}>
                    <Typography component="h5" variant="h5">
                        Przypomnij hasło
                    </Typography>
                    <ValidatorForm
                        ref="form"
                        onSubmit={this.handleSubmit}
                        className={classes.form}
                    >
                        <TextValidator
                            label="Podaj swój email"
                            onChange={this.handleChangeEmail}
                            name="email"
                            value={email}
                            type="text"
                            validators={['required', 'isEmail']}
                            errorMessages={['To pole jest wymagane', 'Wpisz poprawny adres']}
                            margin="normal" fullWidth
                        />
                        <div className={classes.wrapper}>
                            <Button
                                variant="contained"
                                color="primary"
                                type="submit"
                                disabled={this.state.loading}
                                fullWidth
                                className={buttonClassname}
                            >
                                Przypomnij
                            </Button>
                            {this.state.loading && <CircularProgress size={24} className={classes.buttonProgress}/>}
                        </div>
                    </ValidatorForm>
                </Paper>
            </main>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login, getNotification};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(PasswordRecovery));

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
});

class LoginForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            username: '',
            password: '',
            loading: false,
            success: false,
            user: {},
        };
    }

    handleChange = (event) => {
        const email = event.target.value;
        this.setState({email});
    };

    handleChangeUsername = (event) => {
        const username = event.target.value;
        this.setState({username});
    };

    handleChangePassword = (event) => {
        let password = event.target.value;
        this.setState({password});
    };

    handleSubmit = () => {
        if (!this.state.loading) {
            this.setState(
                {
                    success: false,
                    loading: true,
                })
        }
        axios.post('/oauth/v2/token', {
            "grant_type": "password",
            "client_id": client_id,
            "client_secret": client_secret,
            "username": this.state.username,
            "password": this.state.password
        }).then((response) => {
            let token = response.data.access_token;
            axios.post('/api/v1/seciurity', {}, {
                headers: {'Authorization': 'Bearer ' + token}
            }).then((response) => {
                localStorage.setItem('token', token);
                toast.success("Zalogowales sie", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                this.props.login(response.data);
                this.setState({
                    success: true,
                    loading: false,
                })
            }).catch((e) => {
                if (e.response.data.error_description === 'User account is disabled.') {
                    toast.error("Potwierdz konto", {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                } else if (e.response.data.errors.title === 'App.Domain.User.Exception.UserIsBannedException') {
                    toast.error("Konto zbanowane", {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                } else {
                    toast.error("Coś poszło nie tak", {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                }
                this.setState(
                    {
                        success: false,
                        loading: false,
                    })
            });
        }).catch((e) => {
            toast.error("Zły login albo hasło", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
            this.setState(
                {
                    success: false,
                    loading: false,
                })
        })
    };

    render() {
        const {username} = this.state;
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
            <ValidatorForm
                ref="form"
                onSubmit={this.handleSubmit}
                className={classes.form}
            >
                <TextValidator
                    label="Nazwa Użytkownika"
                    onChange={this.handleChangeUsername}
                    name="email"
                    value={username}
                    type="text"
                    validators={['required']}
                    errorMessages={['To pole jest wymagane']}
                    margin="normal" required fullWidth
                />
                <TextValidator
                    label="Password"
                    onChange={this.handleChangePassword}
                    name="password"
                    type="password"
                    validators={['required']}
                    errorMessages={['this field is required']}
                    value={this.state.password}
                    margin="normal" required fullWidth
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
                        Zaloguj sie
                    </Button>
                    {this.state.loading && <CircularProgress size={24} className={classes.buttonProgress}/>}
                </div>
            </ValidatorForm>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(LoginForm));

import React from 'react';
import Button from '@material-ui/core/Button';
import {ValidatorForm, TextValidator} from 'react-material-ui-form-validator';
import FormControl from "../../containers/Registtration/Registration";
import withStyles from "@material-ui/core/es/styles/withStyles";
import CircularProgress from "@material-ui/core/CircularProgress";
import classNames from 'classnames';
import green from '@material-ui/core/colors/green';
import Redirect from "react-router-dom/es/Redirect";
import {ToastContainer, toast} from 'react-toastify';
import axios from './../../axios/axios';
import {ErrorMessage} from "../Notification/ErrorMessage";

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

class RegistrationForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            email: '',
            user: {
                password: '',
                repeatPassword: '',
            },
            username: '',
            loading: false,
            success: false,
        };
    }

    componentDidMount() {
        // custom rule will have name 'isPasswordMatch'
        ValidatorForm.addValidationRule('isPasswordMatch', (value) => {
            if (value !== this.state.user.password) {
                return false;
            }
            return true;
        });

        ValidatorForm.addValidationRule('minLenght', (value) => {
            if (value.length < 0) {
                return false;
            }
            return true;
        });

        ValidatorForm.addValidationRule('minLenghtPassword', (value) => {
            if (value.length < 6) {
                return false;
            }
            return true;
        });

        ValidatorForm.addValidationRule('maxLenght', (value) => {
            if (value.length > 255) {
                return false;
            }
            return true;
        });
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
        const {user} = this.state;
        user[event.target.name] = event.target.value;
        this.setState({user});
    };

    handleSubmit = () => {
        if (!this.state.loading) {
            this.setState(
                {
                    success: false,
                    loading: true,
                })
        }
        axios.post('/api/v1/user/register', {
            username: this.state.username,
            email: this.state.email,
            plainPassword: {
                first: this.state.user.password,
                second: this.state.user.repeatPassword,
            }
        }).then((response) => {
            toast.success("Zarejestrowales sie ;). Zaloguj sie na adres email podany przy rejestracji i potwierdz konto", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
            this.setState(
                {
                    success: true,
                    loading: false,
                });
        }).catch((e) => {
           console.log(e.response.status);
            ErrorMessage(e);
            this.setState(
                {
                    success: false,
                    loading: false,
                })
        })
    };

    render() {
        const {email, username} = this.state;
        const {classes} = this.props;
        const buttonClassname = classNames({
            [classes.buttonSuccess]: this.state.success,
        });

        if (this.state.success) {
            return (<Redirect to="/"/>)
        }

        return (
            <ValidatorForm
                ref="form"
                onSubmit={this.handleSubmit}
                className={classes.form}
            >
                <TextValidator
                    label="Email"
                    onChange={this.handleChange}
                    name="email"
                    value={email}
                    validators={['required', 'isEmail','minLenght', 'maxLenght']}
                    errorMessages={['To pole jest wymagane', 'To nie jest poprawny aders email', 'Pole jest za krótkie', 'Pole to jest za długie']}
                    margin="normal" required fullWidth
                />
                <TextValidator
                    label="Nazwa Użytkownika"
                    onChange={this.handleChangeUsername}
                    name="email"
                    value={username}
                    type="text"
                    validators={['required','minLenght', 'maxLenght']}
                    errorMessages={['To pole jest wymagane', 'Pole jest za krótkie', 'Pole to jest za długie']}
                    margin="normal" required fullWidth
                />
                <TextValidator
                    label="Password"
                    onChange={this.handleChangePassword}
                    name="password"
                    type="password"
                    validators={['required','minLenghtPassword', 'maxLenght']}
                    errorMessages={['this field is required', 'Pole jest za krótkie', 'Pole to jest za długie', 'Haslo musi zawierać jedna cyfre']}
                    value={this.state.user.password}
                    margin="normal" required fullWidth
                />
                <TextValidator
                    label="Repeat password"
                    onChange={this.handleChangePassword}
                    name="repeatPassword"
                    type="password"
                    validators={['isPasswordMatch', 'required']}
                    errorMessages={['Hasła musza być takie same', 'To pole jest wymagane']}
                    value={this.state.user.repeatPassword}
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
                        Zarejestruj sie
                    </Button>
                    {this.state.loading && <CircularProgress size={24} className={classes.buttonProgress}/>}
                </div>
            </ValidatorForm>
        );
    }
}

export default withStyles(styles)(RegistrationForm);

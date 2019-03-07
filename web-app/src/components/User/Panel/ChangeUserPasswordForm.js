import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Modal from "@material-ui/core/Modal";
import Typography from "@material-ui/core/Typography";
import withStyles from "@material-ui/core/es/styles/withStyles";
import Button from "@material-ui/core/Button";
import {TextValidator, ValidatorForm} from "react-material-ui-form-validator";
import CircularProgress from "@material-ui/core/CircularProgress";
import green from "@material-ui/core/colors/green";
import axios from "../../../axios/axios";
import {toast} from "react-toastify";
import {Paper} from "@material-ui/core";
import Divider from "@material-ui/core/Divider";
import {ErrorMessage} from "../../Notification/ErrorMessage";

function getModalStyle() {
    const top = 50;
    const left = 50;

    return {
        top: `${top}%`,
        left: `${left}%`,
        transform: `translate(-${top}%, -${left}%)`,
    };
}

const styles = theme => ({
    paper: {
        width: 'auto',
        margin: theme.spacing.unit,
        backgroundColor: theme.palette.background.paper,
        boxShadow: theme.shadows[5],
        padding: theme.spacing.unit * 4,
        outline: 'none',
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

class ChangeUserPasswordForm extends Component {
    constructor(props) {
        super(props);
        this.state = {
            open: false,
            oldPassword: '',
            firstPassword: '',
            secondPassword: '',
            loading: false,
        }
    }

    componentDidMount() {
        // custom rule will have name 'isPasswordMatch'
        ValidatorForm.addValidationRule('isPasswordMatch', (value) => {
            if (value !== this.state.firstPassword) {
                return false;
            }
            return true;
        });
        ValidatorForm.addValidationRule('isPasswordNotSame', (value) => {
            if (value === this.state.oldPassword) {
                return false;
            }
            return true;
        });

        ValidatorForm.addValidationRule('minLenght', (value) => {
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

    handleOpen = () => {
        this.setState({
            open: true,
        });
    };

    handleClose = () => {
        this.setState({
            open: false,
        });
    };
    handleSubmit = () => {
        this.setState({
            loading: true,
        });
        axios.patch(`/api/v1/user/change/password`, {
            oldPassword: this.state.oldPassword,
            plainPassword: {
                first: this.state.firstPassword,
                second: this.state.secondPassword,
            },
        }, {
            headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`}
        }).then((response) => {
            toast.success("Zmieniono", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
            this.setState({
                success: true,
                loading: false,
                oldPassword: '',
                firstPassword: '',
                secondPassword: '',
            });
        }).catch((error) => {
            console.log(error.response);
            ErrorMessage(error);
            this.setState({
                success: false,
                loading: false,
            });
        })
    };

    handleChangeOldPassword = (e) => {
        this.setState({
            oldPassword: e.target.value
        })
    };

    handleChangeFirstPassword = (e) => {
        this.setState({
            firstPassword: e.target.value
        })
    };

    handleChangeSecondPassword = (e) => {
        this.setState({
            secondPassword: e.target.value
        })
    };

    render() {
        const {classes, category} = this.props;
        const {oldPassword, firstPassword, secondPassword} = this.state;
        return (
            <Paper className={classes.paper}>
                <Typography variant="subtitle1" id="simple-modal-description">
                    <ValidatorForm
                        ref="form"
                        onSubmit={this.handleSubmit}
                        className={classes.form}
                    >
                        <Typography variant="h6" id="tableTitle">
                            Zmień swoje hasło
                        </Typography>
                        <TextValidator
                            label="Obecne Hasło"
                            onChange={this.handleChangeOldPassword}
                            name="oldPassword"
                            value={oldPassword}
                            type="password"
                            validators={['required']}
                            errorMessages={['To pole jest wymagane']}
                            margin="normal" required fullWidth
                        />
                        <TextValidator
                            label="Nowe hasło"
                            onChange={this.handleChangeFirstPassword}
                            name="firstPassword"
                            value={firstPassword}
                            type="password"
                            validators={['required', 'isPasswordNotSame', 'minLenght', 'maxLenght']}
                            errorMessages={['To pole jest wymagane', 'Wpisz inne hasło', 'Pole jest za krótkie', 'Pole jest za długie']}
                            margin="normal" required fullWidth
                        />
                        <TextValidator
                            label="Powtórz"
                            onChange={this.handleChangeSecondPassword}
                            name="secondPassword"
                            value={secondPassword}
                            type="password"
                            validators={['required', 'isPasswordMatch', 'minLenght', 'maxLenght']}
                            errorMessages={['To pole jest wymagane', 'Hasła musza być takie same', 'Pole jest za krótkie', 'Pole jest za długie']}
                            margin="normal" required fullWidth
                        />
                        <div className={classes.wrapper}>
                            <Button
                                variant="contained"
                                color="primary"
                                type="submit"
                                disabled={this.state.loading}
                                fullWidth
                                className={classes.buttonClassname}
                            >
                                Edytuj
                            </Button>
                            {this.state.loading &&
                            <CircularProgress size={24} className={classes.buttonProgress}/>}
                        </div>
                    </ValidatorForm>
                </Typography>
            </Paper>
        );
    }
}

ChangeUserPasswordForm.propTypes = {};

export default withStyles(styles)(ChangeUserPasswordForm);
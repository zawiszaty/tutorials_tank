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
        width: theme.spacing.unit * 50,
        backgroundColor: theme.palette.background.paper,
        boxShadow: theme.shadows[5],
        padding: theme.spacing.unit * 4,
        outline: 'none',
        margin: theme.spacing.unit,
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

class ChangeUserNameForm extends Component {
    constructor(props) {
        super(props);
        this.state = {
            open: false,
            name: this.props.user.name,
            loading: false,
        }
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
        axios.patch(`/api/v1/user/change/username`, {username: this.state.name}, {
            headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`}
        }).then((response) => {
            toast.success("Zmieniono", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
            this.setState({
                success: true,
                loading: false,
            });
        }).catch((e) => {
            toast.error("Coś poszło nie tak", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
        })
    };

    handleChangeName = (e) => {
        this.setState({
            name: e.target.value
        })
    };

    render() {
        const {classes, category} = this.props;
        const {name} = this.state;
        return (
            <Paper className={classes.paper}>
                <Typography variant="subtitle1" id="simple-modal-description">
                    <ValidatorForm
                        ref="form"
                        onSubmit={this.handleSubmit}
                        className={classes.form}
                    >
                        <Typography variant="h6" id="tableTitle">
                            Zmień swoją nazwe
                        </Typography>
                        <TextValidator
                            label="Nazwa"
                            onChange={this.handleChangeName}
                            name="email"
                            value={name}
                            type="text"
                            validators={['required']}
                            errorMessages={['To pole jest wymagane']}
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

ChangeUserNameForm.propTypes = {};

export default withStyles(styles)(ChangeUserNameForm);
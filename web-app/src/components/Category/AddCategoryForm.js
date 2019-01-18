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
import Typography from "@material-ui/core/Typography";

const styles = theme => ({
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
        height: 'auto',
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

class AddCategoryForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            name: '',
            loading: false,
            success: false,
        };
    }

    handleChangeName = (e) => {
        this.setState({
            name: e.target.value
        })
    };

    handleSubmit = () => {
        if (!this.state.loading) {
            this.setState(
                {
                    success: false,
                    loading: true,
                })
        }
        axios.post('/api/v1/category', {name: this.state.name}, {
            headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`}
        }).then((response) => {
            toast.success("Dodano", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
            this.setState({
                success: true,
                loading: false,
            });
            this.props.getCategory();
        }).catch((e) => {
            toast.error("Coś poszło nie tak", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
        })
    };

    render() {
        const {name} = this.state;
        const {classes} = this.props;
        const buttonClassname = classNames({
            [classes.buttonSuccess]: this.state.success,
        });

        return (
            <ValidatorForm
                ref="form"
                onSubmit={this.handleSubmit}
                className={classes.form}
            >
                <Typography variant="h6" id="tableTitle">
                  Dodaj nowa Kategorie
                </Typography>
                <TextValidator
                    label="Nazwa Kategori"
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
                        className={buttonClassname}
                    >
                        Dodaj
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

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(AddCategoryForm));
